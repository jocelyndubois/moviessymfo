@echo off
DEL GeneratedMovies.txt
chcp 1252
for /f "usebackq TOKENS=*" %%i in (`dir /s /b /o:gen *.avi *.mkv`) do echo %%~nxi >> GeneratedMovies.txt
pause