<?php 

class migration 
{
    
    private $old_db_settigs;
    private $new_db_settigs;
    private $old_db;
    private $new_db;
    
    function __construct($db=false)
    {
        //Get DB settings
        $ini = parse_ini_file("migration.ini",true);
        $this->old_db_settigs=$ini['old_database'];
        $this->new_db_settigs=$ini['new_database'];
        if($db)
		{
			$this->init_old();
			$this->init_new();
		}
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
		$param=sprintf("mysql:host=%s;dbname=%s",$host,$db_name);
        $this->old_db = new PDO($param, $username, $password);
        
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
		$param=sprintf("mysql:host=%s;dbname=%s",$host,$db_name);
        $this->new_db = new PDO($param, $username, $password);
        
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
	public function getTableName($name)
	{
		return $name;
	}
	
	
	/*
	*@desc Gets List of unique Customers from the Old Database
	*@return array of customers
	*/
	public function getAllCustomers()
	{
		$sql=sprintf("select email from %s GROUP BY email",$this->getTableName('customer_entity'));
		$conn=$this->getOldDB();
		$data=$conn->query($sql);
		return $data->fetchAll(PDO::FETCH_COLUMN);
	}
	
	/*
	*@desc Gets customer attributes from the old database
	*@return Associative array of Eav attribute table
	*/
	public function getExistingCustomerAttributes()
	{
		$sql=sprintf("SELECT * FROM %s WHERE entity_type_id=1",$this->getTableName('eav_attribute'));
		$conn=$this->getOldDB();
		$data=$conn->query($sql);
		return $data->fetchAll(PDO::FETCH_ASSOC);
	}
    
	/*
	*@desc get corresponding attribute value in new database
	*@param $attribute= associative array containing the folowing fields, attribute_code,backend_type,frontend_input, $
	* attribute_type=1,2,3,4 (1= customer, 2=address,3=catalog, 4=product  values can be obtained from the eav_entity_type table)
	*@return associative array containg Matched attribute_id,attribute_code,backend_type,frontend_input or 0 
	*/
	public function getMatchedAttribute($attribute,$attribute_type)
	{
		$sql=sprintf("SELECT * FROM %s WHERE entity_type_id=%d AND attribute_code=%s",$this->getTableName('eav_attribute'),$attribute_type,$attribute['attribute_code']);
		$conn=$this->getNewDB();
		$data=$conn->query($sql);
		print_r($data);
		$row=$data->fetchAll(PDO::FETCH_ASSOC);
		return $row;
		//$this->log('Test');
	}
	/*
	*@desc Returns a list of attributes taht can be imported
	*@return 
	*/
	public function getAttributesToImport()
	{
	}
	private function log($message,$filename="status.log")
	{
		$handel=fopen($filename,'a');
		fputs($handel,$message);
		fclose($handel);
	}
	
}