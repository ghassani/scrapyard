#include "Serial.h"      

using namespace System;

Serial::Serial(){   
    memset( &m_OverlappedRead, 0, sizeof( OVERLAPPED ) );   
    memset( &m_OverlappedWrite, 0, sizeof( OVERLAPPED ) );   
	m_bOpened = FALSE;   
}   
       
Serial::~Serial()   
{   
	this->Close();   
}   
       
    BOOL Serial::Open( int nPort, int nBaud, int nDataBit, int nStopBit )   
    {   
        if(m_bOpened)   
            return TRUE;   
       
        char szPort[15];    
        DCB dcb;   
       
        sprintf(szPort, "\\\\.\\COM%d", nPort );//2005-10-20 zj added "\\\\.\\" for com port>10   
        handCom = CreateFileA(szPort, GENERIC_READ | GENERIC_WRITE,    
                             0, NULL, OPEN_EXISTING,    
                             FILE_ATTRIBUTE_NORMAL | FILE_FLAG_OVERLAPPED,   
                             NULL );   
        if( handCom == INVALID_HANDLE_VALUE )    
            return FALSE;   
       
        memset( &m_OverlappedRead, 0, sizeof(OVERLAPPED));   
        memset( &m_OverlappedWrite, 0, sizeof(OVERLAPPED));   
       
        COMMTIMEOUTS CommTimeOuts;   
        CommTimeOuts.ReadIntervalTimeout = 0xFFFFFFFF;   
        CommTimeOuts.ReadTotalTimeoutMultiplier = 0xFFFFFFFF;   
        CommTimeOuts.ReadTotalTimeoutConstant = 1200;   
        CommTimeOuts.WriteTotalTimeoutMultiplier = 0;   
        CommTimeOuts.WriteTotalTimeoutConstant = 0;   
       
        SetCommTimeouts( handCom, &CommTimeOuts );   

        m_OverlappedRead.hEvent = CreateEvent( NULL, TRUE, FALSE, NULL );   
        m_OverlappedWrite.hEvent = CreateEvent( NULL, TRUE, FALSE, NULL );   
       
        dcb.DCBlength = sizeof( DCB );   
        GetCommState( handCom, &dcb );   
        
        dcb.BaudRate = nBaud;         // CBR_115200   
        dcb.ByteSize = nDataBit;   
        dcb.Parity = NOPARITY;   
        dcb.StopBits = ONESTOPBIT;   
       
        if( !SetCommState( handCom, &dcb ) ||   
            !SetupComm( handCom, 1024, 1024) ||   
            m_OverlappedRead.hEvent == NULL ||   
            m_OverlappedWrite.hEvent == NULL )   
        {   
            DWORD dwError = GetLastError();   
            if( m_OverlappedRead.hEvent != NULL ) CloseHandle( m_OverlappedRead.hEvent );   
            if( m_OverlappedWrite.hEvent != NULL ) CloseHandle( m_OverlappedWrite.hEvent );   
            CloseHandle( handCom );   
           
            return FALSE;   
        }   
       
        m_bOpened = TRUE;   
        PurgeComm( handCom, PURGE_TXABORT | PURGE_RXABORT | PURGE_TXCLEAR | PURGE_RXCLEAR ) ;   
       
        return m_bOpened;   
    }   
       
       
    BOOL Serial::Close( void )   
    {   
        if( !m_bOpened || handCom == INVALID_HANDLE_VALUE ) return( TRUE );   
       
        if( m_OverlappedRead.hEvent != NULL ) CloseHandle( m_OverlappedRead.hEvent );   
        if( m_OverlappedWrite.hEvent != NULL ) CloseHandle( m_OverlappedWrite.hEvent );   
       
        CloseHandle( handCom );   
        m_bOpened = FALSE;   
        handCom = NULL;   
       
        return( TRUE );   
    }   
       
    BOOL Serial::WriteCommByte( char ucByte )   
    {
       
        BOOL bWriteStat;   
        DWORD dwBytesWritten;   
       
        if(!PurgeComm(handCom, PURGE_RXCLEAR | PURGE_TXCLEAR | PURGE_RXABORT | PURGE_TXABORT))   
            return FALSE;   
        bWriteStat = WriteFile( handCom, (LPSTR) &ucByte, 1, &dwBytesWritten, &m_OverlappedWrite );   
        if( !bWriteStat && ( GetLastError() == ERROR_IO_PENDING ) )   
        {   
            if( WaitForSingleObject( m_OverlappedWrite.hEvent, 1000 ) )    
                dwBytesWritten = 0;   
            else   
            {   
                GetOverlappedResult( handCom, &m_OverlappedWrite, &dwBytesWritten, FALSE );   
                m_OverlappedWrite.Offset += dwBytesWritten;   
            }   
        }   
       
        return( TRUE );   
    }   
       
    int Serial::WriteCommBytes(const char * ucBytes, int length )   
    {   
        //以前出现过句柄异常，所以此处加了判断   
        if( handCom==(HANDLE)0x00000000 ||    
            handCom==(HANDLE)0xcccccccc)   
            return -1;   
       
        BOOL bWriteStat;   
        DWORD dwBytesWritten;   
        //PurgeComm(handCom, PURGE_RXCLEAR | PURGE_TXCLEAR | PURGE_RXABORT | PURGE_TXABORT);   
        if(!::PurgeComm(handCom, PURGE_RXCLEAR |PURGE_TXCLEAR |PURGE_RXABORT |PURGE_TXABORT))   
            return -2;   
        bWriteStat = WriteFile( handCom, (LPSTR) ucBytes, length, &dwBytesWritten, &m_OverlappedWrite );   
        if( !bWriteStat && ( GetLastError() == ERROR_IO_PENDING ) )   
        {   
            if( WaitForSingleObject( m_OverlappedWrite.hEvent, 10000 ) )    
                dwBytesWritten = 0;   
            else   
            {   
                GetOverlappedResult( handCom, &m_OverlappedWrite, &dwBytesWritten, FALSE );   
                m_OverlappedWrite.Offset += dwBytesWritten;   
            }   
        }   
       
        return( dwBytesWritten );   
    }   
       
    int Serial::SendData( const char *buffer, int size )   
    {   
        if( !m_bOpened || handCom == NULL ) return( 0 );   
       
        DWORD dwBytesWritten = 0;   
       
        for( int i=0; i<size; i++ )   
        {   
            WriteCommByte( buffer[i] );   
            dwBytesWritten++;   
        }   
       
        return( (int) dwBytesWritten );   
    }   
                                        
    int Serial::ReadDataWaiting( void )   
    {   
        if( !m_bOpened || handCom == NULL ) return( 0 );   
       
        DWORD dwErrorFlags;   
        COMSTAT ComStat;   
       
        ClearCommError( handCom, &dwErrorFlags, &ComStat );   
       
        return( (int)ComStat.cbInQue );   
    }   
       
    int Serial::ReadData( void *buffer, int limit )   
    {   
        if( !m_bOpened || handCom == NULL ) return( 0 );   
       
        BOOL bReadStatus;   
        DWORD dwBytesRead, dwErrorFlags;   
        COMSTAT ComStat;   
       
        ClearCommError( handCom, &dwErrorFlags, &ComStat );   
        if( !ComStat.cbInQue )   
            return( 0 );   
       
        dwBytesRead = (DWORD) ComStat.cbInQue;   
        if( limit < (int) dwBytesRead ) dwBytesRead = (DWORD) limit;   
       
           
        bReadStatus = ReadFile( handCom, buffer, dwBytesRead, &dwBytesRead, &m_OverlappedRead );   
        if( !bReadStatus )   
        {   
            if( GetLastError() == ERROR_IO_PENDING )   
            {   
                WaitForSingleObject( m_OverlappedRead.hEvent, 2000 );   
                return( (int) dwBytesRead );   
            }   
            return( 0 );   
        }   
       
        return( (int) dwBytesRead );   
    }   
       
       
    BOOL Serial::PurgeRxComm()   
    {   
        return ::PurgeComm(handCom, PURGE_RXCLEAR | PURGE_RXABORT);   
    }   
       
    BOOL Serial::PurgeTxComm()   
    {   
        return ::PurgeComm(handCom, PURGE_TXCLEAR |PURGE_TXABORT);   
    }


	int Serial::writeRead(char command[], char outbuf[], int len){
		DWORD readBeginTime;
		size_t bytesRead, bytesWritten;
		unsigned char bufTx[m_maxTxPacketSize];   
		unsigned char bufRx[m_maxRxPacketSize]; 
		unsigned int i;

		readBeginTime = GetTickCount();  
		bytesWritten = this->WriteCommBytes((const char*)command,len);

		printf("DM TX: %u Bytes [CMD:%s] - Expected to Write %u Bytes.\n",bytesWritten,command,len);
		printf("DM TX: ");
		for(unsigned int i2 = 0; i2 < bytesWritten; i2++){
			printf("%02X ",command[i2]);
		}
		printf("\n");

		Sleep(150); 
		do {   
		  bytesRead = this->ReadData(bufRx,m_maxRxPacketSize); 
			if(bytesRead>0){
				printf("DM RX: %u Bytes [RESP:%s] Max Read Length: %u\n",bytesRead,bufRx,m_maxRxPacketSize);
				printf("DM RX: ");
				for(i = 0; i <= bytesRead; i++){
					outbuf[i] = bufRx[i];
					printf("%02X ",bufRx[i]);
				}
				printf("\n");
				return 0;  
			 }
			 Sleep(100);
		} while(GetTickCount() - readBeginTime <= 15*1000 );  // timeout
		printf("DM RX: Timeout\n");
		return -1;  
	}