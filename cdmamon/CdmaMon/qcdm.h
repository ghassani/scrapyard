#ifndef QCDM_H

namespace qcdm {

	#define QCDM_H
	#define FTM_RF_RSP_SIZE	23 
	#define FTM_RF_CMD			119 
	#define	DIAG_NV_ITEM_SIZE	128 
	#define DIAG_MAX_RX_PKT_SIZ (2048 * 2) 
	#define DIAG_MAX_TX_PKT_SIZ (2048 * 2) 
	#define DIAG_RX_TIMEOUT (15*1000)
	/************************************** Diag_Key press **************************************/ 
	
	/*
	unsigned char cmd_DiagPressKeyPound_Code[] = {0x20, 0x00, 0x23, 0x6E, 0xD6, 0x7E}; // key_press 
	unsigned char cmd_DiagPressKeyStar_Code[] = {0x20, 0x00, 0x2a, 0xAF, 0x4B, 0x7E}; // key_press 
	unsigned char cmd_DiagPressKey0_Code[] = {0x20, 0x00, 0x30, 0x74, 0xF4, 0x7E}; // key_press 
	unsigned char cmd_DiagPressKey1_Code[] = {0x20, 0x00, 0x31, 0xFD, 0xE5, 0x7E}; // key_press 
	unsigned char cmd_DiagPressKey2_Code[] = {0x20, 0x00, 0x32, 0x66, 0xD7, 0x7E}; // key_press 
	unsigned char cmd_DiagPressKey3_Code[] = {0x20, 0x00, 0x33, 0xEF, 0xC6, 0x7E}; // key_press 
	unsigned char cmd_DiagPressKey4_Code[] = {0x20, 0x00, 0x34, 0x50, 0xB2, 0x7E}; // key_press 
	unsigned char cmd_DiagPressKey5_Code[] = {0x20, 0x00, 0x35, 0xD9, 0xA3, 0x7E}; // key_press 
	unsigned char cmd_DiagPressKey6_Code[] = {0x20, 0x00, 0x36, 0x42, 0x91, 0x7E}; // key_press 
	unsigned char cmd_DiagPressKey7_Code[] = {0x20, 0x00, 0x37, 0xCB, 0x80, 0x7E}; // key_press 
	unsigned char cmd_DiagPressKey8_Code[] = {0x20, 0x00, 0x38, 0x3c, 0x78, 0x7E}; // key_press 
	unsigned char cmd_DiagPressKey9_Code[] = {0x20, 0x00, 0x39, 0xB5, 0x69, 0x7E}; // key_press 
	unsigned char cmd_DiagPressKeySEND_UP_Code[] = {0x20, 0x00, 0x50, 0x72, 0x97, 0x7E}; // key_press 
	unsigned char cmd_DiagPressKeyEND_DN_Code[] = {0x20, 0x00, 0x51, 0xFB, 0x86, 0x7E}; // key_press 
	unsigned char cmd_DiagPressKeyMSG_Code[] = {0x20, 0x00, 0x0B, 0x24, 0x7B, 0x7E}; // key_press 
	*/
	//unsigned char cmd_DiagPressATnQCDMGe_Code[] = {0x61, 0x74, 0x24, 0x71, 0x63, 0x64, 0x6D, 0x67, 0x0D}; // AT& 
	/*
	unsigned char cmd_DiagPressATnQCDMGe_Code[] = {0x61, 0x74, 0x24, 0x71, 0x63, 0x64, 0x6D, 0x67, 0x0D, 0x0A}; // AT& 
	unsigned char cmd_unKnown1_Code[] = {0x0F, 0xFE, 0xF9, 0xFF, 0xFF, 0x70, 0x9C, 0x7E};  
	unsigned char cmd_DiagPressDMpass_Code[] = {0x46, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF,0xFE, 0x74, 0x7E};  
	*/
	/************************************** DM SEND SPC **************************************/ 
	/*char cmd_DiagSendSpcZeros[] = { 0x30, 0x30, 0x30, 0x30, 0x30, 0x30 };
	unsigned char cmd_DiagSendSpcZerosWithCrc[] = { 0x41, 0x30, 0x30, 0x30, 0x30, 0x30, 0x30, 0xDF, 0x8A, 0x7E };*/

	enum DMCommands{
		DIAG_CMD_VERSION_INFO = 0,  /* Version info */
		DIAG_CMD_ESN          = 1,  /* ESN */
		DIAG_CMD_PEEKB        = 2,  /* Peek byte */
		DIAG_CMD_PEEKW        = 3,  /* Peek word */
		DIAG_CMD_PEEKD        = 4,  /* Peek dword */
		DIAG_CMD_POKEB        = 5,  /* Poke byte */
		DIAG_CMD_POKEW        = 6,  /* Poke word */
		DIAG_CMD_POKED        = 7,  /* Poke dword */
		DIAG_CMD_OUTP         = 8,  /* Byte output */
		DIAG_CMD_OUTPW        = 9,  /* Word output */
		DIAG_CMD_INP          = 10, /* Byte input */
		DIAG_CMD_INPW         = 11, /* Word input */
		DIAG_CMD_STATUS       = 12, /* Station status */
		DIAG_CMD_LOGMASK      = 15, /* Set logging mask */
		DIAG_CMD_LOG          = 16, /* Log packet */
		DIAG_CMD_NV_PEEK      = 17, /* Peek NV memory */
		DIAG_CMD_NV_POKE      = 18, /* Poke NV memory */
		DIAG_CMD_BAD_CMD      = 19, /* Invalid command (response) */
		DIAG_CMD_BAD_PARM     = 20, /* Invalid parameter (response) */
		DIAG_CMD_BAD_LEN      = 21, /* Invalid packet length (response) */
		DIAG_CMD_BAD_DEV      = 22, /* Not accepted by the device (response) */
		DIAG_CMD_BAD_MODE     = 24, /* Not allowed in this mode (response) */
		DIAG_CMD_TAGRAPH      = 25, /* Info for TA power and voice graphs */
		DIAG_CMD_MARKOV       = 26, /* Markov stats */
		DIAG_CMD_MARKOV_RESET = 27, /* Reset Markov stats */
		DIAG_CMD_DIAG_VER     = 28, /* Diagnostic Monitor version */
		DIAG_CMD_TIMESTAMP    = 29, /* Return a timestamp */
		DIAG_CMD_TA_PARM      = 30, /* Set TA parameters */
		DIAG_CMD_MESSAGE      = 31, /* Request for msg report */
		DIAG_CMD_HS_KEY       = 32, /* Handset emulation -- keypress */
		DIAG_CMD_HS_LOCK      = 33, /* Handset emulation -- lock or unlock */
		DIAG_CMD_HS_SCREEN    = 34, /* Handset emulation -- display request */
		DIAG_CMD_PARM_SET     = 36, /* Parameter download */
		DIAG_CMD_NV_READ      = 38, /* Read NV item */
		DIAG_CMD_NV_WRITE     = 39, /* Write NV item */
		DIAG_CMD_CONTROL      = 41, /* Mode change request */
		DIAG_CMD_ERR_READ     = 42, /* Error record retreival */
		DIAG_CMD_ERR_CLEAR    = 43, /* Error record clear */
		DIAG_CMD_SER_RESET    = 44, /* Symbol error rate counter reset */
		DIAG_CMD_SER_REPORT   = 45, /* Symbol error rate counter report */
		DIAG_CMD_TEST         = 46, /* Run a specified test */
		DIAG_CMD_GET_DIPSW    = 47, /* Retreive the current DIP switch setting */
		DIAG_CMD_SET_DIPSW    = 48, /* Write new DIP switch setting */
		DIAG_CMD_VOC_PCM_LB   = 49, /* Start/Stop Vocoder PCM loopback */
		DIAG_CMD_VOC_PKT_LB   = 50, /* Start/Stop Vocoder PKT loopback */
		DIAG_CMD_ORIG         = 53, /* Originate a call */
		DIAG_CMD_END          = 54, /* End a call */
		DIAG_CMD_SW_VERSION   = 56, /* Get software version */
		DIAG_CMD_DLOAD        = 58, /* Switch to downloader */
		DIAG_CMD_TMOB         = 59, /* Test Mode Commands and FTM commands*/
		DIAG_CMD_STATE        = 63, /* Current state of the phone */
		DIAG_CMD_PILOT_SETS   = 64, /* Return all current sets of pilots */
		DIAG_CMD_SPC          = 65, /* Send the Service Programming Code to unlock */
		DIAG_CMD_BAD_SPC_MODE = 66, /* Invalid NV read/write because SP is locked */
		DIAG_CMD_PARM_GET2    = 67, /* (obsolete) */
		DIAG_CMD_SERIAL_CHG   = 68, /* Serial mode change */
		DIAG_CMD_PASSWORD     = 70, /* Send password to unlock secure operations */
		DIAG_CMD_BAD_SEC_MODE = 71, /* Operation not allowed in this security state */
		DIAG_CMD_PRL_WRITE         = 72,  /* Write PRL */
		DIAG_CMD_PRL_READ          = 73,  /* Read PRL */
		DIAG_CMD_SUBSYS            = 75,  /* Subsystem commands */
		DIAG_CMD_FEATURE_QUERY     = 81,
		DIAG_CMD_SMS_READ          = 83,  /* Read SMS message out of NV memory */
		DIAG_CMD_SMS_WRITE         = 84,  /* Write SMS message into NV memory */
		DIAG_CMD_SUP_FER           = 85,  /* Frame Error Rate info on multiple channels */
		DIAG_CMD_SUP_WALSH_CODES   = 86,  /* Supplemental channel walsh codes */
		DIAG_CMD_SET_MAX_SUP_CH    = 87,  /* Sets the maximum # supplemental channels */
		DIAG_CMD_PARM_GET_IS95B    = 88,  /* Get parameters including SUPP and MUX2 */
		DIAG_CMD_FS_OP             = 89,  /* Embedded File System (EFS) operations */
		DIAG_CMD_AKEY_VERIFY       = 90,  /* AKEY Verification */
		DIAG_CMD_HS_BMP_SCREEN     = 91,  /* Handset Emulation -- Bitmap screen */
		DIAG_CMD_CONFIG_COMM       = 92,  /* Configure communications */
		DIAG_CMD_EXT_LOGMASK       = 93,  /* Extended logmask for > 32 bits */
		DIAG_CMD_EVENT_REPORT      = 96,  /* Static Event reporting */
		DIAG_CMD_STREAMING_CONFIG  = 97,  /* Load balancing etc */
		DIAG_CMD_PARM_RETRIEVE     = 98,  /* Parameter retrieval */
		DIAG_CMD_STATUS_SNAPSHOT   = 99,  /* Status snapshot */
		DIAG_CMD_RPC               = 100, /* Used for RPC */
		DIAG_CMD_GET_PROPERTY      = 101,
		DIAG_CMD_PUT_PROPERTY      = 102,
		DIAG_CMD_GET_GUID          = 103, /* GUID requests */
		DIAG_CMD_USER_CMD          = 104, /* User callbacks */
		DIAG_CMD_GET_PERM_PROPERTY = 105,
		DIAG_CMD_PUT_PERM_PROPERTY = 106,
		DIAG_CMD_PERM_USER_CMD     = 107, /* Permanent user callbacks */
		DIAG_CMD_GPS_SESS_CTRL     = 108, /* GPS session control */
		DIAG_CMD_GPS_GRID          = 109, /* GPS search grid */
		DIAG_CMD_GPS_STATISTICS    = 110,
		DIAG_CMD_TUNNEL            = 111, /* Tunneling command code */
		DIAG_CMD_RAM_RW            = 112, /* Calibration RAM control using DM */
		DIAG_CMD_CPU_RW            = 113, /* Calibration CPU control using DM */
		DIAG_CMD_SET_FTM_TEST_MODE = 114, /* Field (or Factory?) Test Mode */
		DIAG_CMD_DEVICE_CHIPSET = 124,
	};

	/* Subsystem IDs used with DIAG_CMD_SUBSYS; these often obsolete many of
	 * the original DM commands.
	 */
	enum DMSubsysCommand{
		DIAG_SUBSYS_HDR             = 5,  /* High Data Rate (ie, EVDO) */
		DIAG_SUBSYS_GPS             = 13,
		DIAG_SUBSYS_SMS             = 14,
		DIAG_SUBSYS_CM              = 15, /* Call manager */
		DIAG_SUBSYS_NW_CONTROL_6500 = 50, /* for Novatel Wireless MSM6500-based devices */
		DIAG_SUBSYS_ZTE             = 101, /* for ZTE EVDO devices */
		DIAG_SUBSYS_NW_CONTROL_6800 = 250 /* for Novatel Wireless MSM6800-based devices */
	};

	enum DMMode{
		DIAG_MODE_OFFLINE_D = 0x1,
		DIAG_MODE_RESET = 0x2,
		DIAG_MODE_UNKNOWN_1 = 0x3,
		DIAG_MODE_ONLINE = 0x4,
		DIAG_MODE_LOW = 0x5,
	};

	/*
	Public modeOfflineD() As Byte = {&H29, &H1, &H0, &H31, &H40, &H7E}
    Public modeReset() As Byte = {&H29, &H2, &H0, &H59, &H6A, &H7E}
    Public modeOnline() As Byte = {&H29, &H4, &H0, &H89, &H3E, &H7E}
    Public modeLow() As Byte = {&H29, &H5, &H0, &H51, &H27, &H7E}
	*/
	/* HDR subsystem command codes */
	enum {
		DIAG_SUBSYS_HDR_STATE_INFO  = 8, /* Gets EVDO state */
	};

	enum {
		DIAG_SUBSYS_CM_STATE_INFO = 0, /* Gets Call Manager state */
	};

	/* NW_CONTROL subsystem command codes (only for Novatel Wireless devices) */
	enum {
		DIAG_SUBSYS_NW_CONTROL_AT_REQUEST     = 3, /* AT commands via diag */
		DIAG_SUBSYS_NW_CONTROL_AT_RESPONSE    = 4,
		DIAG_SUBSYS_NW_CONTROL_MODEM_SNAPSHOT = 7,
		DIAG_SUBSYS_NW_CONTROL_ERI            = 8, /* Extended Roaming Indicator */
		DIAG_SUBSYS_NW_CONTROL_PRL            = 12,
	};

	enum {
		DIAG_SUBSYS_NW_CONTROL_MODEM_SNAPSHOT_TECH_CDMA_EVDO = 7,
		DIAG_SUBSYS_NW_CONTROL_MODEM_SNAPSHOT_TECH_WCDMA = 20,
	};

	enum {
		DIAG_SUBSYS_ZTE_STATUS = 0,
	};

	enum {
		CDMA_PREV_UNKNOWN       = 0,
		CDMA_PREV_IS_95         = 1, /* and J_STD008 */
		CDMA_PREV_IS_95A        = 2,
		CDMA_PREV_IS_95A_TSB74  = 3,
		CDMA_PREV_IS_95B_PHASE1 = 4,
		CDMA_PREV_IS_95B_PHASE2 = 5,
		CDMA_PREV_IS2000_REL0   = 6,
		CDMA_PREV_IS2000_RELA   = 7
	};

	enum {
		CDMA_BAND_CLASS_0_CELLULAR_800   = 0,  /* 800 MHz cellular band */
		CDMA_BAND_CLASS_1_PCS            = 1,  /* 1800 to 2000 MHz PCS band */
		CDMA_BAND_CLASS_2_TACS           = 2,  /* 872 to 960 MHz TACS band */
		CDMA_BAND_CLASS_3_JTACS          = 3,  /* 832 to 925 MHz JTACS band */
		CDMA_BAND_CLASS_4_KOREAN_PCS     = 4,  /* 1750 to 1870 MHz Korean PCS band */
		CDMA_BAND_CLASS_5_NMT450         = 5,  /* 450 MHz NMT band */
		CDMA_BAND_CLASS_6_IMT2000        = 6,  /* 2100 MHz IMT-2000 band */
		CDMA_BAND_CLASS_7_CELLULAR_700   = 7,  /* Upper 700 MHz band */
		CDMA_BAND_CLASS_8_1800           = 8,  /* 1800 MHz band */
		CDMA_BAND_CLASS_9_900            = 9,  /* 900 MHz band */
		CDMA_BAND_CLASS_10_SECONDARY_800 = 10, /* Secondary 800 MHz band */
		CDMA_BAND_CLASS_11_PAMR_400      = 11, /* 400 MHz European PAMR band */
		CDMA_BAND_CLASS_12_PAMR_800      = 12, /* 800 MHz PAMR band */
		CDMA_BAND_CLASS_13_IMT2000_2500  = 13, /* 2500 MHz IMT-2000 Extension Band */
		CDMA_BAND_CLASS_14_US_PCS_1900   = 14, /* US PCS 1900 MHz Band */
		CDMA_BAND_CLASS_15_AWS           = 15, /* AWS 1700 MHz band */
		CDMA_BAND_CLASS_16_US_2500       = 16, /* US 2500 MHz Band */
		CDMA_BAND_CLASS_17_US_FLO_2500   = 17, /* US 2500 MHz Forward Link Only Band */
		CDMA_BAND_CLASS_18_US_PS_700     = 18, /* 700 MHz Public Safety Band */
		CDMA_BAND_CLASS_19_US_LOWER_700  = 19  /* Lower 700 MHz Band */
	};

	enum {
		CDMA_STATUS_SNAPSHOT_STATE_NO_SERVICE         = 0x00,
		CDMA_STATUS_SNAPSHOT_STATE_INITIALIZATION     = 0x01,
		CDMA_STATUS_SNAPSHOT_STATE_IDLE               = 0x02,
		CDMA_STATUS_SNAPSHOT_STATE_VOICE_CHANNEL_INIT = 0x03,
		CDMA_STATUS_SNAPSHOT_STATE_WAITING_FOR_ORDER  = 0x04,
		CDMA_STATUS_SNAPSHOT_STATE_WAITING_FOR_ANSWER = 0x05,
		CDMA_STATUS_SNAPSHOT_STATE_CONVERSATION       = 0x06,
		CDMA_STATUS_SNAPSHOT_STATE_RELEASE            = 0x07,
		CDMA_STATUS_SNAPSHOT_STATE_SYSTEM_ACCESS      = 0x08,
		CDMA_STATUS_SNAPSHOT_STATE_OFFLINE_CDMA       = 0x10,
		CDMA_STATUS_SNAPSHOT_STATE_OFFLINE_HDR        = 0x11,
		CDMA_STATUS_SNAPSHOT_STATE_OFFLINE_ANALOG     = 0x12,
		CDMA_STATUS_SNAPSHOT_STATE_RESET              = 0x13,
		CDMA_STATUS_SNAPSHOT_STATE_POWER_DOWN         = 0x14,
		CDMA_STATUS_SNAPSHOT_STATE_POWER_SAVE         = 0x15,
		CDMA_STATUS_SNAPSHOT_STATE_POWER_UP           = 0x16,
		CDMA_STATUS_SNAPSHOT_STATE_LOW_POWER_MODE     = 0x17,
		CDMA_STATUS_SNAPSHOT_STATE_SEARCHER_DSMM      = 0x18, /* Dedicated System Measurement Mode */
		CDMA_STATUS_SNAPSHOT_STATE_HDR                = 0x40,
	};

	enum {
		QCDM_SUCCESS = 0,
		QCDM_ERROR_INVALID_ARGUMENTS = 1,
		QCDM_ERROR_SERIAL_CONFIG_FAILED = 2,
		QCDM_ERROR_VALUE_NOT_FOUND = 3,
		QCDM_ERROR_RESPONSE_UNEXPECTED = 4,
		QCDM_ERROR_RESPONSE_BAD_LENGTH = 5,
		QCDM_ERROR_RESPONSE_MALFORMED = 6,
		QCDM_ERROR_RESPONSE_BAD_COMMAND = 7,
		QCDM_ERROR_RESPONSE_BAD_PARAMETER = 8,
		QCDM_ERROR_RESPONSE_NOT_ACCEPTED = 9,
		QCDM_ERROR_RESPONSE_BAD_MODE = 10,
		QCDM_ERROR_NVCMD_FAILED = 11,
		QCDM_ERROR_SPC_LOCKED = 12,
		QCDM_ERROR_NV_ERROR_BUSY = 13,
		QCDM_ERROR_NV_ERROR_BAD_COMMAND = 14,
		QCDM_ERROR_NV_ERROR_MEMORY_FULL = 15,
		QCDM_ERROR_NV_ERROR_FAILED = 16,
		QCDM_ERROR_NV_ERROR_INACTIVE = 17,  /* NV location is not active */
		QCDM_ERROR_NV_ERROR_BAD_PARAMETER = 18,
		QCDM_ERROR_NV_ERROR_READ_ONLY = 19, /* NV location is read-only */
		QCDM_ERROR_RESPONSE_FAILED = 20,    /* command-specific failure */
	};

	enum{ 
        NOT_AN_NV_ITEM = -1222,
        NV_ESN_I = 0,
        NV_ESN_CHKSUM_I,
        NV_VERNO_MAJ_I,
        NV_VERNO_MIN_I,
        NV_SCM_I,
        NV_SLOT_CYCLE_INDEX_I,
        NV_MOB_CAI_REV_I,
        NV_MOB_FIRM_REV_I,
        NV_MOB_MODEL_I,
        NV_CONFIG_CHKSUM_I,
        NV_PREF_MODE_I,
        NV_CDMA_PREF_SERV_I,
        NV_ANALOG_PREF_SERV_I,
        NV_CDMA_SID_LOCK_I,
        NV_CDMA_SID_ACQ_I,
        NV_ANALOG_SID_LOCK_I,
        NV_ANALOG_SID_ACQ_I,
        NV_ANALOG_FIRSTCHP_I,
        NV_ANALOG_HOME_SID_I,
        NV_ANALOG_REG_I,
        NV_PCDMACH_I,
        NV_SCDMACH_I,
        NV_PPCNCH_I,
        NV_SPCNCH_I,
        NV_NAM_CHKSUM_I,
        NV_A_KEY_I,
        NV_A_KEY_CHKSUM_I,
        NV_SSD_A_I,
        NV_SSD_A_CHKSUM_I,
        NV_SSD_B_I,
        NV_SSD_B_CHKSUM_I,
        NV_COUNT_I,
        NV_MIN1_I,
        NV_MIN2_I,
        NV_MOB_TERM_HOME_I,
        NV_MOB_TERM_FOR_SID_I,
        NV_MOB_TERM_FOR_NID_I,
        NV_ACCOLC_I,
        NV_SID_NID_I,
        NV_MIN_CHKSUM_I,
        NV_CURR_NAM_I,
        NV_ORIG_MIN_I,
        NV_AUTO_NAM_I,
        NV_NAME_NAM_I,
        NV_NXTREG_I,
        NV_LSTSID_I,
        NV_LOCAID_I,
        NV_PUREG_I,
        NV_ZONE_LIST_I,
        NV_SID_NID_LIST_I,
        NV_DIST_REG_I,
        NV_LAST_CDMACH_I,
        NV_CALL_TIMER_I,
        NV_AIR_TIMER_I,
        NV_ROAM_TIMER_I,
        NV_LIFE_TIMER_I,
        NV_RUN_TIMER_I,
        NV_DIAL_I,
        NV_STACK_I,
        NV_STACK_IDX_I,
        NV_PAGE_SET_I,
        NV_PAGE_MSG_I,
        NV_EAR_LVL_I,
        NV_SPEAKER_LVL_I,
        NV_RINGER_LVL_I,
        NV_BEEP_LVL_I,
        NV_CALL_BEEP_I,
        NV_CONT_KEY_DTMF_I,
        NV_CONT_STR_DTMF_I,
        NV_SVC_AREA_ALERT_I,
        NV_CALL_FADE_ALERT_I,
        NV_BANNER_I,
        NV_LCD_I,
        NV_AUTO_POWER_I,
        NV_AUTO_ANSWER_I,
        NV_AUTO_REDIAL_I,
        NV_AUTO_HYPHEN_I,
        NV_BACK_LIGHT_I,
        NV_AUTO_MUTE_I,
        NV_MAINTRSN_I,
        NV_LCKRSN_P_I,
        NV_LOCK_I,
        NV_LOCK_CODE_I,
        NV_AUTO_LOCK_I,
        NV_CALL_RSTRC_I,
        NV_SEC_CODE_I,
        NV_HORN_ALERT_I,
        NV_ERR_LOG_I,
        NV_UNIT_ID_I,
        NV_FREQ_ADJ_I,
        NV_VBATT_I,
        NV_FM_TX_PWR_I,
        NV_FR_TEMP_OFFSET_I,
        NV_DM_IO_MODE_I,
        NV_CDMA_TX_LIMIT_I,
        NV_FM_RSSI_I,
        NV_CDMA_RIPPLE_I,
        NV_CDMA_RX_OFFSET_I,
        NV_CDMA_RX_POWER_I,
        NV_CDMA_RX_ERROR_I,
        NV_CDMA_TX_SLOPE_1_I,
        NV_CDMA_TX_SLOPE_2_I,
        NV_CDMA_TX_ERROR_I,
        NV_PA_CURRENT_CTL_I,
        NV_SONY_ATTEN_1_I,
        NV_SONY_ATTEN_2_I,
        NV_VOC_GAIN_I,
        NV_SPARE_1_I,
        NV_SPARE_2_I,
        NV_DATA_SRVC_STATE_I,
        NV_DATA_IO_MODE_I,
        NV_IDLE_DATA_TIMEOUT_I,
        NV_MAX_TX_ADJ_I,
        NV_INI_MUTE_I,
        NV_FACTORY_INFO_I,
        NV_SONY_ATTEN_3_I,
        NV_SONY_ATTEN_4_I,
        NV_SONY_ATTEN_5_I,
        NV_DM_ADDR_I,
        NV_CDMA_PN_MASK_I,
        NV_SEND_TIMEOUT_I,
        NV_FM_AGC_SET_VS_PWR_I,
        NV_FM_AGC_SET_VS_FREQ_I,
        NV_FM_AGC_SET_VS_TEMP_I,
        NV_FM_EXP_HDET_VS_PWR_I,
        NV_FM_ERR_SLP_VS_PWR_I,
        NV_FM_FREQ_SENSE_GAIN_I,
        NV_CDMA_RX_LIN_OFF_0_I,
        NV_CDMA_RX_LIN_SLP_I,
        NV_CDMA_RX_COMP_VS_FREQ_I,
        NV_CDMA_TX_COMP_VS_FREQ_I,
        NV_CDMA_TX_COMP_VS_VOLT_I,
        NV_CDMA_TX_LIN_MASTER_OFF_0_I,
        NV_CDMA_TX_LIN_MASTER_SLP_I,
        NV_CDMA_TX_LIN_VS_TEMP_I,
        NV_CDMA_TX_LIN_VS_VOLT_I,
        NV_CDMA_TX_LIM_VS_TEMP_I,
        NV_CDMA_TX_LIM_VS_VOLT_I,
        NV_CDMA_TX_LIM_VS_FREQ_I,
        NV_CDMA_EXP_HDET_VS_AGC_I,
        NV_CDMA_ERR_SLP_VS_HDET_I,
        NV_THERM_I,
        NV_VBATT_PA_I,
        NV_HDET_OFF_I,
        NV_HDET_SPN_I,
        NV_ONETOUCH_DIAL_I,
        NV_FM_AGC_ADJ_VS_FREQ_I,
        NV_FM_AGC_ADJ_VS_TEMP_I,
        NV_RF_CONFIG_I,
        NV_R1_RISE_I,
        NV_R1_FALL_I,
        NV_R2_RISE_I,
        NV_R2_FALL_I,
        NV_R3_RISE_I,
        NV_R3_FALL_I,
        NV_PA_RANGE_STEP_CAL_I,
        NV_LNA_RANGE_POL_I,
        NV_LNA_RANGE_RISE_I,
        NV_LNA_RANGE_FALL_I,
        NV_LNA_RANGE_OFFSET_I,
        NV_POWER_CYCLES_I,
        NV_ALERTS_LVL_I,
        NV_ALERTS_LVL_SHADOW_I,
        NV_RINGER_LVL_SHADOW_I,
        NV_BEEP_LVL_SHADOW_I,
        NV_EAR_LVL_SHADOW_I,
        NV_TIME_SHOW_I,
        NV_MESSAGE_ALERT_I,
        NV_AIR_CNT_I,
        NV_ROAM_CNT_I,
        NV_LIFE_CNT_I,
        NV_SEND_PIN_I,
        NV_AUTO_ANSWER_SHADOW_I,
        NV_AUTO_REDIAL_SHADOW_I,
        NV_SMS_I,
        NV_SMS_DM_I,
        NV_IMSI_MCC_I,
        NV_IMSI_11_12_I,
        NV_DIR_NUMBER_I,
        NV_VOICE_PRIV_I,
        NV_SPARE_B1_I,
        NV_SPARE_B2_I,
        NV_SPARE_W1_I,
        NV_SPARE_W2_I,
        NV_FSC_I,
        NV_ALARMS_I,
        NV_STANDING_ALARM_I,
        NV_ISD_STD_PASSWD_I,
        NV_ISD_STD_RESTRICT_I,
        NV_DIALING_PLAN_I,
        NV_FM_LNA_CTL_I,
        NV_LIFE_TIMER_G_I,
        NV_CALL_TIMER_G_I,
        NV_PWR_DWN_CNT_I,
        NV_FM_AGC_I,
        NV_FSC2_I,
        NV_FSC2_CHKSUM_I,
        NV_WDC_I,
        NV_HW_CONFIG_I,
        NV_CDMA_RX_LIN_VS_TEMP_I,
        NV_CDMA_ADJ_FACTOR_I,
        NV_CDMA_TX_LIM_BOOSTER_OFF_I,
        NV_CDMA_RX_SLP_VS_TEMP_I,
        NV_CDMA_TX_SLP_VS_TEMP_I,
        NV_PA_RANGE_VS_TEMP_I,
        NV_LNA_SWITCH_VS_TEMP_I,
        NV_FM_EXP_HDET_VS_TEMP_I,
        NV_N1M_I,
        NV_IMSI_I,
        NV_IMSI_ADDR_NUM_I,
        NV_ASSIGNING_TMSI_ZONE_LEN_I,
        NV_ASSIGNING_TMSI_ZONE_I,
        NV_TMSI_CODE_I,
        NV_TMSI_EXP_I,
        NV_HOME_PCS_FREQ_BLOCK_I,
        NV_DIR_NUMBER_PCS_I,
        NV_ROAMING_LIST_I,
        NV_MRU_TABLE_I,
        NV_REDIAL_I,
        NV_OTKSL_I,
        NV_TIMED_PREF_MODE_I,
        NV_RINGER_TYPE_I,
        NV_ANY_KEY_ANSWER_I,
        NV_BACK_LIGHT_HFK_I,
        NV_RESTRICT_GLOBAL_I,
        NV_KEY_SOUND_I,
        NV_DIALS_SORTING_METHOD_I,
        NV_LANGUAGE_SELECTION_I,
        NV_MENU_FORMAT_I,
        NV_RINGER_SPKR_LVL_I,
        NV_BEEP_SPKR_LVL_I,
        NV_MRU2_TABLE_I,
        NV_VIBRATOR_I,
        NV_FLIP_ANSWERS_I,
        NV_DIAL_RESTRICT_LVLS_I,
        NV_DIAL_STATE_TABLE_LEN_I,
        NV_DIAL_STATE_TABLE_I,
        NV_VOICE_PRIV_ALERT_I,
        NV_IP_ADDRESS_I,
        NV_CURR_GATEWAY_I,
        NV_DATA_QNC_ENABLED_I,
        NV_DATA_SO_SET_I,
        NV_UP_LINK_INFO_I,
        NV_UP_PARMS_I,
        NV_UP_CACHE_I,
        NV_ELAPSED_TIME_I,
        NV_PDM2_I,
        NV_RX_AGC_MINMAX_I,
        NV_VBATT_AUX_I,
        NV_DTACO_CONTROL_I,
        NV_DTACO_INTERDIGIT_TIMEOUT_I,
        NV_PDM1_I,
        NV_BELL_MODEM_I,
        NV_PDM1_VS_TEMP_I,
        NV_PDM2_VS_TEMP_I,
        NV_SID_NID_LOCK_I,
        NV_PRL_ENABLED_I,
        NV_ROAMING_LIST_683_I,
        NV_SYSTEM_PREF_I,
        NV_HOME_SID_NID_I,
        NV_OTAPA_ENABLED_I,
        NV_NAM_LOCK_I,
        NV_IMSI_T_S1_I,
        NV_IMSI_T_S2_I,
        NV_IMSI_T_MCC_I,
        NV_IMSI_T_11_12_I,
        NV_IMSI_T_ADDR_NUM_I,
        NV_UP_ALERTS_I,
        NV_UP_IDLE_TIMER_I,
        NV_SMS_UTC_I,
        NV_ROAM_RINGER_I,
        NV_RENTAL_TIMER_I,
        NV_RENTAL_TIMER_INC_I,
        NV_RENTAL_CNT_I,
        NV_RENTAL_TIMER_ENABLED_I,
        NV_FULL_SYSTEM_PREF_I,
        NV_BORSCHT_RINGER_FREQ_I,
        NV_PAYPHONE_ENABLE_I,
        NV_DSP_ANSWER_DET_ENABLE_I,
        NV_EVRC_PRI_I,
        NV_AFAX_CLASS_20_I,
        NV_V52_CONTROL_I,
        NV_CARRIER_INFO_I,
        NV_AFAX_I,
        NV_SIO_PWRDWN_I,
        NV_PREF_VOICE_SO_I,
        NV_VRHFK_ENABLED_I,
        NV_VRHFK_VOICE_ANSWER_I,
        NV_PDM1_VS_FREQ_I,
        NV_PDM2_VS_FREQ_I,
        NV_SMS_AUTO_DELETE_I,
        NV_SRDA_ENABLED_I,
        NV_OUTPUT_UI_KEYS_I,
        NV_POL_REV_TIMEOUT_I,
        NV_SI_TEST_DATA_1_I,
        NV_SI_TEST_DATA_2_I,
        NV_SPC_CHANGE_ENABLED_I,
        NV_DATA_MDR_MODE_I,
        NV_DATA_PKT_ORIG_STR_I,
        NV_UP_KEY_I,
        NV_DATA_AUTO_PACKET_DETECTION_I,
        NV_AUTO_VOLUME_ENABLED_I,
        NV_WILDCARD_SID_I,
        NV_ROAM_MSG_I,
        NV_OTKSL_FLAG_I,
        NV_BROWSER_TYPE_I,
        NV_SMS_REMINDER_TONE_I,
        NV_UBROWSER_I,
        NV_BTF_ADJUST_I,
        NV_FULL_PREF_MODE_I,
        NV_UP_BROWSER_WARN_I,
        NV_FM_HDET_ADC_RANGE_I,
        NV_CDMA_HDET_ADC_RANGE_I,
        NV_PN_ID_I,
        NV_USER_ZONE_ENABLED_I,
        NV_USER_ZONE_I,
        NV_PAP_DATA_I,
        NV_DATA_DEFAULT_PROFILE_I,
        NV_PAP_USER_ID_I = 318,
        NV_PAP_PASSWORD_I,
        NV_STA_TBYE_I,
        NV_STA_MIN_THR_I,
        NV_STA_MIN_RX_I,
        NV_STA_MIN_ECIO_I,
        NV_STA_PRI_I,
        NV_PCS_RX_LIN_OFF_0_I = 325,
        NV_PCS_RX_LIN_SLP_I = 326,
        NV_PCS_RX_COMP_VS_FREQ_I = 327,
        NV_PCS_TX_COMP_VS_FREQ_I = 328,
        NV_PCS_TX_LIN_MASTER_OFF_0_I = 329,
        NV_PCS_TX_LIN_MASTER_SLP_I = 330,
        NV_PCS_TX_LIN_VS_TEMP_I = 331,
        NV_PCS_TX_LIM_VS_TEMP_I = 332,
        NV_PCS_TX_LIM_VS_FREQ_I = 333,
        NV_PCS_EXP_HDET_VS_AGC_I = 334,
        NV_PCS_HDET_OFF_I = 335,
        NV_PCS_HDET_SPN_I = 336,
        NV_PCS_R1_RISE_I = 337,
        NV_PCS_R1_FALL_I = 338,
        NV_PCS_R2_RISE_I = 339,
        NV_PCS_R2_FALL_I = 340,
        NV_PCS_R3_RISE_I = 341,
        NV_PCS_R3_FALL_I = 342,
        NV_PCS_PA_RANGE_STEP_CAL_I = 343,
        NV_PCS_PDM1_VS_FREQ_I = 344,
        NV_PCS_PDM2_VS_FREQ_I = 345,
        NV_PCS_LNA_RANGE_POL_I = 346,
        NV_PCS_LNA_RANGE_RISE_I = 347,
        NV_PCS_LNA_RANGE_FALL_I = 348,
        NV_PCS_LNA_RANGE_OFFSET_I = 349,
        NV_PCS_RX_LIN_VS_TEMP_I = 350,
        NV_PCS_ADJ_FACTOR_I = 351,
        NV_PCS_PA_RANGE_VS_TEMP_I = 352,
        NV_PCS_PDM1_VS_TEMP_I = 353,
        NV_PCS_PDM2_VS_TEMP_I = 354,
        NV_PCS_RX_SLP_VS_TEMP_I = 355,
        NV_PCS_TX_SLP_VS_TEMP_I = 356,
        NV_PCS_RX_AGC_MINMAX_I = 357,
        NV_PA_OFFSETS_I = 358,
        NV_CDMA_TX_LIN_MASTER_I = 359,
        NV_VEXT_I = 360,
        NV_VLCD_ADC_CNT_I = 361,
        NV_VLCD_DRVR_CNT_I = 362,
        NV_VREF_ADJ_PDM_CNT_I = 363,
        NV_IBAT_PER_LSB_I = 364,
        NV_IEXT_I = 365,
        NV_IEXT_THR_I = 366,
        NV_CDMA_TX_LIN_MASTER0_I = 367,
        NV_CDMA_TX_LIN_MASTER1_I = 368,
        NV_CDMA_TX_LIN_MASTER2_I = 369,
        NV_CDMA_TX_LIN_MASTER3_I = 370,
        NV_TIME_FMT_SELECTION_I = 371,
        NV_SMS_BC_SERVICE_TABLE_SIZE_I = 372,
        NV_SMS_BC_SERVICE_TABLE_I = 373,
        NV_SMS_BC_CONFIG_I = 374,
        NV_SMS_BC_USER_PREF_I = 375,
        NV_LNA_RANGE_2_RISE_I = 376,
        NV_LNA_RANGE_2_FALL_I = 377,
        NV_LNA_RANGE_12_OFFSET_I = 378,
        NV_NONBYPASS_TIMER_I = 379,
        NV_BYPASS_TIMER_I = 380,
        NV_IM_LEVEL1_I = 381,
        NV_IM_LEVEL2_I = 382,
        NV_CDMA_LNA_OFFSET_VS_FREQ_I = 383,
        NV_CDMA_LNA_12_OFFSET_VS_FREQ_I = 384,
        NV_AGC_PHASE_OFFSET_I = 385,
        NV_RX_AGC_MIN_11_I = 386,
        NV_PCS_LNA_RANGE_2_RISE_I = 387,
        NV_PCS_LNA_RANGE_2_FALL_I = 388,
        NV_PCS_LNA_RANGE_12_OFFSET_I = 389,
        NV_PCS_NONBYPASS_TIMER_I = 390,
        NV_PCS_BYPASS_TIMER_I = 391,
        NV_PCS_IM_LEVEL1_I = 392,
        NV_PCS_IM_LEVEL2_I = 393,
        NV_PCS_CDMA_LNA_OFFSET_VS_FREQ_I = 394,
        NV_PCS_CDMA_LNA_12_OFFSET_VS_FREQ_I = 395,
        NV_PCS_AGC_PHASE_OFFSET_I = 396,
        NV_PCS_RX_AGC_MIN_11_I = 397,
        NV_RUIM_CHV_1_I = 398,
        NV_RUIM_CHV_2_I = 399,
        NV_GPS1_CAPABILITIES_I = 400,
        NV_GPS1_PDE_ADDRESS_I = 401,
        NV_GPS1_ALLOWED_I = 402,
        NV_GPS1_PDE_TRANSPORT_I = 403,
        NV_GPS1_MOBILE_CALC_I = 404,
        NV_PREF_FOR_RC_I = 405,
        NV_SIO_DEV_MAP_MENU_ITEM_I = 408,
        NV_TTY_I = 409,
        NV_PA_RANGE_OFFSETS_I = 410,
        NV_TX_COMP0_I = 411,
        NV_MM_SDAC_LVL_I = 412,
        NV_BEEP_SDAC_LVL_I = 413,
        NV_SDAC_LVL_I = 414,
        NV_MM_LVL_I = 415,
        NV_MM_LVL_SHADOW_I = 416,
        NV_MM_SPEAKER_LVL_I = 417,
        NV_MM_PLAY_MODE_I = 418,
        NV_MM_REPEAT_MODE_I = 419,
        NV_TX_COMP1_I = 420,
        NV_TX_COMP2_I = 421,
        NV_TX_COMP3_I = 422,
        NV_PRIMARY_DNS_I = 423,
        NV_SECONDARY_DNS_I = 424,
        NV_DIAG_PORT_SELECT_I = 425,
        NV_GPS1_PDE_PORT_I = 426,
        NV_MM_RINGER_FILE_I = 427,
        NV_MM_STORAGE_DEVICE_I = 428,
        NV_DATA_SCRM_ENABLED_I = 429,
        NV_RUIM_SMS_STATUS_I = 430,
        NV_PCS_TX_LIN_MASTER0_I = 431,
        NV_PCS_TX_LIN_MASTER1_I = 432,
        NV_PCS_TX_LIN_MASTER2_I = 433,
        NV_PCS_TX_LIN_MASTER3_I = 434,
        NV_PCS_PA_RANGE_OFFSETS_I = 435,
        NV_PCS_TX_COMP0_I = 436,
        NV_PCS_TX_COMP1_I = 437,
        NV_PCS_TX_COMP2_I = 438,
        NV_PCS_TX_COMP3_I = 439,
        NV_DIAG_RESTART_CONFIG_I = 440,
        NV_BAND_PREF_I = 441,
        NV_ROAM_PREF_I = 442,
        NV_GPS1_GPS_RF_DELAY_I = 443,
        NV_GPS1_CDMA_RF_DELAY_I = 444,
        NV_PCS_ENC_BTF_I = 445,
        NV_CDMA_ENC_BTF_I = 446,
        NV_BD_ADDR_I = 447,
        NV_SUBPCG_PA_WARMUP_DELAY_I = 448,
        NV_GPS1_GPS_RF_LOSS_I = 449,
        NV_DATA_TRTL_ENABLED_I = 450,
        NV_AMPS_BACKSTOP_ENABLED_I = 451,
        NV_RSVD_ITEM_452_I = 452,
        NV_RSVD_ITEM_453_I = 453,
        NV_DS_DEFAULT_BAUD_I = 454,
        NV_DIAG_DEFAULT_BAUD_I = 455,
        NV_RSVD_ITEM_456_I = 456,
        NV_RSVD_ITEM_457_I = 457,
        NV_RSVD_ITEM_458_I = 458,
        NV_DS_QCMIP_I = 459,
        NV_DS_MIP_RETRIES_I = 460,
        NV_DS_MIP_RETRY_INT_I = 461,
        NV_DS_MIP_PRE_RE_RRQ_TIME_I = 462,
        NV_DS_MIP_NUM_PROF_I = 463,
        NV_DS_MIP_ACTIVE_PROF_I = 464,
        NV_DS_MIP_GEN_USER_PROF_I = 465,
        NV_RSVD_ITEM_466_I = 466,
        NV_RSVD_ITEM_467_I = 467,
        NV_RSVD_ITEM_468_I = 468,
        NV_RSVD_ITEM_469_I = 469,
        NV_RSVD_ITEM_470_I = 470,
        NV_RSVD_ITEM_471_I = 471,
        NV_RSVD_ITEM_472_I = 472,
        NV_RSVD_ITEM_473_I = 473,
        NV_RSVD_ITEM_474_I = 474,
        NV_RSVD_ITEM_475_I = 475,
        NV_RSVD_ITEM_476_I = 476,
        NV_RSVD_ITEM_477_I = 477,
        NV_RSVD_ITEM_478_I = 478,
        NV_RSVD_ITEM_479_I = 479,
        NV_RSVD_ITEM_480_I = 480,
        NV_RSVD_ITEM_481_I = 481,
        NV_RSVD_ITEM_482_I = 482,
        NV_RSVD_ITEM_483_I = 483,
        NV_RSVD_ITEM_484_I = 484,
        NV_RSVD_ITEM_485_I = 485,
        NV_RSVD_ITEM_486_I = 486,
        NV_RSVD_ITEM_487_I = 487,
        NV_RSVD_ITEM_488_I = 488,
        NV_RSVD_ITEM_489_I = 489,
        NV_RSVD_ITEM_490_I = 490,
        NV_RSVD_ITEM_491_I = 491,
        NV_RSVD_ITEM_492_I = 492,
        NV_RSVD_ITEM_493_I = 493,
        NV_RSVD_ITEM_494_I = 494,
        NV_DS_MIP_QC_DRS_OPT_I = 495,
        NV_RSVD_ITEM_496_I = 496,
        NV_RSVD_ITEM_497_I = 497,
        NV_RSVD_ITEM_498_I = 498,
        NV_RSVD_ITEM_499_I = 499,
        NV_RSVD_ITEM_500_I = 500,
        NV_RING_SOUND_I = 501,
        NV_VIB_LVL_I = 502,
        NV_MULTILANG_I = 503,
        NV_CALL_CONNECT_ALERT_I = 504,
        NV_THEME_I = 505,
        NV_CARRIER_LOGO_I = 506,
        NV_RSVD_ITEM_507_I = 507,
        NV_RSVD_ITEM_508_I = 508,
        NV_RSVD_ITEM_509_I = 509,
        NV_RSVD_ITEM_510_I = 510,
        NV_RSVD_ITEM_511_I = 511,
        NV_RSVD_ITEM_512_I = 512,
        NV_RSVD_ITEM_513_I = 513,
        NV_RSVD_ITEM_514_I = 514,
        NV_RSVD_ITEM_515_I = 515,
        NV_RSVD_ITEM_516_I = 516,
        NV_RSVD_ITEM_517_I = 517,
        NV_CDMA_TX_LIM_VS_TEMP_FREQ_I = 518,
        NV_RSVD_ITEM_519_I = 519,
        NV_RSVD_ITEM_520_I = 520,
        NV_VOCODER_I = 521,
        NV_ENHANCED_RC_I = 522,
        NV_PREF_REV_RC_I = 523,
        NV_RSVD_ITEM_524_I = 524,
        NV_RSVD_ITEM_525_I = 525,
        NV_RSVD_ITEM_526_I = 526,
        NV_RSVD_ITEM_527_I = 527,
        NV_RSVD_ITEM_528_I = 528,
        NV_RSVD_ITEM_529_I = 529,
        NV_RSVD_ITEM_530_I = 530,
        NV_SMS_ALERT_SEL_I = 531,
        NV_SMS_2MIN_ALERT_I = 532,
        NV_SMS_DEFERRED_SEL_I = 533,
        NV_SMS_VALIDITY_SEL_I = 534,
        NV_SMS_PRIORITY_SEL_I = 535,
        NV_SMS_REPLY_SEL_I = 536,
        NV_SMS_DEST_ALERT_SEL_I = 537,
        NV_SMS_ORIG_MSG_ID_I = 538,
        NV_RSVD_ITEM_539_I = 539,
        NV_RSVD_ITEM_540_I = 540,
        NV_RSVD_ITEM_541_I = 541,
        NV_RSVD_ITEM_542_I = 542,
        NV_RSVD_ITEM_543_I = 543,
        NV_RSVD_ITEM_544_I = 544,
        NV_RSVD_ITEM_545_I = 545,
        NV_DS_MIP_2002BIS_MN_HA_AUTH_I = 546,
        NV_RSVD_ITEM_547_I = 547,
        NV_RSVD_ITEM_548_I = 548,
        NV_RSVD_ITEM_549_I = 549,
        NV_RSVD_ITEM_550_I = 550,
        NV_LNA_GAIN_POL_I = 551,
        NV_LNA_GAIN_PWR_MIN_I = 552,
        NV_LNA_GAIN_PWR_MAX_I = 553,
        NV_CDMA_LNA_LIN_OFF_0_I = 554,
        NV_GPS1_LO_CAL_I = 555,
        NV_GPS1_ANT_OFF_DB_I = 556,
        NV_GPS1_PCS_RF_DELAY_I = 557,
        NV_FM_RVC_COMP_VS_FREQ_I = 558,
        NV_FM_FSG_VS_TEMP_I = 559,
        NV_QDSP_SND_CTRL_I = 560,
        NV_AUDIO_ADJ_PHONE_I = 561,
        NV_AUDIO_ADJ_EARJACK_I = 562,
        NV_AUDIO_ADJ_HFK_I = 563,
        NV_RSVD_ITEM_564_I = 564,
        NV_RSVD_ITEM_565_I = 565,
        NV_RSVD_ITEM_566_I = 566,
        NV_RSVD_ITEM_567_I = 567,
        NV_RSVD_ITEM_568_I = 568,
        NV_RSVD_ITEM_569_I = 569,
        NV_RSVD_ITEM_570_I = 570,
        NV_RSVD_ITEM_571_I = 571,
        NV_RSVD_ITEM_572_I = 572,
        NV_RSVD_ITEM_573_I = 573,
        NV_RSVD_ITEM_574_I = 574,
        NV_RSVD_ITEM_575_I = 575,
        NV_RSVD_ITEM_576_I = 576,
        NV_RSVD_ITEM_577_I = 577,
        NV_RSVD_ITEM_578_I = 578,
        NV_HDR_AN_AUTH_NAI_I = 579,
        NV_HDR_AN_AUTH_PASSWORD_I = 580,
        NV_DS_MIP_ENABLE_PROF_I = 714,
        NV_GPS_DOPP_SDEV_I = 736,
        NV_PPP_PASSWORD_I = 906,
        NV_PPP_USER_ID_I = 910,
        NV_HDR_AN_AUTH_PASSWORD_LONG_I = 1192,
        NV_HDR_AN_AUTH_USER_ID_LONG_I = 1194,
        NV_MEID_I = 1943,
        NV_DS_MIP_RM_NAI_I = 2825,
        NV_DS_SIP_RM_NAI_I = 2953,
        NV_CDMA_SO68_I = 0x1006,
        NV_WAP_PORT_1_I = 0x1429,
        NV_WAP_PORT_2_I = 0x142A,
        NV_WAP_LVL_PORT_1_I = 0x142B,
        NV_WAP_LVL_PORT_2_I = 0x142C,
        NV_WAP_DOMAIN_NAME_1_I = 0x142D,
        NV_WAP_DOMAIN_NAME_2_I = 0x142E,
        NV_WAP_USER_NAME_I = 0x142F,
        NV_WAP_PASSWORD_I = 0x1430,
        NV_WAP_HOMEPAGE_I = 0x1431,
        NV_WAP_UA_PROF_I = 0x1432,
        NV_SCR_VERSION_I = 0x143F,
        NV_MMS_SET_DELIVERY_ACK_I = 0x14B7,
        //nathan 4.22
        NV_MMS_MMSC_URL_I = 0x14BB,
        NV_MMS_MMSC_UPLOAD_URL_I = 0x14BC,
        NV_MMS_MMSC_UAPROF_URL_I = 0x14BD,
        NV_LG2_MMS_SEND_FROM_ADD_I = 0x14BF,
        //nathan 4.22
        NV_MMS_MMSC_USER_AGENT_I = 0x14C0,
        NV_MMS_MMSC_SERVER_PORT_URL_I = 0x14C1,
        NV_MMS_GATEWAY_SERVER_NAME_IP_I = 0x14C6,
        NV_MMS_HTTP_METHOD_URL_I = 0x14C7,
        NV_MMS_MMSC_SECONDARY_NAME_IP_I = 0x14C8,
        NV_MMS_GATEWAY_PORT_I = 0x14CB,
        NV_MMS_HTTP_HDR_CONNECTION_I = 0x14CC,
        NV_MMS_HTTP_HDR_CONTENT_TYPE_I = 0x14CD,
        NV_MMS_HTTP_HDR_ACCEPT_I = 0x14CF,
        NV_MMS_HTTP_HDR_ACCEPT_LANGUAGE_I = 0x14D0,
        NV_MMS_HTTP_HDR_ACCEPT_CHARSET_I = 0x14D1,
        NV_MMS_HTTP_HDR_NAME_CONTENT_I = 0x14D2,
        NV_MMS_HTTP_HDR_NAME_USER_AGENT_I = 0x14D3,
        NV_MMS_HTTP_HDR_NAME_UAPROFILE_I = 0x14D4,
        NV_MMS_HTTP_HDR_NAME_ACCEPT_I = 0x14D5,
        NV_MMS_HTTP_HDR_NAME_ACCEPT_LANGUAGE_I = 0x14D6,
        NV_MMS_HTTP_HDR_NAME_ACCEPT_CHARSET_I = 0x14D7,
        NV_MMS_HTTP_HDR_NAME_ACCEPT_ENCODING_I = 0x14D8,
        NV_MMS_HTTP_HDR_NAME_AUTHENTICATION_I = 0x14D9,
        NV_MMS_HTTP_HDR_NAME_CONNECTION_I = 0x14DA,
        NV_MMS_HTTP_HDR_NAME_PROXY_AUTHORIZATION_I = 0x14DB,
        NV_BREW_SERVER_I = 0x1519,
        NV_BREW_PRIMARY_IP_I = 0x151B,
        NV_BREW_SECONDARY_IP_I = 0x151C,
        NV_BREW_CARRIER_ID_I = 0x151D,
        NV_BREW_BKEY_I = 0x151E,
        NV_BREW_DOWNLOAD_FLAG_I = 0x151F,
        NV_BREW_AUTH_POLICY = 0x1520,
        NV_BREW_PRIVACY_POLICY_I = 0x1521,
        NV_BREW_PLATFORM_I = 0x1523,
        NV_BREW_AIRTIME_CHARGE_I = 0x1524,
        NV_BREW_DISALLOW_DORMANCY = 0x1527,
        NV_BREW_SUBSCRIBER_ID_LEN_I = 0x1528,
        NV_FALLBACK_FLAG_I = 0x153B,
        NV_SIP_DUN_USER_ID_I = 0x1F55,
        NV_MOTODROID_1F68_I = 0x1F68,
        NV_MOTODROID_1F69_I = 0x1F69,
        NV_MOTODROID_1F6A_I = 0x1F6A,
        NV_MOTODROID_1F6B_I = 0x1F6B,
        NV_MOTODROID_1F9B_I = 0x1F9B,

        NV_LG_MMS_ACK_DELIVERY_I = 23003,
        NV_LG_MMS_MMSC_URL_I = 23007,
        //New NvItems for LG
        NV_LG_MMS_MMSC_UPLOAD_URL_I = 23008,
        NV_LG_MMS_MMSC_UAPROF_URL_I = 23009,
        NV_LG_MMS_MMSC_USER_AGENT_I = 23010,

        NV_LG_MMS_MMSC_SERVER_POST_URL_I = 23011,
        NV_LG_MMS_SEND_FROM_ADD_I = 23014,
        NV_LG_BROWSER_PROXYSET_PRIMARYPORT_I = 26501,
        NV_LG_BROWSER_PROXYSET_SECONDARYPORT_I = 26502,
        NV_LG_BROWSER_PROXYSET_PRIMARYDOMAINNAME_I = 26505,
        NV_LG_BROWSER_PROXYSET_SECONDARYDOMAINNAME_I = 26506,
        NV_LG_BROWSER_PROXYSET_USERNAME_I = 26507,
        NV_LG_BROWSER_PROXYSET_PASSWORD_I = 26508,
        NV_LG_BROWSER_PROXYSET_HOMEPAGE_I = 26509,

        NV_WAP_PORT1_I = 50161,
        NV_WAP_PORT2_I = 50162,
        NV_WAP_DOMAIN_NAME1_I = 50163,
        NV_WAP_DOMAIN_NAME2_I = 50164,
        NV_WAP_USERNAME_I = 50165,
        NV_WAP_PASSWORD2_I = 50166,
        NV_WAP_HOMEPAGE2_I = 50167,
        NV_MMS_MMSC_URL2_I = 50307,
        NV_MMS_MMSC_UPLOAD_URL2_I = 50308,
        NV_MMS_MMSC_UAPROF_URL2_I = 50309,
        NV_MMS_SEND_FROM_ADD_I = 50311,
        NV_MMS_MMSC_SERVER_POST_URL_I = 50313,

        NV_MAX_I,
        NV_ITEMS_ENUM_PAD = 0x7FFF
			
	}; 

	/* Request Structures */
	typedef struct{ 
		byte cmd; 
		byte accepted; 
	} DMCommand; 


	/* Response Structures */

	typedef struct{ 
		byte cmd; 
		byte responseBody[130];
	} DMCommandResponse; 
 

	typedef struct{ 
		byte cmd; 
		byte pad[11];
		byte responseBody[119];
	} DMChipsetResponse; 

	typedef struct{ 
		char compiledAt[20];
		char releasedAt[20];
	} DMVersionInfo; 

	typedef struct{ 
		byte cmd; 
		byte accepted; 
	} DMSendSPCResponse; 

	typedef struct { 
		byte cmd; 
		byte nvCmd[2];
		byte nam[1];
		byte responseBody[130];	
	} DMReadNVMDNResponse;

	typedef struct { 
		byte cmd; 
		byte nvCmd[2]; 
		byte responseBody[130];	
	} DMReadNVResponse;

	typedef struct { 
		byte cmd; 
		byte nvCmd[2]; 
		byte pad[1];
		byte responseBody[130];	
	} DMReadNVResponseAlt;
}

#endif  /* QCDM_H */