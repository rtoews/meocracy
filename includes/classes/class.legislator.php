<?php
class Legislator extends DBInterface
{
	public function __construct($legislator_id = 0)
	{
		parent::__construct('legislator', $legislator_id);
        if ($legislator_id) {
            $this->id = $this->id();
            $this->full_name = $this->name_first() . ' ' . $this->name_last();
            $this->img_name = remove_accents(strtolower(str_replace(' ', '', $this->name_last()) . '_' . str_replace(' ', '', $this->name_first())));
            $this->party = substr($this->party(), 0, 1);
            $this->office = $this->name_title();
        }
 	}

}
?>
