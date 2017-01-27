<?php
/*
    SQL-Editor - An SQl table editor
    Copyright (C) 2016 Andrew S
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published
    by the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.
    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


class SQLResponse { 
    private static $response;
    private static $array_cache;
    private static $length;
    private static $index;
    
   /**
    * Creates a SQL response object
    * @param {Object} r - Result of query
    */
    
    function __construct($r) {
        self::$response = $r;
        $this->initialise();
    }
    
   /**
    * Initialise and fill the array_cache;
    */
    
    function initialise() {
         $array = [];
        $length = 0;
        while ($row = mysqli_fetch_array(self::$response)) {
            array_push($array,$row);
            $length ++;
        } 
        self::$array_cache = $array;
        self::$length = $length;
        self::$index = 0;
    }
    
    /**
    * Gets an array of rows
    * @returns {Array} rows
    */
    
    function toArray() {
       return self::$array_cache;
       
    }
    
    /**
    * Gets raw response data
    * @returns {Object} response
    */
    
    function getResponse() {
        return self::$response;
    }
    
    /**
    * Gets next row
    * @returns {Object} Row
    */
    
    function next() {
       $ind = self::$index ++;
        if (isset(self::$array_cache[$ind])) {
         return self::$array_cache[$ind];
        } else {
            return false;
        }
    }
    
   /**
    * Resets the pointer for nextItem();
    */
    
    function reset() {
        self::$index = 0;
    }
    
   /**
    * Gets the length of rows
    * @returns {Number} length of rows
    */
    function getLength() {
        return self::$length;
    }
    
    
}

/** Connector to sql server*/

class SQLConnector {
 private static $db;
    
   /**
    * Creates a SQL connector object
    * @param {Array} options - Options for connection
    */
    
   function __construct($a,$b,$c,$d,$e) {
        
        self::$db = mysqli_connect($a,$b,$c,$d,$e);
    }
    
   /**
    * Gets the error if there is one
    * @returns {Error} Error
    */
    
    function getError() {
        return mysqli_error(self::$db);
    }
    
   /**
    * Sends a query to the mysql server, and returns a SQL response object
    * @param {String} query - Query to send
    * @returns {SQLResponse} Response object
    */
    
    function query($query,$giveResult = false) {
      $r = mysqli_query(self::$db,$query);
        if (!$r) {
            return false;
        }
        if (!$giveResult) {
          return new SQLResponse($r);
        }
    }
    
   /**
    * Inserts an object into the sql database
    * @param {String} table - Table to insert object
    * @param {Object} object - Object to insert
    * @returns {SQLResponse} Response object
    */
    
    function insert($table,$object) {
        $keys = array_keys($object);
        $query = "INSERT INTO `".$table."` (";
        $comma = "";
        foreach ($keys as $key) {
            $query .= $comma . "`". $key ."`";
                $comma = ",";
        }
        $query .= ") VALUES (";
        $comma = "";
         foreach ($object as $item) {
             $colon = "'";
             if (is_int($item)) {
                 $colon = "";
             }
            $query .= $comma . $colon . $item . $colon; 
             $comma = ",";
        }
        $query .= ")";
        return $this->query($query);
        
    }
    
   /**
    * Gets the database connection
    * @returns {SQLDatabase}
    */
    
    function getDB() {
        return self::$db;
    }
    
   /**
    * Closes the database connection
    */
    
    function close() {
         mysqli_close(self::$db);
    }
    
}


?>
