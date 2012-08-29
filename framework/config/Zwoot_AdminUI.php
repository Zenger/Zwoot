<?php
    class Zwoot_Config_AdminUI 
    {
        /* Fields available in this version of Zwoot */
        static $tabs;
        static $field_id = 0;
        private static $fields = array( "text", 'email' );
        
        
        public function __construct($fields = null)
        {
            
          
            
            foreach($fields as $tab => $field_block)
            {
                $tab_index = "Zwoot_" .zwoof($tab , 'key');
                self::$tabs[ $tab_index  ]['title'] =   $tab;
                
               
                 
                
                foreach($field_block as $key=>$value)
                {
                    $callback = "Zwoot_" . ucfirst( self::get_field_type($value) );
                    
                    if (method_exists($this, $callback) && in_array(self::get_field_type($value) , self::$fields))
                    {
                       self::$tabs[ $tab_index ]['fields'][] =  $this->{$callback}( $key , $value );
                       self::$field_id++;
                    }
                }
                
                
            }
            

        }
        
        
        public static function Zwoot_Text( $id , $params )
        {
            
            $template = array(
                'type' => 'text',
                'title' => 'Your title',
                'description' => '',
                'placeholder' => '' ,
                'class' =>  '',
                'field_id' => 'zwoot-field-' . self::$field_id
            );
            
            $remap = array(); $i = 0;
            
            if ( gettype($params) <> "array")
            {
                $params = zwoof($params ,'ini');
                foreach($template as $k=>$v)
                {
                    $remap[$k] = ($params[$i])?$params[$i]:$v;
                    $i++;
                }
            }
            else
            {
                foreach($template as $k=>$v)
                {
                    if ($params[$k]){
                        $remap[$k] = $params[$k];
                    }
                    else
                    {
                        $remap[$k] = $v;
                    }
                   
                }
            }
           return $remap;
            
        }
        
        public static function get_field_type($field)
        {
            if (is_array($field))
            {
                $type = $field[0];
                if (empty($type)) $type = $field['type'];
            }
            else
            {
                $field = self::parse_ini_value( $field );
                if (!empty($field[0])) $type = $field[0];
            }
            if (!in_array($type, self::$fields)) $type = self::$fields[0];
            
            return $type;
        }
        
        public static function parse_ini_value($string , $parse_as = null)
        {
            if ($parse_as == null)
            {
                return explode(',', $string);
            }
        }
    }
?>