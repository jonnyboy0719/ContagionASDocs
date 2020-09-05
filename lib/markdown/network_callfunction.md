`CallFunction` uses [Networked Data][NetData] to send it's information across.

**Usage example**
```cpp
void Example()
{
	// Our NetData
	NetData nData;

	// String input, 1st value
	nData.Write( "Hello World" );

	// Interger, 2nd value
	nData.Write( 2 );

	// Unassigned Interger, 3rd value
	uint64 udata = 10;
	nData.Write( udata );

	// Call our function "MyNetworkedValue"
	Network::CallFunction( "MyNetworkedValue", nData );
}

// Our function, can be placed on the same AS file, or in another file.
void MyNetworkedValue( NetObject@ pData )
{
	// Make sure we aren't invalid
	if ( pData is null ) return;

	// Grab our data
	string value1 = "null";
	int value2 = 0;
	uint64 value3 = 0;

	if ( pData.HasIndexValue( 0 ) )
		value1 = pData.GetString( 0 );

	if ( pData.HasIndexValue( 1 ) )
		value2 = pData.GetInt( 1 );

	if ( pData.HasIndexValue( 2 ) )
		value3 = pData.GetUint64( 2 );

	// Print our stuff
	Log.PrintToServerConsole(
		LOGTYPE_INFO,
		"Network Test",
		"[NetWork]\n\tValue 1: %1\n\tValue 2: %2\n\tValue 3: %3\n",
		value1,
		value2,
		value3
	);
}
```
