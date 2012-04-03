<?php
class Legislator extends DBInterface
{
	public function __construct($legislator_id = 0)
	{
        $title_abbr = array('Senator' => 'Sen.', 'Assemblymember' => 'Asm.');
		parent::__construct('legislator', $legislator_id);
        if ($legislator_id) {
            $dist_number = substr($this->district(), 2, 2);
            $this->id = $this->id();
            $this->img_name = remove_accents(strtolower(fix_name($this->name_last()) . '_' . fix_name($this->name_first()))) . '.jpg';
            $this->image = $this->get_image_src();
            $this->level = $this->state() ? 'State' : '';
            $this->title = $title_abbr[$this->name_title()];
            $this->full_name = $this->name_first() . ' ' . $this->name_last();
            $this->party = substr($this->party(), 0, 1);
            $this->district = $this->state() . '-' . $dist_number;
            $this->full_title = sprintf("%s %s - %s (%s)",
                $this->level,
                $this->title,
                $this->party,
                $this->district);
            $this->office = $this->name_title();
        }
 	}

    public function get_image_src()
    {
        $src = SPONSOR_IMAGE_PATH . $this->img_name;
        if (!file_exists(DOC_ROOT . $src)) {
            $src = 'missing.png';
        }
        return $src;
    }

}
?>
