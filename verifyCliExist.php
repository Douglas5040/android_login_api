<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['email']) && isset($_POST['cpf_cnpj'])) {

    // receiving the post params
    $cpf_cnpj = $_POST['cpf_cnpj'];
    $email = $_POST['email'];

    // check if user is already existed with the same email
    if ($db->isUserCliExist($email, $cpf_cnpj)) {
        // user already existed
        $response["error"] = TRUE;
        $response["error_msg"] = "Usuario ja existente " . $email;
        echo json_encode($response);
    } else {
        // create a new user
        $response["error"] = FALSE;
        $response["error_msg"] = "Usuario not existente " . $email;
        echo json_encode($response);
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "dados inseridos (email or cpf_cnpj) estão incorretos!";
    echo json_encode($response);
}
?>

