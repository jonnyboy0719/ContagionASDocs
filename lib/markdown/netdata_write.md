**NetData write example**
```cpp
NetData nData;

// String input, 1st value
nData.Write( "Hello World" );

// Interger, 2nd value
nData.Write( 2 );

// Unassigned Interger, 3rd value
uint64 udata = 10;
nData.Write( udata );
```
