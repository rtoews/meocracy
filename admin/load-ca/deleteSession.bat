@Echo off
cd \pubinfo
cls
Set /P sey=Please enter session year to delete:

echo \T deleteSession.log                                                                  > deleteSession.sql
echo use `capublic`;                                                                      >> deleteSession.sql
echo Delete From capublic.bill_analysis_tbl         Where bill_id like '%sey%%%';         >> deleteSession.sql
echo Delete From capublic.bill_detail_vote_tbl      Where bill_id like '%sey%%%';         >> deleteSession.sql
echo Delete From capublic.bill_history_tbl          Where bill_id like '%sey%%%';         >> deleteSession.sql
echo Delete From capublic.bill_summary_vote_tbl     Where bill_id like '%sey%%%';         >> deleteSession.sql
echo Delete From capublic.bill_tbl                  Where bill_id like '%sey%%%';         >> deleteSession.sql
echo Delete From capublic.bill_version_authors_tbl  Where bill_version_id like '%sey%%%'; >> deleteSession.sql
echo Delete From capublic.bill_version_tbl          Where bill_version_id like '%sey%%%'; >> deleteSession.sql
echo Delete From capublic.committee_hearing_tbl     Where bill_id like '%sey%%%';         >> deleteSession.sql
echo Delete From capublic.daily_file_tbl            Where bill_id like '%sey%%%';         >> deleteSession.sql
echo Delete From capublic.legislator_tbl            Where session_year like '%sey%%%';    >> deleteSession.sql
echo Delete From capublic.location_code_tbl         Where session_year like '%sey%%%';    >> deleteSession.sql
echo Commit;                                                                              >> deleteSession.sql
echo Quit                                                                                 >> deleteSession.sql

Set /P ans=Are you sure you want to delete ALL records for session year %sey%? [y/n]:
If NOT %ans% == y goto QUIT

Set /P pwd=Please enter your mysql root password:
@Echo on
mysql -hlocalhost -uroot -p%pwd% -Dcapublic -v -v -f < deleteSession.sql 2>&1 > deleteSession.log
more deleteSession.log
@Echo off

:QUIT
@Echo done...
Pause
