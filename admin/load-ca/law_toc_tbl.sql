Truncate Table capublic.law_toc_tbl;
LOAD DATA LOCAL
  INFILE "/tmp/LAW_TOC_TBL.dat"
  REPLACE
  INTO TABLE capublic.law_toc_tbl
  FIELDS TERMINATED BY '\t'
  OPTIONALLY ENCLOSED BY '`'
  LINES TERMINATED BY '\n'
(
   LAW_CODE
  ,DIVISION
  ,TITLE
  ,PART
  ,CHAPTER
  ,ARTICLE
  ,HEADING
  ,ACTIVE_FLG
  ,TRANS_UID
  ,TRANS_UPDATE
  ,NODE_SEQUENCE
  ,NODE_LEVEL
  ,NODE_POSITION
  ,NODE_TREEPATH
  ,CONTAINS_LAW_SECTIONS
  ,HISTORY_NOTE
  ,OP_STATUES
  ,OP_CHAPTER
  ,OP_SECTION
)


