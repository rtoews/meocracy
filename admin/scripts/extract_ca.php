<?php
function db()
{
    $connect = mysql_connect('localhost', 'meo', 'Badlsd22');
    return $connect;
}

class ExtractStateLegislation {
    private $_connect;
    private $_state;

    public function __construct($state = 'CA')
    {
        $this->_connect = db();
        $this->_read_state_data();
    }


    private function _sponsor($legislation_id, $sponsor_id, $sponsor_name, $primary_sponsor) 
    {
        mysql_select_db('meocracy', $this->_connect);
        if ($sponsor_id > 0) {
            $sql = "SELECT legislation_sponsor_id FROM legislation_sponsor WHERE sponsor_id='$sponsor_id' AND legislation_id='$legislation_id'";
            $result = mysql_query($sql, $this->_connect);
            if (mysql_num_rows($result) == 0) {
                $sql = "INSERT INTO legislation_sponsor (sponsor_id, legislation_id, sponsor_name, primary_sponsor) VALUES ('$sponsor_id', '$legislation_id', '$sponsor_name', '$primary_sponsor')";
            }
        }
        else {
            $sql = "INSERT INTO legislation_sponsor (sponsor_name, legislation_id) VALUES ('$sponsor_name', '$legislation_id')";
        }
        mysql_query($sql, $this->_connect);
    }


    /*
     * _retrieve_legislator_data:  Retrieve legislator data from California
     *     state database.  Return data for insertion into meocracy table.
     */
    private function _retrieve_legislator_data($name)
    {
        mysql_select_db('capublic', $this->_connect);
        $data = array();
        $sql = "SELECT district, last_name, first_name, session_year, house_type, legislator_name, author_name, name_title, party, active_flg
                FROM legislator_tbl
                WHERE author_name='$name'
               ";
        $result = mysql_query($sql, $this->_connect);
        if ($row = mysql_fetch_assoc($result)) {
            $data = $row;
        }
        return $data;
    }


    /*
     * _insert_legislator:  Accept data retrieved from California state database
     *     and insert it into meocracy legislator table.
     */
    private function _insert_legislator($legislator_data)
    {
        mysql_select_db('meocracy', $this->_connect);
        $district = $legislator_data['district'];
        $name_last = $legislator_data['last_name'];
        $name_first = $legislator_data['first_name'];
        $session_year = $legislator_data['session_year'];
        $house_type = $legislator_data['house_type'];
        $legislator_name = $legislator_data['legislator_name'];
        $author_name = $legislator_data['author_name'];
        $name_title = $legislator_data['name_title'];
        $party = $legislator_data['party'];
        $active_flag = $legislator_data['active_flg'];

        $sql = "INSERT INTO legislator
                (district, state, name_last, name_first, session_year, house_type, legislator_name, author_name, name_title, party, active_flag)
                VALUES ('$district', 'CA', '$name_last', '$name_first', '$session_year', '$house_type', '$legislator_name', '$author_name', '$name_title', '$party', '$active_flag')
               ";
        mysql_query($sql, $this->_connect);
        $id = mysql_insert_id($this->_connect);
        return $id;
    }


    private function _parse_xml_data($xml)
    {
        $data = array();
        if (preg_match('/<caml:digesttext>(.*)<\/caml:digesttext>/is', $xml, $match)) {
            $data['summary'] = $match[1];
        }
        if (preg_match('/<caml:bill( .*?)?>(.*)<\/caml:bill>/is', $xml, $match)) {
            $data['bill_text'] = $match[2];
        }
        return $data;
    }


    private function _get_sponsor_id($name)
    {
        mysql_select_db('meocracy', $this->_connect);
        $sponsor_id = 0;
        $sql = "SELECT id FROM legislator WHERE author_name='$name'";
print "SQL: $sql\n";
        $result = mysql_query($sql, $this->_connect);
        if ($row = mysql_fetch_object($result)) {
            $sponsor_id = $row->id;
        }
        else {
            $data = $this->_retrieve_legislator_data($name);
            if (!empty($data)) {
                $sponsor_id = $this->_insert_legislator($data);
            }
        }
        return $sponsor_id;
    }


    /*
     * _is_legislation: Accept the California state bill ID, and search for it
     *     in the meocracy legislation table.  Return the legislation tabke's
     *     primary key if the record exists; false, otherwise.
     */
    private function _is_legislation($bill_id)
    {
        mysql_select_db('meocracy', $this->_connect);
        $legislation_id = false;
        $sql = "SELECT legislation_id FROM legislation WHERE bill_id='".$bill_id."'";
print "SQL: $sql\n";
        $result = mysql_query($sql, $this->_connect);
        if ($row = mysql_fetch_object($result)) {
            $legislation_id = $row->legislation_id;
        }
        return $legislation_id;
    }


    /*
     * _legislation:  Accept legislation data read from the California state
     *     database.  Check to see whether the bill record exists in the
     *     meocracy legislation table.  
     *     If the bill exists, add the sponsor.
     *     If the bill does not exist in the meocracy table, add it.
     */
    private function _legislation($legislation_data)
    {
        mysql_select_db('meocracy', $this->_connect);
        $bill_id = $legislation_data['bill_id'];
        $latest_version = $legislation_data['latest_version'];
        $status = $legislation_data['status'];
        $subject = $legislation_data['subject'];
        $location = $legislation_data['location'];
        $chamber = $legislation_data['chamber'];
        $summary = $legislation_data['summary'];
        $sponsor_id = $legislation_data['sponsor_id'];
        $sponsor_name = $legislation_data['sponsor_name'];
        $primary_sponsor = $legislation_data['primary_sponsor'];
        if (false === $legislation_id = $this->_is_legislation($bill_id)) {
print "Apparently missing bill ID $bill_id\n";
            $legislation_id = $this->_insert_legislation($legislation_data);

            $sql = "INSERT INTO legislation
                        (bill_id, bill_latest_id, status, title, current_location, current_chamber, summary)
                        VALUES ('$bill_id', '$latest_version', '$status', '$subject', '$location', '$chamber', '$summary')
                        ";
            mysql_query($sql, $this->_connect);
            $legislation_id = mysql_insert_id($this->_connect);
        }
        $this->_sponsor($legislation_id, $sponsor_id, $sponsor_name, $primary_sponsor);
    }


    private function _read_state_data()
    {
        mysql_select_db('capublic', $this->_connect);
        $sql = "SELECT b.bill_id, b.latest_bill_version_id, b.current_status, b.current_location, b.current_house,
                       bv.subject, bv.bill_xml,
                       bva.name, bva.primary_author_flg
                FROM bill_tbl b 
                JOIN bill_version_tbl bv ON b.latest_bill_version_id=bv.bill_version_id
                JOIN bill_version_authors_tbl bva ON b.latest_bill_version_id=bva.bill_version_id
                LEFT JOIN legislator_tbl l ON bva.name=l.author_name
                WHERE b.current_status <> 'Died'
                ORDER BY b.bill_id
        ";
        $result = mysql_query($sql, $this->_connect);
        while ($row = mysql_fetch_object($result)) {
            $text_data = $this->_parse_xml_data($row->bill_xml);
            $sponsor_id = $this->_get_sponsor_id($row->name);

            $state_data = array(
                'bill_id' => $row->bill_id,
                'latest_version' => $row->bill_latest_id,
                'status' => $row->current_status,
                'subject' => $row->subject,
                'location' => $row->current_location,
                'chamber' => $row->current_house,
                'summary' => $text_data['summary'],
                'sponsor_id' => $sponsor_id,
                'sponsor_name' => $row->name,
                'primary_sponsor' => $row->primary_author_flg,
            );

            $this->_legislation($state_data);

            mysql_select_db('capublic', $this->_connect);
        }
    }
}

$extract = new ExtractStateLegislation();
?>
