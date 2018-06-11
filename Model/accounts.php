<?php

require_once('./Entity/account.php');
require_once('./Model/db_connect.php');

class accounts
{
    private $db;

    public function __construct()
    {
        $this->db = new db_connect();
    }

    public function checkCreateAccount($login, $email){
        $array = array();

        $query = "SELECT COUNT(id) AS num FROM accounts WHERE login='".$login."';";
        $query = $this->db->getQuery($query);
        $result = $query->fetch(PDO::FETCH_BOTH);
        if($result['num'] == 1){
            $array['login'] = 0;
        }
        else{
            $array['login'] = 1;
        }

        $query = "SELECT COUNT(id) AS num FROM accounts WHERE email='".$email."';";
        $query = $this->db->getQuery($query);
        $result = $query->fetch(PDO::FETCH_BOTH);
        if($result['num'] == 1){
            $array['email'] = 0;
        }
        else{
            $array['email'] = 1;
        }

        return $array;
    }


    public function createAccount($login, $email, $password, $confirmPassword, $termsAccept){
        if(empty($login) OR empty($email) OR empty($password) OR empty($confirmPassword))
            return 0;
        if($termsAccept != 1)
            return 0;
        if($password != $confirmPassword)
            return 0;

        $resultCheckAccount = $this->checkCreateAccount($login, $email);
        if(($resultCheckAccount['login'] == 0) OR ($resultCheckAccount['email'] == 0))
            return 0;

        $password = md5($password);

        $query = "INSERT INTO accounts(id, login, email, password) VALUES('', :login, :email, :password);";
        $stmt = $this->db->prepareQuery($query);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        return 1;
    }

    public function login($login, $password){
        $query = "SELECT COUNT(id) AS num FROM accounts WHERE login='".$login."' AND password='".md5($password)."';";
        $query = $this->db->getQuery($query);
        $result = $query->fetch(PDO::FETCH_BOTH);
        if($result['num'] == 0)
            return 0;

        $_SESSION['login'] = $login;
        $_SESSION['logged'] = true;

        return 1;

    }

    public function getIdAccount($login){
        $query = "SELECT id FROM accounts WHERE login='".$login."';";
        $query = $this->db->getQuery($query);
        $result = $query->fetch(PDO::FETCH_BOTH);
        return $result['id'];
    }

}