<?php


    ini_set("display_errors" , 1);
    ini_set("error_reporting" , 2);
    error_reporting(E_PARSE | E_ERROR | E_DEPRECATED | E_WARNING);
    
    /* init the core run the Framework */
    define('ZwootApp' , true); // Security Fix
    
    /* Zwoot - What am I ? Plugin or Theme */
    define('ZwootWai' , rtrim ( basename( dirname(dirname(dirname(__FILE__) )) ) , 's' ) ) ;
    
    /* Zwoot - Where am i ? */
    define('ZwootWeai' , dirname(dirname(__FILE__)) );
    
    /* Zwoot url */
    if (ZwootWai == "theme")
    {
	define('ZwootUrl' , get_template_directory_uri() . DIRECTORY_SEPARATOR . basename(dirname(dirname(__FILE__))) );
    }
    else
    {
	define('ZwootUrl' , plugins_url() . DIRECTORY_SEPARATOR . basename(dirname(dirname(__FILE__))) );
    }
   
    /* Public Folder Location */
    define("ZwootPublic" , ZwootUrl . DIRECTORY_SEPARATOR . "public" );
    
    // Critical vs Plain Exceptions
    class CriticalException extends Exception  {
	public function wp_die()
	{
	    wp_die( $this->getMessage() );
	}
    }

 
    
    /* Main Framework Classes Autoloader */
    
    function Zwoot_Load( $class )
    {
	
	// Include only classes that are part of Zwoot
	if (strpos($class , "Zwoot_") === false) return;
	    
	$wordpress = array('WP_User_Search'); //workaround for autoload
	
	if (in_array($class,$wordpress)) return;
	
	
	$file_name = explode("_",  str_replace("Zwoot_" , "" , $class));
	$folder = strtolower($file_name[0]);
	
	if (count($file_name) == 1)
	{
	    $file_name[0] = $folder;
	    $file_name[1] = "Zwoot_" . ucfirst($folder);
	}
	else
	{
	    $file_name[0] = $folder;
	    $file_name[1] = "Zwoot_" . ucfirst($file_name[1]);
	}
	
	
	$file = ZwootWeai . DIRECTORY_SEPARATOR . "framework". DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR , $file_name) . ".php";
	
	
	if (!file_exists($file))
	{
	    throw new CriticalException("No class '".$file ."' found");
	}
	else
	{
	    include_once( $file );
	   
	    if (!class_exists( $class )) throw new CriticalException("File '$file' exists but the class $class was not found!");
	    
	}
	
    }
    // Autoload > PHP 5.1.2
    spl_autoload_register('Zwoot_Load');
    
    // Filter var > 5.2.0
    
    class Zwoot
    {
	static $instance;
	
	
	public function __construct()
	{
	    try {
		/* Make Zwoot Instance public */
		$this->instance = $this;
		
		/* Load Config, will load template related files */
		$this->config = new Zwoot_Config();
	    }
	    catch (CriticalException $e)
	    {
		$e->wp_die();
	    }
	    
	    
	}
	
	
	public static function get_instance()
	{
	    
	    if (!isset(self::$instance))
	    {
		self::$instance = new Zwoot();
	    }
    
	    return self::$instance;
	}
	
	public function filter()
	{
	    
	}
	
	
    }
    
    
    $zwoot  = new Zwoot();