<?php
require_once(DOC_ROOT . '/includes/classes/class.city.php');

class User extends DBInterface
{
    public function __construct($user_id = 0)
    {
        parent::__construct('user', $user_id);
        if ($user_id) {
            $sql = sprintf("SELECT region_id FROM user_region WHERE user_id=%d AND region_type=%d ORDER BY updated_date DESC LIMIT 1", $user_id, REGION_CITY);
            $city_id = db()->Get_Cell($sql);
            $city = new City($city_id);
            $this->city_id = $city_id;
            $this->county_id = $city->county_id();
            $this->state_id = $city->state_id();
        }
    }

	/**
	 * 
	 * Simple login function.
	 * @param $username string Username
	 * @param $password string Password
	 * @return boolean
	 * 
	 */
	public function login( $username, $password, $encrypted = true )
	{
        // valid login requires status_id to be 1, in addition to matching email and password.  The status_id indicates email has been confirmed.
        if (!$encrypted) {
		    $sql_raw = "SELECT user_id FROM user WHERE mobile_phone='{$username}' AND password='{$password}' AND status=1";
        }
        else {
		    $sql_raw = "SELECT user_id FROM user WHERE mobile_phone='{$username}' AND password='{$this->generateHashedPassword($password)}' AND status=1";
        }
$sql_raw = "SELECT user_id FROM user WHERE mobile_phone='{$username}' AND status=1";
		
		if ($data = db()->Get_Row($sql_raw)) {
			$this->retrieve_record($data['user_id']);
			$_SESSION['user_id'] = $this->id();
			return true;
		}

		return false;
	}
	
    public static function checkUserExists($phone)
    {
        $sql = "SELECT user_id FROM user WHERE mobile_phone='$phone'";
        if ($data = db()->Get_Cell($sql)) {
            return true;
        }
        return false;
    }

    public function signup($mobile_phone, $password, $signup_data)
    {
        $encrypted_pass = $this->generateHashedPassword($password);
        $this->mobile_phone($mobile_phone);
        $this->password($encrypted_pass);
        if (!empty($signup_data)) {
            foreach ($signup_data as $key => $value) {
                $this->_fields[$key] = $value;
            }
        }
        $this->status(1);
        $this->created_date(TODAY_DATE);

        if ($user_id = $this->insert()) {
            $_SESSION['user_id'] = $user_id;
        }

        return $user_id;
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
	
    public static function validate_phone($value)
    {
        if (preg_match('/^\d{10}$/', $value)) {
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
	
	/**
	 *
	 * STATIC FUNCTIONS
	 * 
	 */

	/**
	 *
     * Validate Password
     * @param mixed $value
     * @return bool
     * 
     */
	public static function validatePassword( $value )
	{
		if( ( !preg_match( '/\d+/', $value ) || !preg_match( '/\w+/', $value ) || !preg_match( '/^[a-zA-Z0-9]$/', $value ) ) && strlen( $value ) < 4)
		{
			return false;
		}

		return true;
	}

    /**
	 *
	 * If the user has a validation code, allows them to reset their password
	 * @param string $updateCode
	 * @param string $password
	 * 
	 */
	public static function updatePassword( $updateCode, $password )
	{
		// check the database for the passed-in confirm code
		$sql_raw = "SELECT user_id AS uid FROM user WHERE update_code='{$updateCode}'";
		
		if( $uid = db()->Get_Cell($sql_raw) )
		{
			$user = new User( $uid );
			
			$user->update_code( '' );
			
                        $user->password( $password );
			
			return $user->update();			
		}
		else
		{
			return false;
        }
	}
	
	public static function confirm( $confirmCode )
	{
		$errors_int = 0;
		// check the database for the passed-in confirm code
		$sql_raw = "SELECT user_id AS uid FROM user WHERE confirm_code = '{$confirmCode}'";
		
		if( $uid = db()->Get_Cell($sql_raw) )
		{	
			// passed uid is valid
			$sql_raw = "UPDATE user SET confirm_code = '', status = 1 WHERE user_id = {$uid}";
			
			if( !db()->Query( $sql_raw ) )
			{
				$errors_int++;
			}
		}
		
		
		// check for errors
		if($errors_int){
			return false;
		} else {
			return true;
		}
	}
    
	/**
	 *    
     * Sets the update_code field in the database
     * allows a user to reset their password via the code 
     * sent to them via email
     *
	 */
	public static function updateCode( $updateCode, $mobile_phone )
	{
		// check the database for the passed-in confirm code
		$sql_raw = "SELECT user_id AS uid FROM user WHERE mobile_phone = '{$mobile_phone}'";

		if( $uid = db()->Get_Cell( $sql_raw ) )
		{
            // passed uid is valid
			$sql_raw = "UPDATE user SET update_code = '{$updateCode}' WHERE user_id = {$uid}";

			if( db()->Query( $sql_raw ) )
			{
				return true;
			}
		}

		return false;
	}
        
    /**
	 *
	 * Check to see if the password reset code is in the database
	 * @param string $updateCode
	 * 
	 */
    public static function checkCode( $updateCode )
	{
		// check the database for the passed-in confirm code
		$sql_raw = "SELECT user_id AS uid FROM user WHERE update_code = '{$updateCode}'";
				
		if( $uid = db()->Get_Cell($sql_raw) )
		{
            // passed uid is valid
			return true;
		}
		
		return false;
	}
	
	/**
	 *
	 * Checks the database to see if the passed-in username already exists
	 * @param string $username
	 * @return bool
	 * 
	 */
	public static function checkUsernameExists( $username )
	{
		$sql_raw = "SELECT 1 AS success FROM user WHERE mobile_phone = '{$username}'";

		if( db()->Get_Cell( $sql_raw ) )
		{
			return true;
		}
		
		return false;
	}
        
	public static function generateConfirmCode()
	{
		return strtoupper( randomAlphaNum( 12 ) );
	}


    public function get_friend_list()
    {
        require_once(DOC_ROOT . '/personal/friends.php');
        return $friends;
    }

    public static function users_to_be_alerted($tag_id)
    {
        $user_ids = array();
        $sql = sprintf("SELECT user_id FROM user_alert WHERE tag_id=%d", $tag_id);
        $data = db()->Get_Table($sql);
        if (!empty($data)) {
            foreach ($data as $row) {
                if ($row['user_id']) {
                    $user_ids[] = $row['user_id'];
                }
            }
        }
        return $user_ids;
    }


    public static function clear_alerts_except($table, $id, $keep_tags)
    {
        $sql = sprintf("DELETE FROM alert_queue 
                        WHERE alert_item_id=%d
                          AND alert_item_table='%s'
                          AND tag_id NOT IN (%s)", $id, $table, $keep_tags);
        db()->query($sql);
    }


    public static function queue_alerts($table, $id, $tag_id)
    {
        $user_ids = self::users_to_be_alerted($tag_id);
        if (!empty($user_ids)) {
            foreach ($user_ids as $user_id) {
                $user = new User($user_id);
                $user->queue_alert($table, $id, $tag_id);
            }
        }        
    }

    public function queue_alert($table, $item_id, $tag_id)
    {
        $sql = sprintf("SELECT alert_queue_id FROM alert_queue
                        WHERE alert_item_id=%d
                          AND alert_item_table='%s'
                          AND user_id=%d
                          AND sent_date IS NULL", $item_id, $table, $this->id());
        $queue_id = db()->Get_Cell($sql);
        if (!$queue_id) {
            $sql = sprintf("INSERT INTO alert_queue (alert_item_id, alert_item_table, user_id, tag_id, queue_date) 
                                             VALUES (%d,            '%s',             %d,      %d,     '%s'      )", $item_id, $table, $this->id(), $tag_id, date(TODAY_DATETIME));
            try {
                db()->query($sql);
            }
            catch (Exception $e) {
            }
        }
    }
}


class UserRegion extends DBInterface
{
	public function __construct( $user_region_id = 0 )
	{
		parent::__construct( 'user_region', $user_region_id );
 	}
		
    public function add_city($user_id, $city_id)
    {
        $this->user_id($user_id);
        $this->region_id($city_id);
        $this->region_type(REGION_CITY);
        $this->updated_date(date(TODAY_DATETIME));
        $user_region_id = $this->insert();
        return $user_region_id;
    }

    public function get_city($user_id)
    {
        $sql = sprintf("SELECT region_id FROM user_region WHERE user_id=%d AND region_type=%d ORDER BY updated_date DESC", $user_id, REGION_CITY);
        $data = db()->Get_Row($sql);
        if (!empty($data)) {
            $city = new City($data['region_id']);
            return $city;
        }
    }

    public function get_region_for_user($user_id)
    {
        $sql = sprintf("SELECT region_id FROM user_region WHERE user_id=%d ORDER BY updated_date DESC", $user_id);
        $data = db()->Get_Row($sql);
        if (!empty($data)) {
            $city = new City($data['region_id']);
            return $city;
        }
    }
}
