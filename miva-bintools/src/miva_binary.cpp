/*
* This file is part of the ghassani/miva-bintools package.
*
* (c) Gassan idriss <ghassani@gmaill.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

#include "miva_binary.h"

MivaBinary::MivaBinary(std::string fileName, size_t binarySize, MivaBinaryHeader header) : fileName(fileName), binarySize(binarySize), header(header)
{

}

MivaBinary::~MivaBinary()
{

}

const MivaBinaryHeader& MivaBinary::getHeader()
{
	return header;
}

void MivaBinary::setHeader(MivaBinaryHeader newHeader)
{
	header = newHeader;
}

size_t MivaBinary::getSize()
{
	return binarySize;
}

void MivaBinary::setSize(size_t size)
{
	binarySize = size;
}


int MivaBinary::getSegmentTableOffset()
{
	return getHeader().segmentTableOffset;
}

int MivaBinary::getSegmentCount()
{
	return segments.size();
}

std::vector<MivaBinarySegmentInfo>& MivaBinary::getSegments()
{
	return segments;
}


MivaBinarySegmentInfo* MivaBinary::getSegmentInfo(std::string name)
{
	for (auto &seg : segments) {
		if(strstr(seg.name, name.c_str())) {
			return &seg;
		}
	}
	return nullptr;
}

bool MivaBinary::hasSegment(std::string name)
{
	for (auto seg : segments) {
		if(strstr(seg.name, name.c_str())) {
			return true;
		}
	}
	return false;
}

std::vector<MivaBinaryDictionaryEntry>& MivaBinary::getDictionary()
{
	return dictionary;
}

MivaBinaryDictionaryEntry* MivaBinary::getDictionaryEntry(int index)
{
	for (auto &entry : dictionary) {
		if (entry.index == index) {
			return &entry;
		}
	}
	return nullptr;
}

bool MivaBinary::hasDictionaryEntry(int index)
{
	for (auto entry : dictionary) {
		if (entry.index == index) {
			return true;
		}
	}
	return false;
}


std::vector<MivaBinaryFunction>& MivaBinary::getFunctions()
{
	return functions;
}

std::vector<MivaBinaryVariable>& MivaBinary::getVariables()
{
	return variables;
}

MivaBinaryVariable* MivaBinary::getVariable(std::string value, MivaVariableType type)
{
	for (auto &variable : variables) {
		if (variable.type == type && variable.value.compare(value) == 0) {
			return &variable;
		}
	}
	return nullptr;
}

MivaBinaryVariable* MivaBinary::getVariable(int index, MivaVariableType type)
{
	for (auto &variable : variables) {
		if (variable.type == type && variable.index == index) {
			return &variable;
		}
	}
	return nullptr;
}

bool MivaBinary::hasVariable(std::string value, MivaVariableType type)
{
	for (auto variable : variables) {
		if (variable.type == type && variable.value.compare(value) == 0) {
			return true;
		}
	}
	return false;
}

