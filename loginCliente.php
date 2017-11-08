<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['email']) && isset($_POST['cpf_cnpj']) && isset($_POST['password'])) {

    // receiving the post params
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpf_cnpj = $_POST['cpf_cnpj'];

    // get the user by email and password
    $user = $db->getUserCliente($email, $password, $cpf_cnpj);

    if ($user != false) {
        // use is found
        $response["error"] = FALSE;
        $response["uid"] = $user["unique_id"];
        $response["user"]["id_cli"] = $user["id_cli"];
        $response["user"]["name"] = $user["nome"];
        $response["user"]["cpf_cnpj"] = $user["cpf_cnpj"];
        $response["user"]["email"] = $user["email"];
        $response["user"]["ender"] = $user["ender"];
        $response["user"]["bairro"] = $user["bairro"];
        $response["user"]["ponto_referencia"] = $user["ponto_referencia"];
        $response["user"]["cep"] = $user["cep"];
        $response["user"]["celular"] = $user["celular"];
        $response["user"]["fone_fixo"] = $user["fone_fixo"];
        $response["user"]["tipo"] = $user["tipo"];
        $response["user"]["email"] = $user["email"];
        $response["user"]["created_at"] = $user["created_at"];
        $response["user"]["updated_at"] = $user["updated_at"];
        echo json_encode($response);
    } else {
        // user is not found with the credentials
        $response["error"] = TRUE;
        $response["error_msg"] = "As credenciais do login Incorretas!";
        echo json_encode($response);
    }
} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Login ou Senha incorretos!";
    echo json_encode($response);
}
?>

