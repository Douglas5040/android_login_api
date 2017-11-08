<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

class DB_Functions {

    private $conn;

    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
        // connecting to database
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }

    // destructor
    function __destruct() {
        
    }

    /**
     * Storing new user
     * returns user details
     */

    //função decodifica caracteres especiais acentuados
    public function array_utf8_encode($dat)
    {
        if (is_string($dat))
            return utf8_encode($dat);
        if (!is_array($dat))
            return $dat;
        $ret = array();
        foreach ($dat as $i => $d)
            $ret[$i] = self::array_utf8_encode($d);
        return $ret;
    }

    public function storeUser($name, $email, $password, $matricula) {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

        $stmt = $this->conn->prepare("INSERT INTO user_func(unique_id, matricula, name, email, encrypted_password, salt, created_at) VALUES(?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssss", $uuid, $matricula, $name, $email, $encrypted_password, $salt);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM user_func WHERE email = ? or matricula = ?");
            $stmt->bind_param("ss", $email, $matricula);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return $user;
        } else {
            return false;
        }
    }

    public function saveToken($id_cli, $token) {


        $stmt = $this->conn->prepare("INSERT INTO token_nofify_cli(id_cli, token) VALUES(?, ?)");
        $stmt->bind_param("ss", $id_cli, $token);
        if($stmt->execute()){
            $stmt->close();
            return true;
        }else{
            $stmt->close();
            return false;
        }

    }

    public function incluirCliUser($name, $email, $password, $cpf_cnpj, $ender,
                                   $ponto_ref, $cep, $bairro, $fone1, $fone2, $tipo) {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

        $stmt = $this->conn->prepare("INSERT INTO clientes(unique_id, nome, email, cpf_cnpj, ender, ponto_referencia, cep, bairro, celular, fone_fixo, tipo, encrypted_password, salt, created_at) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssssssssssss", $uuid, $name, $email, $cpf_cnpj, $ender, $ponto_ref, $cep, $bairro, $fone1, $fone2, $tipo, $encrypted_password, $salt);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM clientes WHERE email = ? or cpf_cnpj = ?");
            $stmt->bind_param("ss", $email, $cpf_cnpj);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return $user;
        } else {
            return false;
        }
    }

    public function addOS($id_cli, $matri_func, $tipo_manu, $obs, $data, $hora_ini, $hora_fin) {
        $stmt = $this->conn->prepare("INSERT INTO ordem_service(id_os, id_cliente, matri_func, tipo_manu, obs, data, hora_ini, hora_fin) VALUES(null, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $id_cli, $matri_func, $tipo_manu, $obs, $data, $hora_ini, $hora_fin);
        if($stmt->execute()){
            $stmt->close();
            $lastId = $this -> getLastIdOS();
            return $lastId;

        }else return false;
    }

    public function addServices($nome, $descri, $tempo) {
        $stmt = $this->conn->prepare("INSERT INTO services(id_service, nome, descri, tempo) VALUES(null, ?, ?, ?)");
        $stmt->bind_param("sss", $nome, $descri, $tempo);
        if($stmt->execute()){
            $stmt->close();
            return null;

        }else return $stmt->error;
    }

    public function addServicePenCli($lati, $longi, $cli, $ender, $comple, $data, $hora,
                                  $descriCliPro, $descriRefri, $status, $tipoManu , $codRefriCli) {
        $stmt = $this->conn->prepare("INSERT INTO serv_pen(id_serv_pen, latitude, longitude, cliente,	ender, complemento, 
                                                                  data_serv, hora_serv, descriCliProblem, descriCliRefrigera, statusServ, tipoManu, codRefriCli ) 
                                             VALUES(null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("ssssssssssss", $lati, $longi, $cli, $ender, $comple, $data, $hora, $descriCliPro, $descriRefri, $status, $tipoManu, $codRefriCli);
        $retorno = array();
        if($stmt->execute()){
            $stmt->close();
            $retorno["erro"] = false;
            $retorno["retorno"] = $this->getLastIdAServicePen();
            return $retorno;

        }else{

            $retorno["erro"] = true;
            $retorno["retorno"] = $stmt->error;
            return $retorno;
        }
    }
    public function addPecs($nome, $modelo, $marca) {
        $uuid = null;
        $stmt = $this->conn->prepare("INSERT INTO pecs(id_pc, nome, modelo, marca) VALUES(null, ?, ?, ?)");
        $stmt->bind_param("sss", $nome, $modelo, $marca);

        if($stmt->execute()){
            $stmt->close();
            return null;

        }else return $stmt->error;
    }

    public function addServicesFuncOS($id_service, $id_os) {

        $stmt = $this->conn->prepare("INSERT INTO services_func(id_service, id_services_os) VALUES(?, ?)");
        $stmt->bind_param("ss", $id_service, $id_os);
        if($stmt->execute()){
            $stmt->close();
            return null;

        }else return $stmt->error;

    }
    public function addPcsProbleOS($id_pc, $id_pcs_os) {


        $stmt = $this->conn->prepare("INSERT INTO pecs_prob_serv_func(id_pc, id_pcs_os) VALUES(?, ?)");
        $stmt->bind_param("ss", $id_pc, $id_pcs_os);
        if($stmt->execute()){
            $stmt->close();
            return null;

        }else return $stmt->error;

    }

    public function addPosiFunc($matriFunc,  $latitude, $longitude, $dataPosi, $horaPosi) {
        $uuid = null;
        $stmt = $this->conn->prepare("INSERT INTO posi_func(cod, matriFunc, latitude, longitude, dataPosi, horaPosi) VALUES(null, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $matriFunc,  $latitude, $longitude, $dataPosi, $horaPosi);

        if($stmt->execute()){
            $stmt->close();
            return null;

        }else return $stmt->error;
    }

    public function addDescriAr($id_cliente, $lotacionamento, $peso, $has_control, $has_exaustor, $saida_ar, $capaci_termica, $tencao_tomada, $has_timer, $tipo_modelo, $marca, $temp_uso, $nivel_econo, $tamanho, $foto1, $foto2, $foto3) {
        $stmt = $this->conn->prepare("INSERT INTO refrigeradores_clientes (id_refri, lotacionamento, id_cliente, peso , has_control, has_exaustor, saida_ar, nivel_econo,tamanho,
                                            capaci_termica, tencao_tomada, has_timer, tipo_modelo, marca, temp_uso, foto1, foto2, foto3)   VALUES(null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssssssssssssssss", $lotacionamento, $id_cliente, $peso, $has_control, $has_exaustor, $saida_ar, $nivel_econo, $tamanho, $capaci_termica, $tencao_tomada, $has_timer, $tipo_modelo, $marca, $temp_uso, $foto1, $foto2, $foto3);

        if ($stmt->execute()) {
            $stmt->close();
            $lastId = $this -> getLastIdAR();
            return $lastId;
        } else {
            $stmt->close();
            return null;
        }
    }


    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPasswordAndMatricula($email, $password, $matricula) {

        $stmt = $this->conn->prepare("SELECT * FROM user_func WHERE email = ? or matricula = ?");

        $stmt->bind_param("ss", $email, $matricula);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $user2 =  $this->array_utf8_encode($user);
            $stmt->close();

            // verifying user password
            $salt = $user2['salt'];
            $encrypted_password = $user2['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $user2;
            }
        } else {
            return NULL;
        }
    }
    public function getUserCliente($email, $password, $cpf_cnpj) {

        $stmt = $this->conn->prepare("SELECT * FROM clientes WHERE email = ? or cpf_cnpj = ?");

        $stmt->bind_param("ss", $email, $cpf_cnpj);

        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $user2 =  $this->array_utf8_encode($user);
            $stmt->close();

            // verifying user password
            $salt = $user2['salt'];
            $encrypted_password = $user2['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $user2;
            }
        } else {
            return NULL;
        }
    }
    public function getLastIdOS() {

        $stmt = $this->conn->prepare("SELECT * FROM ordem_service ");

        if ($stmt->execute()) {
            $lastId = $stmt->get_result()->fetch_all(3);
            $stmt->close();
            return $lastId[count($lastId)-1]["id_os"];
        } else {
            return NULL;
        }
    }
    public function getLastIdAR() {

        $stmt = $this->conn->prepare("SELECT * FROM refrigeradores_clientes ");

        if ($stmt->execute()) {
            $lastId = $stmt->get_result()->fetch_all(3);
            $stmt->close();
            return $lastId[count($lastId)-1]["id_refri"];
        } else {
            return NULL;
        }
    }

    public function getLastIdAServicePen() {

        $stmt = $this->conn->prepare("SELECT * FROM serv_pen ");

        if ($stmt->execute()) {
            $lastId = $stmt->get_result()->fetch_all(3);
            $stmt->close();
            return $lastId[count($lastId)-1]["id_serv_pen"];
        } else {
            return NULL;
        }
    }

    public function getServices() {

        $stmt = $this->conn->prepare("SELECT * FROM services ");

        if ($stmt->execute()) {
            $services = $stmt->get_result()->fetch_all(3);
            $services2 = $this->array_utf8_encode($services);
            $stmt->close();
            return $services2;
        } else {
            echo json_last_error_msg();
            return NULL;
        }
    }

    public function getPecs() {

        $stmt = $this->conn->prepare("SELECT * FROM pecs ");

        if ($stmt->execute()) {
            $pecs = $stmt->get_result()->fetch_all(3);
            $pecs2 = $this->array_utf8_encode($pecs);
            $stmt->close();
            return $pecs2;
        } else {
            echo json_last_error_msg();
            return NULL;
        }
    }

    public function getMarcas() {

        $stmt = $this->conn->prepare("SELECT * FROM marcas_ar ");

        if ($stmt->execute()) {
            $marcas_ar = $stmt->get_result()->fetch_all(3);
            $marcas_ar2 = $this->array_utf8_encode($marcas_ar);
            $stmt->close();
            return $marcas_ar2;
        } else {
            echo json_last_error_msg();
            return NULL;
        }
    }

    public function getModelos() {

        $stmt = $this->conn->prepare("SELECT * FROM modelos_ar ");

        if ($stmt->execute()) {
            $modelos_ar = $stmt->get_result()->fetch_all(3);
            $modelos_ar2 = $this->array_utf8_encode($modelos_ar);
            $stmt->close();
            return $modelos_ar2;
        } else {
            echo json_last_error_msg();
            return NULL;
        }
    }

    public function getTencaoTomada() {

        $stmt = $this->conn->prepare("SELECT * FROM tencao_tomada_ar ");

        if ($stmt->execute()) {
            $tencao_tomada_ar = $stmt->get_result()->fetch_all(3);
            $tencao_tomada_ar2 = $this->array_utf8_encode($tencao_tomada_ar);
            $stmt->close();
            return $tencao_tomada_ar2;
        } else {
            echo json_last_error_msg();
            return NULL;
        }
    }

    public function getNvEcon() {

        $stmt = $this->conn->prepare("SELECT * FROM nivel_econo_ar ");

        if ($stmt->execute()) {
            $nivel_econo_ar = $stmt->get_result()->fetch_all(3);
            $nivel_econo_ar2 = $this->array_utf8_encode($nivel_econo_ar);
            $stmt->close();
            return $nivel_econo_ar2;
        } else {
            echo json_last_error_msg();
            return NULL;
        }
    }

    public function getBTU() {

        $stmt = $this->conn->prepare("SELECT * FROM capaci_termica_ar ");

        if ($stmt->execute()) {
            $capaci_termica_ar = $stmt->get_result()->fetch_all(3);
            $capaci_termica_ar2 = $this->array_utf8_encode($capaci_termica_ar);
            $stmt->close();
            return $capaci_termica_ar2;
        } else {
            echo json_last_error_msg();
            return NULL;
        }
    }


    //Função consulta dados de serviços pendentes
    public function getServPen($status, $matriFunc) {
        if($matriFunc == null) {
            $stmt = $this->conn->prepare("SELECT id_serv_pen, cliente, complemento, serv_pen.ender, cep, lotacionamento, statusServ,latitude, longitude,
                                            date_format(data_serv,'%d/%m/%Y') as data_serv, hora_serv,descriCliProblem, descriTecniProblem, descriCliRefrigera,
                                             nome, tipo, celular, fone_fixo, codRefriCli FROM serv_pen,clientes where id_cli=cliente AND statusServ LIKE ? ORDER BY `serv_pen`.`data_serv` ASC");
            $stmt->bind_param("s", $status);
        }else{
            $stmt = $this->conn->prepare("SELECT id_serv_pen, cliente, complemento, serv_pen.ender, cep, lotacionamento, statusServ,latitude, longitude,
                                            date_format(data_serv,'%d/%m/%Y') as data_serv, hora_serv,descriCliProblem, descriTecniProblem, descriCliRefrigera,
                                             nome, tipo, celular, fone_fixo, codRefriCli FROM serv_pen,clientes where id_cli=cliente AND statusServ LIKE ? AND MatriFuncTec LIKE ? ORDER BY `serv_pen`.`data_serv` ASC");
            $stmt->bind_param("ss", $status,$matriFunc);
        }
        if($stmt->execute()) {

            $user = $stmt->get_result()->fetch_all(3);
            //echo json_encode($stmt->get_result()->fetch_array());
            //var_dump($user);
            $user2 = $this->array_utf8_encode($user);
            $stmt->close();

            //echo json_encode($user2, JSON_UNESCAPED_UNICODE);
            return $user2;
        } else {
            echo json_last_error_msg();
            return null;
        }

    }

    //Função consulta dados de serviços pendentes
    public function getServPenCli($status, $cliente) {
        if($status == null) {
            $stmt = $this->conn->prepare("SELECT id_serv_pen, cliente, complemento, serv_pen.ender, cep, lotacionamento, statusServ,latitude, longitude,
                                            date_format(data_serv,'%d/%m/%Y') as data_serv, hora_serv,descriCliProblem, descriTecniProblem, descriCliRefrigera,
                                             nome, tipo, celular, fone_fixo, codRefriCli FROM serv_pen,clientes where id_cli=cliente AND cliente LIKE ? ORDER BY `serv_pen`.`data_serv` ASC");
            $stmt->bind_param("s", $cliente);
        }else{
            $stmt = $this->conn->prepare("SELECT id_serv_pen, cliente, complemento, serv_pen.ender, cep, lotacionamento, statusServ,latitude, longitude,
                                            date_format(data_serv,'%d/%m/%Y') as data_serv, hora_serv,descriCliProblem, descriTecniProblem, descriCliRefrigera,
                                             nome, tipo, celular, fone_fixo, codRefriCli FROM serv_pen,clientes where id_cli=cliente AND statusServ LIKE ? AND cliente LIKE ? ORDER BY `serv_pen`.`data_serv` ASC");
            $stmt->bind_param("ss", $status,$cliente);
        }
        if($stmt->execute()) {

            $user = $stmt->get_result()->fetch_all(3);
            //echo json_encode($stmt->get_result()->fetch_array());
            //var_dump($user);
            $user2 = $this->array_utf8_encode($user);
            $stmt->close();

            //echo json_encode($user2, JSON_UNESCAPED_UNICODE);
            return $user2;
        } else {
            echo json_last_error_msg();
            return null;
        }

    }

    //Função consulta dados de serviços pendentes
    public function getServAndRefri($id_refri) {

            $stmt = $this->conn->prepare("SELECT id_serv_pen, cliente, statusServ, date_format(data_serv,'%d/%m/%Y') as data_serv, hora_serv,
                                                  codRefriCli, peso, has_control, has_exaustor, has_timer, saida_ar, capaci_termica, tencao_tomada, tipo_modelo,
                                                  marca, temp_uso, nivel_econo, tamanho, foto1, foto2, foto3, refrigeradores_clientes.lotacionamento, statusServ
                                                FROM serv_pen,refrigeradores_clientes,clientes where codRefriCli = refrigeradores_clientes.id_refri 
                                                AND id_refri = ? AND id_cliente=clientes.id_cli AND id_cliente=cliente ORDER BY `serv_pen`.`data_serv` ASC ");
            $stmt->bind_param("s", $id_refri);

        if($stmt->execute()) {

            $servRefri = $stmt->get_result()->fetch_all(3);
            //echo json_encode($stmt->get_result()->fetch_array());
            //var_dump($user);
            $servRefri2 = $this->array_utf8_encode($servRefri);
            $stmt->close();

            //echo json_encode($user2, JSON_UNESCAPED_UNICODE);
            return $servRefri2;
        } else {
            echo json_last_error_msg();
            return null;
        }

    }

    //Função consulta dados de serviços pendentes
    public function getArCli($id_refri) {
        $stmt = $this->conn->prepare("SELECT * FROM refrigeradores_clientes where id_refri = ?");
        $stmt->bind_param("s", $id_refri);

        if($stmt->execute()) {

            $arCli = $stmt->get_result()->fetch_all(3);
            //echo json_encode($stmt->get_result()->fetch_array());
            //var_dump($user);
            $arCli2 = $this->array_utf8_encode($arCli);
            $stmt->close();

            //echo json_encode($user2, JSON_UNESCAPED_UNICODE);
            return $arCli2;
        } else {
            echo json_last_error_msg();
            return null;
        }

    }

    //Função consulta dados de serviços pendentes
    public function getArAllCli($id_cli, $status) {
        $stmt = $this->conn->prepare("SELECT * FROM refrigeradores_clientes where id_cliente = ? AND status = ?");
        $stmt->bind_param("ss", $id_cli, $status);

        if($stmt->execute()) {

            $arCli = $stmt->get_result()->fetch_all(3);
            //echo json_encode($stmt->get_result()->fetch_array());
            //var_dump($user);
            $arCli2 = $this->array_utf8_encode($arCli);
            $stmt->close();

            //echo json_encode($user2, JSON_UNESCAPED_UNICODE);
            return $arCli2;
        } else {
            echo json_last_error_msg();
            return null;
        }

    }

    //Função consulta dados de serviços pendentes
    public function getAllAr() {
        $stmt = $this->conn->prepare("SELECT * FROM refrigeradores_clientes ");

        if($stmt->execute()) {

            $arCli = $stmt->get_result()->fetch_all(3);
            //echo json_encode($stmt->get_result()->fetch_array());
            //var_dump($user);
            $arCli2 = $this->array_utf8_encode($arCli);
            $stmt->close();

            //echo json_encode($user2, JSON_UNESCAPED_UNICODE);
            return $arCli2;
        } else {
            echo json_last_error_msg();
            return null;
        }

    }
    /**
     * Check user is existed or not
     */
    public function isUserExisted($email, $matricula) {
        $stmt = $this->conn->prepare("SELECT email from user_func WHERE email = ? or matricula = ?");

        $stmt->bind_param("ss", $email, $matricula);

        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // user existed 
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }
    public function isUserCliExist($email, $cpf_cnpj) {
        $stmt = $this->conn->prepare("SELECT id_cli from clientes WHERE email = ? or cpf_cnpj = ?");

        $stmt->bind_param("ss", $email, $cpf_cnpj);

        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // user existed
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }

    public function updateDescriAr($id_ar, $lotacionamento, $peso, $has_control, $has_exaustor, $saida_ar, $capaci_termica, $tencao_tomada, $has_timer, $tipo_modelo, $marca, $temp_uso, $nivel_econo, $tamanho, $foto1, $foto2, $foto3) {
        $stmt = $this->conn->prepare("UPDATE refrigeradores_clientes SET peso = ?, lotacionamento = ?, has_control = ?, has_exaustor = ?, saida_ar = ?, nivel_econo = ?,tamanho = ?,
                                            capaci_termica = ?, tencao_tomada = ?, has_timer = ?, tipo_modelo = ?, marca = ?, temp_uso = ?, foto1 = ?, foto2 = ?, foto3 = ?  WHERE id_refri LIKE ?");

        $stmt->bind_param("sssssssssssssssss", $peso, $lotacionamento, $has_control, $has_exaustor, $saida_ar, $nivel_econo, $tamanho, $capaci_termica, $tencao_tomada, $has_timer, $tipo_modelo, $marca, $temp_uso, $foto1, $foto2, $foto3, $id_ar);

        $stmt->execute();

        if ($stmt->store_result()) {
            // user existed
            json_encode($stmt->get_result());
            $stmt->close();
            return true;
        } else {
            // user not existed
            json_encode($stmt->get_result());
            $stmt->close();
            return false;
        }
    }

    public function updateDadosCli($id, $nome, $ender, $bairro, $ponto_referencia, $cep, $celular, $fone_fixo, $email, $cpf_cnpj, $tipo) {
        $stmt = $this->conn->prepare("UPDATE clientes SET nome = ?, ender = ?, bairro = ?, ponto_referencia = ?, cep = ?,
                                            celular = ?, fone_fixo = ?, email = ?, cpf_cnpj = ?, tipo = ? WHERE id_cli = ?");

        $stmt->bind_param("sssssssssss", $nome, $ender, $bairro, $ponto_referencia, $cep, $celular, $fone_fixo, $email, $cpf_cnpj, $tipo, $id);

        $stmt->execute();

        if ($stmt->store_result()) {
            // user existed
            json_encode($stmt->get_result());
            $stmt->close();
            return true;
        } else {
            // user not existed
            json_encode($stmt->get_result());
            $stmt->close();
            return false;
        }
    }

    public function updateStatusServ($idServ, $newStatus) {
        $stmt = $this->conn->prepare("UPDATE serv_pen SET statusServ = ?  WHERE id_serv_pen LIKE ?");

        $stmt->bind_param("ss",$newStatus, $idServ);

        $stmt->execute();

        if ($stmt->store_result()) {
            // user existed
            json_encode($stmt->get_result());
            $stmt->close();
            return true;
        } else {
            // user not existed
            json_encode($stmt->get_result());
            $stmt->close();
            return false;
        }
    }

    public function updateStatusRefri($idRefri, $newStatus) {
        $stmt = $this->conn->prepare("UPDATE refrigeradores_clientes SET status = ?  WHERE id_refri LIKE ?");

        $stmt->bind_param("ss",$newStatus, $idRefri);

        $stmt->execute();

        if ($stmt->store_result()) {
            // user existed
            json_encode($stmt->get_result());
            $stmt->close();
            return true;
        } else {
            // user not existed
            json_encode($stmt->get_result());
            $stmt->close();
            return false;
        }
    }
    public function updateMatriFunc($idServ, $newMatriFunc) {
        $stmt = $this->conn->prepare("UPDATE serv_pen SET MatriFuncTec = ?  WHERE id_serv_pen LIKE ?");

        $stmt->bind_param("ss",$newMatriFunc, $idServ);

        $stmt->execute();

        if ($stmt->store_result()) {
            // user existed
            json_encode($stmt->get_result());
            $stmt->close();
            return true;
        } else {
            // user not existed
            json_encode($stmt->get_result());
            $stmt->close();
            return false;
        }
    }
    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {

        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {

        $hash = base64_encode(sha1($password . $salt, true) . $salt);

        return $hash;
    }

}

?>
