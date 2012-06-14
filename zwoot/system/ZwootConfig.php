<?php
    if (!defined('ZwootApp') || !constant('ZwootApp'))
    {
	die("<!-- Don't call me directly -->");
    }
    
    define('ZwootConfigFile' , ZwootWeai .  '/template.ini'); // i strongly do not sugest you changing this
    
    /* Include all libraries */
    
    require('ZwootAdminUi.php');
    
    class ZwootConfig extends Zwoot
    {
	
	
	/* Parse the config file */
	public static function Run()
	{
	    self::ParseConfig();
	    return;
	   
	}
	
	
	public static function ParseConfig()
	{
	    
	    if (!file_exists(ZwootConfigFile))
	    {
		//die()
		wp_die("No config file at : " . ZwootConfigFile);
	    }
	    
	    /* Sorry but for now only INI will be used */
	    $config = parse_ini_file(ZwootConfigFile , true);
	    
	    if (empty($config) || $config == false)
	    {
		ZwootConfig::LoadDefaults(); //fresh install
	    
	    }
	    
	    parent::$config = $config;
	    /* Init all the data */
	    
	    ZwootConfig::LoadConfig();
	    
	    return;
	}
	
	/* Runs all the included libraries */
	
	public static function LoadConfig()
	{
	    /* If an UI is required Init UI */
	    if (parent::$config['system']['show_admin_ui'] == true) // yes == not === , parse_ini_file returns 1 on true and 0 on false (note)
	    {
		ZwootAdminUi::Run();
	    }
	}
	
	/* Set the default values */
	
	public static function LoadDefaults()
	{
	    parent::$config = array(
		
		'menus' => array(
		    array('main_nav', "Main Nav"),
		    array('main_nav', "Footer nav"),
		),
		
		'sidebars' =>array(
		    array('sidebar_1' , 'Sidebar #1'),
		    array('sidebar_2' , 'Sidebar #2')
		),
	    
	
	    );
	}
	
	
	
	
    }
    
?>