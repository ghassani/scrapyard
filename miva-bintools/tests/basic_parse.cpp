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
#include "hexdump.h"

void dump_binary_info(MivaBinary& bin)
{
	printf("Binary Size: %lu bytes\n", bin.getSize());

	printf("Binary Version: 0x%04X\n", bin.getHeader().version);

	printf("Segment Table at offset %d (0x%08X). Total of %d segments.\n", 
		bin.getSegmentTableOffset(), 
		bin.getSegmentTableOffset(), 
		bin.getSegmentCount()
	);

	for (auto &seg : bin.getSegments()) {
		printf("Segment: %.4s @ 0x%08X - 0x%08X - Size: %d bytes\n", 
			seg.name, 
			seg.offset, 
			seg.offset + seg.size, 
			seg.size
		);
	}

	printf("Dictionary Entries: %lu\n", bin.getDictionary().size());

#ifdef TEST_VERBOSE_DICTIONARY
	for (auto &e : bin.getDictionary()) {
		printf("Index: %d | %s\n", e.index, e.value.c_str());
	}
#endif

	printf("Functions: %lu\n", bin.getFunctions().size());

#ifdef TEST_VERBOSE_FUNCTIONS
	int i = 1;
	for (auto &func : bin.getFunctions()) {
		printf("Function %d: Name: %s Number of Parameters: %lu Number of Locals: %lu Flags: %d (%04X)\n", 
			i,
			func.name.c_str(),
			func.parameters.size(),
			func.locals.size(),
			func.flags, func.flags
		);

		int i2 = 1;
		for (auto &param : func.parameters) {
			printf("\tParameter %d: Name: %s Flags: %d (%04X)\n", 
				i2,
				param.name.c_str(),
				param.flags, param.flags
			);
			i2++;
		}

		for (auto &local : func.locals) {
			printf("\tLocal %d: Name: %s\n", 
				local.index,
				local.value.c_str()
			);
		}

		printf("\nFunction %s Code:\n", func.name.c_str());
		hexdump(&func.code[0], func.codeLength);
		i++;
	}
#endif

	printf("Variables: %lu\n", bin.getVariables().size());
	
	int gcount = 0, scount = 0, ucount = 0;

	for (auto &var : bin.getVariables()) {
		if (var.type == kMivaVariableTypeGlobal) {
			gcount++;
		} else if (var.type == kMivaVariableTypeSystem) {
			scount++;
		} else {
			ucount++;
		}
	}

	printf("Global Variables: %d\n", gcount);
	printf("System Variables: %d\n", scount);
	printf("Unkown Variables: %d\n", ucount);

#ifdef TEST_VERBOSE_VARIABLES
	int vi = 0;
	for (auto &var : bin.getVariables()) {
		printf("Variable %d: %s Type: ", vi, var.value.c_str());
		if (var.type == kMivaVariableTypeGlobal) {
			printf("Global\n");
		} else if (var.type == kMivaVariableTypeSystem) {
			printf("System\n");
		} else {
			printf("Unknown\n");
		}
		vi++;
	}
#endif

}

int main(int argc, char** argv)
{  
	if (argc < 2) {
		fprintf(stderr, "Expected a program name as argument\n");
		return -1;
	}

	MivaBinaryParser parser;

	try {
		MivaBinary bin = parser.parse(argv[1]);
		dump_binary_info(bin);
	} catch(MivaBinaryParserError e) {
		std::cout << e.what() << std::endl;
		return -1;
	}

	return 0;
}


