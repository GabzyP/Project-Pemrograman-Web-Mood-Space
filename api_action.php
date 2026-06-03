<?php
session_start();
require 'koneksi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit;
}

$action = $_POST['action'] ?? '';
$konten_id = isset($_POST['konten_id']) ? (int)$_POST['konten_id'] : 0;
$user_id = $_SESSION['user_id'];

if ($action === 'follow') {
    $target_id = isset($_POST['target_id']) ? (int)$_POST['target_id'] : 0;
    if ($target_id <= 0 || $target_id === $user_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid target']);
        exit;
    }

    $stmt = $conn->prepare("SELECT id FROM follows WHERE follower_id = ? AND following_id = ?");
    $stmt->bind_param("ii", $user_id, $target_id);
    $stmt->execute();
    $exists = $stmt->get_result()->num_rows > 0;
    $stmt->close();

    if ($exists) {
        $stmt = $conn->prepare("DELETE FROM follows WHERE follower_id = ? AND following_id = ?");
        $stmt->bind_param("ii", $user_id, $target_id);
        $stmt->execute();
        $stmt->close();

        $conn->query("UPDATE users SET following_count = GREATEST(following_count - 1, 0) WHERE id = $user_id");
        $conn->query("UPDATE users SET followers_count = GREATEST(followers_count - 1, 0) WHERE id = $target_id");

        $status = 'unfollowed';
    } else {
        $stmt = $conn->prepare("INSERT INTO follows (follower_id, following_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $target_id);
        $stmt->execute();
        $stmt->close();

        $conn->query("UPDATE users SET following_count = following_count + 1 WHERE id = $user_id");
        $conn->query("UPDATE users SET followers_count = followers_count + 1 WHERE id = $target_id");

        $status = 'followed';
    }

    $stmt = $conn->prepare("SELECT followers_count FROM users WHERE id = ?");
    $stmt->bind_param("i", $target_id);
    $stmt->execute();
    $followers = $stmt->get_result()->fetch_assoc()['followers_count'];
    $stmt->close();

    echo json_encode(['success' => true, 'status' => $status, 'followers_count' => $followers]);
    exit;
}

if ($konten_id <= 0 || !in_array($action, ['like', 'favorite'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}

$table = $action === 'like' ? 'likes' : 'favorites';

$stmt = $conn->prepare("SELECT id FROM $table WHERE user_id = ? AND konten_id = ?");
$stmt->bind_param("ii", $user_id, $konten_id);
$stmt->execute();
$res = $stmt->get_result();
$exists = $res->num_rows > 0;
$stmt->close();

$status = '';
if ($exists) {
    $stmt = $conn->prepare("DELETE FROM $table WHERE user_id = ? AND konten_id = ?");
    $stmt->bind_param("ii", $user_id, $konten_id);
    $stmt->execute();
    $stmt->close();
    $status = 'removed';
} else {
    $stmt = $conn->prepare("INSERT INTO $table (user_id, konten_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $konten_id);
    $stmt->execute();
    $stmt->close();
    $status = 'added';
}

$stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM $table WHERE konten_id = ?");
$stmt->bind_param("i", $konten_id);
$stmt->execute();
$count = $stmt->get_result()->fetch_assoc()['cnt'];
$stmt->close();

echo json_encode([
    'success' => true,
    'status' => $status,
    'count' => $count
]);
