<?php
require_once('script_connect.php');
require_once('class.tag.php');

class TagBuild {
    private $_connect;
    private $_tag_machine;
    private $_tables;

    function __construct()
    {
        global $connect;
        $this->_connect = $connect;

        $this->_tables = array(
            'announcement' => array(
                'table' => 'announcement',
                'key' => 'id',
                'tag_table' => 'announcement_tag',
                'tag_key' => 'announcement_tag_id',
                'foreign' => 'announcement_id',
            ),
            'legislation' => array(
                'table' => 'legislation',
                'key' => 'legislation_id',
                'tag_table' => 'legislation_tag',
                'tag_key' => 'legislation_tag_id',
                'foreign' => 'legislation_id',
            ),
        );

        $this->_tag_machine = new Tag();

//        $this->_process('legislation');
        $this->_process('announcement');
    }    

    private function _process($tbl_ndx)
    {
        $table_specs = $this->_tables[$tbl_ndx];
        $table_name = $table_specs['table'];
        $key_field = $table_specs['key'];

        $field_array = $this->_get_fields($tbl_ndx);
        $field_array[] = $key_field;

        $fields = implode(', ', $field_array);
        $sql = "SELECT $fields FROM $table_name";
echo $sql."\n";
        $result = mysql_query($sql, $this->_connect);
        while ($row = mysql_fetch_assoc($result)) {
            $key = $row[$key_field];
            $data = array();
            foreach ($row as $value) {
                $data[] = $value;
            }
            $words = implode(' ', $data);
            $tags = $this->_tag_machine->extract_tags($words);
            $this->_process_tags($tags, $tbl_ndx, $key);
        }
    }

    private function _process_tags($tags, $table_ndx, $key)
    {
        foreach ($tags as $tag) {
            $tag_id = $this->_get_tag_id($tag);
            $this->_associate_tag_with_record($tag_id, $table_ndx, $key);
        }
    }

    private function _get_tag_id($tag)
    {
        $sql = "SELECT tag_id FROM tag WHERE tag='".$tag."'";
        $result = mysql_query($sql, $this->_connect);
        if ($row = mysql_fetch_object($result)) {
            $tag_id = $row->tag_id;
        }
        else {
            $sql = "INSERT INTO tag SET tag='".$tag."'";
            mysql_query($sql, $this->_connect);
            $tag_id = mysql_insert_id($this->_connect);
        }
        return $tag_id;
    }

    private function _associate_tag_with_record($tag_id, $table_ndx, $fkey)
    {
        $table_specs = $this->_tables[$table_ndx];
        
        $tag_table_name = $table_specs['tag_table'];
        $tag_key = $table_specs['tag_key'];
        $foreign_key = $table_specs['foreign'];

        $sql = "SELECT $tag_key FROM $tag_table_name WHERE tag_id=$tag_id AND $foreign_key=$fkey";
echo $sql."\n";
        $result = mysql_query($sql, $this->_connect);
        if (mysql_num_rows($result) == 0) {
            $sql = "INSERT INTO $tag_table_name SET $foreign_key=$fkey, tag_id=$tag_id";
echo $sql."\n";
            mysql_query($sql, $this->_connect);
        }
    }

    private function _get_fields($tbl)
    {
        $fields = array(
            'announcement' => array('title'),
            'legislation' => array('title'),
        );

        return $fields[$tbl];
    }

}

$tb = new TagBuild();
?>
