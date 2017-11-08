<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

	// json response array
	$response = array();

// receiving the post params

    // get the user by email and password
    $nvEcon= $db->getNvEcon();
    if ($nvEcon != null) {
        //fazer laço de repetição para preencher todos os dados no array Response com os dados do vetor User
        // use is found

        for ($i = 0, $size = count($nvEcon); $i < $size; $i++) {
            //echo "nome: $e->nome - idade: $e->idade - sexo: $e->sexo<br>";
            $response[$i]["id_nv_econ"] = $nvEcon[$i]["id_nv_econ"];
            $response[$i]["nivel_econo"] = $nvEcon[$i]["nivel_econo"];
        }
        $array = $response;
        $respons["error"] = FALSE;
        $respons["data"] = $array;
        echo json_encode($respons, JSON_UNESCAPED_UNICODE);
        //echo json_encode($services[0]['descriCliProblem']);
        // user is not found with the credentials

}else {
// required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Parametro do Status Incorreto!";
    echo json_encode($response);
}
?>

