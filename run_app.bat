@echo off
SET PHP_PATH=C:\xampp\php\php.exe
SET HOST=127.0.0.1
SET PORT=8080
SET DOCROOT=public

echo Checking for PHP at %PHP_PATH%...
if not exist "%PHP_PATH%" (
    echo PHP not found at %PHP_PATH%
    echo Please edit this script to set the correct path to php.exe
    pause
    exit /b
)

echo Checking for document root at %DOCROOT%...
if not exist "%DOCROOT%" (
    echo Document root '%DOCROOT%' not found.
    echo Please run this script from the project root folder.
    pause
    exit /b
)

echo Starting SAMS Server at http://%HOST%:%PORT%...
echo.
echo A new window will open for the server. 
echo Do NOT close that window, or the site will stop working.
echo.

start "SAMS Server Output" /min cmd /k "%PHP_PATH% -S %HOST%:%PORT% -t %DOCROOT%"

echo Waiting for server to initialize...
timeout /t 3 >nul

echo Opening browser...
start http://%HOST%:%PORT%

echo.
echo If the browser did not open, please visit http://%HOST%:%PORT% manually.
echo.
pause
