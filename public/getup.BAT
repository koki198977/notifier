@echo off

START C:\xampp\xampp-control.EXE

timeout /t 25 /nobreak

START C:\xampp\htdocs\app.notifier\dist\win-unpacked\server.EXE
