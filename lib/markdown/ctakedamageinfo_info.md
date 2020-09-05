Example #1:
```cpp
// Create our damage info with no predefined info
CTakeDamageInfo info;

// Who is the inflictor? Make sure the CBaseEntity object pEntityAttacker is valid
info.SetInflictor( pEntityInflictor );

// Who is the attacker? Make sure the CBaseEntity object pEntityAttacker is valid
info.SetAttacker( pEntityAttacker );

// The amount of damage we should do
info.SetDamage( 20.0f );

// The specific damage type
info.SetDamageType( DMG_BUCKSHOT );
```


Example #2:
```cpp
// Create our damage info with inflictor, attacker, damage and dmg type info
CTakeDamageInfo info = CTakeDamageInfo( pEntityInflictor, pEntityAttacker, 20.0f, DMG_BUCKSHOT );
```


Example #3:
```cpp
// Create our damage info with attacker, damage and dmg type info
CTakeDamageInfo info = CTakeDamageInfo( pEntityAttacker, 20.0f, DMG_BUCKSHOT );
```
