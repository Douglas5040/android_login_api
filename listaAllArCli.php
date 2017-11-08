<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

	// json response array
	$response = array();
if (isset($_POST['id_cli']) && isset($_POST['status'])) {

// receiving the post params
    $idCli = $_POST['id_cli'];
    $status = $_POST['status'];

    // get the user by email and password
    $arCli = $db->getArAllCli($idCli, $status);
    if ($arCli != null) {
            //echo "nome: $e->nome - idade: $e->idade - sexo: $e->sexo<br>";
        for ($i = 0, $size = count($arCli); $i < $size; $i++) {
            $response[$i]["id_refri"] = $arCli[$i]["id_refri"];
            $response[$i]["peso"] = $arCli[$i]["peso"];
            $response[$i]["has_control"] = $arCli[$i]["has_control"];
            $response[$i]["has_exaustor"] = $arCli[$i]["has_exaustor"];
            $response[$i]["saida_ar"] = $arCli[$i]["saida_ar"];
            $response[$i]["nivel_econo"] = $arCli[$i]["nivel_econo"];
            $response[$i]["tamanho"] = $arCli[$i]["tamanho"];
            $response[$i]["lotacionamento"] = $arCli[$i]["lotacionamento"];
            $response[$i]["capaci_termica"] = $arCli[$i]["capaci_termica"];
            $response[$i]["tencao_tomada"] = $arCli[$i]["tencao_tomada"];
            $response[$i]["has_timer"] = $arCli[$i]["has_timer"];
            $response[$i]["tipo_modelo"] = $arCli[$i]["tipo_modelo"];
            $response[$i]["marca"] = $arCli[$i]["marca"];
            $response[$i]["temp_uso"] = $arCli[$i]["temp_uso"];
            $response[$i]["foto1"] = $arCli[$i]["foto1"];
            $response[$i]["foto2"] = $arCli[$i]["foto2"];
            $response[$i]["foto3"] = $arCli[$i]["foto3"];
            $response[$i]["id_cliente"] = $arCli[$i]["id_cliente"];
        }
        $array = $response;
        $respons["error"] = FALSE;
        $respons["data"] = $array;
        echo json_encode($respons, JSON_UNESCAPED_UNICODE);
        //echo json_encode($user[0]['descriCliProblem']);
    } else {
        // user is not found with the credentials
        $response["error"] = TRUE;
        $response["error_list"] = "Ar condicionado não encontrado!";
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Parametros Incorretos!";
    echo json_encode($response);
}
?>

