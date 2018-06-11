<?php

require_once('./Model/accounts.php');

class controllerAccount
{
    private $account;

    public function __construct(){
        $this->account = new accounts();

        if(isset($_GET['checkCreateAccount'])){
            $login = htmlspecialchars(trim($_POST['login']));
            $email = htmlspecialchars(trim($_POST['email']));

            $resultCheck = $this->account->checkCreateAccount($login, $email);

            echo json_encode($resultCheck);
        }

        if(isset($_GET['createAccount'])){
            $login = htmlspecialchars(trim($_POST['login']));
            $email = htmlspecialchars(trim($_POST['email']));
            $password = htmlspecialchars(trim($_POST['password']));
            $confirmPassword = htmlspecialchars(trim($_POST['confirmPassword']));
            $termsAccept = htmlspecialchars(trim($_POST['termsAccept']));

            $resultCreate = $this->account->createAccount($login, $email, $password, $confirmPassword, $termsAccept);

            echo json_encode(array('resultCreate' => $resultCreate));
        }

        if(isset($_GET['login'])){
            $login = htmlspecialchars(trim($_POST['login']));
            $password = htmlspecialchars(trim($_POST['password']));

            $resultLogin = $this->account->login($login, $password);

            echo json_encode(array('resultLogin' => $resultLogin));
        }

        if(isset($_GET['logout'])){
            unset($_SESSION['login']);
            unset($_SESSION['logged']);
        }
    }
}