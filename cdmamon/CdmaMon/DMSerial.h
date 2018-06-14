#pragma once
#include "serial.h"


using namespace std;

class DMSerial : public Serial {

public:
	static const size_t m_maxRxPacketSize = 4096;
	static const size_t m_maxTxPacketSize  = 4096;

	DMSerial(void);
	int sendDMCommand(char command,  char data[], char outbuf[]);
	int sendNVCommand(int nvItem,  char data[], char outbuf[]);
};