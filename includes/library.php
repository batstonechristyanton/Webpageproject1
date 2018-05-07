<?php

//Get the acutal document and webroot path for virtual directories
$direx = explode("/", getcwd());
DEFINE ('DOCROOT', '/'.$direx[1].'/'.$direx[2].'/'); // home/username/
DEFINE ('WEBROOT', '/'.$direx[1].'/'.$direx[2].'/'.$direx[3].'/'); // home/username/public_html/



class Db{

  protected $dbconn;  // database connection
  protected $debug=true; //debug flag
  

 /*############################################################
    Function: Constructor
    Loads the database credentials from the config file
    (stored in the document root (outside the webserver)
    
    Creates a database connection using credentials retrived  
    
    Calls error function if there's problems
    ##############################################################*/
  function __construct(){  //debug default to true
  
      // Load configuration as an array. Use the actual location of your configuration file
       //Note: on loki, you file should be located in the pwd folder (which should be in your user directory)
    $config = parse_ini_file(DOCROOT.'pwd/config.ini'); 
    
        
    // Try and connect to the database
    $this->dbconn = @new mysqli($config['domain'],$config['username'],$config['password'],$config['dbname']);
  
     //check for connection error
    if($this->dbconn->connect_error)
        $this->error($this->dbconn->connect_error);
  }
  
  /*############################################################
    Function: Error
    Handles errors generated while working with the database
    
    If Debug is true, generated a meaningful PHP error, plus any extra 
    information passed in (query, stmt error, etc)
    
    If Debug is false, redirects user to generic error page
    Should add writing to log file for real production enviroment
    
    
    ##############################################################*/
  
  public function error($error=""){
     if($this->debug){ //trigger useful error if debugging
        
        trigger_error("Database Action Failed: ".$error, E_USER_ERROR);
      
      }else{ //generic error page if not
        
        //should write to log file here....
        header("Location:error.php"); //redirect to generic error page
        die(); //stop script
      }

  }
  
  
  /*############################################################
    Function: Quote
    String delimits, and SQL escaped passed in parameter for queries
    that aren't using prepared statements    
    ##############################################################*/

  
  public function quote($value)
  {
    return "'".$this->dbconn->real_escape_string($value)."'";
  }  
  
  
  /*############################################################
    Function: Query
    Used for running Update, Delete and Insert queries which don't
    have any user input
    
    Accepts query to be run as a string    
    ##############################################################*/
  
  public function query($query){
    
    if(!$this->dbconn->query($query)){  //run query
    
      $this->error($this->dbconn->error." Query: ".$query); //deal with error
    
    }
  }
  
  
  /*############################################################
    Function: Query_param
    Used for running Update, Delete and Insert queries with user input
    Used prepared statements for security and escaping.
        
    Accepts: 
    $query - query to be run as a string
    $type - string of single character type representations for each
    parameter (s-string, i-integer, d-double, b-blob (although data
    is actually text, just use s)
    $params - array of parameters in the order they appear in query
      note: variables MUST be added to the array by reference   
    ##############################################################*/

  public function query_param($query, $type, $params){
    
    if(!$stmt = $this->dbconn->prepare($query)) //prepare query statement
        $this->error("Statement".$this->dbconn->connect_error." Query: ".$query); //deal with error
      
      
      //bind array of parameters to prepared query statement
      if(!call_user_func_array(array($stmt, "bind_param"), array_merge(array($type), $params)))
         $this->error('Bind Error: '.$stmt->error); //deal with error
             
      
      if(!$stmt->execute()) //run query
          $this->error('Execute Error:'.$stmt->error); //deal with error
      
      if(!$stmt->close()) //close prepared statement
          $this->error('Close Error'); ///deal with error
    

  }

 /*############################################################
    Function: Select
    Used for running select statements without user input
    Use select_param with user input instead for security
        
    Accepts: 
      $query - query to be run as a string
    Returns:
       database resultset (by reference due to potential size)   
    ##############################################################*/
  public function &select($query){
    if(!$result=$this->dbconn->query($query)){  //run query
      $this->error($this->dbconn->error." Query: ".$query); //deal with error
    }

    return $result;  //return database result set
  }
  
  
  /*############################################################
    Function: Select_param
    Used for running select statements which don't have user values
    but do need to return results
    
    Accepts query to be run as a string 
    $type - string of single character type representations for each
    parameter (s-string, i-integer, d-double, b-blob (although data
    is actually text, just use s)
    $params - array of parameters in the order they appear in query
      note: variables MUST be added to the array by reference   
    

    Returns database resultset (by reference)   
    ##############################################################*/

  public function &select_param($query, $type, $params){
    
    if(!$stmt = $this->dbconn->prepare($query)) //prepare query statement
        $this->error('Statement: '.$this->dbconn->error." Query: ".$query); //deal with error
      
      
      //bind array of parameters to prepared query statement
      if(!call_user_func_array(array($stmt, "bind_param"), array_merge(array($type), $params)))
         $this->error('Bind Error: '.$stmt->error); //deal with error
             
      
      if(!$stmt->execute()) //run query
          $this->error('Execute Error:'.$stmt->error); //deal with error
          
      if(!$result=$stmt->get_result()){  //get result set to return
        $this->error('Get Result Error:'.$stmt->error); //deal with error
      }
      
      if(!$stmt->close()) //close statement
          $this->error('Close Error'); //deal with error
    
      return $result; //return result set
  }
  
  
  
   /*############################################################
    Function: Insert
    Same functionality as Query, but returns the insert id of
    record inserted
    
    Accepts query to be run as a string    
    ##############################################################*/
  
  public function insert($query){
    
    if(!$this->dbconn->query($query)){  //run query
    
      $this->error($this->dbconn->error." Query: ".$query); //deal with error
    
    }
    return $this->dbconn->insert_id;
  }
  
  
  /*############################################################
    Function: Insert_param
    Same functionality as Query_param, but returns the insert id of
    record inserted   
         
    Accepts: 
    $query - query to be run as a string
    $type - string of single character type representations for each
    parameter (s-string, i-integer, d-double, b-blob (although data
    is actually text, just use s)
    $params - array of parameters in the order they appear in query
      note: variables MUST be added to the array by reference   
    ##############################################################*/

  public function insert_param($query, $type, $params){
    
    if(!$stmt = $this->dbconn->prepare($query)) //prepare query statement
        $this->error("Statement".$this->dbconn->connect_error." Query: ".$query); //deal with error
      
      
      //bind array of parameters to prepared query statement
      if(!call_user_func_array(array($stmt, "bind_param"), array_merge(array($type), $params)))
         $this->error('Bind: '.$stmt->error); //deal with error
             
      
      if(!$stmt->execute()) //run query
          $this->error('Execute:'.$stmt->error); //deal with error
      
      if(!$stmt->close()) //close prepared statement
          $this->error('close'); ///deal with error
    
      return $this->dbconn->insert_id;
  }
  

} //end of database class


/*############################################################
    Functions for working with Files
##############################################################*/

function createFilename($file, $path, $prefix,$uniqueID){	
	$filename = $_FILES[$file]['name'];
	$exts = explode(".", $filename);
	$ext = $exts[count($exts)-1];
	$filename = $prefix.$uniqueID.".".$ext;
	$newname=$path.$filename;
	return $newname;
}


function checkAndMoveFile($file, $limit, $newname){	
	//modified from http://www.php.net/manual/en/features.file-upload.php
	try{
	    // Undefined | Multiple Files | $_FILES Corruption Attack
	    // If this request falls under any of them, treat it invalid.
	    if(!isset($_FILES[$file]['error']) || is_array($_FILES[$file]['error'])) {
	        throw new RuntimeException('Invalid parameters.');
	    }

	    // Check Error value.
	    switch ($_FILES[$file]['error']) {
	        case UPLOAD_ERR_OK:
	            break;
	        case UPLOAD_ERR_NO_FILE:
	            throw new RuntimeException('No file sent.');
	        case UPLOAD_ERR_INI_SIZE:
	        case UPLOAD_ERR_FORM_SIZE:
	            throw new RuntimeException('Exceeded filesize limit.');
	        default:
	            throw new RuntimeException('Unknown errors.');
	    }

	    // You should also check filesize here. 
	    if ($_FILES[$file]['size'] > $limit) {
	        throw new RuntimeException('Exceeded filesize limit.');
	    }

	    // Check the File type
	    if (exif_imagetype( $_FILES[$file]['tmp_name']) != IMAGETYPE_GIF 
	    	and exif_imagetype( $_FILES[$file]['tmp_name']) != IMAGETYPE_JPEG
	    	and exif_imagetype( $_FILES[$file]['tmp_name']) != IMAGETYPE_PNG){
	        
	        	throw new RuntimeException('Invalid file format.');
	    }
	
	    // $newname should be unique and tested
	    if (!move_uploaded_file($_FILES[$file]['tmp_name'], $newname)){
	        throw new RuntimeException('Failed to move uploaded file.');
	    }
	
	    echo 'File is uploaded successfully.';

	} catch (RuntimeException $e) {
	
	    echo $e->getMessage();
	
	}
	
}

function formatDateDB($x){
return $x=="" ? $x : date('Y-m-d',strtotime($x));
}
?>