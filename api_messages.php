<?php
session_start();
if (!isset($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['error'=>'Unauthorized']); exit; }
require 'koneksi.php';
header('Content-Type: application/json');

$me = (int)$_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

function getAvatarMsg($pic) {
    if ($pic && file_exists($pic)) return $pic;
    if ($pic && (strpos($pic,'http')===0 || strpos($pic,'data:')===0)) return $pic;
    return "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='50' fill='%23555'/%3E%3C/svg%3E";
}

if ($method === 'GET' && ($_GET['action'] ?? '') === 'inbox') {
    $stmt = $conn->prepare("
        SELECT
            partner_id,
            u.username, u.display_name, u.profile_picture,
            last_teks, last_at,
            SUM(CASE WHEN sender_id = partner_id AND is_read = 0 THEN 1 ELSE 0 END) as unread_count
        FROM (
            SELECT
                CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END as partner_id,
                teks as last_teks, created_at as last_at, sender_id, is_read,
                ROW_NUMBER() OVER (
                    PARTITION BY LEAST(sender_id,receiver_id), GREATEST(sender_id,receiver_id)
                    ORDER BY created_at DESC
                ) as rn
            FROM messages
            WHERE sender_id = ? OR receiver_id = ?
        ) sub
        JOIN users u ON u.id = sub.partner_id
        WHERE sub.rn = 1
        GROUP BY partner_id, u.username, u.display_name, u.profile_picture, last_teks, last_at
        ORDER BY last_at DESC
        LIMIT 30
    ");
    $stmt->bind_param("iii", $me, $me, $me);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    foreach ($rows as &$r) {
        $r['profile_picture'] = getAvatarMsg($r['profile_picture']);
    }
    
    echo json_encode($rows);
    exit;
}

if ($method === 'GET' && ($_GET['action'] ?? '') === 'thread') {
    $with = (int)($_GET['with'] ?? 0);
    if (!$with) { echo json_encode([]); exit; }

    $upd = $conn->prepare("UPDATE messages SET is_read=1 WHERE receiver_id=? AND sender_id=? AND is_read=0");
    $upd->bind_param("ii", $me, $with);
    $upd->execute();

    $stmt = $conn->prepare("
        SELECT m.id, m.sender_id, m.receiver_id, m.teks, m.is_read, m.created_at,
               u.username, u.display_name, u.profile_picture
        FROM messages m
        JOIN users u ON u.id = m.sender_id
        WHERE (m.sender_id=? AND m.receiver_id=?) OR (m.sender_id=? AND m.receiver_id=?)
        ORDER BY m.created_at ASC
        LIMIT 100
    ");
    $stmt->bind_param("iiii", $me, $with, $with, $me);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    foreach ($rows as &$r) {
        $r['profile_picture'] = getAvatarMsg($r['profile_picture']);
    }
    
    $fstmt = $conn->prepare("SELECT id FROM follows WHERE follower_id=? AND following_id=?");
    $fstmt->bind_param("ii", $me, $with);
    $fstmt->execute();
    $is_following = $fstmt->get_result()->num_rows > 0;
    $fstmt->close();
    
    $pstmt = $conn->prepare("SELECT display_name, username, profile_picture FROM users WHERE id=?");
    $pstmt->bind_param("i", $with);
    $pstmt->execute();
    $prow = $pstmt->get_result()->fetch_assoc();
    $partner_name = $prow['display_name'] ?: $prow['username'];
    $partner_avatar = getAvatarMsg($prow['profile_picture']);
    $pstmt->close();
    
    echo json_encode([
        'messages' => $rows, 
        'is_following' => $is_following,
        'partner' => ['name' => $partner_name, 'avatar' => $partner_avatar]
    ]);
    exit;
}

if ($method === 'GET' && ($_GET['action'] ?? '') === 'unread_count') {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM messages WHERE receiver_id=? AND is_read=0");
    $stmt->bind_param("i", $me);
    $stmt->execute();
    echo json_encode($stmt->get_result()->fetch_assoc());
    exit;
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $to   = (int)($data['to'] ?? 0);
    $teks = trim($data['teks'] ?? '');
    if (!$to || !$teks || $to === $me) { echo json_encode(['error'=>'Invalid']); exit; }
    if (mb_strlen($teks) > 1000) { echo json_encode(['error'=>'Terlalu panjang']); exit; }

    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, teks) VALUES (?,?,?)");
    $stmt->bind_param("iis", $me, $to, $teks);
    $stmt->execute();
    $new_id = $stmt->insert_id;

    echo json_encode([
        'success' => true,
        'message' => [
            'id' => $new_id,
            'sender_id' => $me,
            'receiver_id' => $to,
            'teks' => $teks,
            'created_at' => date('Y-m-d H:i:s'),
            'is_read' => 0,
        ]
    ]);
    exit;
}

echo json_encode(['error' => 'Method not supported']);



