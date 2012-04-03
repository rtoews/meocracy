@Echo off
cd \pubinfo
Set /P pwd=Please enter your mysql root password:

@Echo on
mysql -hlocalhost -uroot -p%pwd% -Dcapublic -v -v -f < truncateAll.sql 2>&1 | more
@Echo off

@Echo done...
Pause
