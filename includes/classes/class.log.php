<?php
class Log extends DBInterface
{
	public function __construct($log_id = 0)
	{
		parent::__construct('log', $log_id);
 	}


    public function add($msg)
    {
        $this->msg($msg);
        $this->insert();
    }

}
?>
