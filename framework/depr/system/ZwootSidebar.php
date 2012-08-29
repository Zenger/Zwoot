<?php
    class ZwootSidebar extends Zwoot
    {
        
        static $sidebars;
        
        public static function RegisterSidebars()
        {
            foreach(self::$sidebars as $sidebar )
            {
                $sidebar = Zwoot::__filter( $sidebar, 'ini');
                
                register_sidebars(1,  array( 'id' => Zwoot::__filter($sidebar[0] , 'array_key') , 'name' => $sidebar[0] , 'description' => $sidebar[1] ) );
            }
        }
    }
?>