<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['id_serv']) && isset($_POST['newStatus'])) {

    // receiving the post params
    $id_serv = $_POST['id_serv'];
    $new_serv = $_POST['newStatus'];

    // check if user is already existed with the same email
    if ($db->updateStatusServ($id_serv, $new_serv)) {
        // user already existed
        $response["error"] = FALSE;
        $response["error_msg"] = "Status do Service Atualisado" ;
        echo json_encode($response);
    } else {

            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Ocorreu um erro ao atualisar service";

            echo json_encode($response);
        }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "dados inseridos (id_serv e newStatus) estão incorretos! POSTs";
    echo json_encode($response);
}
?>

