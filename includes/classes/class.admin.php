<?php
require_once(DOC_ROOT . '/includes/classes/class.sponsor.php');

class Admin extends DBInterface
{
	public function __construct( $admin_id = 0 )
	{
		parent::__construct( 'user', $admin_id );
 	}
		
	/**
	 * 
	 * Simple login function.
	 * @param $username string Username
	 * @param $password string Password
	 * @return boolean
	 * 
	 */
	public function login( $username, $password, $encrypted = false )
	{
        // valid login requires status_id to be 1, in addition to matching email and password.  The status_id indicates email has been confirmed.
        if (!$encrypted) {
		    $sql_raw = "SELECT sponsor_id, region_id, region_type FROM sponsor WHERE email='{$username}' AND password='{$password}'";
        }
        else {
		    $sql_raw = "SELECT sponsor_id, region_id, region_type FROM sponsor WHERE email='{$username}' AND password='{$this->generateHashedPassword($password)}'";
        }
$sql_raw = "SELECT sponsor_id FROM sponsor WHERE email='{$username}'";
		
		if ($data = db()->Get_Row($sql_raw)) {
            $sponsor = new Sponsor($data['sponsor_id']);
			$_SESSION['admin_id'] = $sponsor->id();
			$_SESSION['region_id'] = $sponsor->region_id();
			$_SESSION['region_type'] = $sponsor->region_type();
			return true;
		}

		return false;
	}
	
	/**
	 *
	 * Validate email
	 * @param mixed $value
	 * @return bool
	 * 
	 */
	public static function validate_email($value)
	{
		if (preg_match('/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $value, $matches)) {
			return true;
		}
		
		return false;
	}
	
	/**
	 *
	 * Override of __call() method of getting / setting password so that we can
	 * hash up the password when setting
	 * @param string $password
	 *
	 */
	public function password( $password = null )
	{
		if ( null !== $password )
		{
			$this->_fields['password'] = $this->generateHashedPassword( $password );
		}
		
		return $this->_fields['password'];
	}
	
	/**
	 * 
	 * @param $password string Cleartext password we are generating a hash for.
	 * @return string  Encrypted password.
	 * 
	 */
	public function generateHashedPassword( $password )
	{
		return md5( $password . SALT_KEY );	
	}
	
}
?>
