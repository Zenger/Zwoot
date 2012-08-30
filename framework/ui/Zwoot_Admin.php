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
	
		// This stays here
		/* The ideea is to call another class to implement it's own template system */
		
		do_action('Zwoot_Template', $this );
		
		

	}
	
	
	public function attach_admin_page()
	{
		$config = Zwoot_Config::get('template');
		add_menu_page( $config['name'], $config['name'], 'edit_theme_options', 'zwoot-framework', array( $this , 'admin_layout'), ZwootPublic  .'/images/zwoot-icon.png', 61 ); 
	}
	
	public function attach_admin_header()
	{
		//load header data
		foreach($this->header as $header_file)
		{
			
			
			if (strpos($header_file , "css/" ) === false)
			{
				echo '<script type="text/javascript" src="'.ZwootPublic . '/' .$header_file.'"></script>' . PHP_EOL;
			}
			else
			{
				echo '<link rel="stylesheet" href="'.ZwootPublic . '/' . $header_file . '" />' . PHP_EOL;
			}
		}
		
		// Admin header hook
		do_action('zwoot_admin_header');
	}
	
	public function showMessages()
	{
		// show notify, erorrs and such
	}
	
	
	public function Template($template_file, $check_alt = true)
	{
		$ds = DIRECTORY_SEPARATOR;
		$current = Zwoot_Config::read('template_path') . $ds ;
		
		$fields = array('text');
		$pages = array('front_page');
		
		if ($check_alt)
		{
			if (file_exists( zwoof( $current . 'zwoot_template/' .$template_file , array('os_dir') ) ) )
			{
				include_once( zwoof( $current . 'zwoot_template/'. $template_file , array('os_dir') )  . '.php'); 
			}
			else
			{
				if (in_array($template_file, $pages))
				{
					include_once( zwoof( ZwootWeai . '/framework/templates/' . $template_file . '.php', 'os_dir' ) );
				}
				else
				{
					if (in_array($template_file , $fields))
					{
						include_once( zwoof( ZwootWeai . '/framework/templates/fields/'. $template_file . '.php' , array('os_dir')) );
					}
				}
			}
		}
	}
	
	// Load Admin Layout
	
	public function admin_layout()
	{
		$this->Template('front_page' , true);
	}


	

}
?>
