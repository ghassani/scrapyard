#
# This file is part of the ghassani/miva-bintools package.
#
# (c) Gassan idriss <ghassani@gmaill.com>
#
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
#
all: lib_and_tests
lib_and_tests: 
	$(CXX) -Wall -std=gnu++11 -shared -fPIC \
		-I./include/ \
		./src/hexdump.cpp \
		./src/miva_disassembler.cpp \
		./src/miva_binary_parser.cpp \
		./src/miva_binary.cpp \
		-o build/miva-bintools.so
	$(MAKE) -C ./tests
clean:
	rm -rf build/*