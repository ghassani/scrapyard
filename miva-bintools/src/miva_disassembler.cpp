/*
* This file is part of the ghassani/miva-bintools package.
*
* (c) Gassan idriss <ghassani@gmaill.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

#include "miva_disassembler.h"

MivaDisassembler::MivaDisassembler()
{

}

MivaDisassembler::~MivaDisassembler()
{

}

std::vector<MivaDisassembledFunction> MivaDisassembler::disassemble(MivaBinary& bin)
{

	if (!bin.getFunctions().size()) {
		throw MivaDisassemblerError("No Functions to Disassemble");
	}

	std::vector<MivaDisassembledFunction> ret;

	int currentFunction = 1;
	
	for (auto &function : bin.getFunctions()) {
		
		std::map<int,std::vector<int>> varMap = initVarMap();

		/*if (currentFunction != 1) { // TODO: fix this and be able to resolve locals in the main entry point
			currentFunction++;
			continue;
		}*/

		MivaDisassembledFunction disasmFunc = {};

		if (function.flags & kMivaFunctionFlagExternal) {
			//printf("Skipping External Function %d...\n", currentFunction);
			currentFunction++;
			continue;
		}

		disasmFunc.function = &function;

		int offset = 0; 

		while (offset < function.codeLength) {
			std::stringstream ss;  // for the end result instruction
			std::stringstream vss; // for the instruction variable refs, appended to ss at end
			std::stringstream ess; // for error messages
			MivaInstruction instruction = {};

			uint16_t opcodeNumber = *(uint16_t*)&function.code[offset];
			
			instruction.opcode = lookupOpcode(opcodeNumber);
			instruction.offset = offset;

			if (instruction.opcode == nullptr) {				
				ess << "Unknown Opcode Encountered: " << opcodeNumber << " in function " << currentFunction << " at offset " << offset;
				throw MivaDisassemblerError(ess.str());
			}

			offset += 2;

			if (instruction.opcode->argFormat.size()) {
				for (int i = 0; i < (int)instruction.opcode->argFormat.size(); i++) {
					char fmt = instruction.opcode->argFormat[i];

					if (fmt == kMivaOpcodeArgFormatString) {
						int32_t refValue = *(int32_t*)&function.code[offset];
						
						if (instruction.opcode->context == kMivaOpcodeVarContextLocal) {

							if (currentFunction == 1) {
								
								if (!isVarInMap(varMap, kMivaVariableTypeString, refValue)) {
									MivaBinaryDictionaryEntry* entry = bin.getDictionaryEntry(refValue);

									if (entry) {
										//printf("(%s) - Entry Local %d Dict Entry Index: %d Dict Entry Value: %s\n", instruction.opcode->mnemonic.c_str(), refValue, entry->index, entry->value.c_str());

										ss << ".local l_" << entry->index << " \"" << entry->value << "\"\n";
										vss << (vss.tellg() > 0 ? ", " : "") << " l_" << entry->index;
									} else {
										ess << "Could not locate dictionary reference in function " << currentFunction << " at offset " << offset << " referencing dictionary entry " << refValue;
										throw MivaDisassemblerError(ess.str());
									}	

									varMap[kMivaVariableTypeString].push_back(refValue);
								}

							} else {

								if (!isVarInMap(varMap, kMivaVariableTypeLocal, refValue)) {
									MivaBinaryFunctionLocal* local = lookupFunctionLocal(function, refValue);
									if (local) {
										//printf("(%s) - Local %d Dict Entry Index: %d Dict Entry Value: %s\n", instruction.opcode->mnemonic.c_str(), refValue, local->index, local->value.c_str());

										ss << ".local l_" << local->index << " \"" << local->value << "\"\n";	
										vss << (vss.tellg() > 0 ? ", " : "") << " l_" << local->index + function.parameters.size();								
									} else {
										ess << "Could not locate local variable reference in function " << currentFunction << " at offset " << offset << " referencing local " << refValue;
										throw MivaDisassemblerError(ess.str());								
									}
									varMap[kMivaVariableTypeLocal].push_back(refValue);
								}
							}

						} else if (instruction.opcode->context == kMivaOpcodeVarContextGlobal) {
							if (!isVarInMap(varMap, kMivaVariableTypeGlobal, refValue)) {
								MivaBinaryVariable* global = bin.getVariable(refValue, kMivaVariableTypeGlobal);

								if (global) {
									//printf("(%s) - Global %d Dict Entry Index: %d Dict Entry Value: %s\n", instruction.opcode->mnemonic.c_str(), refValue, global->index, global->value.c_str());

									ss << ".global g_" << global->index << " \"" << global->value << "\"\n";
									vss << (vss.tellg() > 0 ? ", " : "") << " g_" << global->index;
								} else {
									ess << "Could not locate global variable reference in function " << currentFunction << " at offset " << offset << " referencing global " << refValue;
									throw MivaDisassemblerError(ess.str());
								}

								varMap[kMivaVariableTypeGlobal].push_back(refValue);
							}

						} else if (instruction.opcode->context == kMivaOpcodeVarContextSystem) {
							if (!isVarInMap(varMap, kMivaVariableTypeSystem, refValue)) {
								MivaBinaryVariable* system = bin.getVariable(refValue, kMivaVariableTypeSystem);
								if (system) {
									//printf("(%s) - System %d Dict Entry Index: %d Dict Entry Value: %s\n", instruction.opcode->mnemonic.c_str(), refValue, system->index, system->value.c_str());
									ss << ".system sys_" << system->index << " \"" << system->value << "\"\n";
									vss << (vss.tellg() > 0 ? ", " : "") << " sys_" << system->index;
								} else {
									ess << "Could not locate system variable reference in function " << currentFunction << " at offset " << offset << " referencing system " << refValue;
									throw MivaDisassemblerError(ess.str());
								}
								varMap[kMivaVariableTypeSystem].push_back(refValue);
							}
						} else {
									
							if (!isVarInMap(varMap, kMivaVariableTypeString, refValue)) {
								
								MivaBinaryDictionaryEntry* entry = bin.getDictionaryEntry(refValue);

								if (entry) {
									//printf("(%s) - String %d Dict Entry Index: %d Dict Entry Value: %s\n", instruction.opcode->mnemonic.c_str(), refValue, entry->index, entry->value.c_str());
									ss << ".string s_" << entry->index << " \"" << entry->value << "\"\n";
									vss << (vss.tellg() > 0 ? ", " : "") << " s_" << entry->index;
								} else {
									ess << "Could not locate dictionary reference in function " << currentFunction << " at offset " << offset << " referencing dictionary entry " << refValue;
									throw MivaDisassemblerError(ess.str());
								}

								varMap[kMivaVariableTypeString].push_back(refValue);
							}
						}						
						offset += 4;
					} else if (fmt == kMivaOpcodeArgFormatInt) {
						int32_t val = *(int32_t*)&function.code[offset];
						vss << (vss.tellg() > 0 ? ", " : "") << " " << val;
						offset += 4;
					} else if (fmt == kMivaOpcodeArgFormatLabel) {
						int32_t off = *(int32_t*)&function.code[offset];
						vss << (vss.tellg() > 0 ? ", " : "") << " " << off;
						offset += 4;
					} else if (fmt == kMivaOpcodeArgFormatFunction) {
						int32_t fNumber = *(int32_t*)&function.code[offset];
						vss << (vss.tellg() > 0 ? ", " : "") << " " << fNumber;
						offset += 4;
					} else if (fmt == kMivaOpcodeArgFormatFloat) {
						float f = *(float*)&function.code[offset];
						offset += 8;
						vss << (vss.tellg() > 0 ? ", " : "") << " " << f;
					} else {
						ess << "Unknown opcode argument format encountered: " << fmt;
						throw MivaDisassemblerError(ess.str());
					}
				}
			}
			
			if (vss.str().size()) {
				ss << "\t" << instruction.opcode->mnemonic << vss.str();
			} else {
				ss << "\t" << instruction.opcode->mnemonic;
			}
			
			instruction.disassembly = ss.str();

			//printf("%s\n", ss.str().c_str());

			disasmFunc.instructions.push_back(instruction);
		}

		ret.push_back(disasmFunc);
		currentFunction++;
	}

	// now we need to resolve labels
	return ret;
}

void MivaDisassembler::output(std::vector<MivaDisassembledFunction>& disassembly, std::string outFile)
{
	
	std::ofstream file(outFile, std::ios::out | std::ios::binary | std::ios::trunc);

	if (!file.is_open()) {		
		std::stringstream ess;
		ess << "Could not open " << outFile << " for writing";
		throw MivaDisassemblerError(ess.str());
	}

	if (disassembly.size()) {
		for (auto &inst : disassembly[0].instructions) {
			file << inst.disassembly << std::endl;
		}
	}

	int currentFunction = 1;
	for (auto &funcdisasm : disassembly) {
		
		if (currentFunction == 1) {
			currentFunction++; // put entry at end
			continue;
		}

		file << ".function " << funcdisasm.function->name;
		
		if (funcdisasm.function->flags & kMivaFunctionFlagCompressWhiteSpace) {
			file << " compresswhitespace";
		}

		if (funcdisasm.function->flags & kMivaFunctionFlagErrorOutputLevelOn) {
			file << "erroroutputlevelon";
		}

		if (funcdisasm.function->flags & kMivaFunctionFlagErrorOutputLevelOff) {
			file << "erroroutputleveloff";
		}

		if (funcdisasm.function->flags & kMivaFunctionFlagStdoutInherit) {
			file << "stdoutinherit";
		}

		file << std::endl;

		int pCount = 0;
		for (auto &parameter : funcdisasm.function->parameters) {
			file << ".parameter	l_" << pCount << " \"" << parameter.name << "\"" << std::endl;
			pCount++;
		}

		int i = 0;
		for (auto &inst : funcdisasm.instructions) {
			if (i == (int) funcdisasm.instructions.size() - 2) { 
				// end of function, next 2 instructions are function return value
				//file << ".endfunction" << std::endl;
			}

			file << inst.disassembly << std::endl;
			i++;
		}
		file << ".endfunction" << std::endl;
	}



	file.close();
}

void MivaDisassembler::disassembleAndOutput(MivaBinary& bin, std::string outFile)
{
	std::vector<MivaDisassembledFunction> disassembly = disassemble(bin);
	output(disassembly, outFile);
}

const MivaOpcode* MivaDisassembler::lookupOpcode(uint16_t opcode)
{
	for (int i = 0; i < (int)(sizeof(mivaOpcodes)/sizeof(MivaOpcode)); i++) {
		if (mivaOpcodes[i].opcode == opcode) {			
			return &mivaOpcodes[i];
		}
	}
	return nullptr;
}


MivaBinaryFunctionLocal* MivaDisassembler::lookupFunctionLocal(MivaBinaryFunction& function, int index)
{
	for (auto &local : function.locals) {
		if (local.index == index) {
			return &local;
		}
	}

	return nullptr;
}


std::map<int,std::vector<int>> MivaDisassembler::initVarMap()
{
	std::map<int,std::vector<int>> ret;
	ret[kMivaVariableTypeString] = {};
	ret[kMivaVariableTypeGlobal] = {};
	ret[kMivaVariableTypeLocal]  = {};
	ret[kMivaVariableTypeSystem] = {};
	return ret;
}

bool MivaDisassembler::isVarInMap(std::map<int,std::vector<int>>& varMap, MivaVariableType varType, int number)
{
	auto varTypeExists = varMap.find(varType);

	if (varTypeExists == varMap.end()) {
		throw MivaDisassemblerError("Variable type is not a valid variable type from MivaVariableType");
	}

	for (auto &entry : varMap[varType]) {
		if (entry == number) {
			return true;
		}
	}

	return false;
}