<?php
require_once dirname(__DIR__) . '/repositories/userPDO.php';

function userRoles() { return ['Student', 'Tutor', 'Administrator']; }
function userStatuses() { return ['Active', 'Inactive']; }

function validateAccountInput($input, $includePassword = true) {
    $name = trim((string)($input['full_name'] ?? ''));
    $email = strtolower(trim((string)($input['email'] ?? '')));
    $role = (string)($input['role'] ?? '');
    $errors = [];
    if ($name === '' || mb_strlen($name) > 120) { $errors[] = 'Enter a name of 1 to 120 characters.'; }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 254) { $errors[] = 'Enter a valid email address.'; }
    if (!in_array($role, ['Student', 'Tutor'], true)) { $errors[] = 'Choose Student or Tutor.'; }
    if ($includePassword) {
        $password = (string)($input['password'] ?? '');
        $confirmation = (string)($input['confirm_password'] ?? '');
        if (strlen($password) < 12) { $errors[] = 'Password must contain at least 12 characters.'; }
        if ($password !== $confirmation) { $errors[] = 'Password confirmation does not match.'; }
    }
    return [$errors, ['full_name' => $name, 'email' => $email, 'role' => $role]];
}

function registerUser($pdo, $input) {
    [$errors, $data] = validateAccountInput($input, true);
    if ($errors) { throw new InvalidArgumentException(implode(' ', $errors)); }
    if (findUserByEmail($pdo, $data['email'])) { throw new InvalidArgumentException('An account with that email already exists.'); }
    $hash = password_hash((string)$input['password'], PASSWORD_DEFAULT);
    if ($hash === false) { throw new RuntimeException('Password could not be secured.'); }
    try { return insertUser($pdo, $data['full_name'], $data['email'], $hash, $data['role']); }
    catch (PDOException $e) { throw new InvalidArgumentException('An account with that email already exists.'); }
}

function updateOwnProfile($pdo, $userId, $input) {
    $name = trim((string)($input['full_name'] ?? ''));
    $email = strtolower(trim((string)($input['email'] ?? '')));
    if ($name === '' || mb_strlen($name) > 120 || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 254) { throw new InvalidArgumentException('Enter a valid name and email address.'); }
    $existing = findUserByEmail($pdo, $email);
    if ($existing && (int)$existing['user_id'] !== (int)$userId) { throw new InvalidArgumentException('That email address is already in use.'); }
    $pdo->beginTransaction();
    try {
        updateUserProfileRecord($pdo, $userId, $name, $email);
        $stmt = $pdo->prepare('UPDATE students SET student_name = ?, student_email = ? WHERE user_id = ?');
        $stmt->execute([$name, $email, (int)$userId]);
        $pdo->commit();
    } catch (Throwable $e) { if ($pdo->inTransaction()) { $pdo->rollBack(); } throw $e; }
}

function changePassword($pdo, $user, $input) {
    $current = (string)($input['current_password'] ?? ''); $new = (string)($input['new_password'] ?? ''); $confirm = (string)($input['confirm_password'] ?? '');
    if (!password_verify($current, $user['password_hash'])) { throw new InvalidArgumentException('Current password is incorrect.'); }
    if (strlen($new) < 12) { throw new InvalidArgumentException('Password must contain at least 12 characters.'); }
    if ($new !== $confirm) { throw new InvalidArgumentException('Password confirmation does not match.'); }
    $hash = password_hash($new, PASSWORD_DEFAULT);
    if ($hash === false) { throw new RuntimeException('Password could not be secured.'); }
    updateUserPasswordHash($pdo, $user['user_id'], $hash);
}

function changeUserRole($pdo, $actor, $targetUserId, $newRole) {
    $target = findUserById($pdo, $targetUserId);
    if (!$target || !in_array($newRole, userRoles(), true)) { throw new InvalidArgumentException('Invalid user or role.'); }
    if ((int)$target['user_id'] === (int)$actor['user_id']) { throw new InvalidArgumentException('Administrators cannot change their own role.'); }
    if ($target['role'] === 'Administrator' && $target['status'] === 'Active' && $newRole !== 'Administrator' && countActiveAdministrators($pdo) <= 1) { throw new InvalidArgumentException('The last active administrator cannot lose administrator access.'); }
    updateUserRoleRecord($pdo, $targetUserId, $newRole);
}

function changeUserStatus($pdo, $actor, $targetUserId, $newStatus) {
    $target = findUserById($pdo, $targetUserId);
    if (!$target || !in_array($newStatus, userStatuses(), true)) { throw new InvalidArgumentException('Invalid user or status.'); }
    if ((int)$target['user_id'] === (int)$actor['user_id']) { throw new InvalidArgumentException('Administrators cannot change their own status.'); }
    if ($target['role'] === 'Administrator' && $target['status'] === 'Active' && $newStatus === 'Inactive' && countActiveAdministrators($pdo) <= 1) { throw new InvalidArgumentException('The last active administrator cannot be deactivated.'); }
    updateUserStatusRecord($pdo, $targetUserId, $newStatus);
}

function removeUser($pdo, $actor, $targetUserId) {
    $target = findUserById($pdo, $targetUserId);
    if (!$target) { throw new InvalidArgumentException('User not found.'); }
    if ((int)$target['user_id'] === (int)$actor['user_id']) { throw new InvalidArgumentException('Administrators cannot delete their own account.'); }
    if (userHasTutoringRequests($pdo, $targetUserId)) { throw new InvalidArgumentException('Account deletion is blocked because related tutoring requests must be retained.'); }
    if ($target['role'] === 'Administrator' && $target['status'] === 'Active' && countActiveAdministrators($pdo) <= 1) { throw new InvalidArgumentException('The last active administrator cannot be deleted.'); }
    $pdo->beginTransaction();
    try { deleteStudentForUser($pdo, $targetUserId); deleteUserRecord($pdo, $targetUserId); $pdo->commit(); }
    catch (Throwable $e) { if ($pdo->inTransaction()) { $pdo->rollBack(); } throw $e; }
}
