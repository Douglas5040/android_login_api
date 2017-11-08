<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) &&
    isset($_POST['cpf_cnpj']) && isset($_POST['ender']) && isset($_POST['ponto_ref']) &&
    isset($_POST['cep']) && isset($_POST['bairro']) && isset($_POST['fone1']) &&
    isset($_POST['fone2']) && isset($_POST['tipo'])) {

    // receiving the post params
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpf_cnpj = $_POST['cpf_cnpj'];
    $ender = $_POST['ender'];
    $ponto_ref = $_POST['ponto_ref'];
    $cep = $_POST['cep'];
    $bairro = $_POST['bairro'];
    $fone1 = $_POST['fone1'];
    $fone2 = $_POST['fone2'];
    $tipo = $_POST['tipo'];

    // check if user is already existed with the same email
    if ($db->isUserCliExist($email, $cpf_cnpj)) {
        // user already existed
        $response["error"] = TRUE;
        $response["error_msg"] = "Usuario ja existente " . $email . " - ". $cpf_cnpj;
        echo json_encode($response);
    } else {
        // create a new user
        $user = $db->incluirCliUser($name, $email, $password, $cpf_cnpj, $ender,
                                    $ponto_ref, $cep, $bairro, $fone1, $fone2, $tipo);
        if ($user) {
            // user stored successfully
            $response["error"] = FALSE;
            $response["uid"] = $user["unique_id"];
            $response["user"]["id_cli"] = $user["id_cli"];
            $response["user"]["name"] = $user["nome"];
            $response["user"]["cpf_cnpj"] = $user["cpf_cnpj"];
            $response["user"]["email"] = $user["email"];
            $response["user"]["tipo"] = $user["tipo"];
            $response["user"]["ender"] = $user["ender"];
            $response["user"]["bairro"] = $user["bairro"];
            $response["user"]["created_at"] = $user["created_at"];
            echo json_encode($response);
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Ocorreu um erro ao realizar registro";
            echo json_encode($response);
        }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "dados inseridos (name, email or password) estão incorretos!";
    echo json_encode($response);
}
?>

