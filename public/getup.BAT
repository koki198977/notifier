@echo off

START C:\xampp\xampp-control.EXE

timeout /t 40 /nobreak

START C:\xampp\htdocs\app.notifier\dist\win-unpacked\server.EXE
