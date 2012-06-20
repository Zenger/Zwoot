<?php
    if (!defined('ZwootApp') || !constant('ZwootApp'))
    {
	die("<!-- Don't call me directly -->");
    }
    
    define('ZwootConfigFile' , ZwootWeai .  '/template.ini'); // i strongly do not sugest you changing this
    
    /* Include all libraries */
    
    require('ZwootAdminUi.php');
    require('ZwootMenu.php');
    require('ZwootSidebar.php');
    
    
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
	    if (parent::$config['system']['show_admin_ui'] == true) 
	    {
		ZwootAdminUi::Run();
	    }
	    
	    /* Load Menus */
	    
	    if (!empty(parent::$config['menus']))
	    {
		foreach(parent::$config['menus']['menu'] as $menu)
		{
		    ZwootMenu::$menus[] = $menu;
		}
		
		ZwootMenu::RegisterMenus();
	    }
	    
	    /* Load Sidebars */
	    
	    if (!empty(parent::$config['sidebars']))
	    {
		foreach(parent::$config['sidebars']['sidebar'] as $sidebar)
		{
		    ZwootSidebar::$sidebars[] = $sidebar;
		}
		
		ZwootSidebar::RegisterSidebars();
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