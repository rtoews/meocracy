<?php
class Image extends DBInterface
{
	public function __construct($image_id = 0)
	{
		parent::__construct('ref_image', $image_id);
 	}



}
?>
