A simple example of how to use the `FileSystem::Json::Write`

**Example**
```cpp
string strAttacker = "my_attacker";
string strVictim = "the_victim";

// Creates the JsonValues@ object
JsonValues@ pJson = FileSystem::Json::CreateJson();

// arg and type
FileSystem::Json::Write( pJson, "some_function", "attacker", strAttacker );
FileSystem::Json::Write( pJson, "some_function", "victim", strVictim );
FileSystem::Json::Write( pJson, "some_function", "boolean", true );
FileSystem::Json::Write( pJson, "some_function", "interger", 15 );
FileSystem::Json::Write( pJson, "some_function", "flTest", 27.06f );

// type only
FileSystem::Json::Write( pJson, "text", "Hello there, you are dead" );

// writes to zps/data/custom/ as example.json
FileSystem::Json::CreateFile( "example", pJson );
```
