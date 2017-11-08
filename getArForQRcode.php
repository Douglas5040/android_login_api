<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

	// json response array
	$response = array();
if (isset($_POST['id_refri'])) {

// receiving the post params
    $id_refri = $_POST['id_refri'];

    // get the user by email and password
    $serv = $db->getServAndRefri(trim($id_refri));
    if ($serv != false) {
        //fazer laço de repetição para preencher todos os dados no array Response com os dados do vetor User
        // use is found

        for ($i = 0, $size = count($serv); $i < $size; $i++) {
            //echo "nome: $e->nome - idade: $e->idade - sexo: $e->sexo<br>";
            $response[$i]["id_serv_pen"] = $serv[$i]["id_serv_pen"];
            $response[$i]["cliente"] = $serv[$i]["cliente"];
            $response[$i]["data_serv"] = $serv[$i]["data_serv"];
            $response[$i]["hora_serv"] = $serv[$i]["hora_serv"];
            $response[$i]["statusServ"] = $serv[$i]["statusServ"];
            $response[$i]["codRefriCli"] = $serv[$i]["codRefriCli"];
            $response[$i]["peso"] = $serv[$i]["peso"];
            $response[$i]["has_control"] = $serv[$i]["has_control"];
            $response[$i]["has_exaustor"] = $serv[$i]["has_exaustor"];
            $response[$i]["has_timer"] = $serv[$i]["has_timer"];
            $response[$i]["saida_ar"] = $serv[$i]["saida_ar"];
            $response[$i]["nivel_econo"] = $serv[$i]["nivel_econo"];
            $response[$i]["tamanho"] = $serv[$i]["tamanho"];
            $response[$i]["capaci_termica"] = $serv[$i]["capaci_termica"];
            $response[$i]["tencao_tomada"] = $serv[$i]["tencao_tomada"];
            $response[$i]["tipo_modelo"] = $serv[$i]["tipo_modelo"];
            $response[$i]["marca"] = $serv[$i]["marca"];
            $response[$i]["temp_uso"] = $serv[$i]["temp_uso"];
            $response[$i]["foto1"] = $serv[$i]["foto1"];
            $response[$i]["foto2"] = $serv[$i]["foto2"];
            $response[$i]["foto3"] = $serv[$i]["foto3"];
            $response[$i]["lotacionamento"] = $serv[$i]["lotacionamento"];
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

