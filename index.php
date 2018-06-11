<?php
session_start();

header('Content-type: text/html; charset=UTF-8');

require_once './Controller/controller.php';

$controller = new controller();

