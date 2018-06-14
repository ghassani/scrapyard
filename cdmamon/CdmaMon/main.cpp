#include "DiagForm.h"


using namespace CdmaMon;

[STAThreadAttribute]
int main(array<System::String ^> ^args)
{
	Application::EnableVisualStyles();
	Application::SetCompatibleTextRenderingDefault(false); 

	Application::Run(gcnew DiagForm());
	return 0;
}