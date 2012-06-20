<?php
    /* init the core run the Framework */
    define('ZwootApp' , true); // Security Fix
    
    define('ZwootFlavour' , 'plugin_theme'); //for later implementation , see Work Plan
    
    /* ZwootWai - Zwoot , What am I? , a plugin or a theme. */
    if ( strpos( __FILE__ , '/plugins/') === false )
    {
	define('ZwootWai', 'theme');
    }
    else
    {
	define('ZwootWai' , 'plugin');
    }
    
    /* ZwootWeai - Zwoot, Where am I? , sets the Zwoot folder */

    define('ZwootWeai' , preg_replace('/\/zwoot$/i', '' , dirname(__FILE__) ) );
    
    /* Get the proper URL */
    
    $path = explode('wp-content' , ZwootWeai);
    if (ZwootWai == "plugin")
    {
	define("ZwootUrl" , get_bloginfo('url') . '/wp-content' . $path[1] );
    }
    else
    {
	define("ZwootUrl" , get_bloginfo('url') . '/wp-content' . $path[1]  );
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
	
	/* Parse diasbled for future implementation */
	public static function __filter($str = null, $filter = null , $parse_disabled = false)
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