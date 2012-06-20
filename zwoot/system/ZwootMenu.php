<?php
    class ZwootMenu extends Zwoot
    {
        
        static $menus;
        
        public static function RegisterMenus()
        {
            foreach(self::$menus as $menu_name )
            {
                $menus[Zwoot::__filter($menu_name , 'array_key')] = $menu_name;
            }
            
            register_nav_menus( $menus );
        }
    }
?>