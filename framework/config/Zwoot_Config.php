<?php

    /* Main Config Class */
    /* Parses config file and loads the required classes */
    
    class Zwoot_Config
    {
        static $config;
        
        static $helper;
        
        var $menus;
        var $sidebars;
        
        
        
        public function __construct()
        {
            self::$config = $this->parseConfigFile( ZwootWeai . "/template.ini" );
            new Zwoot_Config_Helper("");
            $this->load_classes();
        }
        
        
        public function load_classes()
        {
            
            // Enable Menus
            if (!empty(self::$config['menus']))
            {
                    $this->menus = new Zwoot_UI_Menu( self::$config['menus'] );
            }
            
            // Enable Sidebars
            if (!empty(self::$config['sidebars']))
            {
                foreach(self::$config['sidebars'] as $sidebar)
                {
                   
                    $this->sidebars[] = new Zwoot_UI_Sidebar( $sidebar );
                }
            }
            
            if (self::$config['system']['show_admin_ui'] == 1)
            {
                $this->ui = new Zwoot_UI_Admin();
            }
            
            
        }
        
        
        public function parseConfigFile($file , $type = "config", $three_dimensional = false)
        {
            $types = array('metabox','ini','config','admin_ui'); // allowed types
            if (!in_array($type, $types)) { $type = $types[0];}
            
            if ($type == "metabox")
            {
                $key_levels = array('title' , 'description');
            }
            else
            {
                $key_levels = array('title', 'description');
            }
            
            if (!file_exists($file))
            {
                throw new CriticalException("File does not exist: $file");
            }
            else
            {
                // first parse as simple ini file
                $config = parse_ini_file($file , true);
                if (!empty($config))
                {
                    $config = Zwoot_Config::config_walk_keys ( $config  , $key_levels );
                    if ($three_dimensional == true)
                    {
                        $config = Zwoot_Config::config_walk_three_dimension($config);
                    }
                    return $config;
                }
                else
                {
                    throw new CriticalException("Config file is empty!");
                }

            }
        }
        
 
        public static function config_walk_keys( array $config , array $key_levels )
        {
           $new_config;
           array_unshift($key_levels , "");  
           foreach($config as $key => $value)
           {
                if (preg_match('/\:/', $key) )
                {
                    $explode = explode(':' , $key);
                    $main_key = trim( $explode[0] ) ;
                    $helper = Zwoot_Config::get_helper_instance();
                    
                    $new_config[ $helper->set($main_key)->key()->get() ] = $value;
                    unset($explode[0]);
                    
                    foreach($explode as $i => $v)
                    {
                        $new_config[ $main_key ][ $key_levels[$i] ] = $v;
                    }
                }
                else
                {
                    $new_config[$key] = $value;
                }
           }
           
          return $new_config;
        }
        
        
        public function config_walk_values ()
        {
            
        }
       
        /* Returns a value from the config */
        public function get($key)
        {
            return self::$config[$key];
        }
        
        
        // Walks ini array, finds . settings and regenerates into a 3 dimensional array.
        public static function config_walk_three_dimension( $config )
        {
            $new_config;
        
            // foreach section
            foreach($config as $section => $section_values)
            {
                foreach($section_values as  $k => $v)
                {
                    if ( preg_match( '/\./', $k))
                    {
                        //has dot
                        $key = explode('.', $k);
                        $new_config[$section][$key[0]][$key[1]] = $v;
                    }
                    else
                    {
                        $new_config[$section][$k] = $v;
                    }
                }
            }

            return $new_config;
        }
     
    }
?>
