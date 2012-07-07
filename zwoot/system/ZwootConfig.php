<?php
    if (!defined('ZwootApp') || !constant('ZwootApp'))
    {
	die("<!-- Don't call me directly -->");
    }
    
    define('ZwootConfigFile' , ZwootWeai .  '/template.ini'); // i strongly do not sugest you changing this
    
    /* Include all libraries */
    
    require('ZwootAdminUi.php'); // Admin Front end
    require('ZwootMenu.php'); // Menus
    require('ZwootSidebar.php'); // Sidebars
    require('ZwootMetaBox.php'); // Metabox
    
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
	    
	    /* Load Metaboxes */
	    ZwootMetabox::RunMetaboxes();
	    
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
	
	
	/* Parses ini files */
	public static function ParseZwootConfig($configFile , $metabox_prefix = "")
	{
	    
	   // @TODO
	    
	}
	
	
	public static function ParseMetaboxConfig($configFile , $prefix = null)
	{
	     /* Too long to hold $metabox_prefix, rename to $PFX (caps) */
	    
	    $PFX = Zwoot::__filter($prefix , 'array_key');
	    
	      /* Handle parse_ini incompatibilities */
	    $replacements = array(
		"'" => '`', // safe	  
	    );
	
	    $metaboxes = "";
	    
	    try
	    {
		$raw = file_get_contents($configFile);
		foreach($replacements as $i => $v)
		{
		    $conf_string = str_replace($i , $v , $raw);
		}
		
		$config = parse_ini_string($conf_string , true);
	    }
	    catch (Exception $e)
	    {
		die($e->getMessage());
	    }
	    
	    if (empty($config))
	    {
		return false;
	    }
	    else
	    {
		
		// Global Sections
		foreach($config as $i => $section)
		{
		    $metabox = Zwoot::__filter( Zwoot::df( $i , 'custom_metabox') , 'array_key' );
		    $metaboxes[$metabox]['title'] = Zwoot::df( $i , 'Custom Metabox');
		    $metaboxes[$metabox]['post_type'] = Zwoot::__filter( Zwoot::df( $section['post'] , 'post') , 'ini' );
		    $metaboxes[$metabox]['context'] =  Zwoot::df( $section['context'] , 'normal' );
		    $metaboxes[$metabox]['priority'] = Zwoot::df ( $section['priority'] , 'high' );
		    
		    unset($section['post']); // we remove and parse the following as Fields
		    unset($section['context']);
		    unset($section['priority']);
		    
		    foreach($section as $k => $mb_section)
		    {
			
			self::mb_ini_string($mb_section, $k);
		    }
		    
		   
		}
		
		
		
	    }
	}
	/* Helper function parses a text string into an array valid for metaboxes */
	public static function mb_ini_string($string , $values_of = false)
	{
	    
	    $PFX = ZwootMetabox::$prefix;
	    
	    $hasPlaceholder = false;

	   if (!is_array($string)) {
		
		if ( preg_match( '/placeholder\:(\w|\s).+/si' , $string , $matches ) )
		{
		    $hasPlaceholder = str_replace('placeholder:' , '' , $matches[0]);
		}
		
		$runner = explode(',' , $string);
		
		$mb_type = (string)strtolower($runner[0]);
		
		/* Metaboxes */
		
		ZwootMetabox::$metaboxes[ ZwootMetabox::Field_ID ( $runner[1] ) ]['type'] = $mb_type;
		ZwootMetabox::$metaboxes[ ZwootMetabox::Field_ID ( $runner[1] ) ]['name'] = $runner[1];
		ZwootMetabox::$metaboxes[ ZwootMetabox::Field_ID ( $runner[1] ) ]['description'] = $runner[2];
		ZwootMetabox::$metaboxes[ ZwootMetabox::Field_ID ( $runner[1] ) ]['values'] = '';
		ZwootMetabox::$metaboxes[ ZwootMetabox::Field_ID ( $runner[1] ) ]['std'] = '';
		ZwootMetabox::$metaboxes[ ZwootMetabox::Field_ID ( $runner[1] ) ]['class'] = rtrim('zwoot_text ' . $runner[3] , ' ');
		ZwootMetabox::$metaboxes[ ZwootMetabox::Field_ID ( $runner[1] ) ]['placeholder'] = $hasPlaceholder;
		
	   }
	   else
	   {
		$values_of = ZwootMetabox::Field_ID( str_replace('.values' , '' , $values_of) );
		foreach( $string as $k => $i)
		{
		    ZwootMetabox::$metaboxes[$values_of]['values'][$k] = $i;
		}
	   }
	   
	  
	   
	   return;
	}
	
	
    }
 
    
?>