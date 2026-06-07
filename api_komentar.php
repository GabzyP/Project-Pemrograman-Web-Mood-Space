<?php
session_start();
if (!isset($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['error'=>'Unauthorized']); exit; }
require 'koneksi.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $konten_id = intval($_GET['konten_id'] ?? 0);
    if (!$konten_id) { echo json_encode([]); exit; }
    
    $stmt = $conn->prepare("
        SELECT k.id, k.teks, k.created_at,
               u.id as user_id, u.username, u.display_name, u.profile_picture
        FROM komentar k
        JOIN users u ON u.id = k.user_id
        WHERE k.konten_id = ?
        ORDER BY k.created_at ASC
        LIMIT 50
    ");
    $stmt->bind_param("i", $konten_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $list = [];
    while ($row = $result->fetch_assoc()) {
        $list[] = [
            'id'           => $row['id'],
            'teks'         => $row['teks'],
            'created_at'   => $row['created_at'],
            'user_id'      => $row['user_id'],
            'username'     => $row['username'],
            'display_name' => $row['display_name'] ?: $row['username'],
            'avatar'       => $row['profile_picture'] ?: '',
        ];
    }
    echo json_encode($list);

} elseif ($method === 'POST') {
    $data      = json_decode(file_get_contents('php://input'), true);
    $konten_id = intval($data['konten_id'] ?? 0);
    $teks      = trim($data['teks'] ?? '');
    $user_id   = $_SESSION['user_id'];
    
    if (!$konten_id || !$teks) { echo json_encode(['error'=>'Invalid']); exit; }
    if (mb_strlen($teks) > 500) { echo json_encode(['error'=>'Terlalu panjang']); exit; }
    
    $stmt = $conn->prepare("INSERT INTO komentar (konten_id, user_id, teks) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $konten_id, $user_id, $teks);
    $stmt->execute();
    $new_id = $stmt->insert_id;

    $stmt2 = $conn->prepare("SELECT username, display_name, profile_picture FROM users WHERE id = ?");
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();
    $me = $stmt2->get_result()->fetch_assoc();
    
    echo json_encode([
        'success' => true,
        'komentar' => [
            'id'           => $new_id,
            'teks'         => $teks,
            'user_id'      => $user_id,
            'username'     => $me['username'],
            'display_name' => $me['display_name'] ?: $me['username'],
            'avatar'       => $me['profile_picture'] ?: '',
        ]
    ]);

} elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id   = intval($data['id'] ?? 0);
    $stmt = $conn->prepare("DELETE FROM komentar WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $_SESSION['user_id']);
    $stmt->execute();
    echo json_encode(['success' => $stmt->affected_rows > 0]);
}