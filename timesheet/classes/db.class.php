<?php
/*
----------------------------------------------------------------
* 
* dbConnect() - To connect with admin database 
* 
----------------------------------------------------------------
*/
class db {
	//-------Database Credentials--------------------
	protected $db_hostname = "10.45.240.84";
	protected $db_username = "root";
	protected $db_password = ""; //amfor90days_+z
	protected $db_name     = "ts_yjr";
        
	var $link;
	var $database;
	var $db1;
	var $db2;
	var $conn1;
	var $conn2;
        
	public function __construct()
        {
            
	}
	
	//--------Open Admin database connection
	public function dbConnect()
	{
            $this->conn1=@mysqli_connect($this->db_hostname, $this->db_username, $this->db_password, $this->db_name);
            if (!$this->conn1)					
               die(mysqli_connect_error());
                                  
	     $this->db1=  mysqli_select_db($this->conn1, $this->db_name) or die(mysqli_error($this->conn1));
             mysqli_autocommit($this->conn1, TRUE);
             return $this->conn1;
             
                   
	}
        public function runsql($dbcon,$query)
        {
            $result= mysqli_query($query,$dbcon);
            if($result)
                return $result;
            else
                echo mysql_error();
        }
        
        public function fetch_data($result)
        {
            $data = mysqli_fetch_array($result);
            return $data;
        }

                //------Destructor --Closes the mysql connection 
	public function __destruct()
	{
		//mysql_close();
	}
		
}
?>
