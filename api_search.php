<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}
require 'koneksi.php';

header('Content-Type: application/json');

$q = trim($_GET['q'] ?? '');
if (strlen($q) < 1) {
    echo json_encode([]);
    exit;
}

$like = '%' . $q . '%';
$stmt = $conn->prepare("
    SELECT id, username, display_name, profile_picture, role
    FROM users
    WHERE username LIKE ? OR display_name LIKE ?
    LIMIT 8
");
$stmt->bind_param("ss", $like, $like);
$stmt->execute();
$result = $stmt->get_result();
$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = [
        'id'           => $row['id'],
        'username'     => $row['username'],
        'display_name' => $row['display_name'],
        'avatar'       => !empty($row['profile_picture']) ? $row['profile_picture'] : null,
        'role'         => $row['role'],
        'profile_url'  => 'profile.php?id=' . $row['id']
    ];
}

$stmt->close();
echo json_encode($users);
