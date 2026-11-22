<?php
function findUserById($pdo, $userId) {
    $stmt = $pdo->prepare('SELECT user_id, full_name, email, password_hash, role, status, created_at FROM users WHERE user_id = ?');
    $stmt->execute([(int)$userId]);
    return $stmt->fetch();
}

function findUserByEmail($pdo, $email) {
    $stmt = $pdo->prepare('SELECT user_id, full_name, email, password_hash, role, status, created_at FROM users WHERE email = ?');
    $stmt->execute([strtolower(trim($email))]);
    return $stmt->fetch();
}

function insertUser($pdo, $fullName, $email, $passwordHash, $role) {
    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password_hash, role, status) VALUES (?, ?, ?, ?, 'Active')");
    $stmt->execute([$fullName, $email, $passwordHash, $role]);
    return (int)$pdo->lastInsertId();
}

function updateUserProfileRecord($pdo, $userId, $fullName, $email) {
    $stmt = $pdo->prepare('UPDATE users SET full_name = ?, email = ? WHERE user_id = ?');
    $stmt->execute([$fullName, $email, (int)$userId]);
    return $stmt->rowCount() === 1;
}

function updateUserPasswordHash($pdo, $userId, $passwordHash) {
    $stmt = $pdo->prepare('UPDATE users SET password_hash = ? WHERE user_id = ?');
    $stmt->execute([$passwordHash, (int)$userId]);
}

function updateUserRoleRecord($pdo, $userId, $role) {
    $stmt = $pdo->prepare('UPDATE users SET role = ? WHERE user_id = ?');
    $stmt->execute([$role, (int)$userId]);
}

function updateUserStatusRecord($pdo, $userId, $status) {
    $stmt = $pdo->prepare('UPDATE users SET status = ? WHERE user_id = ?');
    $stmt->execute([$status, (int)$userId]);
}

function countActiveAdministrators($pdo) {
    return (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'Administrator' AND status = 'Active'")->fetchColumn();
}

function listUsers($pdo, $search = '', $role = '', $status = '') {
    $sql = 'SELECT user_id, full_name, email, role, status, created_at FROM users WHERE 1 = 1';
    $args = [];
    if ($search !== '') { $sql .= ' AND (full_name LIKE ? OR email LIKE ?)'; $args[] = '%' . $search . '%'; $args[] = '%' . $search . '%'; }
    if ($role !== '') { $sql .= ' AND role = ?'; $args[] = $role; }
    if ($status !== '') { $sql .= ' AND status = ?'; $args[] = $status; }
    $sql .= ' ORDER BY full_name, user_id';
    $stmt = $pdo->prepare($sql); $stmt->execute($args);
    return $stmt->fetchAll();
}

function userHasTutoringRequests($pdo, $userId) {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM tutoring_requests r JOIN students s ON s.student_id = r.student_id WHERE s.user_id = ?');
    $stmt->execute([(int)$userId]);
    return (int)$stmt->fetchColumn() > 0;
}

function deleteUserRecord($pdo, $userId) {
    $stmt = $pdo->prepare('DELETE FROM users WHERE user_id = ?');
    $stmt->execute([(int)$userId]);
    return $stmt->rowCount() === 1;
}
