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
#include "miva_binary.h"

/**
* \class MivaBinaryParser
* \brief Parses Miva Script binaries into MivaBinary instances
*/
class MivaBinaryParser
{		
	public:

		/**
		* MivaBinaryParser class constructor
		* @return MivaBinaryParser
		*/
		MivaBinaryParser();

		/**
		* MivaBinaryParser class destructor
		*/
		~MivaBinaryParser();

		/**
		* Parses a Miva Script binary and returns a MivaBinary instance
		* @param path The full or relative path to the Miva Script binary to parse
		* @return MivaBinary
		*/
		MivaBinary parse(std::string path);

	private:

		/**
		* Reads and parses the segment table. Called from MivaBinaryParser::parse
		* @param file The file stream to read from
		* @param file The MivaBinary instance 
		* @return void
		*/
		void readSegmentTable(std::ifstream& file, MivaBinary& bin);
		
		/**
		* Reads and parses the dictionary segment. Called from MivaBinaryParser::parse
		* @param file The file stream to read from
		* @param file The MivaBinary instance 
		* @return void
		*/
		void readDictionary(std::ifstream& file, MivaBinary& bin);
		
		/**
		* Reads and parses the function segment. Called from MivaBinaryParser::parse
		* @param file The file stream to read from
		* @param file The MivaBinary instance 
		* @return void
		*/
		void readFunctions(std::ifstream& file, MivaBinary& bin);
		
		/**
		* Reads and parses the global variables. Called from MivaBinaryParser::parse
		* @param file The file stream to read from
		* @param file The MivaBinary instance 
		* @return void
		*/
		void readGlobalVariables(std::ifstream& file, MivaBinary& bin);
		
		/**
		* Reads and parses the system variables. Called from MivaBinaryParser::parse
		* @param file The file stream to read from
		* @param file The MivaBinary instance 
		* @return void
		*/
		void readSystemVariables(std::ifstream& file, MivaBinary& bin);
};

/**
* \class MivaBinaryParserError
* \brief Thrown when MivaBinaryParser encounteres an error
*/
class MivaBinaryParserError : public std::exception
{
		const MivaBinaryParserError& operator=(MivaBinaryParserError);
		std::string _what;
	public:
		MivaBinaryParserError(std::string message) : _what(message)  { }
		MivaBinaryParserError(const MivaBinaryParserError& second) : _what(second._what) {}
		virtual ~MivaBinaryParserError() throw() {}
		virtual const char* what() const throw () {
			return _what.c_str();
		}
};