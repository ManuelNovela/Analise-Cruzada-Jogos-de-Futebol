<?php
//header("Content-Type: text/html; charset=UTF-8");
define('ABSPATH',dirname(__FILE__).'/');
define('BASEPATH',dirname($_SERVER['PHP_SELF']));

// CONFIGS AND DEFAULTS
require(ABSPATH.'includes/config.php');

$system['system_can_send_link'] = false;  //nao ativo

$system['system_url'] = SYS_URL;
$system['system_public'] = true;
$system['theme'] = 'default';


$system['uploads_directory'] = "content/uploads";
$system['system_assets'] = $system['system_url']."/content/themes/".$system['theme']."/views/assets";
$client_mode = true;
$system['titulo'] = "Apostas | Predições";

// SMARTY
require_once(ABSPATH.'includes/libs/Smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->template_dir = ABSPATH.'content/themes/'.$system['theme'].'/views';
$smarty->compile_dir = ABSPATH.'content/themes/'.$system['theme'].'/views_compiladas';
$smarty->loadFilter('output', 'trimwhitespace');
$smarty->assign("system",$system);

//GET FUNCTIONS E USER CLASS
global $conn;
//$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
//$conn->set_charset('utf8mb4');

require_once(ABSPATH.'includes/functions.php');
require_once(ABSPATH.'includes/api.php');
require_once(ABSPATH.'includes/class-user.php');

session_start();

try {
    $user = new User();
    /* assign variables */
    $smarty->assign('user', $user);
} catch (Exception $e) {
    _error(__("Error"), $e->getMessage());
}



?>
