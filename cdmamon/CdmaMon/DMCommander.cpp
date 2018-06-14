#include "DMCommander.h"


DMCommander::DMCommander(DMSerial &dmPort)
{
	this->dmPort = &dmPort;
}

bool DMCommander::setMode(int mode){
	if(mode <= 0)
		return false;

	char outbuf[this->dmPort->m_maxTxPacketSize];
	char data[3] = {mode, NULL};
	this->dmPort->sendDMCommand(DIAG_CMD_CONTROL, data, outbuf);
	return true;
}
/**
* getNamMdn
* Reads the specified NAME MDN
*/
string DMCommander::getNamMdn(int nam){
	char outbuf[this->dmPort->m_maxTxPacketSize];
	char data[] = {(nam == 2 ? 0x01 : NULL), NULL};
	this->dmPort->sendNVCommand(NV_DIR_NUMBER_I, data, outbuf);
	DMReadNVMDNResponse *resp = (DMReadNVMDNResponse *)outbuf;
	printf("RX: NAM %u MDN %s\n",nam,resp->responseBody);
	return (char*)resp->responseBody;
}

/**
*
*/
string DMCommander::getScm(){
	char outbuf[this->dmPort->m_maxTxPacketSize];
	this->dmPort->sendNVCommand(NV_SCM_I, NULL, outbuf);
	DMReadNVResponse *resp = (DMReadNVResponse *)outbuf;
	printf("RX: SCM %s\n",resp->responseBody);
	char out[3];
	sprintf(out,"%02X",resp->responseBody[0]);
	return out;
}

/**
* getNamMin
* Reads the specified NAM MIN and Decodes it
*/
string DMCommander::getNamMin(int nam){
	char outbuf[this->dmPort->m_maxTxPacketSize];
	char minPart1[3];
	char minPart2[2];
	unsigned int i,i2;
	char data[] = {(nam == 2 ? 0x01 : NULL), NULL};

	this->dmPort->sendNVCommand(NV_MIN1_I, data, outbuf);
	DMReadNVResponseAlt *resp = (DMReadNVResponseAlt *)outbuf;
	printf("RX: NAM %u MIN1 %s\n",nam,resp->responseBody);

	minPart1[0] = resp->responseBody[3];
	minPart1[1] = resp->responseBody[6];
	minPart1[2] = resp->responseBody[5];
	minPart1[3] = resp->responseBody[4];
	
	outbuf[0] = NULL; // clear buf
	this->dmPort->sendNVCommand(NV_MIN2_I, data, outbuf);
	DMReadNVResponseAlt *resp2 = (DMReadNVResponseAlt *)outbuf;
	printf("RX: NAM %u MIN2 %s\n",nam,resp2->responseBody);
	
	minPart2[0] = resp2->responseBody[3];
	minPart2[1] = resp2->responseBody[2];

	std::string s_min1 = bytesToHex(minPart1,4);
	std::string s_min2 = bytesToHex(minPart2,2);

	int32_t i_min1,i_min2,min2,min1a,min1b,min1c;
	char out[10];

	i_min1 = strtoul(s_min1.c_str(),NULL,16);
	i_min2 = strtoul(s_min2.c_str(),NULL,16);

	min2 = ((i_min2+1) % 10) + (((((i_min2 % 100) / 10) + 1) % 10) * 10) + ((((i_min2 / 100) + 1) % 10) * 100);

	min1a = (i_min1 & 0xFFC000) >> 14;
	min1a = ((min1a + 1) % 10) + (((((min1a % 100) / 10) + 1) % 10) * 10) + ((((min1a / 100) + 1) % 10) * 100);

	min1b = ((i_min1 & 0x3C00) >> 10) % 10;

	min1c = (i_min1 & 0x3FF);	
	min1c = ((min1c + 1) % 10) + (((((min1c % 100) / 10) + 1) % 10) * 10) + ((((min1c / 100) + 1) % 10) * 100);

	sprintf(out,"%03i%03i%i%i",min2,min1a,min1b,min1c);

	return out;
}

/**
*
*/
string DMCommander::getNamBanner(int nam){
	char outbuf[this->dmPort->m_maxTxPacketSize];
	char data[] = {(nam == 2 ? 0x01 : NULL), NULL};
	this->dmPort->sendNVCommand(NV_BANNER_I, data, outbuf);
	DMReadNVResponse *resp = (DMReadNVResponse *)outbuf;
	printf("RX: NAM %u Banner %s\n",nam,resp->responseBody);
	return (char*)resp->responseBody;
}

/**
*
*/
string DMCommander::getNamName(int nam){
	char outbuf[this->dmPort->m_maxTxPacketSize];
	char data[] = {(nam == 2 ? 0x01 : NULL), NULL};
	this->dmPort->sendNVCommand(NV_NAME_NAM_I, NULL, outbuf);
	DMReadNVResponseAlt *resp = (DMReadNVResponseAlt *)outbuf;
	printf("RX: NAM %u Name %s\n",nam,resp->responseBody);
	return (char*)resp->responseBody;
}


/**
*
*/
string DMCommander::getHomeSid(int nam){
	char outbuf[this->dmPort->m_maxTxPacketSize];
	char data[] = {(nam == 2 ? 0x01 : NULL), NULL};
	this->dmPort->sendNVCommand(NV_HOME_SID_NID_I, NULL, outbuf);
	DMReadNVResponseAlt *resp = (DMReadNVResponseAlt *)outbuf;
	printf("RX: NAM %u SID %s\n",nam,resp->responseBody);


	char tmp[2];
	tmp[0] = resp->responseBody[1];
	tmp[1] = resp->responseBody[0];

	int homeSid = strtoul(bytesToHex(tmp,2).c_str(),NULL,16);

    char out[20];
	sprintf(out,"%i",homeSid);
	return out;
}

/**
*
*/
string DMCommander::getHomeNid(int nam){
	char outbuf[this->dmPort->m_maxTxPacketSize];
	char data[] = {(nam == 2 ? 0x01 : NULL), NULL};
	this->dmPort->sendNVCommand(NV_HOME_SID_NID_I, NULL, outbuf);
	DMReadNVResponse *resp = (DMReadNVResponse *)outbuf;
	printf("RX: NAM %u NID %s\n",nam,resp->responseBody);

	char tmp[2];
	tmp[0] = resp->responseBody[3];
	tmp[1] = resp->responseBody[2];

	int homeNid = strtoul(bytesToHex(tmp,2).c_str(),NULL,16);

    char out[20];
	sprintf(out,"%i",homeNid);
	return out;
}


/**
*
*/
bool DMCommander::getNamLock(){
	char outbuf[this->dmPort->m_maxTxPacketSize];
	this->dmPort->sendNVCommand(NV_NAM_LOCK_I, NULL, outbuf);
	DMReadNVResponseAlt *resp = (DMReadNVResponseAlt *)outbuf;
	printf("RX: NAM Lock %s\n",resp->responseBody);
	return resp->responseBody[0] == 0x01;

}

/**
*
*/
string DMCommander::getMeid(){
	char outbuf[this->dmPort->m_maxTxPacketSize];
	this->dmPort->sendNVCommand(NV_MEID_I, NULL, outbuf);
	DMReadNVResponse *resp = (DMReadNVResponse *)outbuf;

	char out[14] = {'\0'}, tmp[3];
	for(signed int i = 6; 0 <= i; i--){
		sprintf(tmp,"%02X",resp->responseBody[i]);
		strncat(out,tmp,2);
	}
	printf("RX: MEID %s\n",out);
	return out;
}
/**
*
*/
string DMCommander::getEsn(){
	char outbuf[this->dmPort->m_maxTxPacketSize];
	this->dmPort->sendNVCommand(NV_ESN_I, NULL, outbuf);
	DMReadNVResponse *resp = (DMReadNVResponse *)outbuf;
	printf("RX: ESN %s\n",resp->responseBody);
	return (char*)resp->responseBody;
}

/**
*
*/
string DMCommander::getPppUserId(int profile){
	char outbuf[this->dmPort->m_maxTxPacketSize];
	this->dmPort->sendNVCommand(NV_PPP_USER_ID_I, NULL, outbuf);
	DMReadNVResponseAlt *resp = (DMReadNVResponseAlt *)outbuf;
	printf("RX: PPP User ID %s\n",resp->responseBody);
	return (char*)resp->responseBody;
}

/**
*
*/
string DMCommander::getPppPassword(int profile){
	char outbuf[this->dmPort->m_maxTxPacketSize];
	this->dmPort->sendNVCommand(NV_PPP_PASSWORD_I, NULL, outbuf);
	DMReadNVResponseAlt *resp = (DMReadNVResponseAlt *)outbuf;
	printf("RX: PPP Password %s\n",resp->responseBody);
	return (char*)resp->responseBody;
}

/**
*
*/
string DMCommander::getPapUserId(int profile){
	char outbuf[this->dmPort->m_maxTxPacketSize];
	this->dmPort->sendNVCommand(NV_PAP_USER_ID_I, NULL, outbuf);
	DMReadNVResponseAlt *resp = (DMReadNVResponseAlt *)outbuf;
	printf("RX: PAP User ID %s\n",resp->responseBody);
	return (char*)resp->responseBody;
}

/**
*
*/
string DMCommander::getPapPassword(int profile){
	char outbuf[this->dmPort->m_maxTxPacketSize];
	this->dmPort->sendNVCommand(NV_PAP_PASSWORD_I, NULL, outbuf);
	DMReadNVResponseAlt *resp = (DMReadNVResponseAlt *)outbuf;
	printf("RX: PAP Password %s\n",resp->responseBody);
	return (char*)resp->responseBody;
}

string DMCommander::getHdrAnAuthUserIdLong(int profile){
	char outbuf[this->dmPort->m_maxTxPacketSize];
	this->dmPort->sendNVCommand(NV_HDR_AN_AUTH_USER_ID_LONG_I, NULL, outbuf);
	DMReadNVResponseAlt *resp = (DMReadNVResponseAlt *)outbuf;
	printf("RX: HDR AN AUTH USER ID LONG %s\n",resp->responseBody);
	return (char*)resp->responseBody;
}

/**
*
*/
string DMCommander::getHdrAnAuthPasswordLong(int profile){
	char outbuf[this->dmPort->m_maxTxPacketSize];
	this->dmPort->sendNVCommand(NV_HDR_AN_AUTH_PASSWORD_LONG_I, NULL, outbuf);
	DMReadNVResponseAlt *resp = (DMReadNVResponseAlt *)outbuf;
	printf("RX: HDR AN AUTH Password LONG %s\n",resp->responseBody);
	return (char*)resp->responseBody;
}

/**
*
*/
string DMCommander::getHdrAnAuthNai(int profile){
	char outbuf[this->dmPort->m_maxTxPacketSize];
	this->dmPort->sendNVCommand(NV_HDR_AN_AUTH_NAI_I, NULL, outbuf);
	DMReadNVResponseAlt *resp = (DMReadNVResponseAlt *)outbuf;
	printf("RX: HDR AN AUTH NAI %s\n",resp->responseBody);
	return (char*)resp->responseBody;
}

/**
*
*/
string DMCommander::getHdrAnAuthPassword(int profile){
	char outbuf[this->dmPort->m_maxTxPacketSize];
	this->dmPort->sendNVCommand(NV_HDR_AN_AUTH_PASSWORD_I, NULL, outbuf);
	DMReadNVResponseAlt *resp = (DMReadNVResponseAlt *)outbuf;
	printf("RX: HDR AN AUTH Password %s\n",resp->responseBody);
	return (char*)resp->responseBody;
}



/**
*
*/
DMVersionInfo DMCommander::getVersionInfo(){
	DMVersionInfo out;
	char outbuf[this->dmPort->m_maxTxPacketSize];

	this->dmPort->sendDMCommand(DIAG_CMD_VERSION_INFO, NULL, outbuf);
	DMCommandResponse *resp = (DMCommandResponse *)outbuf;
	
	std::string data = (char*) resp->responseBody;
	std::string compiled = data.substr(0,19);
	std::string released = data.substr(19,19);

	strcpy(out.compiledAt,compiled.c_str());
	strcpy(out.releasedAt,released.c_str());

	printf("RX: Version Info: %s - %s\n",out.compiledAt,out.releasedAt);

	return out;
}

/**
*
*/
string DMCommander::getChipset(){
	
	char outbuf[this->dmPort->m_maxTxPacketSize];

	this->dmPort->sendDMCommand(DIAG_CMD_DEVICE_CHIPSET, NULL, outbuf);
	DMChipsetResponse *resp = (DMChipsetResponse *)outbuf;
	
	printf("RX: Chipset Info: %s\n",resp->responseBody);

	return (char*)resp->responseBody;
}
