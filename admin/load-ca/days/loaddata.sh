cat ./tables_lc.lst | while read line
do
    line=${line%l*}l
    echo Processing $line
    mysql -hlocalhost -umeo -pBadlsd22 -Dcapublic -v -v -f < ../$line.sql 2>&1 > ./$line.log
done
