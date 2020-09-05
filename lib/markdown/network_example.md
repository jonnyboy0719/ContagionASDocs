**Save** also accepts *float*, *int*, *bool* and *string* inputs.

**CNetworked example**
```cpp
CNetworked@ pNetworked = Network::Create( "my_network_example", true );

// Set a new value for the string, interger and the boolean
string strString = "another_value";
int iInt = 10;
bool bBool = true;
float flFloat = 5.0;

// Now tell the network to save these new values
pNetworked.Save( "my_string", strString );
pNetworked.Save( "shared_value", iInt );
pNetworked.Save( "shared_value", bBool );
pNetworked.Save( "shared_value", flFloat );
```
