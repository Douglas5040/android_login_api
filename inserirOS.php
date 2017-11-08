<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['id_cliente']) && isset($_POST['matri_func']) && isset($_POST['tipo_manu']) && isset($_POST['obs'])
    && isset($_POST['data']) && isset($_POST['hora_ini']) && isset($_POST['hora_fin'])) {

    // receiving the post params
    $id_cli = $_POST['id_cliente'];
    $matri_func = $_POST['matri_func'];
    $tipo_manu = $_POST['tipo_manu'];
    $obs = $_POST['obs'];
    $data = $_POST['data'];
    $hora_ini = $_POST['hora_ini'];
    $hora_fin = $_POST['hora_fin'];

    // check if user is already existed with the same email
    $retorno = $db->addOS($id_cli, $matri_func, $tipo_manu, $obs, $data, $hora_ini, $hora_fin);
    if(!$retorno){

        $response["error"] = TRUE;
        $response["error_msg"] = $retorno;
        echo json_encode($response);

    }else{

        $response["error"] = FALSE;
        $response["last_os"] = $retorno;
        $response["error_msg"] = "Dados inseridos com sucesso!";
        echo json_encode($response);
    }


} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "dados inseridos (id_cliente, matri_func, obs or tipo_manu) estão incorretos!";
    echo json_encode($response);
}
?>

