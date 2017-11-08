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
    $marcas = $db->getMarcas();
    if ($marcas != null) {
        //fazer laço de repetição para preencher todos os dados no array Response com os dados do vetor User
        // use is found

        for ($i = 0, $size = count($marcas); $i < $size; $i++) {
            //echo "nome: $e->nome - idade: $e->idade - sexo: $e->sexo<br>";
            $response[$i]["id_marca"] = $marcas[$i]["id_marca"];
            $response[$i]["marca"] = $marcas[$i]["marca"];
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

