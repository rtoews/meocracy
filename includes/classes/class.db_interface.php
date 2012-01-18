<?php
require_once( 'class.database.php' );

class DBInterface
{
    private $_primary_key = null;
    private $_id = 0;
    private $_is_modified = false;
    protected $_fields = array();
    private $_sql_table = null;
    public $success = false;
        
    /**
     *
     * @param string $sql_table Table name to interface with.
     * @param int $id Primary key id of named table to optionally fetch data for.
     * 
     */
    public function __construct($table = null, $id = 0)
    {
        if ($table == null) {
            throw new Exception('Must specify a table as the first paramater of the constructor');
        }
        $this->sql_table($table);
        // Get the primary key based on the supplied table.
        $this->primary_key();
        // Only load the data if we are fed an id > 0, otherwise we will be building for an insert or loading later.
        if ((int)$id > 0) {
            $this->_id = $id;
            $this->retrieve_record();
        }
    }
            
    public function id() {
        return $this->_id;
    }
        
    /**
     * 
     * Fetch the primary key
     * @return string Name of primary key of table specified on object instantiation.
     * 
     */
    public function primary_key()
    {
        if ($this->_primary_key === null) {            
            $sql = "SHOW KEYS FROM {$this->sql_table()} WHERE Key_name='PRIMARY'";

            //  Make sure primary key exists
            if ($results = db()->Get_Row($sql)) {
                $this->_primary_key = $results['Column_name'];
            }
            else {
                throw new Exception( "Unable to fetch primary key for {$this->sql_table()}" );
            }
        }

        return $this->_primary_key;
    }
    
    /**
     * 
     * Verifies that specified table exists; sets private $_sql_table.
     * @param string Name of table this object interfaces with
     * @return string Name of table this object interfaces with
     * 
     */
    public function sql_table($sql_table = null)
    {
        if ($sql_table !== null) {
            $tables = db()->Get_Table('SHOW TABLES');
            if (!empty($tables)) {
                foreach ($tables as $table) {
                    $indexed_array = array_values($table);
                    $table_name = $indexed_array[0];

                    if ($sql_table == $table_name) {
                        $this->_sql_table = $sql_table;
                    }
                }
            }

            if (null === $this->_sql_table) {
                throw new Exception( "Specified table does not exist '{$this->_sql_table}'" );
            }            
        }

        return $this->_sql_table;
    }


    /**
     * Load details by by primary key.
     * @param int $id Primary key id to load
     * @return bool true on success, false otherwise
     */    
    public function retrieve_record($id = 0) {
        // if valid $id specified, override default $_id
        if ((int)$id > 0) {
            $this->_id = $id;
        }

        if ((int)$this->_id > 0) {
            $primary_key = $this->primary_key();
            $sql = "SELECT * FROM {$this->sql_table()} WHERE {$primary_key}={$this->id()}";

            if ($data = db()->Get_Row($sql)) {
                foreach ($data as $key => $value) {
                    // If current field is not the primary key, call the setter to create the key => value pairs in $this->_fields.
                    if ($key !== $primary_key) {
                        $this->$key($value);                    
                    }
                }

                $this->success = true;

                return true;
            }
        }

        return false;
    }

    /**
     * 
     * Setter / Getter.  This method simply assigns the first variable to the passed function named. I.e. it doesn't take into account multiple variables passed to the function
     * @param string $method Name of dynamically called method
     * @param mixed $arguments List of parameters passed to dynamically called method
     * @return string Value of dynamically set method arguments or stored arguments retrieved from getter
     * 
     */
    public function __call($method, $arguments)
    {
        if (method_exists($this, $method)) {
            call_user_func_array(array($this, $method), $arguments);            
        }

        if (count($arguments) > 0) {
            /*
             * Setter
             */
            $value = $arguments[0];
            if (is_string($value)) {
                $value = db()->real_escape_string($value);
                $value = stripslashes($value);
            }
            $this->_fields[$method] = $value;
            $this->_is_modified = true;
            
            return $value;
        }
        else {
            /*
             * Getter
             */
            if (!empty($this->_fields[$method])) {
                return $this->_fields[$method];                
            }
            else {
                return null;
            }
        }
    }

    /**
     *
     * Updates the specified ID from the stored object details, or the ID stored in the object if none is given.
     * @param int $id Optional ID to update with values from this object (table & field values)
     * @return bool true if update succeeded, false otherwise
     */
    public function update($id = 0)
    {
        if (true === $this->_is_modified) {
            /*
             * Perform update
             */
            if ((int)$id <= 0 && $this->_id > 0) {
                $id = $this->_id;
            }
            
            $where = "{$this->primary_key()} = {$id}";
            $result = db()->Update_Table($this->sql_table(), $this->_fields(), $where);

            return $result;
        }
        
        return false;
    }
    
    /**
     * 
     * Deletes the specified ID, or the one stored in the object if none is given.
     * @param int $id
     * @return bool true if delete succeeded, false otherwise
     * 
     */
    public function delete($id = 0)
    {
        if (0 === $id && $this->_id > 0) {
            $id = $this->_id;
        }
        
        $sql_raw = "DELETE FROM {$this->sql_table()} WHERE {$this->primary_key()} = {$id} LIMIT 1";

        if (db()->Get_Cell($sql_raw)) {
            return true;
        }
        
        return false;
    }
    
    private function _fields()
    {        
        return $this->_fields;
    }
    
    /**
     * 
     * Inserts a new record
     * @return mixed ID of inserted record, false otherwise
     * 
     */
    public function insert()
    {
        if (count($this->_fields()) > 0) {
            if ($insert_id = db()->Insert_Row($this->sql_table(), $this->_fields())) {
                $this->_id = $insert_id;
                
                return $this->_id;
            }
            
            throw new Exception("Unable to insert new row into {$this->sql_table()}");
        }
        
        return false;
    }
    
    /**
     * 
     * The following set & get methods are simply here to match what the ticket specs require and interface
     * with the logic in the __call() method
     * 
     */    
     
     public function xget($field)
     {
         return $this->$field();
     }
     
     public function xset($field, $value)
     {
         return $this->$field($value);
     }
     

    private function _fetchAll($key, $id)
    {
        $query = "SELECT {$this->primary_key()} FROM {$this->sql_table()}";
        
        if (null !== $key && $id > 0) {
            $query .= " WHERE {$key} = {$id}";
        }

        $objects = array();

        if ($results = db()->Get_Table($query)) {
            foreach ($results as $result) {
                $objects[] = new DBInterface($this->sql_table(), $result[$this->primary_key()]);
            }    
        }
        
        return $objects;
    }
    
    /**
     * This was added to simplify the front-end code for building an array of objects when all of a particular table type for an id are requested.
     * i.e. to retrieve all videos
     */
    public static function fetchAll($table, $key = null, $id = 0)
    {
        $db_interface = new DBInterface($table);
        
        return $db_interface->_fetchAll($key, $id);
    }
    
}
