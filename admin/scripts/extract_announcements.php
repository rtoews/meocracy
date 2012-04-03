<?php
require_once('script_connect.php');

class ExtractAnnouncements {
    private $_current_tag;
    private $_depth;
    private $_ndx;
    private $_parser;
    private $_tag_at_depth;
    public $data;

    public function __construct($url)
    {
        $xml_parser = xml_parser_create();
        xml_set_element_handler($xml_parser, "self::_startElement", "self::_endElement");
        xml_set_character_data_handler($xml_parser,"self::_char");

        $xml = file($url);
        $data = implode(' ', $xml);
        if (!xml_parse($xml_parser, $data)) {
            die(sprintf("XML error: %s at line %d",
                xml_error_string(xml_get_error_code($xml_parser)),
                xml_get_current_line_number($xml_parser)));
        }
        xml_parser_free($xml_parser);
    }

    private function _startElement($parser, $name, $attrs) 
    {
        $this->_current_tag = trim($name);
        $this->_depth[$parser]++;
        $d = $this->_depth[$parser];
        $this->_tag_at_depth[$d] = $this->_current_tag;
        if ($name == 'ITEM') {
            $this->_ndx++;
        }
    }

    private function _endElement($parser, $name) 
    {
        $this->_depth[$parser]--;
    }

    private function _char($parser,$data)
    {
        global $depth;
        global $current_tag;
        global $tag_at_depth;
    
        $data = trim(strip_tags($data));
        $data = preg_replace('/\n/', '<br/>', $data);
        $data = preg_replace('/\s+/', ' ', $data);
        if ($data) {
            if ($this->_tag_at_depth[$this->_depth[$parser] - 1] == 'ITEM') {
                $this->data[$this->_ndx-1][$this->_current_tag] = $data;
            }
        }
    }
}


class ProcessFeeds {
    private $_connect;
    public function __construct($feeds)
    {
        global $connect;
        $this->_connect = $connect;

        $cities = file($feeds);
        foreach ($cities as $city) {
            list($name, $feed) = array_map(trim, split("\t", $city));
            if ($feed) {
                echo "$name: $feed\n";
                $city_id = $this->_get_city_by_name($name, 5);
                $announcements = new ExtractAnnouncements($feed);
                foreach ($announcements->data as $item) {
                    $this->_save_announcement($city_id, $item);
                }
            }
        }

    }


    private function _get_city_by_name($city, $state_id)
    {
        global $connect;
        $city_id = null;
        $sql = "SELECT city_id FROM ref_city WHERE city_name='$city' AND state_id='$state_id' ORDER BY zip LIMIT 1";
        $result = mysql_query($sql, $this->_connect);
        if ($row = mysql_fetch_assoc($result)) {
            $city_id = $row['city_id'];
        }
        return $city_id;
    }


    private function _save_announcement($city_id, $item)
    {
        $title = mysql_real_escape_string($item['TITLE']);
        $description = mysql_real_escape_string($item['DESCRIPTION']);
        $pub_date = date('Y-m-d', strtotime($item['PUBDATE']));

        $sql = "SELECT id FROM announcement WHERE title='$title' AND text='$description' AND pub_date='$pub_date'";
        $result = mysql_query($sql, $this->_connect);
        if (mysql_num_rows($result) == 0) {
            $sql = "INSERT INTO announcement (title, text, pub_date, region_id, region_type) VALUES ('$title', '$description', '$pub_date', $city_id, 4)";
            mysql_query($sql, $this->_connect);
        }
    }
}


$pf = new ProcessFeeds('feeds.txt');

?>
