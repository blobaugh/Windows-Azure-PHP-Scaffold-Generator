echo off

set PWD=%CD%

REM Folder containing the scaffold

echo Cleaning up previous scaffolder files
 rmdir /S /Q %PWD%\build
 mkdir %PWD%\build

echo Building scaffold .phar file 
 call scaffolder build -in="%PWD%\Scaffold" -out="%PWD%\build\Scaffold.phar"

echo Creating project directories
call scaffolder run -out="%PWD%\build\Scaffold" -s="%PWD%\build\Scaffold.phar"