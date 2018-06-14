#
# This file is part of the ghassani/miva-bintools package.
#
# (c) Gassan idriss <ghassani@gmaill.com>
#
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
#
all: scripts basic_default basic_verbose disasm_default
scripts: 
	#you need miva compiler in your paths yo
	/opt/msc/bin/mvc -o ../build/test1.mvc test1.mv 
	/opt/msc/bin/mvc -c -o ../build/test1.mva test1.mv 
basic_default: 
	$(CXX) -Wall -std=gnu++11 \
		-I./../include/ \
		./../src/hexdump.cpp \
		./../src/miva_disassembler.cpp \
		./../src/miva_binary_parser.cpp \
		./../src/miva_binary.cpp \
		./basic_parse.cpp  \
		-o ./../build/basic_parse_test
basic_verbose: 
	$(CXX) -Wall -std=gnu++11 \
		-I./../include/ \
		-D TEST_VERBOSE_DICTIONARY \
		-D TEST_VERBOSE_FUNCTIONS \
		-D TEST_VERBOSE_VARIABLES \
		./../src/hexdump.cpp \
		./../src/miva_disassembler.cpp \
		./../src/miva_binary_parser.cpp \
		./../src/miva_binary.cpp \
		./basic_parse.cpp  \
		-o ./../build/basic_parse_test_verbose
disasm_default:
	$(CXX) -Wall -std=gnu++11 \
		-I./../include/ \
		./../src/hexdump.cpp \
		./../src/miva_disassembler.cpp \
		./../src/miva_binary_parser.cpp \
		./../src/miva_binary.cpp \
		./disassemble_test.cpp  \
		-o ./../build/disassemble_test
clean:
	rm -rf ../build/index.mvc
	rm -f ../basic_parse_test