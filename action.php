<?php
    error_reporting(E_ALL);
    date_default_timezone_set('America/Bogota');
    require './Controllers/controller.php';

    $data = [];
    $data[] = $_REQUEST['name'];
    $data[] = $_REQUEST['email'];
    $politicas = $_REQUEST['acepto_terminos'];

if(!empty($_REQUEST['name']) && !empty($_REQUEST['email'])){


    if($politicas == "on"){
        $externalContent = file_get_contents('http://checkip.dyndns.com/');
        preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $externalContent, $m);
        $data[] = $m[1];
        $data[] = $_SERVER['HTTP_REFERER'];
        $data[] = date('Y-m-d');
        $data[] = date('H:i:s');
    }

    $controller = new Controller();
    $clientid = $controller->getClient(); // Get Client ID
    $list = $controller->getList($clientid); // Get List ID
    $findSub = $controller->findSubInList($clientid , $list, $data[1]); //Find if sub exist


    if( isset($findSub['entries'][0])){
        $userid = $findSub['entries'][0]['id'];
        $updateSub = $controller->updateSub($clientid , $list, $data);
        $controller->updateSubDB($data);
        $date  = date('m/d/y');
        $fecha = date('H:i:s');
        file_put_contents("logs.log", PHP_EOL . $data[1].' - ' . $date. ' ' . $fecha . ' - (Agregado exitosamente' .  print_r($updateSub,true) . ' )',FILE_APPEND);
    }else{
        $date  = date('m/d/y');
        $createSub = $controller->addSub($clientid , $list, $data);
        $controller->addSubDB($data);
        file_put_contents("logs.log", PHP_EOL . $data[1].' - ' . $date. ' ' . $fecha . ' - (Agregado exitosamente' .  print_r($createSub,true) . ' )',FILE_APPEND);
    }
        header('Location: https://localhost/ideaware');
}else{
        header('Location: https://localhost/ideaware?error=true');
}
    
?>