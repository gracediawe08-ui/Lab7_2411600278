@echo off
cd /d C:\xampp\htdocs\Lab7_2411600278

C:\php82\php.exe artisan schedule:run >> storage\logs\scheduler.log 2>&1