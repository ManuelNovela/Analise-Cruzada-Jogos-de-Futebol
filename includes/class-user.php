<?php

class User
{
    public $_logged_in = false;
    public $_is_admin = false;
    public $_is_client = false;

    private $_cookie_user_id = "c_user";
    private $_cookie_user_token = "xs";
    private $_cookie_user_referrer = "ref";

    public function __construct()
    {
        global $is_sudosu;
        if($is_sudosu) {
            $this->sudosu();
        }else{
            if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {
                $acessado = isset($_SESSION['acessado'])? $_SESSION['acessado'] : false;
                if($acessado){
                    $this->_is_admin = true;
                    $this->_is_client = false;
                }else{
                    $this->_is_admin = ($_SESSION['user_group'] == 1) ? true : false;
                    $this->_is_client = ($_SESSION['user_group'] == 2) ? true : false;
                }
                $this->_logged_in = true;

            } else {
                $this->logout();
            }
        }

    }
    public function logout(){
        session_destroy();
        $_SESSION['loggedIn'] = false;
        $_SESSION['email'] = '';
        $_SESSION['user_id'] = 0;
        $_SESSION['user_group'] = 0;
        $this->_logged_in = false;
        $this->_is_admin = false;
        $this->_is_client = false;
    }
    public function login($email,$password){
        global $conn, $system;
        if (isset($email) && isset($password)) {
            //if(isset($_COOKIE[$this->_cookie_user_id]) && isset($_COOKIE[$this->_cookie_user_token])) {
            $email = $conn->real_escape_string($email);
            $password = $conn->real_escape_string($password);
            //var_dump(password_hash($password, PASSWORD_BCRYPT));

            $sql = $conn->query("SELECT * FROM usuario WHERE email=".secure($email));
            if ($sql->num_rows >  0){
                $data = $sql->fetch_assoc();
                $passwordHash = $data['password'];
                if (password_verify($password, $passwordHash)) {

                    $this->_logged_in = true;
                    $this->_is_admin = ($data['user_group'] == 1)? true: false;
                    $this->_is_client = ($data['user_group'] == 2)? true: false;

                    session_start();
                    $_SESSION['loggedIn'] = true;
                    $_SESSION['email'] = $data['email'];
                    $_SESSION['user_id'] = $data['id'];
                    $_SESSION['user_group'] = $data['user_group'];
                    $_SESSION['servidor_ip'] = $data['servidor_ip'];
                    $_SESSION['instancia'] = $data['instancia'];

                    setcookie('welcome', 'sim', time() + (86400 * 30), "/");
                    return 1;
                }
            }
        }
    }
    public function signin($email,$password,$nome){
        global $conn, $system;
        if (isset($email) && isset($password)) {
            $email = $conn->real_escape_string($email);
            $password = $conn->real_escape_string($password);
            $nome = $conn->real_escape_string($nome);
            $instancia = password_hash($nome, PASSWORD_BCRYPT);

            if(strlen($instancia) > 16){
                $instancia = substr($nome,0,3) .'_'. substr($instancia,10,12);
            }
            $ePassword = password_hash($password, PASSWORD_BCRYPT);

		var_dump(sprintf("INSERT INTO `usuario`(`email`, `password`, `user_group`, `nome`, `instancia`, `ultimo`) VALUES (%s,%s,2,%s,%s,NOW() )", secure($email), secure($ePassword), secure($nome), secure($instancia) ));
            $sql = $conn->query(sprintf("INSERT INTO `usuario`(`email`, `password`, `user_group`, `nome`, `instancia`, `ultimo`) VALUES (%s,%s,2,%s,%s,NOW() )", secure($email), secure($ePassword), secure($nome), secure($instancia) )) or _error("SQL_ERROR_THROWEN");
            return true;
        }
    }
    public function change_password($password){
        global $conn;
        $ePassword = password_hash($password, PASSWORD_BCRYPT);
        $conn->query(sprintf( "UPDATE `usuario` SET `password`= %s WHERE `id`= %s", secure($ePassword),secure($_SESSION['user_id'], 'int') )) or _error("SQL_ERROR_THROWEN");
        return true;
    }
    public function getDados(){
        global $conn, $system;
        $sql = $conn->query("SELECT * FROM usuario WHERE id=".$_SESSION['user_id'].";");

        while ($data = $sql->fetch_assoc()){
            $data['id'] = $data['id'];
            $data['email'] = $data['email'];
            $data['password'] = $data['password'];
            $data['user_group'] = $data['user_group'];
            $data['nome'] = $data['nome'];
            $data['instancia'] = $data['instancia'];
            $data['servidor_ip'] = $data['servidor_ip'];
            $data['ultimo'] = $data['ultimo'];
            $data['foto'] = $data['foto'];
            return $data;
        }
        return null;
    }
    public function getDadosId($id){
        global $conn, $system;
        $sql = $conn->query("SELECT * FROM usuario WHERE id=".$id.";");

        while ($data = $sql->fetch_assoc()){
            $data['id'] = $data['id'];
            $data['email'] = $data['email'];
            $data['password'] = $data['password'];
            $data['user_group'] = $data['user_group'];
            $data['nome'] = $data['nome'];
            $data['instancia'] = $data['instancia'];
            $data['servidor_ip'] = $data['servidor_ip'];
            $data['limite_envio'] = $data['limite_envio'];
            $data['ultimo'] = $data['ultimo'];
            return $data;
        }
        return null;
    }
    public function getUltimo($id){
        global $conn, $system;
        $sql = $conn->query("SELECT ultimo FROM usuario WHERE id=".$id.";");
        while ($data = $sql->fetch_assoc()){
            return $data['ultimo'];
        }
        return '2022-02-13 06:30:33';
    }
    public function setUltimo($id){
        global $conn, $system;
        $sql = $conn->query("UPDATE usuario SET ultimo = NOW()  WHERE id=".$id.";");
        return true;
    }


    public function sudosu(){
        session_start();
        $this->_logged_in = true;
        $this->_is_admin =  true;
        $_SESSION['loggedIn'] = true;
        $_SESSION['email'] = 'the king in the north';
        $_SESSION['user_id'] = 1;
        $_SESSION['user_group'] = 1;
        $_SESSION['servidor_ip'] = 'localhost';
        $_SESSION['instancia'] = 'this . name';
        return true;
    }
    public function acessar($id){
        global $conn, $system;

        $sql = $conn->query("SELECT * FROM usuario WHERE id=".secure($id, 'int'));
        if ($sql->num_rows >  0){

            $data = $sql->fetch_assoc();

            $this->_logged_in = true;
            $this->_is_admin = true;
            $this->_is_client = false;

            session_start();
            $_SESSION['loggedIn'] = true;
            $_SESSION['email'] = $data['email'];
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['user_group'] = $data['user_group'];
            $_SESSION['servidor_ip'] = $data['servidor_ip'];
            $_SESSION['instancia'] = $data['instancia'];
            $_SESSION['acessado'] = true;


            setcookie('welcome', 'sim', time() + (86400 * 30), "/");
            return 1;

        }
    }
}
?>