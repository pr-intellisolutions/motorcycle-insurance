<?php
define('DIR_BASE', dirname(__FILE__) . DIRECTORY_SEPARATOR);

if (file_exists(DIR_BASE.'install'))
{
	$url = cut_string_using_last('/', $_SERVER["PHP_SELF"], 'left', true);
	$install = 'install';

	if (file_exists(DIR_BASE.'db_config.ini'))
	{
		echo "Es recomendable que una vez la aplicación ha sido instalada se remueva el directorio de instalación localizado
			en: ".$url.$install;
	}
	else
	{
		header("Location: ".$url.$install);
		die();
	}
}

require_once('includes/template.php');
require_once('includes/db.php');
require_once('includes/config.php');
require_once('includes/session.php');
require_once('includes/user.php');

$template = new Template;
$site_config = new SiteConfig;
$user = new User;

define('SITE_URL', 'http://'.$site_config->site_host.$site_config->site_module);

function cut_string_using_last($character, $string, $side, $keep_character=true) { 
    $offset = ($keep_character ? 1 : 0); 
    $whole_length = strlen($string); 
    $right_length = (strlen(strrchr($string, $character)) - 1); 
    $left_length = ($whole_length - $right_length - 1); 
    switch($side) { 
        case 'left': 
            $piece = substr($string, 0, ($left_length + $offset)); 
            break; 
        case 'right': 
            $start = (0 - ($right_length + $offset)); 
            $piece = substr($string, $start); 
            break; 
        default: 
            $piece = false; 
            break; 
    } 
    return($piece); 
}

?>
