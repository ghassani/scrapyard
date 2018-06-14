/*
* This file is part of the ghassani/miva-bintools package.
*
* (c) Gassan idriss <ghassani@gmaill.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

#include "miva_binary_parser.h"

MivaBinaryParser::MivaBinaryParser()
{

}

MivaBinaryParser::~MivaBinaryParser()
{

}

MivaBinary MivaBinaryParser::parse(std::string path)
{
	
	MivaBinaryHeader header = {};

	std::ifstream file(path, std::ios::in | std::ios::binary);

	if (!file.is_open()) {
		throw MivaBinaryParserError("Could not open file for reading");
	}

	file.seekg(0, file.end);
	size_t binarySize = file.tellg();
	file.seekg(0, file.beg);

	if (binarySize <= sizeof(header)) {
		file.close();
		throw MivaBinaryParserError("File size does not appear to represent a valid MivaScript binary.");
	}

	file.read(reinterpret_cast<char*>(&header), sizeof(header));

	if(!strstr(header.magic, MIVA_BINARY_MAGIC)) {
		file.close();
		throw MivaBinaryParserError("File is not a valid MivaScript binary");
	}

	MivaBinary bin(path, binarySize, header);

	try {
		readSegmentTable(file, bin);
		readDictionary(file, bin);
		readGlobalVariables(file, bin);
		readSystemVariables(file, bin);
		readFunctions(file, bin);
	} catch (...) {
		file.close();
		throw;
	}

	file.close();

	return bin;
}


void MivaBinaryParser::readSegmentTable(std::ifstream& file, MivaBinary& bin)
{
	bin.getSegments().reserve(bin.getHeader().segmentCount);
	
	file.seekg(bin.getHeader().segmentTableOffset, file.beg);

	for (int i = 0; i < bin.getHeader().segmentCount; i++) {
		MivaBinarySegmentInfo seg = {};
		file.read(reinterpret_cast<char*>(&seg), sizeof(MivaBinarySegmentInfo));
		bin.getSegments().push_back(seg);
	}
}

void MivaBinaryParser::readDictionary(std::ifstream& file, MivaBinary& bin)
{
	MivaBinarySegmentInfo* dictInfo = bin.getSegmentInfo("dict");

	if (!dictInfo) {
		throw MivaBinaryParserError("Segment dict not found");
	}

	file.seekg(dictInfo->offset, file.beg);

	int entryCount = 0, entrySize = 0;

	file.read(reinterpret_cast<char*>(&entryCount), sizeof(entryCount));
	file.read(reinterpret_cast<char*>(&entrySize), sizeof(entrySize));
	
	file.seekg(4, file.cur); // need to skip a dword here. reserved?
		
	for (int i = 0; i < entryCount; i++) {
		MivaBinaryDictionaryEntry entry = {};
		entry.index = i;
		file.read(reinterpret_cast<char*>(&entry.offset), sizeof(entry.offset));
		file.read(reinterpret_cast<char*>(&entry.length), sizeof(entry.length));
		bin.getDictionary().push_back(entry);
	}

	// we are at the start of the dictionary values; save the position
	int stringsPos = file.tellg();

	for (auto &e : bin.getDictionary()) {
		file.seekg(stringsPos + e.offset, file.beg); // seek back to stringPos on each entry

		uint8_t* str = new uint8_t[e.length]; // need some memory for the string

		file.read(reinterpret_cast<char*>(str), e.length);

		e.value.insert(e.value.begin(), str, str + e.length);

		delete[] str;
	}
}

void MivaBinaryParser::readFunctions(std::ifstream& file, MivaBinary& bin)
{
	MivaBinarySegmentInfo* funcSegment = bin.getSegmentInfo("func");

	if (!funcSegment) {
		throw MivaBinaryParserError("Segment func not found");
	}

	file.seekg(funcSegment->offset, file.beg);
	
	int16_t unkOffset = 0;
	int8_t 	unkByte = 0;
	int 	functionCount = 0;

	// told by dev these two first values are bucket/chain related
	file.read(reinterpret_cast<char*>(&unkOffset), sizeof(unkOffset));
	file.read(reinterpret_cast<char*>(&unkByte), sizeof(unkByte));
	file.read(reinterpret_cast<char*>(&functionCount), sizeof(functionCount));

	bin.getFunctions().reserve(functionCount + 1); // +1 for entry point

	// add the entry point
	MivaBinaryFunction startFunction = {};
	startFunction.name = "entry";
	bin.getFunctions().push_back(startFunction);
	
	// seek past this unknown data
	file.seekg(6 * unkOffset, file.cur);
	
	// seek past this unknown data
	file.seekg(4 * functionCount, file.cur);
	
	for (int i = 0; i < functionCount; i++) {
		MivaBinaryFunction function = {};
		MivaBinaryFunctionHeader fHeader = {};
		
		file.read(reinterpret_cast<char*>(&fHeader), sizeof(fHeader));

		function.flags = fHeader.flags;
		function.dictionaryEntry = fHeader.nameDictionaryEntry;

		MivaBinaryDictionaryEntry* funcDictEntry = bin.getDictionaryEntry(fHeader.nameDictionaryEntry);

		if (funcDictEntry) {
			function.name = funcDictEntry->value;
		} else {
			std::stringstream ss;
			ss << "unk" << i;
			function.name = ss.str();
		}
		
		if (fHeader.numberOfParameters > 0) {
			function.parameters.reserve(fHeader.numberOfParameters);
			for (int i2 = 0; i2 < fHeader.numberOfParameters; i2++) {
				MivaBinaryFunctionParameter parameter = {};
				MivaBinaryFunctionParameterHeader fpHeader = {};
				
				file.read(reinterpret_cast<char*>(&fpHeader), sizeof(fpHeader));

				MivaBinaryDictionaryEntry* paramDictEntry = bin.getDictionaryEntry(fpHeader.nameDictionaryEntry);

				if (paramDictEntry) {
					parameter.name = paramDictEntry->value;
				} else {
					std::stringstream ss;
					ss << "unk" << i2;
					parameter.name = ss.str();
				}

				parameter.flags = fpHeader.flags;
				parameter.dictionaryEntry = fpHeader.nameDictionaryEntry;

				function.parameters.push_back(parameter);
			}
		}

		int8_t 	unkOffset2 = 0;
		int16_t unkValue = 0;
		int 	numberOfLocals = 0;

		file.read(reinterpret_cast<char*>(&unkOffset2), sizeof(unkOffset2));
		file.read(reinterpret_cast<char*>(&unkValue), sizeof(unkValue));
		file.read(reinterpret_cast<char*>(&numberOfLocals), sizeof(numberOfLocals));
		
		if (numberOfLocals > 0) {
			file.seekg(6*(unkOffset2 != 0 ? unkOffset2 : numberOfLocals), file.cur);
			for (int i3 = 0; i3 < numberOfLocals; i3++) {
				MivaBinaryFunctionLocal local = {};

				file.read(reinterpret_cast<char*>(&local.dictionaryEntry), sizeof(local.dictionaryEntry));
				file.seekg(sizeof(int), file.cur); // dont need this?
				
				MivaBinaryDictionaryEntry* localDictEntry = bin.getDictionaryEntry(local.dictionaryEntry);

				if (localDictEntry) {
					local.value.append("l_").append(localDictEntry->value);
				} else {
					std::stringstream ss;
					ss << "l_unk" << i3;
					local.value = ss.str();
				}

				local.index = i3;
				function.locals.push_back(local);

			}
		}/* else {
			file.seekg(7, file.cur);
		}*/
		
		bin.getFunctions().push_back(function);
	}

	// now copy the code into each function, including the entry
	MivaBinarySegmentInfo* segsSegment = bin.getSegmentInfo("segs");

	if (!segsSegment) {
		throw MivaBinaryParserError("Segment segs not found");
	}

	file.seekg(segsSegment->offset, file.beg);
	
	int numberOfFunctions = 0;
	int unk1 = 0;
	file.read(reinterpret_cast<char*>(&numberOfFunctions), sizeof(numberOfFunctions));
	file.read(reinterpret_cast<char*>(&unk1), sizeof(unk1));

	std::vector<MivaBinaryFunction>& functions = bin.getFunctions();

	for (int i = 0; i < numberOfFunctions; i++) {
		file.read(reinterpret_cast<char*>(&functions[i].codeOffset), sizeof(functions[i].codeOffset));
		file.read(reinterpret_cast<char*>(&functions[i].codeLength), sizeof(functions[i].codeLength));
	}

	int backPos = file.tellg();

	for (auto &function : functions) {
		file.seekg(backPos, file.beg);

		file.seekg(function.codeOffset, file.cur);

		uint8_t* code = new uint8_t[function.codeLength];

		file.read(reinterpret_cast<char*>(code), function.codeLength);

		function.code.insert(function.code.begin(), code, code + function.codeLength);
	
		delete[] code;

	}

}

void MivaBinaryParser::readGlobalVariables(std::ifstream& file, MivaBinary& bin)
{
	MivaBinarySegmentInfo* globalsSegment = bin.getSegmentInfo("glbl");
	
	if (!globalsSegment) {
		throw MivaBinaryParserError("Segment glbl not found");
	}

	file.seekg(globalsSegment->offset, file.beg);

	int16_t unkOffset = 0;
	int8_t 	unkByte = 0;
	int 	numberOfGlobals = 0;
		
	file.read(reinterpret_cast<char*>(&unkOffset), sizeof(unkOffset));
	file.read(reinterpret_cast<char*>(&unkByte), sizeof(unkByte));
	file.read(reinterpret_cast<char*>(&numberOfGlobals), sizeof(numberOfGlobals));
	
	bin.getVariables().reserve(bin.getVariables().size() + numberOfGlobals);

	// seek past this unknown data
	file.seekg(6 * unkOffset, file.cur);

	for (int i = 0; i < numberOfGlobals; i++) {
		MivaBinaryVariable var = {};

		file.read(reinterpret_cast<char*>(&var.dictionaryEntry), sizeof(var.dictionaryEntry));
		file.read(reinterpret_cast<char*>(&var.unk1), sizeof(var.unk1));

		var.index = i;
		var.type = kMivaVariableTypeGlobal;

		MivaBinaryDictionaryEntry* globalDictEntry = bin.getDictionaryEntry(var.dictionaryEntry);

		if (globalDictEntry) {
			var.value = globalDictEntry->value;
		} else {
			std::stringstream ss;
			ss << "unk_global" << i+1;
			var.value = ss.str();
		}

		bin.getVariables().push_back(var);
	}
}

void MivaBinaryParser::readSystemVariables(std::ifstream& file, MivaBinary& bin)
{
	MivaBinarySegmentInfo* systemSegment = bin.getSegmentInfo("syst");
	
	if (!systemSegment) {
		throw MivaBinaryParserError("Segment syst not found");
	}

	file.seekg(systemSegment->offset, file.beg);

	int16_t unkOffset = 0;
	int8_t 	unkByte = 0;
	int 	numberOfSystemVariables = 0;
		
	file.read(reinterpret_cast<char*>(&unkOffset), sizeof(unkOffset));
	file.read(reinterpret_cast<char*>(&unkByte), sizeof(unkByte));
	file.read(reinterpret_cast<char*>(&numberOfSystemVariables), sizeof(numberOfSystemVariables));
	
	bin.getVariables().reserve(bin.getVariables().size() + numberOfSystemVariables);

	// seek past this unknown data
	file.seekg(6 * unkOffset, file.cur);

	for (int i = 0; i < numberOfSystemVariables; i++) {
		MivaBinaryVariable var = {};

		file.read(reinterpret_cast<char*>(&var.dictionaryEntry), sizeof(var.dictionaryEntry));
		file.read(reinterpret_cast<char*>(&var.unk1), sizeof(var.unk1));

		var.index = i;
		var.type = kMivaVariableTypeSystem;

		MivaBinaryDictionaryEntry* systemDictEntry = bin.getDictionaryEntry(var.dictionaryEntry);

		if (systemDictEntry) {
			var.value = systemDictEntry->value;
		} else {
			std::stringstream ss;
			ss << "unk_system" << i+1;
			var.value = ss.str();
		}

		bin.getVariables().push_back(var);
	}
}
