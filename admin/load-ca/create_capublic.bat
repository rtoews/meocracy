@Echo off
cd \pubinfo
Set /P pwd=Please enter your mysql root password:

@Echo on
mysql -hlocalhost -P3306 -uroot -p%pwd% -v -v -f < capublic.sql 2>&1 | more
@Echo off

@Echo done...
Pause
