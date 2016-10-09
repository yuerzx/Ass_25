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
    public $result;
    protected $_type;

    function __construct()
    {
        // Add a little source of safety in case of forgetting something
        $this->setParams();
        $this->connDB();
    }

    public function bind($bind, $choice, $length){
        // clean up all the data before insert
        $choice = strtolower($choice);
        //the ideas from lecture notes
        $choice = str_replace("'", "''", $choice);
        oci_bind_by_name($this->result, $bind, $choice, $length);
    }

    public function setQuery($sql){
        $this->_query = $sql;
        if($parse=oci_parse($this->_conn, $this->_query)){
            $this->result = $parse;
        }
    }

    public function getResults($type = null){
        //improved from lecture notes as the notes are repeating again and again
        //And combined two functions together
        //default is just to excute the code
        if(!@oci_execute($this->result)){
            echo "Something wrong with the Query please check and try again!";
            return false;
        }else{
            if($type == 'ARRAY'){
                while ($row = oci_fetch_assoc($this->result)) {
                    $this->_array[] = $row;
                }
                return $this->_array;
            }else{
                return true;
            }
        }

    }

    public function setParams()
    {
        $this->_username = 's25909436';
        $this->_password = 'monash00';
        $this->_db = 'llama.its.monash.edu.au/FIT2076';
    }

    public function connDB()
    {
        $this->_conn = oci_connect($this->_username,
            $this->_password, $this->_db) or die("Unable to reach database please try again later");
    }

    function __destruct()
    {
        //improved from lecture notes, in case of the errors
        if($this->result){
            oci_free_statement($this->result);
        }
        oci_close($this->_conn);
    }



    public function generateOptionList($tableName, $selectValue = 2){
        $sql = "SELECT * FROM ".$tableName;
        $id = strtoupper($tableName)."_ID";
        $name = strtoupper($tableName)."_NAME";
        $this->setQuery($sql);
        $results = $this->getResults('ARRAY');
        if(sizeof($results)){
            echo "<label for='{$tableName}'>".ucwords($tableName).'</label>';
            echo '<select class="form-control" name="'.$tableName.'">';
            foreach ($results as $res){
                if($selectValue == $res[$id]){
                    echo '<option value="'.$res[$id].'" selected>'.ucwords(trim($res[$name])).'</option>';
                }else{
                    echo '<option value="'.$res[$id].'">'.ucwords(trim($res[$name])).'</option>';
                }
            }
            echo '</select>';
        }else{
            echo "Please enter data to start";
        }

    }

    // $idType is the primary key in database for us to find out the record
    public function generateTableFromArray($res, $idType)
    {
        if(sizeof($res)):
            //in case of empty record
        ?>

        <table class="table">
            <?php //var_dump($res); ?>
            <?php
            $count = 0;
            foreach ($res as $items) {
                if (!$count) {
                    // if we are at the first line of a table
                    // output the table head
                    ?>
                    <thead>
                    <tr>
                        <?php
                        foreach (array_keys($items) as $key) {
                            echo "<td>" . $key . "</td>";
                        }
                        ?>
                        <td>
                            Actions
                        </td>
                    </tr>
                    </thead>
                    <?php
                    $count++;
                }
                ?>
                <?php
                echo "<tr>";
                foreach ($items as $value) {
                    echo "<td>" . ucwords($value) . "</td>";
                }


                ?>
                <td>
                    <a href="<?= '?action=edit&id=' . $items[$idType] ?>">Edit</a>
                    |
                    <a href="<?= '?action=del&id=' . $items[$idType] ?>">Del</a>
                </td>
                <?php
                echo "</tr>";
                ?>
                </tr>
                </tbody>

                <?php
            }

            ?>
        </table>
    <?php
        else:
            echo "Seems there are no data in the database, please add new to start";
        endif;

    }
}