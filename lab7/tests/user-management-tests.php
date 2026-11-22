<?php
require_once dirname(__DIR__) . '/services/userService.php';
$pdo = new PDO('sqlite::memory:'); $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); $pdo->exec('PRAGMA foreign_keys = ON');
$pdo->exec(file_get_contents(dirname(__DIR__) . '/database/setup-database.sql')); $pdo->exec(file_get_contents(dirname(__DIR__) . '/database/upgrade-to-user-management.sql'));
$passed=0;$failed=0;function check($condition,$label){global $passed,$failed;if($condition){$passed++;echo "PASS: $label\n";}else{$failed++;echo "FAIL: $label\n";}}
$student=registerUser($pdo,['full_name'=>'Test Student','email'=>'student@example.test','password'=>'TestPassword123','confirm_password'=>'TestPassword123','role'=>'Student']);check($student>0,'UM-01 registers an active Student');check(findUserById($pdo,$student)['status']==='Active','UM-01 default status is Active');
try{registerUser($pdo,['full_name'=>'Duplicate','email'=>'student@example.test','password'=>'TestPassword123','confirm_password'=>'TestPassword123','role'=>'Student']);check(false,'UM-01 rejects duplicate email');}catch(InvalidArgumentException $e){check(true,'UM-01 rejects duplicate email');}
$admin=insertUser($pdo,'Test Administrator','admin@example.test',password_hash('Administrator123',PASSWORD_DEFAULT),'Administrator');$actor=findUserById($pdo,$admin);changeUserRole($pdo,$actor,$student,'Tutor');check(findUserById($pdo,$student)['role']==='Tutor','UM-06 administrator changes a role');changeUserStatus($pdo,$actor,$student,'Inactive');check(findUserById($pdo,$student)['status']==='Inactive','UM-07 administrator deactivates account');
echo "Results: $passed passed, $failed failed\n";exit($failed>0?1:0);
