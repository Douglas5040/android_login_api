<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['id']) && isset($_POST['nome']) && isset($_POST['ender']) && isset($_POST['bairro']) && isset($_POST['ponto_referencia']) && isset($_POST['cep']) &&
    isset($_POST['celular']) && isset($_POST['fone_fixo']) && isset($_POST['email']) && isset($_POST['cpf_cnpj']) && isset($_POST['tipo']) ) {

    // receiving the post params
    $id  = $_POST['id'];
    $nome  = $_POST['nome'];
    $ender = $_POST['ender'];
    $bairro = $_POST['bairro'];
    $ponto_referencia = $_POST['ponto_referencia'];
    $cep = $_POST['cep'];
    $celular = $_POST['celular'];
    $fone_fixo = $_POST['fone_fixo'];
    $email = $_POST['email'];
    $cpf_cnpj = $_POST['cpf_cnpj'];
    $tipo = $_POST['tipo'];

    // check if user is already existed with the same email
    if ($db->updateDadosCli($id, $nome, $ender, $bairro, $ponto_referencia, $cep, $celular, $fone_fixo, $email, $cpf_cnpj, $tipo)) {
        // user already existed
        $response["error"] = FALSE;
        $response["error_msg"] = "update de dados do cliente do cliente feita com sucesso!!!" ;
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    } else {

            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Ocorreu um erro ao atualisar dados do cliente";

            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "dados inseridos  estão incorretos! POSTs";
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
?>

