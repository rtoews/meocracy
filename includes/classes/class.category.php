<?php
class Category extends DBInterface
{
	public function __construct($lc_id = 0)
	{
		parent::__construct('category', $lc_id);
        if ($lc_id) {
            $sql = "SELECT COUNT(*) FROM legislation WHERE current_location IN (SELECT lc_category FROM category WHERE lc_parent_id=$lc_id)";
            $l_count = db()->Get_Cell($sql);
            $sql = "SELECT COUNT(*) FROM announcement WHERE category_id=$lc_id";
            $a_count = db()->Get_Cell($sql);
            $total_count = $l_count = $a_count; 
            $this->cat_count($total_count);
        }
 	}

    public static function get_location($location)
    {
        $sql = "SELECT description FROM category WHERE lc_category='$location'";
        $description = db()->Get_Cell($sql);
        return $description;
    }

}
?>
