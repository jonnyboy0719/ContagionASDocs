The function `Ent_Fire` also accepts string arrays for the entity names and functions.

Examples:  
```cpp
// Method 1
array<string> doorFunctions = { "Lock", "SetUnreakable" };
Engine.Ent_Fire( "door_roofstairs", doorFunctions );

// Method 2
array<string> boltcuttersArray = {
	"boltcutters05",	// Roof Cutters
	"boltcutters04",	// Basement Cutters
};
Engine.Ent_Fire( boltcuttersArray, "Kill" );

// Method 3
array<string> doorsToLockUnlock = { "hallway_doors1_a", "hallway_doors1_b" };
array<string> doorFunctions = { "Lock", "SetUnbreakable" };
Engine.Ent_Fire( doorsToLockUnlock, doorFunctions );
```

