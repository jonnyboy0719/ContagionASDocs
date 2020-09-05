Example:
```cpp
CASConVar@ pConVarTest = null;

void OnPluginInit()
{
	@pConVarTest = ConVar::Create( "zps_var", "10", "This is an example test var" );
	ConVar::Register( pConVarTest, "ConVar_TestString" );
}

void ConVar_TestString(string &in strNewValue, string &in strOldValue)
{
	Chat.PrintToChat( all, "new var >> \"" + strNewValue + "\"" );
	Chat.PrintToChat( all, "old var >> \"" + strOldValue + "\"" );
}
```
