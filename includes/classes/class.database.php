<?php
    /**
    * @package Database
    */

    require_once( $_SERVER["DOCUMENT_ROOT"] . "/includes/config.php" );

	/**
    * MySQLi Database Wrapper
    * @package Database
    */
    class Database extends DatabaseAbstract
    {
        private static $_link_instance;
        public static $_link_parent;

        /**
        * Retrieves an instantiated database object.
        * @return Database
        */
        public static function get_Database()
        {
            if(!isset(self::$_link_instance) || self::$_link_parent != 'Database')
            {
                self::$_link_instance = new Database();
            }
			return self::$_link_instance;
        }

        /**
        * Open a new connection to the MySQL server
        * @param mixed $host
        * @param mixed $database
        * @param mixed $username
        * @param mixed $password
        * @param mixed $port
        * @return bool True if connected, False if failed
        */
        protected function __construct( $host = DB_HOST, $database = DB_DATABASE, $username = DB_USERNAME, $password = DB_PASSWORD, $port = 3306 )
        {
        	$this->_error = new Database_Error();
            $this->_meta = new Database_Meta();

            $this->_link = parent::__construct( $host, $username, $password, $database, $port );
            if( mysqli_connect_error() )
            {
                $this->_error->set_number( mysqli_connect_errno() );
                $this->_error->set_message( mysqli_connect_error() );
                return false;
            }
            else
            {
            	self::$_link_instance = $this;
            	self::$_link_parent = 'Database';
            	return true;
            }
        }

        /**
        * @deprecated Open a new connection to the MySQL server
        * @param mixed $host
        * @param mixed $database
        * @param mixed $username
        * @param mixed $password
        * @param mixed $port
        * @return bool True if connected, False if failed
        */
        public function connect( $host = DB_HOST, $database = DB_NAME, $username = DB_USERNAME, $password = DB_PASSWORD, $port = 3306 )
        {
            return $this->__construct( $host, $username, $password, $database, $port );
        }
    }


    /**
    * MySQLi Database Wrapper
    * @package Database
    */
    abstract class DatabaseAbstract extends mysqli
    {
        public $_error;
        public $_link;
        public $_result;
        public $_meta;
        private $_queries = array();


        /**
        * Destruct all objects, this is to prevent a PHP bug before version 5.3
        * @uses disconnect()
        * @see http://bugs.php.net/bug.php?id=33595
        * @see http://bugs.php.net/bug.php?id=33487
        */
        public function __destruct()
        {
            if( $this->_link != null )
                $this->disconnect();
            unset( $this->_error );
            unset( $this->_link );
            unset( $this->_result );
            unset( $this->_meta );
			unset( $this->_queries );
        }

        public function disconnect()
        {
            $this->close();
            $this->_link = null;
        }

        /**
        * Querries the Database
        * @param string Query
        * @return bool True on Success, False on Fail
        */
        public function query( $query )
        {
            if ( true === DEBUG ) $start = $this->_getTime();

            /** Implemented because Stored Procedures return multiple results **/
            parent::multi_query( $query );
            $this->_result = $this->use_result();
            if ( true === DEBUG ) $this->_logQuery($query, $start);


            /** Set the Error and Meta variables **/
            $this->_error = new Database_Error( $this->errno, $this->error );
            $this->_meta = new Database_Meta( $this->insert_id, $this->affected_rows );

            /** Check if there was a result **/
            if( $this->_result !== false )
            {
                return "monkeys";//true;
            }
            else
            {
                if( $this->_result )
                    $this->_result->free();
                return false;
//                return $this->error; //was "return false;", modified to return the error if there is an error
            }
        }


        /**
        * Get Result Count
        * @param string Query
        * @return mixed Count or False
        */
        public function Get_Count( $query )
        {
            try
            {
                if( $this->query( $query ) )
                {
                    $this->_result->free();
                    /** Clear the Results Buffer caused by Stored Procedures **/
                    $this->Clear_Results_Buffer();
                    return $this->_meta->affected_rows();
                }
                else throw new exception();
            }
            catch( Exception $e )
            {
                if( $this->_result )
                    $this->_result->free();
                /** Clear the Results Buffer caused by Stored Procedures **/
                $this->Clear_Results_Buffer();
                return false;
            }
        }

        /**
        * Get Table Cell
        * @param string Query
        * @param string Name of the Cell to Retrieve
        * @return mixed Result Array or False
        */
        public function Get_Cell( $query, $cell = null, $resulttype = MYSQLI_ASSOC )
        {
            try
            {
                if( $this->query( $query ) )
                {
                    $return = $this->_result->fetch_array( $resulttype );
                    $this->_result->free();
                    /** Clear the Results Buffer caused by Stored Procedures **/
                    $this->Clear_Results_Buffer();

                    if( $return != false )
                    {
                        if( !is_null($cell) )
                        {
                            if( array_key_exists( $cell, $return ) )
                            {
                                return $return[$cell];
                            }
                            else throw new Exception("key $cell does not exist in results from $query", 6);
                        }
                        else
                        {
                            /** Fixes an issue with unidentified index when using $return[0]; **/
                            $keys = array_keys( $return );
                            return $return[$keys[0]];
                        }
                    }
                    else throw new Exception($query, 2);
                }
                else throw new Exception($query, 1);
            }
            catch( Exception $e )
            {
                $this->_output_diagnostic(__FUNCTION__, $e, $this->_error);
                /** Clear the Results Buffer caused by Stored Procedures **/
                $this->Clear_Results_Buffer();
                return false;
            }
        }


        /**
        * Get Table Row
        * @param string Query
        * @return mixed Result Array or False
        */
        public function Get_Row( $query, $resulttype = MYSQLI_ASSOC )
        {
            try
            {
                if( $this->query( $query ) )
                {
                    $return = $this->_result->fetch_array( $resulttype );
                    $this->_result->free();
                    /** Clear the Results Buffer caused by Stored Procedures **/
                    $this->Clear_Results_Buffer();
                    return $return;
                }
                else throw new Exception($query, 1);
            }
            catch( Exception $e )
            {
                $this->_output_diagnostic(__FUNCTION__, $e, $this->_error);
                if( $this->_result )
                    $this->_result->free();
                /** Clear the Results Buffer caused by Stored Procedures **/
                $this->Clear_Results_Buffer();
                return false;
            }
        }

        /**
        * Get Table Row, with the last result in the result set
        * @param string Query
        * @return mixed Result Array or False
        */
        public function Get_Row_Last_Result( $query )
        {
            try
            {
                if( $this->query( $query ) )
                {
                    $this->_result->free();
                	$return = null;
                    while ($this->next_result())
                    {
                    	//free each result.
                        $result = $this->use_result();
                        if ($result instanceof mysqli_result)
                        {
                        	$return = $result->fetch_array();
                            $result->free();
                        }
                    }
                    /** Clear the Results Buffer caused by Stored Procedures **/
                    $this->Clear_Results_Buffer();
                    return $return;
                }
                else throw new Exception();
            }
            catch( Exception $e )
            {
                if( $this->_result )
                    $this->_result->free();
                /** Clear the Results Buffer caused by Stored Procedures **/
                $this->Clear_Results_Buffer();
                return false;
            }
        }


        /**
        * Get Table Data
        * @param string Query
        * @return mixed Result Array or False
        */
        public function Get_Table( $query, $resulttype = MYSQLI_ASSOC )
        {
            try
            {
                if( $this->query( $query ) )
                {
                    $return = array();
                    while( $row = $this->_result->fetch_array( $resulttype ) )
                    {
                        $return[] = $row;
                    }
                    $this->_result->free();
                    /** Clear the Results Buffer caused by Stored Procedures **/
                    $this->Clear_Results_Buffer();

					/**
					 * Bug Fix: Would always return true, even if no values were found.
					 * Will now check the size of the array, if it is 0, an exception will be thrown
					 */
                    if( sizeof( $return ) <= 0 )
                        throw new Exception($query);
                    else
                        return $return;
                }
                else throw new Exception($query, 1);
            }
            catch( Exception $e )
            {
                $this->_output_diagnostic(__FUNCTION__, $e, $this->_error);
                //if( $this->_result )
                //$this->_result->free();
                /** Clear the Results Buffer caused by Stored Procedures **/
                $this->Clear_Results_Buffer();
                return false;
            }
        }
        /**
    * Update a table row with an array of data
    *
    * @param string $table Table to insert data into
    * @param string $table Data as column=>value
    * @param string $where Conditional statement for data to update
    * @return array(lat,lon)
    */
    function Update_Table($table, $data, $where = "") 
	{

        $values = array();
        foreach ($data as $key=>$val) {
            // only add slashes if $val is string.  addslashes will issue warning if $val is, for instance, an array.
            if (is_string($val))
            {
                $val = addslashes($val);
            }
            $values[] = sprintf("%s = '%s'", $key, $val);
        }

        $query = sprintf(
        "UPDATE %s SET %s WHERE %s",
        $table, join(',', $values), $where
        );
        $this->query($query);
        $result = $this->error === '';

        return $result;
    }
        
        

	    /**
	    // Function: Insert_Row
	    //
	    // Description: Insert a new row into a table.
	    //
	    // Input: $table -> The table to insert the row into. $data_array -> The array containing the
	    // information.
	    //
	    // Output: None*/
	    function Insert_Row( $table, $data_array, $valid_fields = '' )
	    {
	
	        // keep only valid fields
	        if ( is_array( $valid_fields ) )
	            $data_array = $this->Strip_Extra_Fields( $data_array, $valid_fields );
	
	        // build query
	        $query = "insert into ".$table." ( ";
	
	        foreach ( $data_array as $key => $value )
	            $query .= $key.", ";
	
	        $query = substr( $query, 0, strlen( $query ) - 2 );
	
	        $query .= ") values ( ";
	
	        foreach ( $data_array as $key => $value ) {
	            if ( $value != 'NOW()' )
	                $query .= "'".addslashes($value)."', ";
	            else
	                $query .= $value.", ";
	        }
	
	        $query = substr( $query, 0, strlen( $query ) - 2 );
	
	        $query .= ")";
log_time($query);
	        $this->query( $query );
	
	        return $this->_meta->_last_insert_id;
	
	    }
   
    // ---------------------------------------------------------------------------------------------------
    //
    // Function: Strip_Extra_Fields
    //
    // Description: Strip any unnecessary fields from the data array. This is used to make sure that
    //				extra fields don't 'cause a problem while entering into the database.
    //
    // Input: $data_array
    //
    // Output: An array containing only acceptable fields.
    //
    // ---------------------------------------------------------------------------------------------------
    function Strip_Extra_Fields( $data_array, $valid_fields )
    {

        reset( $data_array );
        foreach ( $data_array as $key => $value )
        {
            if ( in_array( $key, $valid_fields ) )
                $rv_array[$key] = $value;
        }

        return $rv_array;

    }
    // ---------------------------------------------------------------------------------------------------


        /**
        * Builds a Stored Procedure that can be used with a Query
        *
        * @param string $name
        * @param array|string $in_values
        * @param array $out_values
        * @return string $sp
        */
        public function Build_Stored_Procedure( $name, $in_values = null, $out_values  = null, $allow_null = false )
        {
            $sp = "CALL {$name}(";
            if( !is_null($in_values) )
            {
                if( is_array( $in_values ) )
                {
	            	$size = sizeof( $in_values );
	                $i = 1;
	                foreach( $in_values as $ia )
	                {
	                    if( is_empty( $ia ) && false === $allow_null )
						{
	                        $sp .= 'NULL';
						}
	                    elseif( is_numeric( $ia ) )
						{
	                        $sp .= $this->real_escape_string_special( $ia );
						}
	                    else
						{
	                        $sp .= "'" . $this->real_escape_string_special( $ia ) . "'";
						}
	                    if( $i < $size )
	                    {
	                        $sp .= ', ';
	                    }
	                    $i++;
	                }
                }
                else
                {
                    if( is_null( $in_values ) && false === $allow_null )
                        $sp .= "null";
                    elseif( is_numeric( $in_values ) )
                        $sp .= $this->real_escape_string_special( $in_values );
                    else
                        $sp .= "'" . $this->real_escape_string_special( $in_values ) . "'";
                }
            }
            if( !is_null($out_values) )
            {
                $size = sizeof( $out_values );
                $i = 1;
                if( sizeof( $in_values ) > 0 )
                    $sp .= ', ';
                foreach( $out_values as $oa )
                {
                    $sp .= "@" . $oa . "";
                    if( $i < $size )
                    {
                        $sp .= ', ';
                    }
                    $i++;
                }
            }
            $sp .= ");";
            return $sp;
        }

        public function Clear_Results_Buffer()
        {
            while ($this->next_result())
            {
            //free each result.
                $result = $this->use_result();
                if ($result instanceof mysqli_result)
                {
                    $result->free();
                }
            }
        }

        public function real_escape_string_special( $value )
        {
            $retval = $this->real_escape_string( $value );
            $retval = addcslashes($retval, '%');
            return $retval;
        }

		public function getQueries()
		{
			return $this->_queries;
		}

		private function _logQuery($sql, $start)
		{
			$query = array
			(
				'sql' => $sql,
				'time' => ($this->_getTime() - $start) * 1000
			);

			array_push($this->_queries, $query);
		}
	
		private function _getTime()
		{
			$time = microtime();

			$time = explode(' ', $time);

			$time = $time[1] + $time[0];

			$start = $time;

			return $start;
		}	


        /**
         * private function _output_diagnostic.  Output diagnostic messages resulting from caught exceptions within the Database class.
         * @param string $fn - name of function in which exception was caught.
         * @param object $ex - exception object, containing, among other potentially useful tidbits, custom error message and custom error code.
         * @param object $db_error - Database_Error object, as set within Database class.
         *
         * TO SUPPRESS OUTPUT:
         *    The _output_diagnostic function will not execute unless the DEBUG flag is set to TRUE.
         *    Even if the DEBUG flag is set to true, output can be hidden by simply applying CSS display:none to class debug.
         * 
         * EXCEPTION MESSAGE
         *    When an exception is thrown, a string containing helpful information can be sent.
         *
         * EXCEPTION CODE
         *    When an exception is thrown, the second parameter can be a code to provide further information about the exception.
         *    The codes used here are based on a binary switching scheme:
         *       1's bit:  failure error; e.g., a stored procedure cannot be called, either because it does not exist or it contains an error.
         *       2's bit:  empty data set.
         *       4's bit:  information / warning message.
         *    Note that each bit has its own associated CSS class, so that the diagnostic message display can be controlled from a style sheet.
         *    If desired, for instance, CSS can cause only "fail" errors to be displayed.
         */
        private function _output_diagnostic($fn, $ex, $db_error)
        {
            if (1||!DEBUG) return;
            $ex_message = $ex->getMessage();
            $db_message = $db_error->message();
            $ex_code = $ex->getCode();
            $css_classes = 'debug';
            // $ex_code == 0 is the default for Exceptions.  For present purposes, this value will hide the diagnostic block.
            $hide = ($ex_code == 0) ? ' style="display:none"' : '';
            if ($ex_code & 1)
            {
                $css_classes .= ' fail';
            }
            if ($ex_code & 2)
            {
                $css_classes .= ' empty';
                $hide = ' style="display:none"';
            }
            if ($ex_code & 4)
            {
                $css_classes .= ' warning';
            }
            // Output error within a DIV block with a class of 'debug'
            print '<div class="' . $css_classes . '"' . $hide . '>';
            printf('<span class="fn_name">%s</span>: <span class="exception_msg">%s</span><br/>', $fn, $ex_message);
            if ($db_message)
            {
                printf('<span class="db_error_label">DB Error</span>: <span class="db_error">%s</span><br/>', $db_message);
            }
            print '</div>';
        }

    }
    

    /**
    * MySQL Database Error
    * @package Database
    */
    final class Database_Error
    {
        private $_number;
        private $_message;

        /**
        * Error Constructor
        * @param mixed $number
        * @param mixed $message
        */
        public function __construct( $number = 0, $message = '' )
        {
            $this->set_number( $number );
            $this->set_message( $message );
        }

        /**
        * Sets a MySQL Error Message
        * @param string $message
        */
        public function set_message( $message )
        {
            $this->_message = $message;
        }

        /**
        * Sets a MySQL Error Number
        * @param integer $number
        */
        public function set_number( $number )
        {
            $this->_number = $number;
        }

        /**
        * Gets a MySQL Error Number
        * @return integer $number
        */
        public function number()
        {
            return $this->_number;
        }

        /**
        * Gets a MySQL Error Message
        * @return string $message
        */
        public function message()
        {
            return $this->_message;
        }
    }

    /**
    * MySQL Query Meta Data
    * @package Database
    */
    final class Database_Meta
    {
        public $_last_insert_id;
        public $_affected_rows;

        /**
        * Meta Constructor
        * @param ineteger $insert_id
        * @param ineteger $affected_rows
        */
        public function __construct( $insert_id = 0, $affected_rows = 0 )
        {
            $this->set_insert_id( $insert_id );
            $this->set_affected_rows( $affected_rows );
        }

        /**
        * Sets a MySQL Insert Id
        * @param ineteger $insert_id
        */
        public function set_insert_id( $insert_id )
        {
            $this->_last_insert_id = $insert_id;
        }

        /**
        * Sets a MySQL Affected Rows
        * @param ineteger $affected_rows
        */
        public function set_affected_rows( $affected_rows )
        {
            $this->_affected_rows = $affected_rows;
        }

        /**
        * Gets a MySQL Insert Id
        * @return integer $insert_id
        */
        public function insert_id()
        {
            return $this->_last_insert_id;
        }

        /**
        * Gets a MySQL Error Affected Rows
        * @return ineteger $affected_rows
        */
        public function affected_rows()
        {
            return $this->_affected_rows;
        }
    }

	/**
	* A shortcut function to Database::get_Database()
	* @return Database
	*/
    function db()
    {
        return Database::get_Database();
    }
?>
