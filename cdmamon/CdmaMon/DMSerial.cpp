#include "DMSerial.h"
#include "qcdm.h"
#include "iostream"
#include "util.h"

using namespace std;
using namespace qcdm;
/*
*
*
*/
DMSerial::DMSerial(void)
{

}

/*
*
*
*/
int DMSerial::sendNVCommand(int nvItem,  char data[], char outbuf[]){
	const unsigned char readNvCommand = qcdm::DIAG_CMD_NV_READ;

	char cmdbuf[m_maxTxPacketSize] = {'\0'};
	char tmpbuf[m_maxRxPacketSize] = {'\0'};
	unsigned int i,key = 0;
	size_t encap_len = 0;

	cmdbuf[key++] = qcdm::DIAG_CMD_NV_READ;

	// 2 bytes in packet for NV Item to Read/Write
	char* nvItemBytes = static_cast<char*>(static_cast<void*>(&nvItem));
	for(unsigned int c = 0; c < strlen(nvItemBytes); c++){
		cmdbuf[key++] = nvItemBytes[c];
	}

	if(data != NULL){
		for( key++,i = 0; i < strlen(data); i++,key++){
			cmdbuf[key] = data[i];
		}
	}

	// fill the rest of the way with 0x0
	for(key; key < 133; key++){
		cmdbuf[key] = 0x00;
	}
	cmdbuf[key++] = NULL; // terminate cmdbuf
	
	encap_len = dm_encapsulate_buffer(cmdbuf, 133, sizeof(cmdbuf), tmpbuf, sizeof(tmpbuf));

	if(encap_len == 0)
		return -1;
		
	tmpbuf[encap_len] = NULL; // set end of packet
	
	return writeRead(tmpbuf, outbuf, encap_len); 
}

/*
*
*
*/
int DMSerial::sendDMCommand(char command,  char data[], char outbuf[]){
	char cmdbuf[m_maxTxPacketSize];
	char tmpbuf[m_maxRxPacketSize];
	unsigned int i,key = 1;
	size_t encap_len = 0;

	cmdbuf[0] = command;
	if(data != NULL){
		for( i = 0; i < strlen(data); i++,key++){
			cmdbuf[key] = data[i];
		}
	}
	cmdbuf[key++] = NULL; // terminate cmdbuf

	
	int dataSize = strlen(cmdbuf);
	switch(command){
		case DIAG_CMD_VERSION_INFO:		dataSize = 1;		break;
		case DIAG_CMD_CONTROL:			dataSize = 3;		break; // send an extra 0x00 for mode change? didnt work..
	}

	encap_len = dm_encapsulate_buffer(cmdbuf, dataSize, sizeof(cmdbuf), tmpbuf, sizeof(tmpbuf));
	
	if(encap_len == 0)
		return -1;
		
	tmpbuf[encap_len] = NULL;

	return writeRead(tmpbuf, outbuf, encap_len); 
}
