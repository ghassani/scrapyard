/*
* This file is part of the ghassani/miva-bintools package.
*
* (c) Gassan idriss <ghassani@gmaill.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

#pragma once

#include <string.h>
#include <stdio.h>
#include <stdint.h>
#include <fstream>
#include <sstream>
#include <vector>

#define MIVA_BINARY_MAGIC_LENGTH 	4
#define MIVA_BINARY_MAGIC	"Miva"

/**
* \struct MivaBinaryHeader
*/
struct MivaBinaryHeader 
{
	char magic[MIVA_BINARY_MAGIC_LENGTH];
	int  version;
	int  reserved[4];
	int  segmentTableOffset;
	int  segmentCount;
};

/**
* \struct MivaBinarySegmentInfo
*/
struct MivaBinarySegmentInfo
{
	char name[4];
	int  offset;
	int  size;
};

/**
* \struct MivaBinaryDictionaryEntry
*/
struct MivaBinaryDictionaryEntry 
{
	int index;
	int offset;
	int length;
	std::string value;
};

/**
* \struct MivaBinaryFunctionParameter
*/
struct MivaBinaryFunctionParameter
{
	int flags;
	int dictionaryEntry;
	std::string name;
};

/**
* \struct MivaBinaryFunctionLocal
*/
struct MivaBinaryFunctionLocal
{
	int index;
	int dictionaryEntry;
	std::string value;
};

/**
* \struct MivaBinaryFunction
*/
struct MivaBinaryFunction
{
	int flags;
	int codeOffset;
	int codeLength;
	int dictionaryEntry;
	std::string name;
	std::vector<uint8_t> code;
	std::vector<MivaBinaryFunctionParameter> parameters;
	std::vector<MivaBinaryFunctionLocal> locals;
};

/**
* \struct MivaBinaryVariable
*/
struct MivaBinaryVariable
{
	int index;
	int type;
	int unk1;
	int dictionaryEntry;
	std::string value;
};

/**
* \struct MivaBinaryFunctionHeader
*/
struct MivaBinaryFunctionHeader
{
	int nameDictionaryEntry;
	int numberOfParameters;
	int flags;
	int unk1;
	int unk2;
};

/**
* \struct MivaBinaryFunctionParameterHeader
*/
struct MivaBinaryFunctionParameterHeader 
{
	int nameDictionaryEntry;
	int flags;
	int unk1;
};

/**
* \enum MivaVariableType
*/
enum MivaVariableType 
{
	kMivaVariableTypeString = 0,
	kMivaVariableTypeGlobal = 1,
	kMivaVariableTypeLocal 	= 2,
	kMivaVariableTypeSystem = 3,
};

/**
* \enum MivaFunctionFlag
*/
enum MivaFunctionFlag 
{
	kMivaFunctionFlagExternal 				= 0x1,
	kMivaFunctionFlagCompressWhiteSpace 	= 0x2,
	kMivaFunctionFlagErrorOutputLevelOn 	= 0x4,
	kMivaFunctionFlagErrorOutputLevelOff 	= 0x8,
	kMivaFunctionFlagStdoutInherit 			= 0x10,
};

/**
* \enum MivaFunctionParameterFlag
*/
enum MivaFunctionParameterFlag 
{
	kMivaFunctionParameterFlagReference = 0x01
};

/**
* \class MivaBinary
* \brief Represents a Miva Script binary
*/
class MivaBinary
{
	protected:
		std::string fileName;
		size_t binarySize;
		MivaBinaryHeader header;

		std::vector<MivaBinarySegmentInfo> segments;
		std::vector<MivaBinaryDictionaryEntry> dictionary;
		std::vector<MivaBinaryFunction> functions;
		std::vector<MivaBinaryVariable> variables;
	public:
		/**
		* MivaBinary class constructor
		* @param fileName A string containing the path to the binary file
		* @param binarySize The size of the binary file
		* @param header The parsed header of the binary file
		* @return MivaBinary
		*/
		MivaBinary(std::string fileName, size_t binarySize, MivaBinaryHeader header);
		
		/**
		* MivaBinary class destructor
		*/
		~MivaBinary();
	   
		/**
		* Returns the header of the parsed binary
		* @see struct MivaBinaryHeader
		* @return MivaBinaryHeader&
		*/
		const MivaBinaryHeader& getHeader();
	   
		/**
		* Sets the header of the parsed binary
		* @param newHeader A MivaBinaryHeader object
		* @see struct MivaBinaryHeader
		* @return MivaBinaryHeader&
		*/
		void setHeader(MivaBinaryHeader newHeader);

		/**
		* Gets the size of the parsed binary
		* @return size_t
		*/
		size_t getSize();

		/**
		* Sets the size of the parsed binary
		* @param size The size of the binary
		* @return void
		*/
		void setSize(size_t size);

		/**
		* Gets the offset in the binary where the segment table information is located at
		* @return int
		*/
		int getSegmentTableOffset();

		/**
		* Gets the total number of segments defined in the segment table
		* @return int
		*/
		int getSegmentCount();

		/**
		* Gets all the segment information parsed from the segment table
		* @return std::vector<MivaBinarySegmentInfo>&
		*/
		std::vector<MivaBinarySegmentInfo>& getSegments();

		/**
		* Gets the parsed dictionary parsed out of the binary
		* @return std::vector<MivaBinaryDictionaryEntry>&
		*/
		std::vector<MivaBinaryDictionaryEntry>& getDictionary();
		
		/**
		* Gets a specific dictionary entry or nullptr when it does not exist
		* @return MivaBinaryDictionaryEntry*|nullptr
		*/
		MivaBinaryDictionaryEntry* getDictionaryEntry(int index);
		
		/**
		* Checks if a specific dictionary entry exists or not
		* @param index The index number
		* @return bool
		*/
		bool hasDictionaryEntry(int index);

		/**
		* Gets the functions parsed from the binary
		* @see MivaBinaryFunction
		* @return std::vector<MivaBinaryFunction>&
		*/
		std::vector<MivaBinaryFunction>& getFunctions();

		/**
		* Gets a specific segment info table entry or nullptr when it does not exist
		* @param name A string containing the name of the segment. This is always 4 characters.
		* @return MivaBinarySegmentInfo*|nullptr
		*/
		MivaBinarySegmentInfo* getSegmentInfo(std::string name);	
		
		/**
		* Checks if a specific segment (by name) exists or not
		* @param name The segment name. This is always 4 characters.
		* @return bool
		*/
		bool hasSegment(std::string name);

		/**
		* Gets all variables of all types.
		* @return std::vector<MivaBinaryVariable>& 
		*/
		std::vector<MivaBinaryVariable>& getVariables();

		/**
		* Gets a variable by value and type. Returns nullptr if it does not exist
		* @param vale The variable value
		* @param type A variable type
		* @return MivaBinaryVariable*|nullptr
		*/
		MivaBinaryVariable* getVariable(std::string value, MivaVariableType type);

		/**
		* Gets a variable by index and type. Returns nullptr if it does not exist
		* @param index The variable index
		* @param type A variable type
		* @return MivaBinaryVariable*|nullptr
		*/
		MivaBinaryVariable* getVariable(int index, MivaVariableType type);
		
		/**
		* Checks if a specific variable (by value and type) exists or not
		* @param value The variable value to look up
		* @param type A variable type
		*	@see MivaVariableType
		* @return bool
		*/
		bool hasVariable(std::string value, MivaVariableType type);
};
