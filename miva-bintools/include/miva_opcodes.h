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

#define kMivaOpcodeArgFormatString 115 // s
#define kMivaOpcodeArgFormatInt 105 // i
#define kMivaOpcodeArgFormatLabel 108 // l
#define kMivaOpcodeArgFormatFunction 102 // f
#define kMivaOpcodeArgFormatFloat 100 //d


enum MivaOpcodeVarContext
{
	kMivaOpcodeVarContextNone = 0,
	kMivaOpcodeVarContextSystem = 1,
	kMivaOpcodeVarContextGlobal = 2,
	kMivaOpcodeVarContextLocal = 3,

};

struct MivaOpcode 
{
	uint16_t opcode;
	MivaOpcodeVarContext context;
	std::string mnemonic;
	std::string argFormat;

};

static const MivaOpcode mivaOpcodes[] = {
	{ 0x0000, kMivaOpcodeVarContextNone, "noop" , "" },
	{ 0x0007, kMivaOpcodeVarContextNone, "pushd" , "" },
	{ 0x0009, kMivaOpcodeVarContextNone, "pushn" , "" },
	{ 0x000b, kMivaOpcodeVarContextNone, "pop" , "" },
	{ 0x000f, kMivaOpcodeVarContextNone, "popd" , "" },
	{ 0x0011, kMivaOpcodeVarContextNone, "popn" , "" },
	{ 0x0013, kMivaOpcodeVarContextNone, "spush" , "" },
	{ 0x0014, kMivaOpcodeVarContextNone, "spop" , "" },
	{ 0x0015, kMivaOpcodeVarContextNone, "out" , "" },
	{ 0x0016, kMivaOpcodeVarContextNone, "add" , "" },
	{ 0x0017, kMivaOpcodeVarContextNone, "sub" , "" },
	{ 0x0018, kMivaOpcodeVarContextNone, "cat" , "" },
	{ 0x0019, kMivaOpcodeVarContextNone, "bitand" , "" },
	{ 0x001a, kMivaOpcodeVarContextNone, "bitor" , "" },
	{ 0x001b, kMivaOpcodeVarContextNone, "bitxor" , "" },
	{ 0x001c, kMivaOpcodeVarContextNone, "bitoc" , "" },
	{ 0x001d, kMivaOpcodeVarContextNone, "bitsl" , "" },
	{ 0x001e, kMivaOpcodeVarContextNone, "bitsr" , "" },
	{ 0x001f, kMivaOpcodeVarContextNone, "div" , "" },
	{ 0x0020, kMivaOpcodeVarContextNone, "mul" , "" },
	{ 0x0021, kMivaOpcodeVarContextNone, "mod" , "" },
	{ 0x0022, kMivaOpcodeVarContextNone, "rounds" , "" },
	{ 0x0023, kMivaOpcodeVarContextNone, "crypt" , "" },
	{ 0x0024, kMivaOpcodeVarContextNone, "pow" , "" },
	{ 0x0025, kMivaOpcodeVarContextNone, "roundf" , "" },
	{ 0x002c, kMivaOpcodeVarContextNone, "cmp" , "" },
	{ 0x002d, kMivaOpcodeVarContextNone, "and" , "" },
	{ 0x002e, kMivaOpcodeVarContextNone, "or" , "" },
	{ 0x002f, kMivaOpcodeVarContextNone, "in" , "" },
	{ 0x0030, kMivaOpcodeVarContextNone, "cin" , "" },
	{ 0x0031, kMivaOpcodeVarContextNone, "ein" , "" },
	{ 0x0032, kMivaOpcodeVarContextNone, "ecin" , "" },
	{ 0x0033, kMivaOpcodeVarContextNone, "eq" , "" },
	{ 0x0034, kMivaOpcodeVarContextNone, "ne" , "" },
	{ 0x0035, kMivaOpcodeVarContextNone, "ge" , "" },
	{ 0x0036, kMivaOpcodeVarContextNone, "le" , "" },
	{ 0x0037, kMivaOpcodeVarContextNone, "lt" , "" },
	{ 0x0038, kMivaOpcodeVarContextNone, "gt" , "" },
	{ 0x0039, kMivaOpcodeVarContextNone, "not" , "" },
	{ 0x003a, kMivaOpcodeVarContextNone, "dup" , "" },
	{ 0x003b, kMivaOpcodeVarContextNone, "ret" , "" },
	{ 0x003c, kMivaOpcodeVarContextNone, "retn" , "" },
	{ 0x003e, kMivaOpcodeVarContextNone, "elem" , "" },
	{ 0x003f, kMivaOpcodeVarContextNone, "elem_ro" , "" },
	{ 0x0040, kMivaOpcodeVarContextNone, "memb" , "" },
	{ 0x0041, kMivaOpcodeVarContextNone, "memb_ro" , "" },
	{ 0x0042, kMivaOpcodeVarContextNone, "store" , "" },
	{ 0x0043, kMivaOpcodeVarContextNone, "exit" , "" },
	{ 0x0045, kMivaOpcodeVarContextNone, "do_file" , "" },
	{ 0x0046, kMivaOpcodeVarContextNone, "do_function" , "" },
	{ 0x0047, kMivaOpcodeVarContextNone, "hide" , "" },
	{ 0x0048, kMivaOpcodeVarContextNone, "lockfile" , "" },
	{ 0x0049, kMivaOpcodeVarContextNone, "lockfile_end" , "" },
	{ 0x0051, kMivaOpcodeVarContextNone, "out_comp" , "" },
	{ 0x0052, kMivaOpcodeVarContextNone, "errmsg" , "" },
	{ 0x0055, kMivaOpcodeVarContextNone, "poprn" , "" },
	{ 0x0057, kMivaOpcodeVarContextNone, "refer" , "" },
	{ 0x0058, kMivaOpcodeVarContextNone, "capture" , "" },
	{ 0x0059, kMivaOpcodeVarContextNone, "capture_end" , "" },
	{ 0x005a, kMivaOpcodeVarContextNone, "isnull" , "" },
	{ 0x005b, kMivaOpcodeVarContextNone, "swap" , "" },
	{ 0x0105, kMivaOpcodeVarContextNone, "export" , "" },
	{ 0x0108, kMivaOpcodeVarContextNone, "pop3_delete", "" },
	{ 0x010b, kMivaOpcodeVarContextNone, "smtp_end", "" },
	{ 0x0200, kMivaOpcodeVarContextNone, "dbopen" , "" },
	{ 0x0201, kMivaOpcodeVarContextNone, "dbclose" , "" },
	{ 0x0202, kMivaOpcodeVarContextNone, "dbskip" , "" },
	{ 0x0203, kMivaOpcodeVarContextNone, "dbgo" , "" },
	{ 0x0204, kMivaOpcodeVarContextNone, "dbadd" , "" },
	{ 0x0205, kMivaOpcodeVarContextNone, "dbupdate" , "" },
	{ 0x0206, kMivaOpcodeVarContextNone, "dbdelete" , "" },
	{ 0x0207, kMivaOpcodeVarContextNone, "dbundelete" , "" },
	{ 0x0208, kMivaOpcodeVarContextNone, "dbprimary" , "" },
	{ 0x0209, kMivaOpcodeVarContextNone, "dbfind" , "" },
	{ 0x020a, kMivaOpcodeVarContextNone, "dbsetindex" , "" },
	{ 0x020b, kMivaOpcodeVarContextNone, "dbmakeindex" , "" },
	{ 0x020c, kMivaOpcodeVarContextNone, "dbpack" , "" },
	{ 0x020d, kMivaOpcodeVarContextNone, "dbreindex" , "" },
	{ 0x020e, kMivaOpcodeVarContextNone, "dbcreate" , "" },
	{ 0x0210, kMivaOpcodeVarContextNone, "dbopenview" , "" },
	{ 0x0211, kMivaOpcodeVarContextNone, "dbcloseview" , "" },
	{ 0x0212, kMivaOpcodeVarContextNone, "dbquery" , "" },
	{ 0x0213, kMivaOpcodeVarContextNone, "dbfilter" , "" },
	{ 0x0214, kMivaOpcodeVarContextNone, "dbreveal" , "" },
	{ 0x0215, kMivaOpcodeVarContextNone, "dbrevealagg" , "" },
	{ 0x0216, kMivaOpcodeVarContextNone, "dbopenf" , "" },
	{ 0x0217, kMivaOpcodeVarContextNone, "dbcommit" , "" },
	{ 0x0218, kMivaOpcodeVarContextNone, "dbrollback" , "" },
	{ 0x0219, kMivaOpcodeVarContextNone, "dbtransaction" , "" },
	{ 0x021a, kMivaOpcodeVarContextNone, "dbcommand" , "" },
	{ 0x4001, kMivaOpcodeVarContextNone, "pushc", "s" },
	{ 0x4002, kMivaOpcodeVarContextNone, "pushi", "i" },
	{ 0x4004, kMivaOpcodeVarContextSystem, "pushs", "s" },
	{ 0x4005, kMivaOpcodeVarContextGlobal, "pushg", "s" },
	{ 0x4006, kMivaOpcodeVarContextLocal, "pushl", "s" },
	{ 0x400a, kMivaOpcodeVarContextNone, "pushnc", "s" },
	{ 0x400c, kMivaOpcodeVarContextSystem, "pops", "s" },
	{ 0x400d, kMivaOpcodeVarContextGlobal, "popg", "s" },
	{ 0x400e, kMivaOpcodeVarContextLocal, "popl", "s" },
	{ 0x4012, kMivaOpcodeVarContextNone, "popnc", "s" },
	{ 0x4025, kMivaOpcodeVarContextNone, "jmp", "l" },
	{ 0x4026, kMivaOpcodeVarContextNone, "jmp_eq", "l" },
	{ 0x4027, kMivaOpcodeVarContextNone, "jmp_lt", "l" },
	{ 0x4028, kMivaOpcodeVarContextNone, "jmp_gt", "l" },
	{ 0x4029, kMivaOpcodeVarContextNone, "jmp_le", "l" },
	{ 0x402a, kMivaOpcodeVarContextNone, "jmp_ge", "l" },
	{ 0x402b, kMivaOpcodeVarContextNone, "jmp_ne", "l" },
	{ 0x403d, kMivaOpcodeVarContextNone, "call", "f" },
	{ 0x4044, kMivaOpcodeVarContextNone, "lineno", "i" },
	{ 0x404a, kMivaOpcodeVarContextNone, "sourcefile", "s" },
	{ 0x404c, kMivaOpcodeVarContextNone, "erroutput", "i" },
	{ 0x404d, kMivaOpcodeVarContextNone, "poplvnc", "s" },
	{ 0x404e, kMivaOpcodeVarContextNone, "localize", "s" },
	{ 0x404f, kMivaOpcodeVarContextNone, "localizev", "s" },
	{ 0x4050, kMivaOpcodeVarContextNone, "stdoutput", "i" },
	{ 0x4053, kMivaOpcodeVarContextGlobal, "poprg", "s" },
	{ 0x4054, kMivaOpcodeVarContextLocal, "poprl", "s" },
	{ 0x4056, kMivaOpcodeVarContextNone, "poprnc", "s" },
	{ 0x4101, kMivaOpcodeVarContextNone, "import", "l" },
	{ 0x4102, kMivaOpcodeVarContextNone, "importf", "l" },
	{ 0x4103, kMivaOpcodeVarContextNone, "import_loop", "l" },
	{ 0x4104, kMivaOpcodeVarContextNone, "import_stop", "l" },
	{ 0x4106, kMivaOpcodeVarContextNone, "pop3", "l" },
	{ 0x4107, kMivaOpcodeVarContextNone, "pop3_loop", "l" },
	{ 0x4109, kMivaOpcodeVarContextNone, "pop3_stop", "l" },
	{ 0x410a, kMivaOpcodeVarContextNone, "smtp", "l" },
	{ 0x410c, kMivaOpcodeVarContextNone, "http", "l" },
	{ 0x410d, kMivaOpcodeVarContextNone, "http_loop", "l" },
	{ 0x410e, kMivaOpcodeVarContextNone, "http_stop", "l" },
	{ 0x410f, kMivaOpcodeVarContextNone, "commerce", "l" },
	{ 0x4110, kMivaOpcodeVarContextNone, "commerce_loop", "l" },
	{ 0x4111, kMivaOpcodeVarContextNone, "commerce_stop", "l" },
	{ 0x4112, kMivaOpcodeVarContextNone, "http_cf", "l" },
	{ 0x5008, kMivaOpcodeVarContextNone, "pushdc", "ss" },
	{ 0x5010, kMivaOpcodeVarContextNone, "popdc", "ss" },
	{ 0x504b, kMivaOpcodeVarContextNone, "tagerror", "ii" },
	{ 0x505c, kMivaOpcodeVarContextNone, "swapn", "ii" },
	{ 0x5113, kMivaOpcodeVarContextNone, "httpn", "li" },
	{ 0x5301, kMivaOpcodeVarContextGlobal, "cmpgg", "ss" },
	{ 0x5302, kMivaOpcodeVarContextGlobal, "cmpgi", "si" },
	{ 0x5303, kMivaOpcodeVarContextNone, "incg", "s" },
	{ 0x5305, kMivaOpcodeVarContextGlobal, "storegi", "si" },
	{ 0x8003, kMivaOpcodeVarContextNone, "pushf", "d" },
};
