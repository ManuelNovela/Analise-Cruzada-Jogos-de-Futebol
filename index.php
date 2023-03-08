<?php

require_once('auto_inicializador.php');

/*if(!$system['system_public']) {
    user_access();
}

if(!$user->_logged_in){
    $smarty->display('login.tpl');
    die();
}


if(!$user->_is_admin && !$user->_is_client) {
    _error(__('System Message'), __("You don't have the right permission to access this"));
}
if($user->_is_client && !$client_mode) {
    _error(__('System Message'), __("You don't have the right permission to access this"));
}
*/



//info
$smarty->assign('matches', getMatches());

$smarty->assign("view",'');
try {
    //display
    $smarty->display('index.tpl');
}catch (Exception $e){
    _error("Error tpl");
}

global $conn;
//$conn->close();
?>
