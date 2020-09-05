```cpp
// Grabs our network "Example"
CNetworked@ pNetworked = Network::Get( "Example" );

// Check if it's valid
if ( pNetworked !is null )
	Network::Dump( pNetworked );
```
