<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

	// json response array
	$response = array();
if (isset($_POST['status']) && isset($_POST['matriFunc'])) {

// receiving the post params
    $status = $_POST['status'];
    $matriFunc = $_POST['matriFunc'];

    // get the user by email and password
    $serv = $db->getServPen(trim($status),trim($matriFunc));
    if ($serv != false) {
        //fazer laço de repetição para preencher todos os dados no array Response com os dados do vetor User
        // use is found

        for ($i = 0, $size = count($serv); $i < $size; $i++) {
            //echo "nome: $e->nome - idade: $e->idade - sexo: $e->sexo<br>";
            $response[$i]["uid"] = $serv[$i]["id_serv_pen"];
            $response[$i]["latitude"] = $serv[$i]["latitude"];
            $response[$i]["longitude"] = $serv[$i]["longitude"];
            $response[$i]["cliente"] = $serv[$i]["cliente"];
            $response[$i]["lotacionamento"] = $serv[$i]["lotacionamento"];
            $response[$i]["ender"] = $serv[$i]["ender"];
            $response[$i]["complemento"] = $serv[$i]["complemento"];
            $response[$i]["cep"] = $serv[$i]["cep"];
            $response[$i]["data_serv"] = $serv[$i]["data_serv"];
            $response[$i]["hora_serv"] = $serv[$i]["hora_serv"];
            $response[$i]["descriCliProblem"] = $serv[$i]["descriCliProblem"];
            $response[$i]["descriTecniProblem"] = $serv[$i]["descriTecniProblem"];
            $response[$i]["descriCliRefrigera"] = $serv[$i]["descriCliRefrigera"];
            $response[$i]["statusServ"] = $serv[$i]["statusServ"];
            $response[$i]["nome"] = $serv[$i]["nome"];
            $response[$i]["tipo"] = $serv[$i]["tipo"];
            $response[$i]["fone1"] = $serv[$i]["celular"];
            $response[$i]["fone2"] = $serv[$i]["fone_fixo"];
            $response[$i]["id_refriCli"] = $serv[$i]["codRefriCli"];
        }
        $array = $response;
        $respons["error"] = FALSE;
        $respons["data"] = $array;
        echo json_encode($respons, JSON_UNESCAPED_UNICODE);
        //echo json_encode($serv[0]['descriCliProblem']);
    } else {
        // user is not found with the credentials
        $response["error"] = TRUE;
        $response["error_list"] = "Lista Vazia!";
        echo json_encode($response);
    }
}else {
// required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Parametro do Status Incorreto!";
    echo json_encode($response);
}
?>

