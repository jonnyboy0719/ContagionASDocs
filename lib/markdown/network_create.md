```cpp
// Create our network called "Example", and make sure we don't destroy it on map change.
// However, if it already exists, it will simply grab the existing one instead.
CNetworked@ pNetworked = Network::Create( "Example", false );
```
