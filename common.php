<?php
define('DIR_BASE', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('CONFIG_FILE', DIR_BASE . 'config.ini');

require_once('includes/template.php');
require_once('includes/config.php');
require_once('includes/db.php');
require_once('includes/session.php');
require_once('includes/user.php');

$site_config = new SiteConfig;
$template = new Template;
$user = new User;

define('SITE_URL', 'http://'.$site_config->site_host.$site_config->site_module);

?>
