<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['nome']) && isset($_POST['descri']) && isset($_POST['tempo'])) {

    // receiving the post params
    $nome = $_POST['nome'];
    $descri = $_POST['descri'];
    $tempo = $_POST['tempo'];

    $retorno = $db->addServices($nome,  $descri, $tempo);
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
    $response["error_msg"] = "dados inseridos (nome, descri, tempo) estão incorretos!";
    echo json_encode($response);
}
?>

