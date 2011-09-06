****Beta Project**** - Take note that this is a very beta project. If you find any issues or have any ideas please report them.

Creating a scaffold for the Windows Azure SDK for PHP (http://phpazure.codeplex.com) can be a complex and daunting experience. This project aims to abstract as much of that as possible.

Usage on Windows
----------------
For purposes of illustration this documentation will use C:\temp for all file locations. Also it is assumed that the Windows Azure SDK for PHP has been correctly setup. See the following link for a tutorial on setting up the PHP SDK http://azurephp.interoperabilitybridges.com/articles/setup-the-windows-azure-sdk-for-php

- Download this project to C:\temp\Windows-Azure-PHP-Scaffold-Generator
- Open a terminal and run the command: scaffolder run -s="C:\temp\Windows-Azure-PHP-Scaffold-Generator\Scaffold.phar" -out="C:\temp\Scaffold"
- You can now place all your project files in C:\Temp\Scaffold\Scaffold\resources\WebRole
- If you wish to add additional logic to the scaffold (Downloading and unpacking archives, etc) you may do so in C:\Temp\Scaffold\Scaffold\index.php
- When you are done setting up the scaffold run the following command to generate a .phar file for distribution and usage: scaffolder build -in="C:\temp\Scaffold" -out="C:\temp\CustomScaffold.phar"

Custom scaffold logic
---------------------
Custom scaffold logic allows your scaffolder to do things such as download and extract archives, perform file operations on the project, etc.

Because this project is still in beta stages see the index.php file for code documentation: C:\Temp\Scaffold\Scaffold\index.php
