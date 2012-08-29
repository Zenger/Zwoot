<?php

class Zwoot_UI_Admin
{
	
	var $header = array(
		'css/ZwootAdmin.css',
		'js/ZwootUI.js',
		'js/ZwootShortCodes.js'
	);
	
	// template system instance
	var $template;
	
	public function __construct()
	{
		// Load Header
		add_action('admin_head' , array($this, 'attach_admin_header'));
		// Attach a page
		add_action('admin_menu', array($this , 'attach_admin_page') );
	
		// If any custom template, load it first
		echo "FEELING AWESOME YAH?";
		do_action('Zwoot_Template', $this );
	
		// Init template system
		if (empty($this->template))
		{
			$this->template = new Zwoot_UI_Template();
		}
	}
	
	
	public function attach_admin_page()
	{
		$config = Zwoot_Config::get('template');
		add_menu_page( $config['name'], $config['name'], 'edit_theme_options', 'zwoot-framework', array( $this , 'admin_layout'), ZwootPublic . DIRECTORY_SEPARATOR .'images/zwoot-icon.png', 61 ); 
	}
	
	public function attach_admin_header()
	{
		//load header data
		foreach($this->header as $header_file)
		{
			
			
			if (strpos($header_file , "css/" ) === false)
			{
				echo '<script type="text/javascript" src="'.ZwootPublic . DIRECTORY_SEPARATOR .$header_file.'"></script>' . PHP_EOL;
			}
			else
			{
				echo '<link rel="stylesheet" href="'.ZwootPublic . DIRECTORY_SEPARATOR . $header_file . '" />' . PHP_EOL;
			}
		}
		
		// Admin header hook
		do_action('zwoot_admin_header');
	}
	
	
	public function admin_layout()
	{
	
		// carefull changing this
		$config_file = ZwootWeai . DIRECTORY_SEPARATOR  .  "config/AdminUI.ini";
		try
		{
			if (!file_exists($config_file))
			{
				throw new CriticalException("File $config_file does not exist! If you don't need an Admin UI then set show_admin_ui to false in template.ini");
			}
			else
			{	// parse ini file as admin_ui config file (will make 3dimensional arrays)
				$fields = Zwoot_Config::parseConfigFile($config_file , 'admin_ui' , true);
				
				// parse layout fields
				$this->layout = new Zwoot_Config_AdminUI( $fields );
				
				
				 
				// Render UI
				 $this->render_ui();
				 
				
			}
		}
		catch (CriticalException $e)
		{
			$e->wp_die();
		}
		
	}
	
	
	public function render_ui()
	{
		
		
		echo $this->template->wrapper_start();
			
		//$this->template->wrapper_end();
	}
	

	

}
?>
