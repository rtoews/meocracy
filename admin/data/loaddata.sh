cat ../load-ca/tables_lc.lst | while read line
do
    line=${line%l*}l
    echo Processing $line
    mysql -hlocalhost -umeo -pBadlsd22 -Dcapublic -v -v -f < ../load-ca/$line.sql 2>&1 > ../load-ca/$line.log
done
