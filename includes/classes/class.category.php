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

    public static function legislation_get_category($location)
    {
        $sql = "SELECT lc_parent_id FROM category WHERE lc_category='$location'";
        $parent_id = db()->Get_Cell($sql);
        $sql = "SELECT lc_category FROM category WHERE lc_id='$parent_id'";
        $category_name = db()->Get_Cell($sql);
        return array($parent_id, $category_name);
    }

    public static function announcement_get_category($id)
    {
        $sql = "SELECT ac_category_id FROM announcement_category WHERE ac_announcement_id='$id'";
        $category_id = db()->Get_Cell($sql);
        $sql = "SELECT lc_category FROM category WHERE lc_id='$category_id'";
        $category_name = db()->Get_Cell($sql);
        return array($category_id, $category_name);
    }
}
?>
