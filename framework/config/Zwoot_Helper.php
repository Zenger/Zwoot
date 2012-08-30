<?php
    class Zwoot_Config_Helper
    {
        // Holds params
        var $params;
        
        //holds the value (to be accesed from outside)
        var $value;
        
        public function __construct( $value , $filter = null , $params = null )
        {
            // if no filters, ignore call
            if ($filter == null) return;
            
            //Allowed filters
            $filters = array(
                'filter',
                'strip_tags',
                'strip',
                'trim',
                'key',
                'ini',
                'os_dir',
                'clean' //removes special chars (doesn't filter, but removes (must be called before filter))
            );
            
            /* Callable params for filters */
            $this->params = $params;
            
            if (is_array($filter))
            {
                foreach($filter as $method)
                {
                    if (in_array($method, $filters))
                    {
                        $this->value = $this->{$method}( $value );
                    }
                }
            }
            else
            {
                if (in_array($filter, $filters))
                {
                    $this->value = $this->{$filter}( $value );
                }
            }
            
            return $value;
        }
        
        /* Filters a string */
        public function filter( $value )
        {
            if (is_array($value))
            {
                return $this->filter_array($value);
            }
            
            return filter_var($value , FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
        
        /* Filters an array */
        public function filter_array( $value )
        {
            if (is_string($value) || is_int($value))
            {
                return $this->filter($value);
            }
            
            return filter_var_array($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
        
        /* Alias to strip_tags */
        public function strip ($value)
        {
            return $this->strip_tags ($value); 
        }
        
        /* Strip all the tags */
        public function strip_tags($value)
        {
            if (is_array($value) )
            {
                return $this->strip_tags_array($value);
            }
            
            return strip_tags($value);
        }
        
        
        /* Strips all tags if value is array */
        public function strip_tags_array( $data , $tags = null )
        {
            
            if (!empty($this->params)) { $tags = $this->params; }
            
            if (is_string($value) || is_int($value))
            {
                return $this->strip_tags($value);
            }
            
            $stripped_data = array();
            
            foreach ($data as $value)
            {
                if (is_array($value))
                {
                    $stripped_data[] = $this->strip_tags_array($value, $tags);
                }
                else
                {
                    $stripped_data[] = strip_tags($value, $tags);
                }
            }
            return $stripped_data;
            
        }
        
        
        /* Creates a valid array key from string */
        public function key($value)
        {
            if (is_int($value) || is_string($value) )
            {
                
               return strtolower(str_replace(' ', '_' , $this->clean( $value ) ));
            }
            
            return;
        }
        
        
        /* Removes all special chars */
        public function clean($value)
        {
            
            $symbols = array('/','!','@','#','$','%','^','&','*',')', '(', '"', "'", '\\' ,"/" , ':',';', '.',',','+','-','`','~' ); //utf8 don't count


            if (is_int($value) || is_string($value) )
            {
                
               return str_replace($symbols, '' , $value  );
            }
            
            return;
        }
        
        /* Reads a ini string and returns an array of values */
        public function ini($value)
        {
            $value = explode(',', $value);
            return $value;
        }
        
        /* Parses a string and return the right directory separator for different OS */
        public function os_dir($value)
        {
            $ds = DIRECTORY_SEPARATOR;
            return str_replace(array('/', '\\') , $ds , $value);
        }
        
    
        public function __destruct()
        {
           //
        }
        
        
        public function get()
        {
            return $this->value;
        }
    }
    
    
    /* Alias, for usability */
    /* Zwoof stands for Zwoot Filter */
    function zwoof( $value , $filters = null , $params = null )
    {
        $filter = new Zwoot_Config_Helper($value , $filters, $params);
        return $filter->get();
    }
    
    /* Small Debugging function */
    function zwood($var, $action, $file, $line, $render = array('meta' => true) )
    {
        if ($action == "v" || $action == "var_dump")
        {
            var_dump($var);
        }
        else
        {
            print_r($var);
        }
        
        echo "Line: " . $line. " in " . $file;
        
    }
    
    
?>