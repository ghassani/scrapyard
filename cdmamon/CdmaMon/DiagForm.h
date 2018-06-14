#pragma once
#include "DMSerial.h"
#include "DMCommander.h"
#include "MeidConverter.h"
#include "qcdm.h"

namespace CdmaMon {

	using namespace System;
	using namespace System::ComponentModel;
	using namespace System::Collections;
	using namespace System::Windows::Forms;
	using namespace System::Data;
	using namespace System::Drawing;


	/// <summary>
	/// Summary for DiagForm
	/// </summary>
	public ref class DiagForm : public System::Windows::Forms::Form
	{


	private: System::Windows::Forms::Button^  writeSelectedEvdoProfile;
	private: System::Windows::Forms::Button^  readSelectedEvdoProfile;
	private: System::Windows::Forms::ComboBox^  evdoProfileSetCombo;
	private: System::Windows::Forms::Label^  label11;
	private: System::Windows::Forms::TabPage^  tabControl_Security;
	private: System::Windows::Forms::GroupBox^  groupBox2;
	private: System::Windows::Forms::GroupBox^  spcGroupBox;
	private: System::Windows::Forms::Button^  sendSPCButton;
	private: System::Windows::Forms::TextBox^  userSPC;

	private: System::Windows::Forms::Button^  sendSPCZerosButton;


	
	private: System::Windows::Forms::TabPage^  tabControl_Android;
	private: System::Windows::Forms::ComboBox^  comboBox1;
	private: System::Windows::Forms::Button^  button2;
	private: System::Windows::Forms::Button^  button1;
	private: System::Windows::Forms::TabPage^  tabControl_QuickFlash;
	private: System::Windows::Forms::ToolStripProgressBar^  progressBarMain;
	private: System::Windows::Forms::CheckBox^  namLock;









	public: 
		DMSerial *dmComPort;
		MeidConverter *meidConverter;
		DMCommander *dmCommander;
	private: System::Windows::Forms::ComboBox^  readSpcMethodCombo;
	private: System::Windows::Forms::TextBox^  deviceInfoCompiled;
	private: System::Windows::Forms::Label^  label13;
	private: System::Windows::Forms::TextBox^  deviceInfoReleased;
	private: System::Windows::Forms::Label^  label12;
	private: System::Windows::Forms::TextBox^  deviceInfoChipset;
	private: System::Windows::Forms::Label^  label14;
	private: System::Windows::Forms::Button^  setModeButton;
	private: System::Windows::Forms::ComboBox^  setModeCombo;
	private: System::Windows::Forms::GroupBox^  groupBox3;
	public: 

	public: 
	private: System::Windows::Forms::Button^  button4;
			 
	public: 
		DiagForm(void)
		{
			InitializeComponent();
			this->meidConverter = new MeidConverter;
			this->dmComPort = new DMSerial;
			this->dmCommander = new DMCommander(*this->dmComPort);

			this->readSpcMethodCombo->SelectedIndex = 0;
			this->readNamSetCombo->SelectedIndex = 0;
			this->evdoProfileSetCombo->SelectedIndex = 0;
		}

	protected:
		
		/// <summary>
		/// Clean up any resources being used.
		/// </summary>
		~DiagForm()
		{
			if (components)
				delete components;
			
			if(meidConverter)
				delete meidConverter;

			if(dmCommander)
				delete dmCommander;

			if(dmComPort)
				delete dmComPort;

			
		}
	private: System::Windows::Forms::TabPage^  tabControl_Terminal;
	protected: 
	private: System::Windows::Forms::TabPage^  tabControl_Other;
	private: System::Windows::Forms::TabPage^  tabControl_Memory;
	private: System::Windows::Forms::Button^  readDeviceInfoButton;
	private: System::Windows::Forms::TabPage^  tabControl_NAM_EVDO;

	private: System::Windows::Forms::ComboBox^  readNamSetCombo;
	private: System::Windows::Forms::Label^  label1;
	private: System::Windows::Forms::TabControl^  tabControl1;
	private: System::Windows::Forms::NotifyIcon^  notifyIcon1;
	private: System::Windows::Forms::Panel^  panelRight;
	private: System::Windows::Forms::GroupBox^  connectionGroupBox;
	private: System::Windows::Forms::ComboBox^  comPortSelection;
	private: System::Windows::Forms::Button^  detectPortsButton;
	private: System::Windows::Forms::Button^  disconnectButton;
	private: System::Windows::Forms::Button^  connectButton;





	private: System::Windows::Forms::StatusStrip^  statusStrip;


	private: System::Windows::Forms::MenuStrip^  menuStrip1;
	private: System::Windows::Forms::ToolStripMenuItem^  aToolStripMenuItem;

	private: System::Windows::Forms::ToolStripMenuItem^  bToolStripMenuItem1;
	private: System::Windows::Forms::ToolStripMenuItem^  cToolStripMenuItem;
	private: System::Windows::Forms::ToolStripMenuItem^  bToolStripMenuItem;
	private: System::Windows::Forms::ToolStripMenuItem^  aToolStripMenuItem2;


	private: System::Windows::Forms::ToolStripMenuItem^  cToolStripMenuItem2;
	private: System::Windows::Forms::ToolStripMenuItem^  aToolStripMenuItem3;
	private: System::Windows::Forms::ToolStripMenuItem^  bVToolStripMenuItem;




















	private: System::Windows::Forms::Button^  readSelectedNamProfileButton;
	private: System::Windows::Forms::Button^  writeSelectedNamProfileButton;































private: System::Windows::Forms::GroupBox^  groupBox1;




private: System::Windows::Forms::Label^  label20;
private: System::Windows::Forms::TextBox^  textBox27;
private: System::Windows::Forms::Label^  label19;
private: System::Windows::Forms::TextBox^  textBox26;
private: System::Windows::Forms::GroupBox^  group_NAM;
private: System::Windows::Forms::Label^  label10;
private: System::Windows::Forms::TextBox^  textBox17;
private: System::Windows::Forms::Label^  label9;
private: System::Windows::Forms::Label^  label8;
private: System::Windows::Forms::TextBox^  textBox13;
private: System::Windows::Forms::TextBox^  textBox14;
private: System::Windows::Forms::TextBox^  textBox15;
private: System::Windows::Forms::TextBox^  textBox16;
private: System::Windows::Forms::Label^  label7;
private: System::Windows::Forms::TextBox^  textBox12;
private: System::Windows::Forms::Label^  label6;
private: System::Windows::Forms::TextBox^  textBox11;
private: System::Windows::Forms::Label^  label5;
private: System::Windows::Forms::TextBox^  textBox10;
private: System::Windows::Forms::Label^  label4;
private: System::Windows::Forms::TextBox^  textBox9;
private: System::Windows::Forms::TextBox^  textBox5;
private: System::Windows::Forms::TextBox^  textBox7;
private: System::Windows::Forms::TextBox^  textBox1;
private: System::Windows::Forms::TextBox^  textBox3;
private: System::Windows::Forms::TextBox^  namSID5;
private: System::Windows::Forms::TextBox^  textBox8;
private: System::Windows::Forms::TextBox^  namSID4;
private: System::Windows::Forms::TextBox^  textBox6;
private: System::Windows::Forms::TextBox^  namSID3;
private: System::Windows::Forms::TextBox^  textBox4;
private: System::Windows::Forms::TextBox^  namSID2;
private: System::Windows::Forms::TextBox^  textBox2;
private: System::Windows::Forms::Label^  namMCCLabel;
private: System::Windows::Forms::Label^  namMNCLabel;
private: System::Windows::Forms::TextBox^  namMCC;
private: System::Windows::Forms::TextBox^  namMNC;
private: System::Windows::Forms::Label^  label3;
private: System::Windows::Forms::Label^  label2;
private: System::Windows::Forms::Label^  namBannerLabel;
private: System::Windows::Forms::Label^  namNameLabel;
private: System::Windows::Forms::TextBox^  namName;
private: System::Windows::Forms::TextBox^  namBanner;
private: System::Windows::Forms::Label^  namMINLabel;
private: System::Windows::Forms::Label^  namMDNLabel;
private: System::Windows::Forms::TextBox^  namSID1;
private: System::Windows::Forms::TextBox^  namNID;
private: System::Windows::Forms::TextBox^  namMIN;
private: System::Windows::Forms::TextBox^  namMDN;
private: System::Windows::Forms::GroupBox^  group_EVDO;
private: System::Windows::Forms::Label^  label25;
private: System::Windows::Forms::TextBox^  evdoHRDPassword2;

private: System::Windows::Forms::Label^  label26;
private: System::Windows::Forms::TextBox^  evdoHRDUsername2;

private: System::Windows::Forms::Label^  label27;
private: System::Windows::Forms::TextBox^  evdoHRDPassword;

private: System::Windows::Forms::Label^  label28;
private: System::Windows::Forms::TextBox^  evdoHRDUsername;

private: System::Windows::Forms::Label^  label29;
private: System::Windows::Forms::TextBox^  evdoPAPPassword;

private: System::Windows::Forms::TextBox^  evdoPAPUsername;

private: System::Windows::Forms::Label^  label30;
private: System::Windows::Forms::TextBox^  evdoPPPPasword;

private: System::Windows::Forms::TextBox^  evdoPPPUsername;

private: System::Windows::Forms::Label^  label31;
private: System::Windows::Forms::Label^  label32;





private: System::Windows::Forms::TextBox^  deviceInfoEsnHex;

private: System::Windows::Forms::TextBox^  deviceInfoEsnDec;

private: System::Windows::Forms::TextBox^  deviceInfoMeidHex;

private: System::Windows::Forms::TextBox^  deviceInfoMeidDec;

private: System::Windows::Forms::Label^  label23;
private: System::Windows::Forms::Label^  label24;
private: System::Windows::Forms::Label^  label22;
private: System::Windows::Forms::Label^  label21;
private: System::Windows::Forms::ToolStripStatusLabel^  toolStripStatusLabel1;
private: System::Windows::Forms::ToolStripStatusLabel^  statusStripLabel1;
private: System::Windows::Forms::ToolStripStatusLabel^  statusStripLabel2;

	protected: 


	private: System::ComponentModel::IContainer^  components;

	private: 
	protected: 

	private:
		/// <summary>
		/// Required designer variable.
		/// </summary>

		
#pragma region Windows Form Designer generated code
		/// <summary>
		/// Required method for Designer support - do not modify
		/// the contents of this method with the code editor.
		/// </summary>
		void InitializeComponent(void)
		{
			this->components = (gcnew System::ComponentModel::Container());
			this->tabControl_Terminal = (gcnew System::Windows::Forms::TabPage());
			this->label20 = (gcnew System::Windows::Forms::Label());
			this->textBox27 = (gcnew System::Windows::Forms::TextBox());
			this->label19 = (gcnew System::Windows::Forms::Label());
			this->textBox26 = (gcnew System::Windows::Forms::TextBox());
			this->tabControl_Other = (gcnew System::Windows::Forms::TabPage());
			this->tabControl_Memory = (gcnew System::Windows::Forms::TabPage());
			this->tabControl_NAM_EVDO = (gcnew System::Windows::Forms::TabPage());
			this->namLock = (gcnew System::Windows::Forms::CheckBox());
			this->group_NAM = (gcnew System::Windows::Forms::GroupBox());
			this->label10 = (gcnew System::Windows::Forms::Label());
			this->textBox17 = (gcnew System::Windows::Forms::TextBox());
			this->label9 = (gcnew System::Windows::Forms::Label());
			this->label8 = (gcnew System::Windows::Forms::Label());
			this->textBox13 = (gcnew System::Windows::Forms::TextBox());
			this->textBox14 = (gcnew System::Windows::Forms::TextBox());
			this->textBox15 = (gcnew System::Windows::Forms::TextBox());
			this->textBox16 = (gcnew System::Windows::Forms::TextBox());
			this->label7 = (gcnew System::Windows::Forms::Label());
			this->textBox12 = (gcnew System::Windows::Forms::TextBox());
			this->label6 = (gcnew System::Windows::Forms::Label());
			this->textBox11 = (gcnew System::Windows::Forms::TextBox());
			this->label5 = (gcnew System::Windows::Forms::Label());
			this->textBox10 = (gcnew System::Windows::Forms::TextBox());
			this->label4 = (gcnew System::Windows::Forms::Label());
			this->textBox9 = (gcnew System::Windows::Forms::TextBox());
			this->textBox5 = (gcnew System::Windows::Forms::TextBox());
			this->textBox7 = (gcnew System::Windows::Forms::TextBox());
			this->textBox1 = (gcnew System::Windows::Forms::TextBox());
			this->textBox3 = (gcnew System::Windows::Forms::TextBox());
			this->namSID5 = (gcnew System::Windows::Forms::TextBox());
			this->textBox8 = (gcnew System::Windows::Forms::TextBox());
			this->namSID4 = (gcnew System::Windows::Forms::TextBox());
			this->textBox6 = (gcnew System::Windows::Forms::TextBox());
			this->namSID3 = (gcnew System::Windows::Forms::TextBox());
			this->textBox4 = (gcnew System::Windows::Forms::TextBox());
			this->namSID2 = (gcnew System::Windows::Forms::TextBox());
			this->textBox2 = (gcnew System::Windows::Forms::TextBox());
			this->namMCCLabel = (gcnew System::Windows::Forms::Label());
			this->namMNCLabel = (gcnew System::Windows::Forms::Label());
			this->namMCC = (gcnew System::Windows::Forms::TextBox());
			this->namMNC = (gcnew System::Windows::Forms::TextBox());
			this->label3 = (gcnew System::Windows::Forms::Label());
			this->label2 = (gcnew System::Windows::Forms::Label());
			this->namBannerLabel = (gcnew System::Windows::Forms::Label());
			this->namNameLabel = (gcnew System::Windows::Forms::Label());
			this->namName = (gcnew System::Windows::Forms::TextBox());
			this->namBanner = (gcnew System::Windows::Forms::TextBox());
			this->namMINLabel = (gcnew System::Windows::Forms::Label());
			this->namMDNLabel = (gcnew System::Windows::Forms::Label());
			this->namSID1 = (gcnew System::Windows::Forms::TextBox());
			this->namNID = (gcnew System::Windows::Forms::TextBox());
			this->namMIN = (gcnew System::Windows::Forms::TextBox());
			this->namMDN = (gcnew System::Windows::Forms::TextBox());
			this->group_EVDO = (gcnew System::Windows::Forms::GroupBox());
			this->comboBox1 = (gcnew System::Windows::Forms::ComboBox());
			this->writeSelectedEvdoProfile = (gcnew System::Windows::Forms::Button());
			this->label25 = (gcnew System::Windows::Forms::Label());
			this->readSelectedEvdoProfile = (gcnew System::Windows::Forms::Button());
			this->evdoHRDPassword2 = (gcnew System::Windows::Forms::TextBox());
			this->evdoProfileSetCombo = (gcnew System::Windows::Forms::ComboBox());
			this->label26 = (gcnew System::Windows::Forms::Label());
			this->label11 = (gcnew System::Windows::Forms::Label());
			this->evdoHRDUsername2 = (gcnew System::Windows::Forms::TextBox());
			this->label27 = (gcnew System::Windows::Forms::Label());
			this->evdoHRDPassword = (gcnew System::Windows::Forms::TextBox());
			this->label28 = (gcnew System::Windows::Forms::Label());
			this->evdoHRDUsername = (gcnew System::Windows::Forms::TextBox());
			this->label29 = (gcnew System::Windows::Forms::Label());
			this->evdoPAPPassword = (gcnew System::Windows::Forms::TextBox());
			this->evdoPAPUsername = (gcnew System::Windows::Forms::TextBox());
			this->label30 = (gcnew System::Windows::Forms::Label());
			this->evdoPPPPasword = (gcnew System::Windows::Forms::TextBox());
			this->evdoPPPUsername = (gcnew System::Windows::Forms::TextBox());
			this->label31 = (gcnew System::Windows::Forms::Label());
			this->label32 = (gcnew System::Windows::Forms::Label());
			this->writeSelectedNamProfileButton = (gcnew System::Windows::Forms::Button());
			this->readSelectedNamProfileButton = (gcnew System::Windows::Forms::Button());
			this->readNamSetCombo = (gcnew System::Windows::Forms::ComboBox());
			this->label1 = (gcnew System::Windows::Forms::Label());
			this->tabControl1 = (gcnew System::Windows::Forms::TabControl());
			this->tabControl_Security = (gcnew System::Windows::Forms::TabPage());
			this->groupBox2 = (gcnew System::Windows::Forms::GroupBox());
			this->spcGroupBox = (gcnew System::Windows::Forms::GroupBox());
			this->readSpcMethodCombo = (gcnew System::Windows::Forms::ComboBox());
			this->button4 = (gcnew System::Windows::Forms::Button());
			this->button2 = (gcnew System::Windows::Forms::Button());
			this->button1 = (gcnew System::Windows::Forms::Button());
			this->sendSPCButton = (gcnew System::Windows::Forms::Button());
			this->userSPC = (gcnew System::Windows::Forms::TextBox());
			this->sendSPCZerosButton = (gcnew System::Windows::Forms::Button());
			this->tabControl_QuickFlash = (gcnew System::Windows::Forms::TabPage());
			this->tabControl_Android = (gcnew System::Windows::Forms::TabPage());
			this->notifyIcon1 = (gcnew System::Windows::Forms::NotifyIcon(this->components));
			this->panelRight = (gcnew System::Windows::Forms::Panel());
			this->groupBox1 = (gcnew System::Windows::Forms::GroupBox());
			this->deviceInfoChipset = (gcnew System::Windows::Forms::TextBox());
			this->label14 = (gcnew System::Windows::Forms::Label());
			this->deviceInfoCompiled = (gcnew System::Windows::Forms::TextBox());
			this->label13 = (gcnew System::Windows::Forms::Label());
			this->deviceInfoReleased = (gcnew System::Windows::Forms::TextBox());
			this->label12 = (gcnew System::Windows::Forms::Label());
			this->readDeviceInfoButton = (gcnew System::Windows::Forms::Button());
			this->deviceInfoEsnHex = (gcnew System::Windows::Forms::TextBox());
			this->deviceInfoEsnDec = (gcnew System::Windows::Forms::TextBox());
			this->deviceInfoMeidHex = (gcnew System::Windows::Forms::TextBox());
			this->deviceInfoMeidDec = (gcnew System::Windows::Forms::TextBox());
			this->label23 = (gcnew System::Windows::Forms::Label());
			this->label24 = (gcnew System::Windows::Forms::Label());
			this->label22 = (gcnew System::Windows::Forms::Label());
			this->label21 = (gcnew System::Windows::Forms::Label());
			this->connectionGroupBox = (gcnew System::Windows::Forms::GroupBox());
			this->setModeButton = (gcnew System::Windows::Forms::Button());
			this->setModeCombo = (gcnew System::Windows::Forms::ComboBox());
			this->comPortSelection = (gcnew System::Windows::Forms::ComboBox());
			this->detectPortsButton = (gcnew System::Windows::Forms::Button());
			this->disconnectButton = (gcnew System::Windows::Forms::Button());
			this->connectButton = (gcnew System::Windows::Forms::Button());
			this->statusStrip = (gcnew System::Windows::Forms::StatusStrip());
			this->statusStripLabel1 = (gcnew System::Windows::Forms::ToolStripStatusLabel());
			this->statusStripLabel2 = (gcnew System::Windows::Forms::ToolStripStatusLabel());
			this->progressBarMain = (gcnew System::Windows::Forms::ToolStripProgressBar());
			this->toolStripStatusLabel1 = (gcnew System::Windows::Forms::ToolStripStatusLabel());
			this->menuStrip1 = (gcnew System::Windows::Forms::MenuStrip());
			this->aToolStripMenuItem = (gcnew System::Windows::Forms::ToolStripMenuItem());
			this->bToolStripMenuItem1 = (gcnew System::Windows::Forms::ToolStripMenuItem());
			this->cToolStripMenuItem = (gcnew System::Windows::Forms::ToolStripMenuItem());
			this->bToolStripMenuItem = (gcnew System::Windows::Forms::ToolStripMenuItem());
			this->aToolStripMenuItem2 = (gcnew System::Windows::Forms::ToolStripMenuItem());
			this->cToolStripMenuItem2 = (gcnew System::Windows::Forms::ToolStripMenuItem());
			this->aToolStripMenuItem3 = (gcnew System::Windows::Forms::ToolStripMenuItem());
			this->bVToolStripMenuItem = (gcnew System::Windows::Forms::ToolStripMenuItem());
			this->groupBox3 = (gcnew System::Windows::Forms::GroupBox());
			this->tabControl_Terminal->SuspendLayout();
			this->tabControl_NAM_EVDO->SuspendLayout();
			this->group_NAM->SuspendLayout();
			this->group_EVDO->SuspendLayout();
			this->tabControl1->SuspendLayout();
			this->tabControl_Security->SuspendLayout();
			this->spcGroupBox->SuspendLayout();
			this->panelRight->SuspendLayout();
			this->groupBox1->SuspendLayout();
			this->connectionGroupBox->SuspendLayout();
			this->statusStrip->SuspendLayout();
			this->menuStrip1->SuspendLayout();
			this->groupBox3->SuspendLayout();
			this->SuspendLayout();
			// 
			// tabControl_Terminal
			// 
			this->tabControl_Terminal->Controls->Add(this->label20);
			this->tabControl_Terminal->Controls->Add(this->textBox27);
			this->tabControl_Terminal->Controls->Add(this->label19);
			this->tabControl_Terminal->Controls->Add(this->textBox26);
			this->tabControl_Terminal->Location = System::Drawing::Point(4, 22);
			this->tabControl_Terminal->Name = L"tabControl_Terminal";
			this->tabControl_Terminal->Size = System::Drawing::Size(473, 385);
			this->tabControl_Terminal->TabIndex = 3;
			this->tabControl_Terminal->Text = L"Terminal";
			this->tabControl_Terminal->UseVisualStyleBackColor = true;
			// 
			// label20
			// 
			this->label20->AutoSize = true;
			this->label20->Location = System::Drawing::Point(13, 57);
			this->label20->Name = L"label20";
			this->label20->Size = System::Drawing::Size(79, 13);
			this->label20->TabIndex = 3;
			this->label20->Text = L"DM Commands";
			// 
			// textBox27
			// 
			this->textBox27->Location = System::Drawing::Point(12, 74);
			this->textBox27->Name = L"textBox27";
			this->textBox27->Size = System::Drawing::Size(360, 20);
			this->textBox27->TabIndex = 2;
			// 
			// label19
			// 
			this->label19->AutoSize = true;
			this->label19->Location = System::Drawing::Point(13, 10);
			this->label19->Name = L"label19";
			this->label19->Size = System::Drawing::Size(76, 13);
			this->label19->TabIndex = 1;
			this->label19->Text = L"AT Commands";
			// 
			// textBox26
			// 
			this->textBox26->Location = System::Drawing::Point(12, 27);
			this->textBox26->Name = L"textBox26";
			this->textBox26->Size = System::Drawing::Size(360, 20);
			this->textBox26->TabIndex = 0;
			// 
			// tabControl_Other
			// 
			this->tabControl_Other->Location = System::Drawing::Point(4, 22);
			this->tabControl_Other->Name = L"tabControl_Other";
			this->tabControl_Other->Size = System::Drawing::Size(473, 385);
			this->tabControl_Other->TabIndex = 4;
			this->tabControl_Other->Text = L"Other";
			this->tabControl_Other->UseVisualStyleBackColor = true;
			// 
			// tabControl_Memory
			// 
			this->tabControl_Memory->Location = System::Drawing::Point(4, 22);
			this->tabControl_Memory->Name = L"tabControl_Memory";
			this->tabControl_Memory->Size = System::Drawing::Size(473, 385);
			this->tabControl_Memory->TabIndex = 2;
			this->tabControl_Memory->Text = L"Memory";
			this->tabControl_Memory->UseVisualStyleBackColor = true;
			// 
			// tabControl_NAM_EVDO
			// 
			this->tabControl_NAM_EVDO->BackColor = System::Drawing::Color::WhiteSmoke;
			this->tabControl_NAM_EVDO->Controls->Add(this->namLock);
			this->tabControl_NAM_EVDO->Controls->Add(this->group_NAM);
			this->tabControl_NAM_EVDO->Controls->Add(this->group_EVDO);
			this->tabControl_NAM_EVDO->Controls->Add(this->writeSelectedNamProfileButton);
			this->tabControl_NAM_EVDO->Controls->Add(this->readSelectedNamProfileButton);
			this->tabControl_NAM_EVDO->Controls->Add(this->readNamSetCombo);
			this->tabControl_NAM_EVDO->Controls->Add(this->label1);
			this->tabControl_NAM_EVDO->Location = System::Drawing::Point(4, 22);
			this->tabControl_NAM_EVDO->Name = L"tabControl_NAM_EVDO";
			this->tabControl_NAM_EVDO->Padding = System::Windows::Forms::Padding(3);
			this->tabControl_NAM_EVDO->Size = System::Drawing::Size(473, 385);
			this->tabControl_NAM_EVDO->TabIndex = 0;
			this->tabControl_NAM_EVDO->Text = L"NAM & EVDO";
			this->tabControl_NAM_EVDO->Click += gcnew System::EventHandler(this, &DiagForm::tabControl_NAM_Click);
			// 
			// namLock
			// 
			this->namLock->AutoSize = true;
			this->namLock->Location = System::Drawing::Point(244, 12);
			this->namLock->Name = L"namLock";
			this->namLock->Size = System::Drawing::Size(77, 17);
			this->namLock->TabIndex = 51;
			this->namLock->Text = L"NAM Lock";
			this->namLock->UseVisualStyleBackColor = true;
			// 
			// group_NAM
			// 
			this->group_NAM->Controls->Add(this->label10);
			this->group_NAM->Controls->Add(this->textBox17);
			this->group_NAM->Controls->Add(this->label9);
			this->group_NAM->Controls->Add(this->label8);
			this->group_NAM->Controls->Add(this->textBox13);
			this->group_NAM->Controls->Add(this->textBox14);
			this->group_NAM->Controls->Add(this->textBox15);
			this->group_NAM->Controls->Add(this->textBox16);
			this->group_NAM->Controls->Add(this->label7);
			this->group_NAM->Controls->Add(this->textBox12);
			this->group_NAM->Controls->Add(this->label6);
			this->group_NAM->Controls->Add(this->textBox11);
			this->group_NAM->Controls->Add(this->label5);
			this->group_NAM->Controls->Add(this->textBox10);
			this->group_NAM->Controls->Add(this->label4);
			this->group_NAM->Controls->Add(this->textBox9);
			this->group_NAM->Controls->Add(this->textBox5);
			this->group_NAM->Controls->Add(this->textBox7);
			this->group_NAM->Controls->Add(this->textBox1);
			this->group_NAM->Controls->Add(this->textBox3);
			this->group_NAM->Controls->Add(this->namSID5);
			this->group_NAM->Controls->Add(this->textBox8);
			this->group_NAM->Controls->Add(this->namSID4);
			this->group_NAM->Controls->Add(this->textBox6);
			this->group_NAM->Controls->Add(this->namSID3);
			this->group_NAM->Controls->Add(this->textBox4);
			this->group_NAM->Controls->Add(this->namSID2);
			this->group_NAM->Controls->Add(this->textBox2);
			this->group_NAM->Controls->Add(this->namMCCLabel);
			this->group_NAM->Controls->Add(this->namMNCLabel);
			this->group_NAM->Controls->Add(this->namMCC);
			this->group_NAM->Controls->Add(this->namMNC);
			this->group_NAM->Controls->Add(this->label3);
			this->group_NAM->Controls->Add(this->label2);
			this->group_NAM->Controls->Add(this->namBannerLabel);
			this->group_NAM->Controls->Add(this->namNameLabel);
			this->group_NAM->Controls->Add(this->namName);
			this->group_NAM->Controls->Add(this->namBanner);
			this->group_NAM->Controls->Add(this->namMINLabel);
			this->group_NAM->Controls->Add(this->namMDNLabel);
			this->group_NAM->Controls->Add(this->namSID1);
			this->group_NAM->Controls->Add(this->namNID);
			this->group_NAM->Controls->Add(this->namMIN);
			this->group_NAM->Controls->Add(this->namMDN);
			this->group_NAM->Location = System::Drawing::Point(6, 34);
			this->group_NAM->Name = L"group_NAM";
			this->group_NAM->Size = System::Drawing::Size(464, 171);
			this->group_NAM->TabIndex = 50;
			this->group_NAM->TabStop = false;
			this->group_NAM->Text = L"NAM";
			// 
			// label10
			// 
			this->label10->AutoSize = true;
			this->label10->Location = System::Drawing::Point(128, 139);
			this->label10->Name = L"label10";
			this->label10->Size = System::Drawing::Size(29, 13);
			this->label10->TabIndex = 91;
			this->label10->Text = L"IMSI";
			// 
			// textBox17
			// 
			this->textBox17->Location = System::Drawing::Point(158, 136);
			this->textBox17->Name = L"textBox17";
			this->textBox17->Size = System::Drawing::Size(123, 20);
			this->textBox17->TabIndex = 90;
			// 
			// label9
			// 
			this->label9->AutoSize = true;
			this->label9->Location = System::Drawing::Point(12, 139);
			this->label9->Name = L"label9";
			this->label9->Size = System::Drawing::Size(32, 13);
			this->label9->TabIndex = 89;
			this->label9->Text = L"S CH";
			// 
			// label8
			// 
			this->label8->AutoSize = true;
			this->label8->Location = System::Drawing::Point(12, 115);
			this->label8->Name = L"label8";
			this->label8->Size = System::Drawing::Size(32, 13);
			this->label8->TabIndex = 88;
			this->label8->Text = L"P CH";
			// 
			// textBox13
			// 
			this->textBox13->Location = System::Drawing::Point(75, 112);
			this->textBox13->Name = L"textBox13";
			this->textBox13->Size = System::Drawing::Size(27, 20);
			this->textBox13->TabIndex = 87;
			// 
			// textBox14
			// 
			this->textBox14->Location = System::Drawing::Point(75, 140);
			this->textBox14->Name = L"textBox14";
			this->textBox14->Size = System::Drawing::Size(27, 20);
			this->textBox14->TabIndex = 86;
			// 
			// textBox15
			// 
			this->textBox15->Location = System::Drawing::Point(46, 112);
			this->textBox15->Name = L"textBox15";
			this->textBox15->Size = System::Drawing::Size(27, 20);
			this->textBox15->TabIndex = 85;
			// 
			// textBox16
			// 
			this->textBox16->Location = System::Drawing::Point(46, 140);
			this->textBox16->Name = L"textBox16";
			this->textBox16->Size = System::Drawing::Size(27, 20);
			this->textBox16->TabIndex = 84;
			// 
			// label7
			// 
			this->label7->AutoSize = true;
			this->label7->Location = System::Drawing::Point(362, 112);
			this->label7->Name = L"label7";
			this->label7->Size = System::Drawing::Size(31, 13);
			this->label7->TabIndex = 83;
			this->label7->Text = L"NAM";
			// 
			// textBox12
			// 
			this->textBox12->Location = System::Drawing::Point(399, 109);
			this->textBox12->Name = L"textBox12";
			this->textBox12->Size = System::Drawing::Size(41, 20);
			this->textBox12->TabIndex = 82;
			// 
			// label6
			// 
			this->label6->AutoSize = true;
			this->label6->Location = System::Drawing::Point(265, 112);
			this->label6->Name = L"label6";
			this->label6->Size = System::Drawing::Size(49, 13);
			this->label6->TabIndex = 81;
			this->label6->Text = L"ACCOLC";
			// 
			// textBox11
			// 
			this->textBox11->Location = System::Drawing::Point(316, 109);
			this->textBox11->Name = L"textBox11";
			this->textBox11->Size = System::Drawing::Size(41, 20);
			this->textBox11->TabIndex = 80;
			// 
			// label5
			// 
			this->label5->AutoSize = true;
			this->label5->Location = System::Drawing::Point(186, 112);
			this->label5->Name = L"label5";
			this->label5->Size = System::Drawing::Size(24, 13);
			this->label5->TabIndex = 79;
			this->label5->Text = L"SCI";
			// 
			// textBox10
			// 
			this->textBox10->Location = System::Drawing::Point(219, 109);
			this->textBox10->Name = L"textBox10";
			this->textBox10->Size = System::Drawing::Size(41, 20);
			this->textBox10->TabIndex = 78;
			// 
			// label4
			// 
			this->label4->AutoSize = true;
			this->label4->Location = System::Drawing::Point(106, 112);
			this->label4->Name = L"label4";
			this->label4->Size = System::Drawing::Size(30, 13);
			this->label4->TabIndex = 77;
			this->label4->Text = L"SCM";
			// 
			// textBox9
			// 
			this->textBox9->Location = System::Drawing::Point(136, 109);
			this->textBox9->Name = L"textBox9";
			this->textBox9->Size = System::Drawing::Size(44, 20);
			this->textBox9->TabIndex = 76;
			// 
			// textBox5
			// 
			this->textBox5->Location = System::Drawing::Point(354, 56);
			this->textBox5->Name = L"textBox5";
			this->textBox5->Size = System::Drawing::Size(41, 20);
			this->textBox5->TabIndex = 75;
			// 
			// textBox7
			// 
			this->textBox7->Location = System::Drawing::Point(354, 83);
			this->textBox7->Name = L"textBox7";
			this->textBox7->Size = System::Drawing::Size(41, 20);
			this->textBox7->TabIndex = 74;
			// 
			// textBox1
			// 
			this->textBox1->Location = System::Drawing::Point(399, 56);
			this->textBox1->Name = L"textBox1";
			this->textBox1->Size = System::Drawing::Size(41, 20);
			this->textBox1->TabIndex = 73;
			// 
			// textBox3
			// 
			this->textBox3->Location = System::Drawing::Point(399, 83);
			this->textBox3->Name = L"textBox3";
			this->textBox3->Size = System::Drawing::Size(41, 20);
			this->textBox3->TabIndex = 72;
			// 
			// namSID5
			// 
			this->namSID5->Location = System::Drawing::Point(309, 56);
			this->namSID5->Name = L"namSID5";
			this->namSID5->Size = System::Drawing::Size(41, 20);
			this->namSID5->TabIndex = 71;
			// 
			// textBox8
			// 
			this->textBox8->Location = System::Drawing::Point(309, 83);
			this->textBox8->Name = L"textBox8";
			this->textBox8->Size = System::Drawing::Size(41, 20);
			this->textBox8->TabIndex = 70;
			// 
			// namSID4
			// 
			this->namSID4->Location = System::Drawing::Point(264, 56);
			this->namSID4->Name = L"namSID4";
			this->namSID4->Size = System::Drawing::Size(41, 20);
			this->namSID4->TabIndex = 69;
			// 
			// textBox6
			// 
			this->textBox6->Location = System::Drawing::Point(264, 83);
			this->textBox6->Name = L"textBox6";
			this->textBox6->Size = System::Drawing::Size(41, 20);
			this->textBox6->TabIndex = 68;
			// 
			// namSID3
			// 
			this->namSID3->Location = System::Drawing::Point(219, 56);
			this->namSID3->Name = L"namSID3";
			this->namSID3->Size = System::Drawing::Size(41, 20);
			this->namSID3->TabIndex = 67;
			// 
			// textBox4
			// 
			this->textBox4->Location = System::Drawing::Point(219, 83);
			this->textBox4->Name = L"textBox4";
			this->textBox4->Size = System::Drawing::Size(41, 20);
			this->textBox4->TabIndex = 66;
			// 
			// namSID2
			// 
			this->namSID2->Location = System::Drawing::Point(175, 56);
			this->namSID2->Name = L"namSID2";
			this->namSID2->Size = System::Drawing::Size(41, 20);
			this->namSID2->TabIndex = 65;
			// 
			// textBox2
			// 
			this->textBox2->Location = System::Drawing::Point(175, 83);
			this->textBox2->Name = L"textBox2";
			this->textBox2->Size = System::Drawing::Size(41, 20);
			this->textBox2->TabIndex = 64;
			// 
			// namMCCLabel
			// 
			this->namMCCLabel->AutoSize = true;
			this->namMCCLabel->Location = System::Drawing::Point(12, 58);
			this->namMCCLabel->Name = L"namMCCLabel";
			this->namMCCLabel->Size = System::Drawing::Size(30, 13);
			this->namMCCLabel->TabIndex = 63;
			this->namMCCLabel->Text = L"MCC";
			// 
			// namMNCLabel
			// 
			this->namMNCLabel->AutoSize = true;
			this->namMNCLabel->Location = System::Drawing::Point(12, 86);
			this->namMNCLabel->Name = L"namMNCLabel";
			this->namMNCLabel->Size = System::Drawing::Size(31, 13);
			this->namMNCLabel->TabIndex = 62;
			this->namMNCLabel->Text = L"MNC";
			// 
			// namMCC
			// 
			this->namMCC->Location = System::Drawing::Point(45, 56);
			this->namMCC->Name = L"namMCC";
			this->namMCC->Size = System::Drawing::Size(49, 20);
			this->namMCC->TabIndex = 61;
			// 
			// namMNC
			// 
			this->namMNC->Location = System::Drawing::Point(45, 83);
			this->namMNC->Name = L"namMNC";
			this->namMNC->Size = System::Drawing::Size(49, 20);
			this->namMNC->TabIndex = 60;
			// 
			// label3
			// 
			this->label3->AutoSize = true;
			this->label3->Location = System::Drawing::Point(98, 58);
			this->label3->Name = L"label3";
			this->label3->Size = System::Drawing::Size(25, 13);
			this->label3->TabIndex = 59;
			this->label3->Text = L"SID";
			// 
			// label2
			// 
			this->label2->AutoSize = true;
			this->label2->Location = System::Drawing::Point(98, 86);
			this->label2->Name = L"label2";
			this->label2->Size = System::Drawing::Size(26, 13);
			this->label2->TabIndex = 58;
			this->label2->Text = L"NID";
			// 
			// namBannerLabel
			// 
			this->namBannerLabel->AutoSize = true;
			this->namBannerLabel->Location = System::Drawing::Point(287, 138);
			this->namBannerLabel->Name = L"namBannerLabel";
			this->namBannerLabel->Size = System::Drawing::Size(41, 13);
			this->namBannerLabel->TabIndex = 57;
			this->namBannerLabel->Text = L"Banner";
			// 
			// namNameLabel
			// 
			this->namNameLabel->AutoSize = true;
			this->namNameLabel->Location = System::Drawing::Point(293, 25);
			this->namNameLabel->Name = L"namNameLabel";
			this->namNameLabel->Size = System::Drawing::Size(35, 13);
			this->namNameLabel->TabIndex = 56;
			this->namNameLabel->Text = L"Name";
			// 
			// namName
			// 
			this->namName->Location = System::Drawing::Point(333, 22);
			this->namName->Name = L"namName";
			this->namName->Size = System::Drawing::Size(102, 20);
			this->namName->TabIndex = 55;
			// 
			// namBanner
			// 
			this->namBanner->Location = System::Drawing::Point(338, 135);
			this->namBanner->Name = L"namBanner";
			this->namBanner->Size = System::Drawing::Size(102, 20);
			this->namBanner->TabIndex = 54;
			// 
			// namMINLabel
			// 
			this->namMINLabel->AutoSize = true;
			this->namMINLabel->Location = System::Drawing::Point(153, 25);
			this->namMINLabel->Name = L"namMINLabel";
			this->namMINLabel->Size = System::Drawing::Size(27, 13);
			this->namMINLabel->TabIndex = 53;
			this->namMINLabel->Text = L"MIN";
			// 
			// namMDNLabel
			// 
			this->namMDNLabel->AutoSize = true;
			this->namMDNLabel->Location = System::Drawing::Point(8, 25);
			this->namMDNLabel->Name = L"namMDNLabel";
			this->namMDNLabel->Size = System::Drawing::Size(32, 13);
			this->namMDNLabel->TabIndex = 52;
			this->namMDNLabel->Text = L"MDN";
			// 
			// namSID1
			// 
			this->namSID1->Location = System::Drawing::Point(131, 56);
			this->namSID1->Name = L"namSID1";
			this->namSID1->Size = System::Drawing::Size(41, 20);
			this->namSID1->TabIndex = 51;
			// 
			// namNID
			// 
			this->namNID->Location = System::Drawing::Point(131, 83);
			this->namNID->Name = L"namNID";
			this->namNID->Size = System::Drawing::Size(41, 20);
			this->namNID->TabIndex = 50;
			// 
			// namMIN
			// 
			this->namMIN->Location = System::Drawing::Point(185, 22);
			this->namMIN->Name = L"namMIN";
			this->namMIN->Size = System::Drawing::Size(102, 20);
			this->namMIN->TabIndex = 49;
			// 
			// namMDN
			// 
			this->namMDN->Location = System::Drawing::Point(45, 22);
			this->namMDN->Name = L"namMDN";
			this->namMDN->Size = System::Drawing::Size(102, 20);
			this->namMDN->TabIndex = 48;
			// 
			// group_EVDO
			// 
			this->group_EVDO->Controls->Add(this->comboBox1);
			this->group_EVDO->Controls->Add(this->writeSelectedEvdoProfile);
			this->group_EVDO->Controls->Add(this->label25);
			this->group_EVDO->Controls->Add(this->readSelectedEvdoProfile);
			this->group_EVDO->Controls->Add(this->evdoHRDPassword2);
			this->group_EVDO->Controls->Add(this->evdoProfileSetCombo);
			this->group_EVDO->Controls->Add(this->label26);
			this->group_EVDO->Controls->Add(this->label11);
			this->group_EVDO->Controls->Add(this->evdoHRDUsername2);
			this->group_EVDO->Controls->Add(this->label27);
			this->group_EVDO->Controls->Add(this->evdoHRDPassword);
			this->group_EVDO->Controls->Add(this->label28);
			this->group_EVDO->Controls->Add(this->evdoHRDUsername);
			this->group_EVDO->Controls->Add(this->label29);
			this->group_EVDO->Controls->Add(this->evdoPAPPassword);
			this->group_EVDO->Controls->Add(this->evdoPAPUsername);
			this->group_EVDO->Controls->Add(this->label30);
			this->group_EVDO->Controls->Add(this->evdoPPPPasword);
			this->group_EVDO->Controls->Add(this->evdoPPPUsername);
			this->group_EVDO->Controls->Add(this->label31);
			this->group_EVDO->Controls->Add(this->label32);
			this->group_EVDO->Location = System::Drawing::Point(6, 211);
			this->group_EVDO->Name = L"group_EVDO";
			this->group_EVDO->Size = System::Drawing::Size(464, 173);
			this->group_EVDO->TabIndex = 49;
			this->group_EVDO->TabStop = false;
			this->group_EVDO->Text = L"EVDO";
			// 
			// comboBox1
			// 
			this->comboBox1->FormattingEnabled = true;
			this->comboBox1->Items->AddRange(gcnew cli::array< System::Object^  >(3) {L"Simple IP", L"Mobile IP", L"Simple+Mobile IP"});
			this->comboBox1->Location = System::Drawing::Point(91, 149);
			this->comboBox1->Name = L"comboBox1";
			this->comboBox1->Size = System::Drawing::Size(128, 21);
			this->comboBox1->TabIndex = 81;
			// 
			// writeSelectedEvdoProfile
			// 
			this->writeSelectedEvdoProfile->Location = System::Drawing::Point(160, 17);
			this->writeSelectedEvdoProfile->Name = L"writeSelectedEvdoProfile";
			this->writeSelectedEvdoProfile->Size = System::Drawing::Size(59, 22);
			this->writeSelectedEvdoProfile->TabIndex = 54;
			this->writeSelectedEvdoProfile->Text = L"Write";
			this->writeSelectedEvdoProfile->UseVisualStyleBackColor = true;
			// 
			// label25
			// 
			this->label25->AutoSize = true;
			this->label25->Location = System::Drawing::Point(226, 128);
			this->label25->Name = L"label25";
			this->label25->Size = System::Drawing::Size(80, 13);
			this->label25->TabIndex = 80;
			this->label25->Text = L"HDR Password";
			// 
			// readSelectedEvdoProfile
			// 
			this->readSelectedEvdoProfile->Location = System::Drawing::Point(94, 17);
			this->readSelectedEvdoProfile->Name = L"readSelectedEvdoProfile";
			this->readSelectedEvdoProfile->Size = System::Drawing::Size(59, 22);
			this->readSelectedEvdoProfile->TabIndex = 53;
			this->readSelectedEvdoProfile->Text = L"Read";
			this->readSelectedEvdoProfile->UseVisualStyleBackColor = true;
			this->readSelectedEvdoProfile->Click += gcnew System::EventHandler(this, &DiagForm::readSelectedEvdoProfileClickEvent);
			// 
			// evdoHRDPassword2
			// 
			this->evdoHRDPassword2->Location = System::Drawing::Point(308, 125);
			this->evdoHRDPassword2->Name = L"evdoHRDPassword2";
			this->evdoHRDPassword2->Size = System::Drawing::Size(128, 20);
			this->evdoHRDPassword2->TabIndex = 79;
			// 
			// evdoProfileSetCombo
			// 
			this->evdoProfileSetCombo->FormattingEnabled = true;
			this->evdoProfileSetCombo->Items->AddRange(gcnew cli::array< System::Object^  >(4) {L"1", L"2", L"3", L"4"});
			this->evdoProfileSetCombo->Location = System::Drawing::Point(50, 17);
			this->evdoProfileSetCombo->Name = L"evdoProfileSetCombo";
			this->evdoProfileSetCombo->Size = System::Drawing::Size(37, 21);
			this->evdoProfileSetCombo->TabIndex = 51;
			this->evdoProfileSetCombo->SelectedIndexChanged += gcnew System::EventHandler(this, &DiagForm::evdoProfileSetCombo_SelectedIndexChanged);
			// 
			// label26
			// 
			this->label26->AutoSize = true;
			this->label26->Location = System::Drawing::Point(226, 102);
			this->label26->Name = L"label26";
			this->label26->Size = System::Drawing::Size(82, 13);
			this->label26->TabIndex = 78;
			this->label26->Text = L"HDR Username";
			// 
			// label11
			// 
			this->label11->AutoSize = true;
			this->label11->Location = System::Drawing::Point(11, 20);
			this->label11->Name = L"label11";
			this->label11->Size = System::Drawing::Size(36, 13);
			this->label11->TabIndex = 52;
			this->label11->Text = L"Profile";
			// 
			// evdoHRDUsername2
			// 
			this->evdoHRDUsername2->Location = System::Drawing::Point(308, 99);
			this->evdoHRDUsername2->Name = L"evdoHRDUsername2";
			this->evdoHRDUsername2->Size = System::Drawing::Size(128, 20);
			this->evdoHRDUsername2->TabIndex = 77;
			// 
			// label27
			// 
			this->label27->AutoSize = true;
			this->label27->Location = System::Drawing::Point(6, 126);
			this->label27->Name = L"label27";
			this->label27->Size = System::Drawing::Size(80, 13);
			this->label27->TabIndex = 76;
			this->label27->Text = L"HDR Password";
			// 
			// evdoHRDPassword
			// 
			this->evdoHRDPassword->Location = System::Drawing::Point(91, 123);
			this->evdoHRDPassword->Name = L"evdoHRDPassword";
			this->evdoHRDPassword->Size = System::Drawing::Size(128, 20);
			this->evdoHRDPassword->TabIndex = 75;
			// 
			// label28
			// 
			this->label28->AutoSize = true;
			this->label28->Location = System::Drawing::Point(6, 100);
			this->label28->Name = L"label28";
			this->label28->Size = System::Drawing::Size(82, 13);
			this->label28->TabIndex = 74;
			this->label28->Text = L"HDR Username";
			// 
			// evdoHRDUsername
			// 
			this->evdoHRDUsername->Location = System::Drawing::Point(91, 97);
			this->evdoHRDUsername->Name = L"evdoHRDUsername";
			this->evdoHRDUsername->Size = System::Drawing::Size(128, 20);
			this->evdoHRDUsername->TabIndex = 73;
			// 
			// label29
			// 
			this->label29->AutoSize = true;
			this->label29->Location = System::Drawing::Point(226, 74);
			this->label29->Name = L"label29";
			this->label29->Size = System::Drawing::Size(77, 13);
			this->label29->TabIndex = 72;
			this->label29->Text = L"PAP Password";
			// 
			// evdoPAPPassword
			// 
			this->evdoPAPPassword->Location = System::Drawing::Point(308, 71);
			this->evdoPAPPassword->Name = L"evdoPAPPassword";
			this->evdoPAPPassword->Size = System::Drawing::Size(128, 20);
			this->evdoPAPPassword->TabIndex = 71;
			// 
			// evdoPAPUsername
			// 
			this->evdoPAPUsername->Location = System::Drawing::Point(308, 45);
			this->evdoPAPUsername->Name = L"evdoPAPUsername";
			this->evdoPAPUsername->Size = System::Drawing::Size(128, 20);
			this->evdoPAPUsername->TabIndex = 69;
			// 
			// label30
			// 
			this->label30->AutoSize = true;
			this->label30->Location = System::Drawing::Point(6, 74);
			this->label30->Name = L"label30";
			this->label30->Size = System::Drawing::Size(77, 13);
			this->label30->TabIndex = 68;
			this->label30->Text = L"PPP Password";
			// 
			// evdoPPPPasword
			// 
			this->evdoPPPPasword->Location = System::Drawing::Point(91, 71);
			this->evdoPPPPasword->Name = L"evdoPPPPasword";
			this->evdoPPPPasword->Size = System::Drawing::Size(128, 20);
			this->evdoPPPPasword->TabIndex = 67;
			// 
			// evdoPPPUsername
			// 
			this->evdoPPPUsername->Location = System::Drawing::Point(91, 45);
			this->evdoPPPUsername->Name = L"evdoPPPUsername";
			this->evdoPPPUsername->Size = System::Drawing::Size(128, 20);
			this->evdoPPPUsername->TabIndex = 65;
			// 
			// label31
			// 
			this->label31->AutoSize = true;
			this->label31->Location = System::Drawing::Point(226, 48);
			this->label31->Name = L"label31";
			this->label31->Size = System::Drawing::Size(79, 13);
			this->label31->TabIndex = 70;
			this->label31->Text = L"PAP Username";
			// 
			// label32
			// 
			this->label32->AutoSize = true;
			this->label32->Location = System::Drawing::Point(6, 48);
			this->label32->Name = L"label32";
			this->label32->Size = System::Drawing::Size(79, 13);
			this->label32->TabIndex = 66;
			this->label32->Text = L"PPP Username";
			// 
			// writeSelectedNamProfileButton
			// 
			this->writeSelectedNamProfileButton->Location = System::Drawing::Point(184, 8);
			this->writeSelectedNamProfileButton->Name = L"writeSelectedNamProfileButton";
			this->writeSelectedNamProfileButton->Size = System::Drawing::Size(54, 22);
			this->writeSelectedNamProfileButton->TabIndex = 48;
			this->writeSelectedNamProfileButton->Text = L"Write";
			this->writeSelectedNamProfileButton->UseVisualStyleBackColor = true;
			// 
			// readSelectedNamProfileButton
			// 
			this->readSelectedNamProfileButton->Location = System::Drawing::Point(124, 8);
			this->readSelectedNamProfileButton->Name = L"readSelectedNamProfileButton";
			this->readSelectedNamProfileButton->Size = System::Drawing::Size(54, 22);
			this->readSelectedNamProfileButton->TabIndex = 3;
			this->readSelectedNamProfileButton->Text = L"Read";
			this->readSelectedNamProfileButton->UseVisualStyleBackColor = true;
			this->readSelectedNamProfileButton->Click += gcnew System::EventHandler(this, &DiagForm::readSelectedNamProfileClickEvent);
			// 
			// readNamSetCombo
			// 
			this->readNamSetCombo->FormattingEnabled = true;
			this->readNamSetCombo->Items->AddRange(gcnew cli::array< System::Object^  >(2) {L"1", L"2"});
			this->readNamSetCombo->Location = System::Drawing::Point(75, 8);
			this->readNamSetCombo->Name = L"readNamSetCombo";
			this->readNamSetCombo->Size = System::Drawing::Size(37, 21);
			this->readNamSetCombo->TabIndex = 1;
			this->readNamSetCombo->SelectedIndexChanged += gcnew System::EventHandler(this, &DiagForm::readNamSet_SelectedIndexChanged);
			// 
			// label1
			// 
			this->label1->AutoSize = true;
			this->label1->Location = System::Drawing::Point(6, 11);
			this->label1->Name = L"label1";
			this->label1->Size = System::Drawing::Size(66, 13);
			this->label1->TabIndex = 2;
			this->label1->Text = L"NAM Profile:";
			// 
			// tabControl1
			// 
			this->tabControl1->Controls->Add(this->tabControl_Security);
			this->tabControl1->Controls->Add(this->tabControl_QuickFlash);
			this->tabControl1->Controls->Add(this->tabControl_NAM_EVDO);
			this->tabControl1->Controls->Add(this->tabControl_Memory);
			this->tabControl1->Controls->Add(this->tabControl_Other);
			this->tabControl1->Controls->Add(this->tabControl_Terminal);
			this->tabControl1->Controls->Add(this->tabControl_Android);
			this->tabControl1->Location = System::Drawing::Point(3, 26);
			this->tabControl1->Name = L"tabControl1";
			this->tabControl1->SelectedIndex = 0;
			this->tabControl1->Size = System::Drawing::Size(481, 411);
			this->tabControl1->TabIndex = 13;
			// 
			// tabControl_Security
			// 
			this->tabControl_Security->Controls->Add(this->groupBox2);
			this->tabControl_Security->Controls->Add(this->spcGroupBox);
			this->tabControl_Security->Location = System::Drawing::Point(4, 22);
			this->tabControl_Security->Name = L"tabControl_Security";
			this->tabControl_Security->Size = System::Drawing::Size(473, 385);
			this->tabControl_Security->TabIndex = 5;
			this->tabControl_Security->Text = L"Security";
			this->tabControl_Security->UseVisualStyleBackColor = true;
			// 
			// groupBox2
			// 
			this->groupBox2->Location = System::Drawing::Point(5, 179);
			this->groupBox2->Name = L"groupBox2";
			this->groupBox2->Size = System::Drawing::Size(465, 186);
			this->groupBox2->TabIndex = 12;
			this->groupBox2->TabStop = false;
			this->groupBox2->Text = L"16-Digit Passcodes";
			// 
			// spcGroupBox
			// 
			this->spcGroupBox->Controls->Add(this->readSpcMethodCombo);
			this->spcGroupBox->Controls->Add(this->button4);
			this->spcGroupBox->Controls->Add(this->button2);
			this->spcGroupBox->Controls->Add(this->button1);
			this->spcGroupBox->Controls->Add(this->sendSPCButton);
			this->spcGroupBox->Controls->Add(this->userSPC);
			this->spcGroupBox->Controls->Add(this->sendSPCZerosButton);
			this->spcGroupBox->Location = System::Drawing::Point(5, 12);
			this->spcGroupBox->Name = L"spcGroupBox";
			this->spcGroupBox->Size = System::Drawing::Size(465, 161);
			this->spcGroupBox->TabIndex = 11;
			this->spcGroupBox->TabStop = false;
			this->spcGroupBox->Text = L"Service Programming Code (SPC)";
			// 
			// readSpcMethodCombo
			// 
			this->readSpcMethodCombo->DisplayMember = L"1";
			this->readSpcMethodCombo->FormattingEnabled = true;
			this->readSpcMethodCombo->Items->AddRange(gcnew cli::array< System::Object^  >(5) {L"NV", L"HTC", L"Samsung", L"Samsung Alternate", 
				L"Kyocera"});
			this->readSpcMethodCombo->Location = System::Drawing::Point(10, 50);
			this->readSpcMethodCombo->Name = L"readSpcMethodCombo";
			this->readSpcMethodCombo->Size = System::Drawing::Size(133, 21);
			this->readSpcMethodCombo->TabIndex = 12;
			// 
			// button4
			// 
			this->button4->Location = System::Drawing::Point(149, 50);
			this->button4->Name = L"button4";
			this->button4->Size = System::Drawing::Size(74, 23);
			this->button4->TabIndex = 11;
			this->button4->Text = L"Read SPC";
			this->button4->UseVisualStyleBackColor = true;
			// 
			// button2
			// 
			this->button2->Location = System::Drawing::Point(339, 19);
			this->button2->Name = L"button2";
			this->button2->Size = System::Drawing::Size(80, 23);
			this->button2->TabIndex = 10;
			this->button2->Text = L"Write 000000";
			this->button2->UseVisualStyleBackColor = true;
			// 
			// button1
			// 
			this->button1->Location = System::Drawing::Point(260, 19);
			this->button1->Name = L"button1";
			this->button1->Size = System::Drawing::Size(74, 23);
			this->button1->TabIndex = 9;
			this->button1->Text = L"Write SPC";
			this->button1->UseVisualStyleBackColor = true;
			// 
			// sendSPCButton
			// 
			this->sendSPCButton->Location = System::Drawing::Point(97, 19);
			this->sendSPCButton->Name = L"sendSPCButton";
			this->sendSPCButton->Size = System::Drawing::Size(74, 23);
			this->sendSPCButton->TabIndex = 6;
			this->sendSPCButton->Text = L"Send SPC";
			this->sendSPCButton->UseVisualStyleBackColor = true;
			this->sendSPCButton->Click += gcnew System::EventHandler(this, &DiagForm::sendSPCClickEvent);
			// 
			// userSPC
			// 
			this->userSPC->Location = System::Drawing::Point(10, 21);
			this->userSPC->Name = L"userSPC";
			this->userSPC->Size = System::Drawing::Size(81, 20);
			this->userSPC->TabIndex = 5;
			// 
			// sendSPCZerosButton
			// 
			this->sendSPCZerosButton->Location = System::Drawing::Point(176, 19);
			this->sendSPCZerosButton->Name = L"sendSPCZerosButton";
			this->sendSPCZerosButton->Size = System::Drawing::Size(80, 23);
			this->sendSPCZerosButton->TabIndex = 7;
			this->sendSPCZerosButton->Text = L"Send 000000";
			this->sendSPCZerosButton->UseVisualStyleBackColor = true;
			this->sendSPCZerosButton->Click += gcnew System::EventHandler(this, &DiagForm::sendSPCZerosClickEvent);
			// 
			// tabControl_QuickFlash
			// 
			this->tabControl_QuickFlash->Location = System::Drawing::Point(4, 22);
			this->tabControl_QuickFlash->Name = L"tabControl_QuickFlash";
			this->tabControl_QuickFlash->Size = System::Drawing::Size(473, 385);
			this->tabControl_QuickFlash->TabIndex = 7;
			this->tabControl_QuickFlash->Text = L"Quick Flash";
			this->tabControl_QuickFlash->UseVisualStyleBackColor = true;
			// 
			// tabControl_Android
			// 
			this->tabControl_Android->Location = System::Drawing::Point(4, 22);
			this->tabControl_Android->Name = L"tabControl_Android";
			this->tabControl_Android->Size = System::Drawing::Size(473, 385);
			this->tabControl_Android->TabIndex = 6;
			this->tabControl_Android->Text = L"Android";
			this->tabControl_Android->UseVisualStyleBackColor = true;
			// 
			// notifyIcon1
			// 
			this->notifyIcon1->Text = L"notifyIcon1";
			this->notifyIcon1->Visible = true;
			this->notifyIcon1->MouseDoubleClick += gcnew System::Windows::Forms::MouseEventHandler(this, &DiagForm::notifyIcon1_MouseDoubleClick);
			// 
			// panelRight
			// 
			this->panelRight->Controls->Add(this->groupBox3);
			this->panelRight->Controls->Add(this->groupBox1);
			this->panelRight->Controls->Add(this->connectionGroupBox);
			this->panelRight->Location = System::Drawing::Point(484, 29);
			this->panelRight->Name = L"panelRight";
			this->panelRight->Size = System::Drawing::Size(252, 408);
			this->panelRight->TabIndex = 11;
			// 
			// groupBox1
			// 
			this->groupBox1->Controls->Add(this->deviceInfoChipset);
			this->groupBox1->Controls->Add(this->label14);
			this->groupBox1->Controls->Add(this->deviceInfoCompiled);
			this->groupBox1->Controls->Add(this->label13);
			this->groupBox1->Controls->Add(this->deviceInfoReleased);
			this->groupBox1->Controls->Add(this->label12);
			this->groupBox1->Controls->Add(this->readDeviceInfoButton);
			this->groupBox1->Controls->Add(this->deviceInfoEsnHex);
			this->groupBox1->Controls->Add(this->deviceInfoEsnDec);
			this->groupBox1->Controls->Add(this->deviceInfoMeidHex);
			this->groupBox1->Controls->Add(this->deviceInfoMeidDec);
			this->groupBox1->Controls->Add(this->label23);
			this->groupBox1->Controls->Add(this->label24);
			this->groupBox1->Controls->Add(this->label22);
			this->groupBox1->Controls->Add(this->label21);
			this->groupBox1->Location = System::Drawing::Point(3, 173);
			this->groupBox1->Name = L"groupBox1";
			this->groupBox1->Size = System::Drawing::Size(252, 231);
			this->groupBox1->TabIndex = 12;
			this->groupBox1->TabStop = false;
			this->groupBox1->Text = L"Device Information";
			// 
			// deviceInfoChipset
			// 
			this->deviceInfoChipset->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 7, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point, 
				static_cast<System::Byte>(0)));
			this->deviceInfoChipset->Location = System::Drawing::Point(73, 169);
			this->deviceInfoChipset->Name = L"deviceInfoChipset";
			this->deviceInfoChipset->ReadOnly = true;
			this->deviceInfoChipset->Size = System::Drawing::Size(175, 18);
			this->deviceInfoChipset->TabIndex = 15;
			// 
			// label14
			// 
			this->label14->AutoSize = true;
			this->label14->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 7, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point, 
				static_cast<System::Byte>(0)));
			this->label14->Location = System::Drawing::Point(9, 171);
			this->label14->Name = L"label14";
			this->label14->Size = System::Drawing::Size(50, 13);
			this->label14->TabIndex = 14;
			this->label14->Text = L"CHIPSET";
			// 
			// deviceInfoCompiled
			// 
			this->deviceInfoCompiled->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 7, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point, 
				static_cast<System::Byte>(0)));
			this->deviceInfoCompiled->Location = System::Drawing::Point(72, 122);
			this->deviceInfoCompiled->Name = L"deviceInfoCompiled";
			this->deviceInfoCompiled->ReadOnly = true;
			this->deviceInfoCompiled->Size = System::Drawing::Size(175, 18);
			this->deviceInfoCompiled->TabIndex = 13;
			// 
			// label13
			// 
			this->label13->AutoSize = true;
			this->label13->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 7, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point, 
				static_cast<System::Byte>(0)));
			this->label13->Location = System::Drawing::Point(8, 125);
			this->label13->Name = L"label13";
			this->label13->Size = System::Drawing::Size(62, 13);
			this->label13->TabIndex = 12;
			this->label13->Text = L"COMPILED";
			// 
			// deviceInfoReleased
			// 
			this->deviceInfoReleased->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 7, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point, 
				static_cast<System::Byte>(0)));
			this->deviceInfoReleased->Location = System::Drawing::Point(72, 146);
			this->deviceInfoReleased->Name = L"deviceInfoReleased";
			this->deviceInfoReleased->ReadOnly = true;
			this->deviceInfoReleased->Size = System::Drawing::Size(175, 18);
			this->deviceInfoReleased->TabIndex = 11;
			// 
			// label12
			// 
			this->label12->AutoSize = true;
			this->label12->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 7, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point, 
				static_cast<System::Byte>(0)));
			this->label12->Location = System::Drawing::Point(8, 149);
			this->label12->Name = L"label12";
			this->label12->Size = System::Drawing::Size(63, 13);
			this->label12->TabIndex = 10;
			this->label12->Text = L"RELEASED";
			// 
			// readDeviceInfoButton
			// 
			this->readDeviceInfoButton->Location = System::Drawing::Point(204, 9);
			this->readDeviceInfoButton->Name = L"readDeviceInfoButton";
			this->readDeviceInfoButton->Size = System::Drawing::Size(43, 20);
			this->readDeviceInfoButton->TabIndex = 9;
			this->readDeviceInfoButton->Text = L"Read";
			this->readDeviceInfoButton->UseVisualStyleBackColor = true;
			this->readDeviceInfoButton->Click += gcnew System::EventHandler(this, &DiagForm::readDeviceInfoButtonClickEvent);
			// 
			// deviceInfoEsnHex
			// 
			this->deviceInfoEsnHex->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 7, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point, 
				static_cast<System::Byte>(0)));
			this->deviceInfoEsnHex->Location = System::Drawing::Point(71, 98);
			this->deviceInfoEsnHex->Name = L"deviceInfoEsnHex";
			this->deviceInfoEsnHex->ReadOnly = true;
			this->deviceInfoEsnHex->Size = System::Drawing::Size(175, 18);
			this->deviceInfoEsnHex->TabIndex = 7;
			// 
			// deviceInfoEsnDec
			// 
			this->deviceInfoEsnDec->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 7, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point, 
				static_cast<System::Byte>(0)));
			this->deviceInfoEsnDec->Location = System::Drawing::Point(71, 75);
			this->deviceInfoEsnDec->Name = L"deviceInfoEsnDec";
			this->deviceInfoEsnDec->ReadOnly = true;
			this->deviceInfoEsnDec->Size = System::Drawing::Size(175, 18);
			this->deviceInfoEsnDec->TabIndex = 6;
			// 
			// deviceInfoMeidHex
			// 
			this->deviceInfoMeidHex->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 7, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point, 
				static_cast<System::Byte>(0)));
			this->deviceInfoMeidHex->Location = System::Drawing::Point(71, 53);
			this->deviceInfoMeidHex->Name = L"deviceInfoMeidHex";
			this->deviceInfoMeidHex->ReadOnly = true;
			this->deviceInfoMeidHex->Size = System::Drawing::Size(175, 18);
			this->deviceInfoMeidHex->TabIndex = 5;
			// 
			// deviceInfoMeidDec
			// 
			this->deviceInfoMeidDec->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 7, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point, 
				static_cast<System::Byte>(0)));
			this->deviceInfoMeidDec->Location = System::Drawing::Point(71, 32);
			this->deviceInfoMeidDec->Name = L"deviceInfoMeidDec";
			this->deviceInfoMeidDec->ReadOnly = true;
			this->deviceInfoMeidDec->Size = System::Drawing::Size(175, 18);
			this->deviceInfoMeidDec->TabIndex = 4;
			// 
			// label23
			// 
			this->label23->AutoSize = true;
			this->label23->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 7, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point, 
				static_cast<System::Byte>(0)));
			this->label23->Location = System::Drawing::Point(8, 101);
			this->label23->Name = L"label23";
			this->label23->Size = System::Drawing::Size(52, 13);
			this->label23->TabIndex = 3;
			this->label23->Text = L"ESN HEX";
			// 
			// label24
			// 
			this->label24->AutoSize = true;
			this->label24->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 7, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point, 
				static_cast<System::Byte>(0)));
			this->label24->Location = System::Drawing::Point(8, 78);
			this->label24->Name = L"label24";
			this->label24->Size = System::Drawing::Size(53, 13);
			this->label24->TabIndex = 2;
			this->label24->Text = L"ESN DEC";
			// 
			// label22
			// 
			this->label22->AutoSize = true;
			this->label22->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 7, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point, 
				static_cast<System::Byte>(0)));
			this->label22->Location = System::Drawing::Point(8, 56);
			this->label22->Name = L"label22";
			this->label22->Size = System::Drawing::Size(58, 13);
			this->label22->TabIndex = 1;
			this->label22->Text = L"MEID HEX";
			// 
			// label21
			// 
			this->label21->AutoSize = true;
			this->label21->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 7, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point, 
				static_cast<System::Byte>(0)));
			this->label21->Location = System::Drawing::Point(8, 34);
			this->label21->Name = L"label21";
			this->label21->Size = System::Drawing::Size(59, 13);
			this->label21->TabIndex = 0;
			this->label21->Text = L"MEID DEC";
			// 
			// connectionGroupBox
			// 
			this->connectionGroupBox->Controls->Add(this->comPortSelection);
			this->connectionGroupBox->Controls->Add(this->detectPortsButton);
			this->connectionGroupBox->Controls->Add(this->disconnectButton);
			this->connectionGroupBox->Controls->Add(this->connectButton);
			this->connectionGroupBox->Location = System::Drawing::Point(2, 3);
			this->connectionGroupBox->Name = L"connectionGroupBox";
			this->connectionGroupBox->Size = System::Drawing::Size(250, 84);
			this->connectionGroupBox->TabIndex = 11;
			this->connectionGroupBox->TabStop = false;
			this->connectionGroupBox->Text = L"Connection Settings";
			// 
			// setModeButton
			// 
			this->setModeButton->Location = System::Drawing::Point(171, 17);
			this->setModeButton->Name = L"setModeButton";
			this->setModeButton->Size = System::Drawing::Size(71, 23);
			this->setModeButton->TabIndex = 6;
			this->setModeButton->Text = L"Set Mode";
			this->setModeButton->UseVisualStyleBackColor = true;
			this->setModeButton->Click += gcnew System::EventHandler(this, &DiagForm::setModeButtonClickEvent);
			// 
			// setModeCombo
			// 
			this->setModeCombo->FormattingEnabled = true;
			this->setModeCombo->Items->AddRange(gcnew cli::array< System::Object^  >(4) {L"Offline D", L"Reset", L"Online", L"Low"});
			this->setModeCombo->Location = System::Drawing::Point(9, 19);
			this->setModeCombo->Name = L"setModeCombo";
			this->setModeCombo->Size = System::Drawing::Size(156, 21);
			this->setModeCombo->TabIndex = 5;
			// 
			// comPortSelection
			// 
			this->comPortSelection->FormattingEnabled = true;
			this->comPortSelection->Items->AddRange(gcnew cli::array< System::Object^  >(15) {L"COM1", L"COM2", L"COM3", L"COM4", L"COM5", 
				L"COM6", L"COM7", L"COM8", L"COM9", L"COM10", L"COM11", L"COM12", L"COM13", L"COM14", L"COM15"});
			this->comPortSelection->Location = System::Drawing::Point(4, 20);
			this->comPortSelection->Name = L"comPortSelection";
			this->comPortSelection->Size = System::Drawing::Size(109, 21);
			this->comPortSelection->TabIndex = 1;
			this->comPortSelection->SelectedIndexChanged += gcnew System::EventHandler(this, &DiagForm::comPortSelection_SelectedIndexChanged);
			// 
			// detectPortsButton
			// 
			this->detectPortsButton->Location = System::Drawing::Point(5, 47);
			this->detectPortsButton->Name = L"detectPortsButton";
			this->detectPortsButton->Size = System::Drawing::Size(52, 23);
			this->detectPortsButton->TabIndex = 2;
			this->detectPortsButton->Text = L"Detect";
			this->detectPortsButton->UseVisualStyleBackColor = true;
			// 
			// disconnectButton
			// 
			this->disconnectButton->Location = System::Drawing::Point(176, 19);
			this->disconnectButton->Name = L"disconnectButton";
			this->disconnectButton->Size = System::Drawing::Size(71, 23);
			this->disconnectButton->TabIndex = 4;
			this->disconnectButton->Text = L"Disconnect";
			this->disconnectButton->UseVisualStyleBackColor = true;
			this->disconnectButton->Click += gcnew System::EventHandler(this, &DiagForm::disconnectButtonClickEvent);
			// 
			// connectButton
			// 
			this->connectButton->Location = System::Drawing::Point(119, 19);
			this->connectButton->Name = L"connectButton";
			this->connectButton->Size = System::Drawing::Size(55, 23);
			this->connectButton->TabIndex = 3;
			this->connectButton->Text = L"Connect";
			this->connectButton->UseVisualStyleBackColor = true;
			this->connectButton->Click += gcnew System::EventHandler(this, &DiagForm::connectButtonClickEvent);
			// 
			// statusStrip
			// 
			this->statusStrip->Items->AddRange(gcnew cli::array< System::Windows::Forms::ToolStripItem^  >(3) {this->statusStripLabel1, 
				this->statusStripLabel2, this->progressBarMain});
			this->statusStrip->Location = System::Drawing::Point(0, 440);
			this->statusStrip->Name = L"statusStrip";
			this->statusStrip->Size = System::Drawing::Size(742, 22);
			this->statusStrip->TabIndex = 12;
			this->statusStrip->ItemClicked += gcnew System::Windows::Forms::ToolStripItemClickedEventHandler(this, &DiagForm::statusStrip1_ItemClicked);
			// 
			// statusStripLabel1
			// 
			this->statusStripLabel1->Name = L"statusStripLabel1";
			this->statusStripLabel1->Size = System::Drawing::Size(79, 17);
			this->statusStripLabel1->Text = L"Disconnected";
			// 
			// statusStripLabel2
			// 
			this->statusStripLabel2->DisplayStyle = System::Windows::Forms::ToolStripItemDisplayStyle::Text;
			this->statusStripLabel2->Name = L"statusStripLabel2";
			this->statusStripLabel2->Size = System::Drawing::Size(546, 17);
			this->statusStripLabel2->Spring = true;
			this->statusStripLabel2->TextAlign = System::Drawing::ContentAlignment::MiddleLeft;
			// 
			// progressBarMain
			// 
			this->progressBarMain->Name = L"progressBarMain";
			this->progressBarMain->Size = System::Drawing::Size(100, 16);
			// 
			// toolStripStatusLabel1
			// 
			this->toolStripStatusLabel1->Name = L"toolStripStatusLabel1";
			this->toolStripStatusLabel1->Size = System::Drawing::Size(0, 17);
			// 
			// menuStrip1
			// 
			this->menuStrip1->Items->AddRange(gcnew cli::array< System::Windows::Forms::ToolStripItem^  >(3) {this->aToolStripMenuItem, 
				this->bToolStripMenuItem, this->cToolStripMenuItem2});
			this->menuStrip1->Location = System::Drawing::Point(0, 0);
			this->menuStrip1->Name = L"menuStrip1";
			this->menuStrip1->Size = System::Drawing::Size(742, 24);
			this->menuStrip1->TabIndex = 8;
			this->menuStrip1->Text = L"menuStrip1";
			// 
			// aToolStripMenuItem
			// 
			this->aToolStripMenuItem->DropDownItems->AddRange(gcnew cli::array< System::Windows::Forms::ToolStripItem^  >(2) {this->bToolStripMenuItem1, 
				this->cToolStripMenuItem});
			this->aToolStripMenuItem->Name = L"aToolStripMenuItem";
			this->aToolStripMenuItem->Size = System::Drawing::Size(37, 20);
			this->aToolStripMenuItem->Text = L"File";
			// 
			// bToolStripMenuItem1
			// 
			this->bToolStripMenuItem1->Name = L"bToolStripMenuItem1";
			this->bToolStripMenuItem1->Size = System::Drawing::Size(116, 22);
			this->bToolStripMenuItem1->Text = L"Settings";
			// 
			// cToolStripMenuItem
			// 
			this->cToolStripMenuItem->Name = L"cToolStripMenuItem";
			this->cToolStripMenuItem->Size = System::Drawing::Size(116, 22);
			this->cToolStripMenuItem->Text = L"Exit";
			// 
			// bToolStripMenuItem
			// 
			this->bToolStripMenuItem->DropDownItems->AddRange(gcnew cli::array< System::Windows::Forms::ToolStripItem^  >(1) {this->aToolStripMenuItem2});
			this->bToolStripMenuItem->Name = L"bToolStripMenuItem";
			this->bToolStripMenuItem->Size = System::Drawing::Size(48, 20);
			this->bToolStripMenuItem->Text = L"Tools";
			// 
			// aToolStripMenuItem2
			// 
			this->aToolStripMenuItem2->Name = L"aToolStripMenuItem2";
			this->aToolStripMenuItem2->Size = System::Drawing::Size(157, 22);
			this->aToolStripMenuItem2->Text = L"MEID Converter";
			// 
			// cToolStripMenuItem2
			// 
			this->cToolStripMenuItem2->DropDownItems->AddRange(gcnew cli::array< System::Windows::Forms::ToolStripItem^  >(2) {this->aToolStripMenuItem3, 
				this->bVToolStripMenuItem});
			this->cToolStripMenuItem2->Name = L"cToolStripMenuItem2";
			this->cToolStripMenuItem2->Size = System::Drawing::Size(44, 20);
			this->cToolStripMenuItem2->Text = L"Help";
			// 
			// aToolStripMenuItem3
			// 
			this->aToolStripMenuItem3->Name = L"aToolStripMenuItem3";
			this->aToolStripMenuItem3->Size = System::Drawing::Size(171, 22);
			this->aToolStripMenuItem3->Text = L"Check for Updates";
			// 
			// bVToolStripMenuItem
			// 
			this->bVToolStripMenuItem->Name = L"bVToolStripMenuItem";
			this->bVToolStripMenuItem->Size = System::Drawing::Size(171, 22);
			this->bVToolStripMenuItem->Text = L"About";
			// 
			// groupBox3
			// 
			this->groupBox3->Controls->Add(this->setModeButton);
			this->groupBox3->Controls->Add(this->setModeCombo);
			this->groupBox3->Location = System::Drawing::Point(2, 88);
			this->groupBox3->Name = L"groupBox3";
			this->groupBox3->Size = System::Drawing::Size(248, 79);
			this->groupBox3->TabIndex = 13;
			this->groupBox3->TabStop = false;
			this->groupBox3->Text = L"Mode";
			// 
			// DiagForm
			// 
			this->AutoScaleDimensions = System::Drawing::SizeF(6, 13);
			this->AutoScaleMode = System::Windows::Forms::AutoScaleMode::Font;
			this->ClientSize = System::Drawing::Size(742, 462);
			this->Controls->Add(this->tabControl1);
			this->Controls->Add(this->statusStrip);
			this->Controls->Add(this->menuStrip1);
			this->Controls->Add(this->panelRight);
			this->MainMenuStrip = this->menuStrip1;
			this->MaximumSize = System::Drawing::Size(758, 500);
			this->MinimumSize = System::Drawing::Size(758, 500);
			this->Name = L"DiagForm";
			this->Text = L"Phone Stuffs";
			this->Load += gcnew System::EventHandler(this, &DiagForm::DiagForm_Load);
			this->tabControl_Terminal->ResumeLayout(false);
			this->tabControl_Terminal->PerformLayout();
			this->tabControl_NAM_EVDO->ResumeLayout(false);
			this->tabControl_NAM_EVDO->PerformLayout();
			this->group_NAM->ResumeLayout(false);
			this->group_NAM->PerformLayout();
			this->group_EVDO->ResumeLayout(false);
			this->group_EVDO->PerformLayout();
			this->tabControl1->ResumeLayout(false);
			this->tabControl_Security->ResumeLayout(false);
			this->spcGroupBox->ResumeLayout(false);
			this->spcGroupBox->PerformLayout();
			this->panelRight->ResumeLayout(false);
			this->groupBox1->ResumeLayout(false);
			this->groupBox1->PerformLayout();
			this->connectionGroupBox->ResumeLayout(false);
			this->statusStrip->ResumeLayout(false);
			this->statusStrip->PerformLayout();
			this->menuStrip1->ResumeLayout(false);
			this->menuStrip1->PerformLayout();
			this->groupBox3->ResumeLayout(false);
			this->ResumeLayout(false);
			this->PerformLayout();

		}
#pragma endregion


private: 
	

	/*
	* connectButtonClickEvent
	*/
	System::Void connectButtonClickEvent(System::Object^  sender, System::EventArgs^  e) {
		
		int comIndexSelected = this->comPortSelection->SelectedIndex;
		if(this->dmComPort->IsOpened()){
			this->statusStripLabel2->Text = "Com Port is Already Connected. Disconnect first.";
			return;
		} else if(comIndexSelected < 0){
			this->statusStripLabel2->Text = "Select a COM Port";
			return;
		}

		Object^ comSelected = this->comPortSelection->SelectedItem;
		System::String ^portNumber = comSelected->ToString()->Replace("COM","");
		int portNumberI = int::Parse(portNumber);

		this->statusStripLabel2->Text = "Connecting to Port " + portNumber;

		int con = this->dmComPort->Open(portNumberI);
		if(!this->dmComPort->IsOpened()){
			this->statusStripLabel2->Text = "Could Not Connect to Port " + portNumber;
			return;
		}

		this->statusStripLabel2->Text = "";
		this->statusStripLabel1->Text = "Connected (COM"+portNumber+")";
	}	

	/*
	* disconnectButtonClickEvent
	*/
	System::Void disconnectButtonClickEvent(System::Object^  sender, System::EventArgs^  e) {
		if(this->dmComPort->IsOpened()){
			this->dmComPort->Close();
			this->statusStripLabel1->Text = "Disconnected";
			this->statusStripLabel2->Text = "";
			
		} else {
			this->statusStripLabel2->Text = "Not Connected";
		}
	}	


	/**
	* sendSPCZerosClickEvent
	*/
	System::Void sendSPCZerosClickEvent(System::Object^  sender, System::EventArgs^  e) {
		if(!this->dmComPort->IsOpened()){
			this->statusStripLabel2->Text = "Please Connect to a COM Port First";
			return;
		}

		this->statusStripLabel2->Text = "Sending SPC 000000";
		this->userSPC->Text = "000000";

		char outbuf[1024];
		char cmdDiagSendSpcZeros[] = { 0x30, 0x30, 0x30, 0x30, 0x30, 0x30, NULL };

		int commandStatus = this->dmComPort->sendDMCommand(qcdm::DIAG_CMD_SPC, cmdDiagSendSpcZeros, outbuf);
	
		if(commandStatus < 0){
			MessageBox::Show("Error Reading/Writing to Device");
			return;
		}

		qcdm::DMSendSPCResponse *dmResp = (qcdm::DMSendSPCResponse *)outbuf;

		MessageBox::Show(dmResp->accepted == 1 ? "SPC Accepted" : "SPC Not Accepted. Wait 10 Seconds");

		return;
	}

	/*
	* sendSPCClickEvent
	*/
	System::Void sendSPCClickEvent(System::Object^  sender, System::EventArgs^  e) {
		if(!this->dmComPort->IsOpened()){
			this->statusStripLabel2->Text = "Please Connect to a COM Port First";
			return;
		}

		System::String ^userSPC = this->userSPC->Text;

		int userSPCLength = this->userSPC->TextLength;
		if(userSPCLength != 6){
			this->statusStripLabel2->Text = "SPC is not valid. Enter 6 Digits";
			this->userSPC->Focus();
			delete userSPC;
			return;
		}
		
		char spcToSend[6];
		array<wchar_t>^ spcChars = this->userSPC->Text->ToCharArray();
		for(int i = 0; i < 6; i++){
			spcToSend[i] = spcChars[i];
		}
		
		this->progressBarMain->Step = 1;

		this->statusStripLabel2->Text = "Sending SPC " + this->userSPC->Text;
		delete userSPC, spcChars;

		this->progressBarMain->PerformStep();

		char outbuf[this->dmComPort->m_maxRxPacketSize];
		int commandStatus = this->dmComPort->sendDMCommand(qcdm::DIAG_CMD_SPC, spcToSend, outbuf);
		this->progressBarMain->PerformStep();
			
		this->progressBarMain->Step = 0;

		if(commandStatus < 0){
			MessageBox::Show("Error Reading/Writing to Device");
			return;
		}

		qcdm::DMSendSPCResponse *dmResp = (qcdm::DMSendSPCResponse *)outbuf;
		MessageBox::Show(dmResp->accepted == 1 ? "SPC Accepted" : "SPC Not Accepted. Wait 10 Seconds");
		this->statusStripLabel2->Text = dmResp->accepted == 1 ? "SPC Accepted" : "SPC Not Accepted. Wait 10 Seconds";
	}

	/**
	* readSelectedNamProfileClickEvent
	*/
	System::Void readSelectedNamProfileClickEvent(System::Object^  sender, System::EventArgs^  e) {
		if(!this->dmComPort->IsOpened()){
			this->statusStripLabel2->Text = "Please Connect to a COM Port First";
			return;
		}
		
		if(this->readNamSetCombo->SelectedIndex == -1){
			 this->readNamSetCombo->SelectedIndex = 0;
		}

		int selectedNamProfile = int::Parse(this->readNamSetCombo->Text);
		this->progressBarMain->Step = 1;
		this->progressBarMain->Minimum = 0;
		this->progressBarMain->Maximum = 7;

		System::String^ namMdn = gcnew System::String(this->dmCommander->getNamMdn(selectedNamProfile).c_str());
		this->namMDN->Text = namMdn;
		this->progressBarMain->PerformStep();

		System::String^ namMin = gcnew System::String(this->dmCommander->getNamMin(selectedNamProfile).c_str());
		this->namMIN->Text = namMin;
		this->progressBarMain->PerformStep();

		System::String^ namBanner = gcnew System::String(this->dmCommander->getNamBanner(selectedNamProfile).c_str());
		this->namBanner->Text = namBanner;
		this->progressBarMain->PerformStep();

		System::String^ namName = gcnew System::String(this->dmCommander->getNamName(selectedNamProfile).c_str());
		this->namName->Text = namName;
		this->progressBarMain->PerformStep();

		this->namLock->Checked = this->dmCommander->getNamLock();

		this->progressBarMain->PerformStep();

		System::String^ namSid = gcnew System::String(this->dmCommander->getHomeSid(selectedNamProfile).c_str());
		this->namSID1->Text = namSid;
		this->progressBarMain->PerformStep();

		System::String^ namNid = gcnew System::String(this->dmCommander->getHomeNid(selectedNamProfile).c_str());
		this->namNID->Text = namNid;
		this->progressBarMain->PerformStep();

		string scm = this->dmCommander->getScm();
		const char* scmC = scm.c_str();
		printf("%02X\n",scmC);
		if(scmC[3] == 0x31){
			
		} else {

		}

	}

	/**
	* readDeviceInfoButtonClickEvent
	*/
	System::Void readDeviceInfoButtonClickEvent(System::Object^  sender, System::EventArgs^  e) {
		if(!this->dmComPort->IsOpened()){
			this->statusStripLabel2->Text = "Please Connect to a COM Port First";
			return;
		}

		string readMeid = this->dmCommander->getMeid();

		try{
			MeidConverter::MEID result = meidConverter->convert((char*)readMeid.c_str());
			System::String^ meidDec = gcnew System::String(result.meidDec.c_str());
			System::String^ meidHex = gcnew System::String(result.meidHex.c_str());
			System::String^ esnDec = gcnew System::String(result.pEsnDec.c_str());
			System::String^ esnHex = gcnew System::String(result.pEsnHex.c_str());

			this->deviceInfoMeidDec->Text = meidDec->ToUpper();
			this->deviceInfoMeidHex->Text = meidHex->ToUpper();
			this->deviceInfoEsnDec->Text = esnDec->ToUpper();
			this->deviceInfoEsnHex->Text = esnHex->ToUpper();

		}catch(std::string e){
			this->statusStripLabel2->Text = "Exception During MEID Conversion";
			printf("MEIDCONVERTER: %s",e.c_str());
		}

		DMVersionInfo versionInfo = this->dmCommander->getVersionInfo();

		this->deviceInfoCompiled->Text = gcnew System::String(versionInfo.compiledAt);
		this->deviceInfoReleased->Text = gcnew System::String(versionInfo.releasedAt);
	
		this->deviceInfoChipset->Text = gcnew System::String(this->dmCommander->getChipset().c_str());
	}

	/**
	* readSelectedEvdoProfileClickEvent
	*/
	System::Void readSelectedEvdoProfileClickEvent(System::Object^  sender, System::EventArgs^  e) {
		if(!this->dmComPort->IsOpened()){
			this->statusStripLabel2->Text = "Please Connect to a COM Port First";
			return;
		}

		if(this->evdoProfileSetCombo->SelectedIndex == -1){
			 this->evdoProfileSetCombo->SelectedIndex = 0;
		}

		int selectedEvdoProfile = int::Parse(this->evdoProfileSetCombo->Text);
		this->progressBarMain->Step = 1;
		this->progressBarMain->Minimum = 0;
		this->progressBarMain->Maximum = 8;

		System::String^ pppUserId = gcnew System::String(this->dmCommander->getPppUserId().c_str());
		this->evdoPPPUsername->Text = pppUserId;
		this->progressBarMain->PerformStep();

		System::String^ pppPassword = gcnew System::String(this->dmCommander->getPppPassword().c_str());
		this->evdoPPPPasword->Text = pppPassword;
		this->progressBarMain->PerformStep();

		System::String^ papUserId = gcnew System::String(this->dmCommander->getPapUserId().c_str());
		this->evdoPAPUsername->Text = papUserId;
		this->progressBarMain->PerformStep();

		System::String^ papPassword = gcnew System::String(this->dmCommander->getPapPassword().c_str());
		this->evdoPAPPassword->Text = papPassword;
		this->progressBarMain->PerformStep();
			 
		System::String^ hdrAnAuthUserIdLong = gcnew System::String(this->dmCommander->getHdrAnAuthUserIdLong().c_str());
		this->evdoHRDUsername->Text = hdrAnAuthUserIdLong;
		this->progressBarMain->PerformStep();
			 
		System::String^ hdrAnAuthPasswordLong = gcnew System::String(this->dmCommander->getHdrAnAuthPasswordLong().c_str());
		this->evdoHRDPassword->Text = hdrAnAuthPasswordLong;
		this->progressBarMain->PerformStep();

		System::String^ hdrAnAuthNai = gcnew System::String(this->dmCommander->getHdrAnAuthNai().c_str());
		this->evdoHRDUsername2->Text = hdrAnAuthNai;
		this->progressBarMain->PerformStep();

		System::String^ hdrAnAuthPasword = gcnew System::String(this->dmCommander->getHdrAnAuthPassword().c_str());
		this->evdoHRDPassword2->Text = hdrAnAuthPasword;
		this->progressBarMain->PerformStep();
	}

	/*
	*
	*
	*/
	System::Void setModeButtonClickEvent(System::Object^  sender, System::EventArgs^  e) {
		if(!this->dmComPort->IsOpened()){
			this->statusStripLabel2->Text = "Please Connect to a COM Port First";
			return;
		}

		if(this->setModeCombo->SelectedIndex == -1){
			this->statusStripLabel2->Text = "Select a Mode";
			return;
		}

		this->statusStripLabel2->Text = this->setModeCombo->Text;

		bool statusChanged = false;
		if(this->setModeCombo->Text == "Offline D"){
			statusChanged = this->dmCommander->setMode(DIAG_MODE_OFFLINE_D);
		} else if(this->setModeCombo->Text == "Reset"){
			statusChanged = this->dmCommander->setMode(DIAG_MODE_RESET);
		} else if(this->setModeCombo->Text == "Online"){
			statusChanged = this->dmCommander->setMode(DIAG_MODE_ONLINE);
		} else if(this->setModeCombo->Text == "Low"){
			statusChanged = this->dmCommander->setMode(DIAG_MODE_LOW);
		}
		
	}

private: System::Void textBox1_TextChanged(System::Object^  sender, System::EventArgs^  e) {
		 } 
private: System::Void button3_Click(System::Object^  sender, System::EventArgs^  e) {
		 }

private: System::Void label1_Click(System::Object^  sender, System::EventArgs^  e) {
		 }

private: System::Void comPortSelection_SelectedIndexChanged(System::Object^  sender, System::EventArgs^  e) {
			 
		 }


private: System::Void DiagForm_Load(System::Object^  sender, System::EventArgs^  e) {
		 }
private: System::Void groupBox1_Enter(System::Object^  sender, System::EventArgs^  e) {
		 }
private: System::Void connectionSettingsLabel_Click(System::Object^  sender, System::EventArgs^  e) {
		 }
private: System::Void statusStrip1_ItemClicked(System::Object^  sender, System::Windows::Forms::ToolStripItemClickedEventArgs^  e) {
		 }
private: System::Void notifyIcon1_MouseDoubleClick(System::Object^  sender, System::Windows::Forms::MouseEventArgs^  e) {
		 }

private: System::Void tabPage2_Click(System::Object^  sender, System::EventArgs^  e) {
		 }
private: System::Void readNamSet_SelectedIndexChanged(System::Object^  sender, System::EventArgs^  e) {
		 }
private: System::Void tabControl_NAM_Click(System::Object^  sender, System::EventArgs^  e) {
		 }
private: System::Void label11_Click(System::Object^  sender, System::EventArgs^  e) {
		 }
private: System::Void label12_Click(System::Object^  sender, System::EventArgs^  e) {
		 }
private: System::Void textBox19_TextChanged(System::Object^  sender, System::EventArgs^  e) {
		 }
private: System::Void listView1_SelectedIndexChanged(System::Object^  sender, System::EventArgs^  e) {
		 }
private: System::Void evdoProfileSetCombo_SelectedIndexChanged(System::Object^  sender, System::EventArgs^  e) {
		 }



};
}
