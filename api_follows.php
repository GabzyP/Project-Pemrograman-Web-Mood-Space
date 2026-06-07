<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([]);
    exit;
}
require 'koneksi.php';

$user_id = intval($_GET['user_id'] ?? 0);
$type    = $_GET['type'] ?? ''; 

if (!$user_id || !in_array($type, ['following', 'followers'])) {
    echo json_encode([]);
    exit;
}

if ($type === 'following') {
    $sql = "SELECT u.id, u.username, u.display_name, u.profile_picture, u.role
            FROM follows f
            JOIN users u ON u.id = f.following_id
            WHERE f.follower_id = ?
            ORDER BY u.display_name ASC";
} else {
    $sql = "SELECT u.id, u.username, u.display_name, u.profile_picture, u.role
            FROM follows f
            JOIN users u ON u.id = f.follower_id
            WHERE f.following_id = ?
            ORDER BY u.display_name ASC";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$list = [];
while ($row = $result->fetch_assoc()) {
    $list[] = [
        'id'           => $row['id'],
        'username'     => $row['username'],
        'display_name' => $row['display_name'] ?: $row['username'],
        'avatar'       => !empty($row['profile_picture']) ? $row['profile_picture'] : '',
        'role'         => $row['role'],
        'profile_url'  => 'profile.php?id=' . $row['id'],
    ];
}
$stmt->close();

header('Content-Type: application/json');
echo json_encode($list);
