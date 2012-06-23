<?php
    /* init the core run the Framework */
    define('ZwootApp' , true); // Security Fix
    
    /* Zwoot - What am I ? Plugin or Theme */
    define('ZwootWai' , rtrim ( basename( dirname(dirname(dirname(__FILE__) )) ) , 's' ) ) ;
    
    /* Zwoot - Where am i ? */
    define('ZwootWeai' , dirname(dirname(__FILE__)) );
    
    /* Zwoot url */
    $path = explode('wp-content', __FILE__);
    if (ZwootWai == "theme")
    {
	define('ZwootUrl' , get_template_directory_uri() . "/zwoot/" );
    }
    else
    {
	define('ZwootUrl' , plugins_url() . "/zwoot/" );
    }
    
    /*
      Some system variables which you shouldn't touch yet, if they prove to be ok, will be moved to the ini file, thanks.
    */
    $__ZwootSystem = array(
	'gzip' => true,
	'cache' => true,
    );
    
    
    /* Load configuration file */
    require('system/ZwootConfig.php');
    
    /* Load safe replacements in case we don't have a WP instance */
    require('system/ZwootAnalogs.php');
    
    /* Main Framework Class */
    class Zwoot
    {
	/* We hold all the precious config here */
	protected static $config;
	
	protected static $__instance;

	/* Init every piece of the framework based on the configuration */
	public function __construct()
	{
	   
	    ZwootConfig::Run();
	}
	
	public static function Run()
	{
	    
	}
	
	
	public static function getConfig($key = null)
	{
	    if (empty($key))
	    {
		return self::$config;
	    }
	    else
	    {
		return self::$config[$key];
	    }
	}
	
	/* @parse_disabled  = In ini config strings  will parse disabled (for future)
	   @parse_vars = In ini config strings will be used vars, this will replace them with vars.
	*/
	public static function __filter($str = null, $filter = null , $parse_vars = false,  $parse_disabled = false )
	{
	    $symbols = array('/','!','@','#','$','%','^','&','*',')', '(', '"', "'", '\\' ,"/" , ':',';', '.',',','+','-','`','~' );
	    
	    $__filters = array(
		'array_key' => strtolower( htmlspecialchars( strip_tags(  str_replace(array($symbols,' ') , '_', $str ) ) )) ,
		'filter' => filter_var( $str , FILTER_SANITIZE_SPECIAL_CHARS )
	    );
	    
	    if ($filter == "ini")
	    {
		/* Filter ini comma separated settings */
		$_ini_conf = explode(",", $str);
		foreach($_ini_conf as $k=>$v)
		{
		    $_ini_conf[$k] =  trim(self::__filter( $v , 'filter' ));
		}
		$__filters['ini'] = $_ini_conf;
	    }
	    
	    return $__filters[$filter];
	}
	
	
    }
    
    $zwoot  = new Zwoot();
    
?>