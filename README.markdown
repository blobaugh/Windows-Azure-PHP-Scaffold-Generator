****BETA PROJECT**** This is currently a very beta project. There is not gaurantee on your results. Please leave feedback with bugs and feature requests

Setup on Windows
----------------
- Download and extract the project files to C:\Program Files\scaffgen
- Edit your system path to include C:\Program Files\scaffgen
- Open a terminal and type 'scaffgen -help' to ensure proper setup

Create a base scaffold
------------------------
If you simply want to create an empty scaffold as a base for your new scaffold run the following command:

scaffgen -out path/to/new/scaffold -name NameOfNewScaffold

You should then find your new blank scaffold at the path you chose all ready to be setup!

Create a scaffold from existing sources
---------------------------------------
Chances are that you already have an existing application and all you need to do is put it into a scaffold for distribution or quick setup of future Windows Azure deploymments. Luckily this tool gives you the option to import your existing application with the following command:

scaffgen -in path/to/existing/sources -out path/to/new/scaffold -name NameOfNewScaffold


Advanced usage
--------------
Windows Azure and these scaffolds are very powerful.  scaffold can be used to automatically setup services on Windows Azure, such as PHP, all without the end user requiring any specialized knowledge. The scaffold will do all the heavy lifting and all the user needs to do is tweak their application before deploymeht to Windows Azure.

This scaffold generator has been setup with ease of customization in mind. Inside the scaffold folder in the path you specified you will find an index.php file. Inside this file you can insert custom logic and setup routines. In fact there are several helper methods that already exist to download remote files, extract archives, and more. At this point in time most of the documentation for that is in the index.php file and you are encouraged to go there and read the comments.

If you want to tweak with the service configuration files or customize the PHP that is deployed take a look inside the root scaffold folder and the WebRole folder.

Additional Resources
--------------------
You are highly encouraged to check out the resources and guides availble on the AzurePHP website, in particular the following my be useful:

- http://azurephp.interoperabilitybridges.com/articles/build-and-deploy-a-windows-azure-php-application
- http://azurephp.interoperabilitybridges.com/articles/packaging-applications
- http://azurephp.interoperabilitybridges.com/articles/deploying-your-first-php-application-to-windows-azure#new_deploy
