<?php
    class Zwoot_UI_Sidebar
    {
        var $sidebars;
        
        public function __construct( $sidebars )
        {
          
            // Filter the input array and sanitize it
            $sidebars = zwoof ($sidebars , array('strip','filter') );
           
            foreach($sidebars as $sidebar)
            {
                $sidebar = zwoof($sidebar, 'ini');
                
                register_sidebars(1, array( 'id' => "Zwoot_" .  zwoof($sidebar[0], 'key') , 'name' => $sidebar[0] , 'description' => $sidebar[1] )  );
                
               
            }
           
            
          
        }
    }

