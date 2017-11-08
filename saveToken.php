<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['token'])) {

    // receiving the post params
    $token = $_POST['token'];


        // create a new user
        $retorno = $db->saveToken($token);
        if ($retorno) {
            // user stored successfully
            $response["error"] = FALSE;
            $response["error_msg"] = "Token inserido com sucesso!!!";

            echo json_encode($response);
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Ocorreu um erro ao realizar registro";
            echo json_encode($response);
        }

} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "dados infalidos (token) estão incorretos!";
    echo json_encode($response);
}
?>

