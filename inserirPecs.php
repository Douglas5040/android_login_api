<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['nome']) && isset($_POST['modelo']) && isset($_POST['marca'])) {

    // receiving the post params
    $nome = $_POST['nome'];
    $modelo = $_POST['modelo'];
    $marca = $_POST['marca'];

    $retorno = $db->addPecs($nome,  $modelo, $marca);
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

