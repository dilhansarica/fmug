@echo off
:BEGIN
cls
set JS_ROOT=..\www\js
set MJSB_ROOT=%JS_ROOT%\lib\mjsb

copy %MJSB_ROOT%\require\i18n.js %MJSB_ROOT%\
mkdir %MJSB_ROOT%\nls
xcopy /e /s /y %JS_ROOT%\i18n %MJSB_ROOT%\nls\

mkdir %MJSB_ROOT%\app
xcopy /e /s /y %JS_ROOT%\app %MJSB_ROOT%\app\
mkdir %MJSB_ROOT%\project
xcopy /e /s /y %JS_ROOT%\lib\project %MJSB_ROOT%\project\
mkdir %MJSB_ROOT%\common
xcopy /e /s /y %JS_ROOT%\lib\common %MJSB_ROOT%\common\

.\bin\node.exe .\bin\createcompress.js %JS_ROOT%\
.\bin\node.exe .\bin\r.js -o %MJSB_ROOT%/compress.js baseUrl=%MJSB_ROOT%

del /q %MJSB_ROOT%\i18n.js %MJSB_ROOT%\compress.js
rmdir /s /q %MJSB_ROOT%\app %MJSB_ROOT%\project %MJSB_ROOT%\common %MJSB_ROOT%\nls
:END
