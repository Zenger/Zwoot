<?php
    /*  This file provides the analog functions of wordress.
	This is usefull if the owner doesn't update the wordpress and the framework and there are
	deprecated functions, or if someone tries to use this as a standalone framework !!!
    */
    
    
    if (!function_exists('wp_die'))
    {
	/* Dies "nicely" */
	function wp_die($string)
	{
	    die($string); // just "nice" 8D
	}
    }
    
    
    if (!function_exists('parse_ini_file'))
    {
	/* Can't run without parse_ini_file */
	wp_die('The parse_ini_file is vital for Zwoot Framework, can\'t continue, sorry');
    }
    



?>