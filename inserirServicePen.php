<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['lati']) && isset($_POST['longi']) && isset($_POST['cli']) &&
    isset($_POST['codRefriCli']) && isset($_POST['ender']) && isset($_POST['comple']) &&
    isset($_POST['data']) && isset($_POST['hora']) && isset($_POST['descriCliPro']) && isset($_POST['descriRefrige']) &&
    isset($_POST['status']) && isset($_POST['tipoManu'])) {

    // receiving the post params
    $lati = $_POST['lati'];
    $longi = $_POST['longi'];
    $cli = $_POST['cli'];
    $ender = $_POST['ender'];
    $comple = $_POST['comple'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $descriCliPro = $_POST['descriCliPro'];
    $descriRefri = $_POST['descriRefrige'];
    $status = $_POST['status'];
    $tipoManu = $_POST['tipoManu'];
    $codRefriCli = $_POST['codRefriCli'];

    $retorno = $db->addServicePenCli($lati, $longi, $cli, $ender, $comple, $data, $hora,
                                        $descriCliPro, $descriRefri, $status, $tipoManu , $codRefriCli);
    if(!$retorno["erro"]){

        $response["error"] = FALSE;
        $response["return"] = $retorno["retorno"];
        echo json_encode($response);

    }else{
        $response["error"] = TRUE;
        $response["error_msg"] = $retorno["retorno"];
        echo json_encode($response);
    }


} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "dados inseridos no service, incorretos!!!";
    echo json_encode($response);
}
?>

