Introduction
============

Welcome to the Contagion Angelscript API documentation!

This will help you creating Angelscript plugins for Contagion for use in servers as well as maps.

We provide some code examples in the dark area to the right whenever we feel it's needed.

Keep in mind that this is an **API documentation, not an Angelscript scripting manual/tutorial**. If you are not familiar with Angelscript and/or you need to brush up your knowledge, please consult it's [scripting manual](http://www.angelcode.com/angelscript/sdk/docs/manual/doc_script.html) first before proceeding.

Like always, we are open to comments, suggestions and potential fixes we can make to this API documentation. The best place to do this would be on our `#contagion-chat` channel on our [Discord server](https://discord.gg/monochrome).

Have fun scripting!

- - -

## Available Angelscript commands

To access these commands, make sure the player has the admin access rights of [LEVEL_ADMIN][AdminAccessLevel_t] or higher.
Command                 | Description                                                                      | Dedicated Server Only
----------------------- | ---------------------------------------------------------------------------------|-----------------------------
ascmd                   | Allows the server to use the angelscript server commands generated by plugins.   | **Yes**
cg_angelscript_debug    | Enable angelscript debug?                                                        | **Yes**
as_listplugins          | List all server side Angelscript plugins.                                        | **No**
as_reloadplugin         | Reload a specific server side Angelscript plugin.                                | **No**
as_loadplugin           | Load a specific server side Angelscript plugin.                                  | **No**
as_unloadplugin         | Unload a specific server side Angelscript plugin.                                | **No**
as_reloadallplugins     | Reload all server side Angelscript plugins.                                      | **No**
as_unloadallplugins     | Unload all server side Angelscript plugins.                                      | **No**
as_processpluginevents  | Processes server side Angelscript plugins's events.                              | **No**
