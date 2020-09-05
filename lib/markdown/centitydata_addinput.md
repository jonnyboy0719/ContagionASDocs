Example of usage:  
```cpp
CEntityData@ inputdata = EntityCreator::EntityData();
inputdata.Add( "targetname", "foobar" );
inputdata.Add( "spawnflags", "8192" );
inputdata.Add( "skin", "2" );
inputdata.Add( "model", "models/props_doors/door_metal.mdl" );

// After it is spawned, let's call some input values
inputdata.Add( "SetBreakable", "1", true );
inputdata.Add( "SetHealth", "100", true, "1.0" );

// Spawn our entity
CBaseEntity @pEntity = EntityCreator::Create( "prop_door_rotating", Vector( 0, 0, 0 ), QAngle( 0, 0, 0 ), inputdata );
```

