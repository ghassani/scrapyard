#pragma once
#include <iostream>
#include <windows.h>
#include "util.h" 

class Serial 
{ 
public: 
	static const size_t m_maxRxPacketSize = 4096;
	static const size_t m_maxTxPacketSize  = 4096;

	BOOL PurgeTxComm( ); 
	BOOL PurgeRxComm( ); 
	Serial(); 
	virtual ~Serial(); 
 
	BOOL Open( int nPort = 1, int nBaud = 115200, int nDataBit = 8, int nStopBit = 1); 
	BOOL Close( void ); 

	int writeRead(char* command, char outbuf[], int len);
	int ReadData( void *, int ); 
	int SendData( const char *, int ); 
	int WriteCommBytes( const char *, int ); 
	int ReadDataWaiting( void ); 

	BOOL ClearBuf(void){ return ::PurgeComm(handCom, PURGE_RXCLEAR |PURGE_TXCLEAR |PURGE_RXABORT |PURGE_TXABORT);} 
	unsigned char* Serial::writeCommand(char* command, int len);
	BOOL IsOpened( void ) 
	{  
		return( m_bOpened );  
	} 
 
protected: 
	BOOL WriteCommByte( char ); 
	OVERLAPPED m_OverlappedRead, m_OverlappedWrite; 
	BOOL m_bOpened; 
	HANDLE handCom;
}; 
 


