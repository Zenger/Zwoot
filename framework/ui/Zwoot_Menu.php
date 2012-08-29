<?php
    class Zwoot_UI_Menu
    {
        var $menus;
        
        public function __construct( $menus )
        {
        
            // Filter the input array and sanitize it
    
            $menus = zwoof ( $menus, array('filter', 'strip') );
            // fix
            $menus = $menus[0];
            // Walk menus array and keep it in this instance
            foreach($menus as $menu)
            {
                
                $this->menus[ zwoof ( $menu , 'key' ) ] = $menu;
                
            }
            

            // Finaly, register nav
            register_nav_menus($this->menus);
        }
    }
?>