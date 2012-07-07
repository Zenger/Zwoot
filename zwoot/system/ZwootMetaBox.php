<?php
    
    class ZwootMetabox extends Zwoot
    {
        static $prefix;
        
        static $metaboxes;
        
        public static function RunMetaboxes()
        {
            $metaboxFile = ZwootWeai . DIRECTORY_SEPARATOR . parent::getConfig('path', 'metabox' ,  'zwoot/config/metabox.ini' );
            self::$prefix = parent::getConfig('system','metabox_prefix','Zwoot_');
            
            if (!file_exists($metaboxFile))
            {
                return; // No metabox for this template
            }
            
            $metaboxes = ZwootConfig::ParseMetaboxConfig( $metaboxFile , self::$prefix );
            
            
            print_r(self::$metaboxes);
            
        }
        
        
        // Helper function transforms name into a id and value using prefix
        public function Field_ID( $string )
        {
            if (!empty($string))
            {
                return self::$prefix . Zwoot::__filter($string , 'array_key');
            }
        }
        
        
    }
?>