<?php
function findStudentByEmail($pdo, $email) {
    $stmt = $pdo->prepare('SELECT student_id, student_name, student_email, user_id FROM students WHERE student_email = ?');
    $stmt->execute([$email]); return $stmt->fetch();
}
function insertStudentIfAbsent($pdo, $name, $email) {
    $stmt = $pdo->prepare('INSERT OR IGNORE INTO students(student_name,student_email) VALUES(?,?)');
    $stmt->execute([$name,$email]);
}
function findStudentByUserId($pdo, $userId) {
    $stmt = $pdo->prepare('SELECT student_id, student_name, student_email, user_id FROM students WHERE user_id = ?');
    $stmt->execute([(int)$userId]); return $stmt->fetch();
}
function ensureStudentForUser($pdo, $user) {
    $student = findStudentByUserId($pdo, $user['user_id']);
    if ($student) { return $student; }
    $student = findStudentByEmail($pdo, $user['email']);
    if ($student && $student['user_id'] !== null && (int)$student['user_id'] !== (int)$user['user_id']) { throw new RuntimeException('That student record is already linked to another account.'); }
    if ($student) {
        $stmt = $pdo->prepare('UPDATE students SET student_name = ?, student_email = ?, user_id = ? WHERE student_id = ?');
        $stmt->execute([$user['full_name'], $user['email'], (int)$user['user_id'], (int)$student['student_id']]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO students (student_name, student_email, user_id) VALUES (?, ?, ?)');
        $stmt->execute([$user['full_name'], $user['email'], (int)$user['user_id']]);
    }
    return findStudentByUserId($pdo, $user['user_id']);
}
function deleteStudentForUser($pdo, $userId) {
    $stmt = $pdo->prepare('DELETE FROM students WHERE user_id = ?');
    $stmt->execute([(int)$userId]);
}
