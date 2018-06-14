/*
* This file is part of the ghassani/miva-bintools package.
*
* (c) Gassan idriss <ghassani@gmaill.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

#pragma once

#include "miva_binary.h"
#include "miva_opcodes.h"
#include <map>

/**
* \struct MivaInstruction
*/
struct MivaInstruction
{
	int32_t offset;
	const MivaOpcode* opcode;	
	std::string disassembly;
	std::string label;
};

/**
* \struct MivaDisassembledFunction
*/
struct MivaDisassembledFunction 
{
	const MivaBinaryFunction* function;
	std::vector<MivaInstruction> instructions;
	int localCount = 0;
	int stringCount = 0;
	int globalCount = 0;
	int systemCount = 0;
};

/**
* \class MivaDisassembler
* \brief Disassembles a MivaBinary instance to Miva Assembly
*/
class MivaDisassembler
{		
	public:
		/**
		* MivaDisassembler class constructor
		* @return MivaDisassembler
		*/
		MivaDisassembler();

		/**
		* MivaDisassembler class destructor
		*/
		~MivaDisassembler();

		std::vector<MivaDisassembledFunction> disassemble(MivaBinary& bin);
		void output(std::vector<MivaDisassembledFunction>& disassembly, std::string outFile);
		void disassembleAndOutput(MivaBinary& bin, std::string outFile);
	private:
		const MivaOpcode* lookupOpcode(uint16_t opcode);
		MivaBinaryFunctionLocal* lookupFunctionLocal(MivaBinaryFunction& function, int index);
		std::map<int,std::vector<int>> initVarMap();
		bool isVarInMap(std::map<int,std::vector<int>>& varMap, MivaVariableType varType, int number);
};

class MivaDisassemblerError : public std::exception
{
	const MivaDisassemblerError& operator=(MivaDisassemblerError);
	std::string _what;
public:
	MivaDisassemblerError(std::string message) : _what(message)  { }
	MivaDisassemblerError(const MivaDisassemblerError& second) : _what(second._what) {}
	virtual ~MivaDisassemblerError() throw() {}
	virtual const char* what() const throw () {
		return _what.c_str();
	}
};