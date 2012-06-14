<?php
    class ZwootAdminUi extends Zwoot
    {
        static $config;

        public function setPageTitle()
        {
            
        }
        
        /* Take config and start working the UI */
        public static function Run()
        {
            self::$config = self::getConfig();
            
            /* Append header files into Admin */
            add_action('admin_head', array(ZwootAdminUi, "Header") );
            
            /* Add Menu page */
            add_action('admin_menu', array(ZwootAdminUi , 'AddAdminPage') );
        }
        
        public static function getConfig()
        {
            $adminConfigFile = ZwootWeai  . "/" . parent::$config['system']['admin_ui'];
            
            if (!file_exists($adminConfigFile))
            {
                /* aww no config */
                self::LoadDefaults();
            }
            
            $conf = parse_ini_file($adminConfigFile , true);
            if (empty($conf))
            {
               self::LoadDefaults();
            }
            else
            {
                return $conf;
            }
        }
        
        /* Append js/css files we required for the admin ui */
        public static function Header()
        {
            ?>
            <link rel="stylesheet" href="<?php echo ZwootUrl; ?>/public/css/Admin.css" />
            <script type="text/javascript" src="<?php echo ZwootUrl; ?>/public/js/Admin.js"></script>
            <?php
            
            // Enque Metabox Styling and so on.
        }
        
        
        public static function LoadDefaults()
        {
            // daw
        }
        
        public static function AddADminPage()
        {
            /* For now will place it under appearance */
            add_menu_page( parent::$config['template']['name'], parent::$config['template']['name'], 'edit_theme_options', 'zwoot-framework', array('ZwootAdminUi' , 'AdminPage'), ZwootUrl . '/public/images/zwoot-icon.png', 61 ); 
        }
        
        public static function AdminPage()
        {
            $args = func_get_args();
            ?>
            
            <div class="wrap">
               <div class="icon32" id="zwoot-logo-icon32"><br></div>
               
               <h2> <?php echo parent::$config['template']['admin_title']; ?> </h2>
                
            </div>
            
            <?php 
        }
    }
?>