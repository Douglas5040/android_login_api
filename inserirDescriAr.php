<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['id_cliente']) && isset($_POST['lotacionamento']) && isset($_POST['saida_ar']) && isset($_POST['has_exaustor']) && isset($_POST['has_control']) && isset($_POST['has_timer']) && isset($_POST['marca']) && isset($_POST['tipo_modelo']) && isset($_POST['foto1'])
    && isset($_POST['capaci_termica']) && isset($_POST['tamanho']) && isset($_POST['temp_uso']) && isset($_POST['peso']) && isset($_POST['tencao_tomada']) && isset($_POST['nivel_econo']) && isset($_POST['foto2']) && isset($_POST['foto3'])) {

    // receiving the post params
    $id_cliente = $_POST['id_cliente'];
    $peso = $_POST['peso'];
    $has_control = $_POST['has_control'];
    $has_exaustor = $_POST['has_exaustor'];
    $saida_ar = $_POST['saida_ar'];
    $capaci_termica = $_POST['capaci_termica'];
    $tencao_tomada = $_POST['tencao_tomada'];
    $has_timer = $_POST['has_timer'];
    $tipo_modelo = $_POST['tipo_modelo'];
    $marca = $_POST['marca'];
    $temp_uso = $_POST['temp_uso'];
    $nivel_econo = $_POST['nivel_econo'];
    $tamanho = $_POST['tamanho'];
    $lotacionamento = $_POST['lotacionamento'];
    $foto1 = $_POST['foto1'];
    $foto2 = $_POST['foto2'];
    $foto3 = $_POST['foto3'];

    // check if user is already existed with the same email
    $lastId = $db->addDescriAr($id_cliente, $lotacionamento, $peso, $has_control, $has_exaustor, $saida_ar, $capaci_termica, $tencao_tomada, $has_timer, $tipo_modelo, $marca, $temp_uso, $nivel_econo, $tamanho, $foto1, $foto2, $foto3);
    if ($lastId != null) {
        // user already existed
        $response["error"] = FALSE;
        $response["error_msg"] = "insert de dados do ar condicionado do cliente feita com sucesso!!!" ;
        $response["last_id_ar"] = $lastId;
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Ocorreu um erro ao inserir dados do AR";

            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "dados inseridos  estão incorretos! POSTs";
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
?>

