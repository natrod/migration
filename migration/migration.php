<?php 

class migration 
{
    
    private $old_db_settigs;
    private $new_db_settigs;
    private $old_db;
    private $new_db;
    
    function __construct()
    {
        //Get DB settings
        $ini = parse_ini_file("migration.ini",true);
        $this->old_db_settigs=$ini['old_database'];
        $this->new_db_settigs=$ini['new_database'];
        $this->init_old();
        $this->init_new();
    }
    
    /*
     * @desc:Returns Connection Parameters for the old database 
     * @return: associative array of old database settings parsed from 
     * the ini file
     */
    public function getOldDBsettings()
    {
        return $this->old_db_settigs;
    }
    
    /*
     * @desc:Returns Connection Parameters for the new database 
     * @return: associative array of new database settings parsed from 
     * the ini file
     */    
    public function getNewDBsettings()
    {
        return $this->new_db_settigs;
    }    
    /**
     * @desc Initializes a connection to the old database 
     * 
     */
    public function init_old()
    {
        $settings=$this->getOldDBsettings();
        $host=$settings['host'];
        $db_name=$settings['database_name'];
        $username=$settings['user'];
        $password=$settings['password'];
        print_r($settings);
        $this->old_db = new PDO('mysql:host=$host;dbname=myDatabase', $username, $password);
        
    }
    /**
     * @desc Initializes a connection to the new or target database 
     * 
     */
        public function init_new()
    {
        $settings=$this->getNewDBsettings();
        $host=$settings['host'];
        $db_name=$settings['database_name'];
        $username=$settings['user'];
        $password=$settings['password'];
        print_r($settings);
        $this->new_db = new PDO('mysql:host=$host;dbname=myDatabase', $username, $password);
        
    }
    /*
     * @return PDO object to old DB
     */
    public function getOldDB()
    {
        return $this->old_db;
    }
    /*
     * @return PDO object to new DB
     */
    public function getNewDB()
    {
        return $this->new_db;
    }
	
	/*
	*
	*@return string
	*/
	getTableName($name)
	{
		return $name
	}
	
	
	/*
	*@desc Gets List of unique Customers from the Old Database
	*@return array of customers
	*/
	public function getAllCustomers()
	{
		$sql=sprintf("select email from %s GROUP BY email",$this->getTableName('customer_entity'));
		$conn=$this->getOldDB;
		$data=$conn->query($sql);
	}
    
    
}