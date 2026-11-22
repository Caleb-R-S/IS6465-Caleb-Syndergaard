<?php
if (PHP_SAPI !== 'cli') { exit("CLI only\n"); }
if ($argc !== 4) { exit("Usage: php create-admin.php \"Name\" email@example.com \"PasswordAtLeast12\"\n"); }
require_once dirname(__DIR__) . '/config/db.php'; require_once dirname(__DIR__) . '/repositories/userPDO.php';
[$script,$name,$email,$password]=$argv; $name=trim($name); $email=strtolower(trim($email));
if ($name==='' || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($password)<12) { exit("Name, valid email, and a 12-character password are required.\n"); }
$pdo=openDatabase(); if(findUserByEmail($pdo,$email)){exit("An account with that email already exists.\n");}$hash=password_hash($password,PASSWORD_DEFAULT);insertUser($pdo,$name,$email,$hash,'Administrator');echo "Administrator account created for $email.\n";
