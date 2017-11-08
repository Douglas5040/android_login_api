<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['id_pc']) && isset($_POST['id_os'])) {

    // receiving the post params
    $id_pc = $_POST['id_pc'];
    $last_os = $_POST['id_os'];

    $retorno = $db->addPcsProbleOS($id_pc,  $last_os);
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
    $response["error_msg"] = "dados inseridos (id_pc) estão incorretos!";
    echo json_encode($response);
}
?>

