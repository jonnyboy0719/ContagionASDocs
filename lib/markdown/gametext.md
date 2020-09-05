**HudTextParams** defines the effects, position etc. for the game_text.

Example:
```cpp
// Create our params
HudTextParams pParams;

// Our X pos (0-1.0)
// If -1, it will be centered
pParams.x = -1;

// Our Y pos (0-1.0)
// If -1, it will be centered
pParams.y = 0.3f;

// Our channel
pParams.channel = 1;

// Fade settings
pParams.fadeinTime = 1.5f;
pParams.fadeoutTime = 0.5f;

// Our hold time
pParams.holdTime = 5.0f;

// Our FX time
pParams.fxTime = 0.25;

// Our primary color
pParams.SetColor( Color( 232, 232, 232 ) );

// Our secondary color
pParams.SetColor2( Color( 240, 110, 0 ) );

// Print our message
Utils.GameText( "The lab is open! Retrieve the Antivirus!", pParams );
```
