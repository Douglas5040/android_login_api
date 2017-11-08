<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['matriFunc']) && isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST['dataPosi']) && isset($_POST['horaPosi'])) {

    // receiving the post params
    $matriFunc = $_POST['matriFunc'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $dataPosi = $_POST['dataPosi'];
    $horaPosi= $_POST['horaPosi'];

    $retorno = $db->addPosiFunc($matriFunc,  $latitude, $longitude, $dataPosi, $horaPosi);
    if($retorno == null){

        $response["error"] = FALSE;
        $response["error_msg"] = "Dados inseridos com sucesso!";
        echo json_encode($response);

    }else{

        $response["error"] = TRUE;
        $response["error_msg"] = $retorno;
        echo json_encode($response);
    }


} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "dados inseridos (nome, modelo, marca) estão incorretos!";
    echo json_encode($response);
}
?>

