**Usage example**
```cpp
void ConnectToDB()
{
	// SQL Connection info
	string strSQLHost = "example.com"; // Our hostname
	int iPort = 0; // if iPort is 0, then it will use default MySQL port
	string strSQLUser = "user"; // Our username
	string strSQLPass = "password"; // Our password
	string strSQLDB = "database"; // Our database

	// Create our connection
	CSQLConnection@ hConnection = SQL::Connect( strSQLHost, iPort, strSQLUser, strSQLPass, strSQLDB );

	// Make sure we are connected
	if ( hConnection is null )
		return;

	// If enabled, it will print errors for SendAndIgnoreQuery.
	// By default it does not print any errors, if it fails to send to send the Query.
	SQL::PrintErrorsOnIgnoreQuery( hConnection, true );

	// Our query
	string strQuery = "SELECTFROM `example_table` WHERE (`authid` = 'foobar')";

	// Send our query
	CSQLQuery@ hQuery = SQL::SendQuery( hConnection, strQuery );
	if ( hQuery !is null )
	{
		// Make sure we have no errors
		if ( !SQL::QueryError( hQuery ) )
		{
			int iID = SQL::ReadResult::GetInt( hQuery, 0 );
			int iHealth = SQL::ReadResult::GetInt( hQuery, "health" );
			string strName = SQL::ReadResult::GetString( hQuery, "name" );
			CSQLDate@ pDate = SQL::ReadResult::GetDate( hQuery, "datetime" );

			// Print our results
			Log.PrintToServerConsole(
				LOGTYPE_INFO,
				"SQL Query",
				"ID: " + iID
				+ " | Health: " + iHealth
				+ " | Name: " + strName
				+ " | Date: " + pDate.ToString( "Y-M-d t" )
			);
		}

		// Always free our query handle
		SQL::FreeHandle( hQuery );
	}

	// Send and ignore (Note, this will not print any errors what so ever, if PrintErrorsOnIgnoreQuery isn't set to true)
	SQL::SendAndIgnoreQuery(
		hConnection,
		"UPDATE `example_foobar` SET `weekly` = " + 10 + ", `daily` = " + 5 + " WHERE `example` = 'foobar';"
	);

	// Disconnect
	SQL::Disconnect( hConnection );
}
```
