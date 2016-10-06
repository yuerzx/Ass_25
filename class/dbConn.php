<?php

class dbConn
{
    //private properties
    private $_username;
    private $_password;
    private $_db;
    private $_query;
    private $_conn;
    private $_array;

    function __construct($sql)
    {
        // Add a little source of safety in case of forgetting something
        $this->_query = mysqli_real_escape_string($sql);
        $this->setParams();
        $this->connDB();
    }

    public function setParams()
    {
        $this->_username = 's99999999';
        $this->_password = 'monash00';
        $this->_db = 'FIT2076';
    }

    public function connDB()
    {
        $this->_conn = oci_connect($this->_username,
            $this->_password, $this->_db);
    }

    public function exe()
    {
        $parse = oci_parse($this->_conn, $this->_query);
        oci_execute($parse);
        while ($row = oci_fetch_assoc($parse)) {
            $this->_array[] = $row;
        }
        return $this->_array;
    }

}