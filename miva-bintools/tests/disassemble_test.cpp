/*
* This file is part of the ghassani/miva-bintools package.
*
* (c) Gassan idriss <ghassani@gmaill.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

#include <string.h>
#include <iostream>
#include "miva_binary.h"
#include "miva_binary_parser.h"
#include "miva_disassembler.h"
#include "hexdump.h"

void disassemble_bin(MivaBinary& bin, std::string outFile)
{
	MivaDisassembler disasm;

	try {
		disasm.disassembleAndOutput(bin, outFile);
	} catch (MivaDisassemblerError e) {
		std::cout << e.what() << std::endl;
		return;
	}
}

int main(int argc, char** argv)
{  
	if (argc < 3) {
		fprintf(stderr, "Expected a program name as argument\n");
		return -1;
	}

	MivaBinaryParser parser;

	try {
		MivaBinary bin = parser.parse(argv[1]);
		disassemble_bin(bin, argv[2]);
	} catch(MivaBinaryParserError e) {
		std::cout << e.what() << std::endl;
		return -1;
	}

	return 0;
}


