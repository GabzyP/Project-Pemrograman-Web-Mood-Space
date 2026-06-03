<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require 'koneksi.php';

$moodColors = [
    'joy'           => '#FFD600',
    'sadness'       => '#1565C0',
    'anger'         => '#C62828',
    'disgust'       => '#2E7D32',
    'fear'          => '#6A1B9A',
    'anxiety'       => '#EF6C00',
    'ennui'         => '#616161',
    'embarrassment' => '#D81B60',
    'envy'          => '#00ACC1',
];

function getAvatar($url) {
    if (!empty($url)) return htmlspecialchars($url);
    return "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='50' fill='%23555'/%3E%3Ccircle cx='50' cy='38' r='18' fill='%23888'/%3E%3Cellipse cx='50' cy='85' rx='28' ry='20' fill='%23888'/%3E%3C/svg%3E";
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_profile') {
    $new_username     = trim($_POST['username'] ?? '');
    $new_display_name = trim($_POST['display_name'] ?? '');
    $new_bio          = trim($_POST['bio'] ?? '');
    $new_picture_url  = null;

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $ftype   = mime_content_type($_FILES['profile_picture']['tmp_name']);
        if (in_array($ftype, $allowed)) {
            $ext      = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
            $filename = 'assets/uploads/avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['profile_picture']['tmp_name'], $filename);
            $new_picture_url = $filename;
        }
    }

    if (empty($new_username)) {
        $errors[] = "Username tidak boleh kosong.";
    } else {
        $chk = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $chk->bind_param("si", $new_username, $_SESSION['user_id']);
        $chk->execute();
        $chk->store_result();
        if ($chk->num_rows > 0) $errors[] = "Username sudah dipakai.";
        $chk->close();
    }

    if (empty($errors)) {
        if ($new_picture_url) {
            $upd = $conn->prepare("UPDATE users SET username=?, display_name=?, bio=?, profile_picture=? WHERE id=?");
            $upd->bind_param("ssssi", $new_username, $new_display_name, $new_bio, $new_picture_url, $_SESSION['user_id']);
        } else {
            $upd = $conn->prepare("UPDATE users SET username=?, display_name=?, bio=? WHERE id=?");
            $upd->bind_param("sssi", $new_username, $new_display_name, $new_bio, $_SESSION['user_id']);
        }
        $upd->execute();
        $upd->close();
        header("Location: profile.php?id=" . $_SESSION['user_id']);
        exit;
    }
}

$stmt_me = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt_me->bind_param("i", $_SESSION['user_id']);
$stmt_me->execute();
$me = $stmt_me->get_result()->fetch_assoc();
$stmt_me->close();

$profile_id = isset($_GET['id']) ? (int)$_GET['id'] : $me['id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $profile_id);
$stmt->execute();
$result = $stmt->get_result();
$profile_user = $result->fetch_assoc();
$stmt->close();

if (!$profile_user) {
    die("User not found");
}

$stmt_likes = $conn->prepare("SELECT COUNT(l.id) as total_likes FROM likes l JOIN konten_mood k ON l.konten_id = k.id WHERE k.uploaded_by = ?");
$stmt_likes->bind_param("i", $profile_id);
$stmt_likes->execute();
$profile_user['likes_count'] = $stmt_likes->get_result()->fetch_assoc()['total_likes'];
$stmt_likes->close();

$profile_id = $profile_user['id'];

$is_owner = ($me['id'] === $profile_user['id']);
$profile_role = $profile_user['role'];
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : ($profile_role == 'creator' ? 'content' : 'favorites');

$is_following = false;
if (!$is_owner) {
    $stmt_follow = $conn->prepare("SELECT id FROM follows WHERE follower_id = ? AND following_id = ?");
    $stmt_follow->bind_param("ii", $_SESSION['user_id'], $profile_id);
    $stmt_follow->execute();
    $is_following = $stmt_follow->get_result()->num_rows > 0;
    $stmt_follow->close();
}

$db_content = [];
if ($activeTab === 'content' && $profile_user['role'] === 'creator') {
    $stmt_content = $conn->prepare("
        SELECT k.*, 
               (SELECT COUNT(*) FROM likes WHERE konten_id = k.id) as like_count,
               (SELECT COUNT(*) FROM favorites WHERE konten_id = k.id) as fav_count,
               (SELECT COUNT(*) FROM likes WHERE konten_id = k.id AND user_id = ?) as user_liked,
               (SELECT COUNT(*) FROM favorites WHERE konten_id = k.id AND user_id = ?) as user_favorited
        FROM konten_mood k
        WHERE k.uploaded_by = ? 
        ORDER BY k.created_at DESC LIMIT 20
    ");
    $stmt_content->bind_param("iii", $_SESSION['user_id'], $_SESSION['user_id'], $profile_user['id']);
    $stmt_content->execute();
    $res_content = $stmt_content->get_result();
    while ($row_content = $res_content->fetch_assoc()) {
        $db_content[] = formatProfileCard($row_content, $moodColors);
    }
    $stmt_content->close();
} elseif ($activeTab === 'favorites') {
    $stmt_content = $conn->prepare("
        SELECT k.*, 
               (SELECT COUNT(*) FROM likes WHERE konten_id = k.id) as like_count,
               (SELECT COUNT(*) FROM favorites WHERE konten_id = k.id) as fav_count,
               (SELECT COUNT(*) FROM likes WHERE konten_id = k.id AND user_id = ?) as user_liked,
               (SELECT COUNT(*) FROM favorites WHERE konten_id = k.id AND user_id = ?) as user_favorited
        FROM favorites f
        JOIN konten_mood k ON f.konten_id = k.id
        WHERE f.user_id = ? 
        ORDER BY f.created_at DESC LIMIT 20
    ");
    $stmt_content->bind_param("iii", $_SESSION['user_id'], $_SESSION['user_id'], $profile_user['id']);
    $stmt_content->execute();
    $res_content = $stmt_content->get_result();
    while ($row_content = $res_content->fetch_assoc()) {
        $db_content[] = formatProfileCard($row_content, $moodColors);
    }
    $stmt_content->close();
} elseif ($activeTab === 'liked') {
    $stmt_content = $conn->prepare("
        SELECT k.*, 
               (SELECT COUNT(*) FROM likes WHERE konten_id = k.id) as like_count,
               (SELECT COUNT(*) FROM favorites WHERE konten_id = k.id) as fav_count,
               (SELECT COUNT(*) FROM likes WHERE konten_id = k.id AND user_id = ?) as user_liked,
               (SELECT COUNT(*) FROM favorites WHERE konten_id = k.id AND user_id = ?) as user_favorited
        FROM likes l
        JOIN konten_mood k ON l.konten_id = k.id
        WHERE l.user_id = ? 
        ORDER BY l.created_at DESC LIMIT 20
    ");
    $stmt_content->bind_param("iii", $_SESSION['user_id'], $_SESSION['user_id'], $profile_user['id']);
    $stmt_content->execute();
    $res_content = $stmt_content->get_result();
    while ($row_content = $res_content->fetch_assoc()) {
        $db_content[] = formatProfileCard($row_content, $moodColors);
    }
    $stmt_content->close();
}

function formatProfileCard($row, $colors) {
    if ($row['tipe'] === 'video') {
        if (!empty($row['media_id'])) {
            $row['thumb'] = "https://img.youtube.com/vi/" . $row['media_id'] . "/mqdefault.jpg";
        } elseif (!empty($row['file_url'])) {
            $extPos = strrpos($row['file_url'], '.');
            if ($extPos !== false) {
                $row['thumb'] = substr($row['file_url'], 0, $extPos) . '.jpg';
            } else {
                $row['thumb'] = $row['file_url'] . '.jpg';
            }
        }
    } elseif ($row['tipe'] === 'music' && !empty($row['cover_url'])) {
        $row['thumb'] = $row['cover_url'];
    } elseif ($row['tipe'] === 'quote' && !empty($row['file_url'])) {
        $row['thumb'] = $row['file_url'];
    } else {
        $moodBg = $colors[$row['mood']] ?? '#616161';
        $label = urlencode(ucfirst($row['mood']));
        $row['thumb'] = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 400'%3E%3Crect width='300' height='400' fill='" . urlencode($moodBg) . "'/%3E%3Ctext x='150' y='200' text-anchor='middle' fill='white' font-size='24' font-family='sans-serif'%3E" . $label . "%3C/text%3E%3C/svg%3E";
    }
    return $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($profile_user['display_name']); ?> (<?php echo htmlspecialchars($profile_user['username']); ?>) - MoodSpace</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Playfair+Display:wght@700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body class="profile-page-body">

    <nav class="ms-navbar" id="main-navbar">
        <div class="ms-navbar__left">
            <a href="index.php" title="MoodSpace Home">
                <img src="assets/logo.png" alt="MoodSpace Logo" class="ms-navbar__logo" style="height:32px;width:auto;max-width:160px;">
            </a>
        </div>

        <div class="ms-navbar__right">
            <button type="button" class="ms-navbar__icon-btn theme-toggle" id="theme-toggle-profile" title="Ubah Tema" onclick="toggleTheme()" style="background:transparent;border:none;cursor:pointer;color:var(--text-secondary);font-size:1.15rem;margin-right:10px;">
                <i class="fas fa-moon" id="theme-icon-profile"></i>
            </button>
            <a href="logout.php" class="ms-navbar__icon-btn" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </a>
            <a href="profile.php?id=<?php echo $me['id']; ?>" class="ms-navbar__avatar" title="Profile" style="box-shadow: 0 0 0 2px var(--mood-color);">
                <img src="<?php echo getAvatar($me['profile_picture']); ?>" alt="Avatar" style="width:100%;height:100%;object-fit:cover;">
            </a>
        </div>
    </nav>

    <main class="ms-profile-main">
        <header class="ms-profile-header">
            <div class="ms-profile-avatar-container">
                <img src="<?php echo getAvatar($profile_user['profile_picture']); ?>" alt="Avatar" class="ms-profile-avatar">
            </div>
            
            <div class="ms-profile-info">
                <div class="ms-profile-names">
                    <h1 class="ms-profile-display-name"><?php echo htmlspecialchars($profile_user['display_name']); ?></h1>
                    <span class="ms-profile-username"><?php echo htmlspecialchars($profile_user['username']); ?></span>
                </div>
                
                <div class="ms-profile-stats">
                    <div class="ms-profile-stat">
                        <strong><?php echo number_format($profile_user['following_count']); ?></strong> <span>Following</span>
                    </div>
                    <div class="ms-profile-stat">
                        <strong data-type="followers"><?php echo number_format($profile_user['followers_count']); ?></strong> <span>Followers</span>
                    </div>
                    <div class="ms-profile-stat">
                        <strong><?php echo number_format($profile_user['likes_count']); ?></strong> <span>Likes</span>
                    </div>
                </div>
                
                <div class="ms-profile-actions">
                    <?php if ($is_owner): ?>
                        <button class="ms-btn ms-btn-outline" onclick="openEditModal()">Edit profile</button>
                    <?php else: ?>
                        <button 
                            class="ms-btn <?php echo $is_following ? 'ms-btn-outline' : 'ms-btn-primary'; ?> btn-follow"
                            data-target-id="<?php echo $profile_id; ?>"
                            data-following="<?php echo $is_following ? '1' : '0'; ?>"
                            id="followBtn">
                            <?php echo $is_following ? 'Following' : 'Follow'; ?>
                        </button>
                        <?php if ($profile_user['role'] === 'creator'): ?>
                            <button class="ms-btn ms-btn-outline">Message</button>
                        <?php endif; ?>
                        <button class="ms-btn ms-btn-icon" title="Share"><i class="fas fa-share"></i></button>
                    <?php endif; ?>
                </div>
                
                <p class="ms-profile-bio"><?php echo htmlspecialchars($profile_user['bio']); ?></p>
            </div>
            
            <?php if ($is_owner && $profile_user['role'] === 'creator'): ?>
            <div style="margin-left:auto;">
                <a href="upload_konten.php" 
                   style="display:inline-flex;align-items:center;gap:8px;background:#6C5CE7;color:#fff;
                          text-decoration:none;border-radius:10px;padding:10px 20px;font-size:14px;font-weight:600;
                          transition:background 0.2s;">
                    <i class="fas fa-plus"></i> Upload Konten
                </a>
            </div>
            <?php endif; ?>
        </header>

        <div class="ms-profile-nav-container">
            <nav class="ms-profile-nav">
                <?php if ($profile_role == 'creator'): ?>
                <a href="?id=<?php echo $profile_id; ?>&tab=content" class="ms-profile-tab <?php echo $activeTab == 'content' ? 'active' : ''; ?>">
                    <i class="fas fa-grip-vertical"></i> Content
                </a>
                <?php endif; ?>
                <a href="?id=<?php echo $profile_id; ?>&tab=favorites" class="ms-profile-tab <?php echo $activeTab == 'favorites' ? 'active' : ''; ?>">
                    <i class="fas fa-bookmark"></i> Favorites
                </a>
                <a href="?id=<?php echo $profile_id; ?>&tab=liked" class="ms-profile-tab <?php echo $activeTab == 'liked' ? 'active' : ''; ?>">
                    <i class="fas fa-heart"></i> Liked
                </a>
            </nav>
        </div>

        <div class="ms-profile-content">
            <?php if (empty($db_content)): ?>
                <div class="ms-profile-empty">
                    <i class="fas <?php echo $activeTab == 'liked' ? 'fa-heart-broken' : ($activeTab == 'favorites' ? 'fa-bookmark' : 'fa-video-slash'); ?>"></i>
                    <h2><?php 
                        if ($activeTab == 'content') echo 'No content available';
                        elseif ($activeTab == 'favorites') echo 'No favorites yet';
                        else echo 'No liked content yet';
                    ?></h2>
                </div>
            <?php else: ?>
                <div class="ms-profile-grid">
                    <?php $idx=0; foreach ($db_content as $item): ?>
                    <div class="ms-profile-card group" onclick="if(!event.target.closest('.btn-action')){ openProfileMedia(<?php echo $idx; ?>); }" style="cursor:pointer;">
                        <img src="<?php echo htmlspecialchars($item['thumb']); ?>" alt="Thumbnail">
                        <div class="ms-profile-card-overlay">
                            <span class="card-stat btn-action btn-like" data-id="<?php echo $item['id']; ?>" data-type="like">
                                <i class="fas fa-heart <?php echo !empty($item['user_liked']) ? 'active' : ''; ?>"></i> 
                                <span class="count-text"><?php echo number_format($item['like_count']); ?></span>
                            </span>
                            <span class="card-stat btn-action btn-favorit" data-id="<?php echo $item['id']; ?>" data-type="favorite">
                                <i class="fas fa-bookmark <?php echo !empty($item['user_favorited']) ? 'active' : ''; ?>"></i> 
                                <span class="count-text"><?php echo number_format($item['fav_count']); ?></span>
                            </span>
                        </div>
                        <div class="ms-profile-card-privacy">
                            <?php 
                                if ($item['tipe'] === 'video') echo '<i class="fas fa-video text-xs"></i>';
                                elseif ($item['tipe'] === 'music') echo '<i class="fas fa-music text-xs"></i>';
                                elseif ($item['tipe'] === 'quote') echo '<i class="fas fa-quote-left text-xs"></i>';
                            ?>
                        </div>
                    </div>
                    <?php $idx++; endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

<div id="editProfileModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.75);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#1a1a2e;border:1px solid rgba(255,255,255,0.1);border-radius:16px;padding:32px;width:100%;max-width:480px;margin:16px;">
        <h2 style="color:#F0EEF8;font-size:20px;font-weight:700;margin-bottom:24px;">Edit Profile</h2>
        <form method="POST" action="profile.php?id=<?php echo $_SESSION['user_id']; ?>" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit_profile">
            
            <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
                <img id="avatarPreview" src="<?php echo getAvatar($me['profile_picture']); ?>" 
                     style="width:64px;height:64px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,0.2);">
                <div>
                    <label style="cursor:pointer;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);
                                  border-radius:8px;padding:8px 16px;color:#F0EEF8;font-size:13px;display:inline-block;">
                        <i class="fas fa-camera"></i> Ganti Foto
                        <input type="file" name="profile_picture" id="avatarInput" accept="image/*" style="display:none;">
                    </label>
                    <p style="color:rgba(240,238,248,0.4);font-size:11px;margin-top:6px;">JPG, PNG, WEBP. Maks 2MB.</p>
                </div>
            </div>

            <div style="margin-bottom:14px;">
                <label style="display:block;color:rgba(240,238,248,0.6);font-size:13px;margin-bottom:6px;">Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($me['username']); ?>"
                       style="width:100%;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);
                              border-radius:10px;padding:12px 14px;color:#F0EEF8;font-size:14px;outline:none;font-family:inherit;">
            </div>

            <div style="margin-bottom:14px;">
                <label style="display:block;color:rgba(240,238,248,0.6);font-size:13px;margin-bottom:6px;">Display Name</label>
                <input type="text" name="display_name" value="<?php echo htmlspecialchars($me['display_name'] ?? ''); ?>"
                       style="width:100%;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);
                              border-radius:10px;padding:12px 14px;color:#F0EEF8;font-size:14px;outline:none;font-family:inherit;">
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block;color:rgba(240,238,248,0.6);font-size:13px;margin-bottom:6px;">Bio</label>
                <textarea name="bio" rows="3"
                          style="width:100%;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);
                                 border-radius:10px;padding:12px 14px;color:#F0EEF8;font-size:14px;outline:none;
                                 resize:vertical;font-family:inherit;"><?php echo htmlspecialchars($me['bio'] ?? ''); ?></textarea>
            </div>

            <?php if (!empty($errors)): ?>
                <p style="color:#E84040;font-size:13px;margin-bottom:12px;"><?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?></p>
            <?php endif; ?>

            <div style="display:flex;gap:12px;justify-content:flex-end;">
                <button type="button" onclick="closeEditModal()"
                        style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.1);
                               border-radius:10px;padding:10px 20px;color:#F0EEF8;cursor:pointer;font-size:14px;font-family:inherit;">
                    Batal
                </button>
                <button type="submit"
                        style="background:#6C5CE7;border:none;border-radius:10px;padding:10px 24px;
                               color:#fff;cursor:pointer;font-size:14px;font-weight:600;font-family:inherit;">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal() {
    document.getElementById('editProfileModal').style.display = 'flex';
}
function closeEditModal() {
    document.getElementById('editProfileModal').style.display = 'none';
}
document.getElementById('editProfileModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});
document.getElementById('avatarInput').addEventListener('change', function() {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
        reader.readAsDataURL(this.files[0]);
    }
});
<?php if (!empty($errors)): ?>
document.addEventListener('DOMContentLoaded', () => openEditModal());
<?php endif; ?>
</script>

<div id="profileMediaModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.95);z-index:10000;align-items:center;justify-content:center;flex-direction:column;" onclick="closeProfileMedia(event)">
    <button onclick="closeProfileMedia(null, true)" style="position:absolute;top:20px;right:20px;background:rgba(255,255,255,0.1);border:none;color:#fff;width:44px;height:44px;border-radius:50%;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background 0.2s;z-index:10001;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
        <i class="fas fa-times"></i>
    </button>
    <div id="profileMediaContent" style="position:relative;width:90vw;max-width:960px;display:flex;align-items:center;justify-content:center;">
    </div>
    <div style="display:flex;align-items:center;gap:32px;margin-top:24px;">
        <button onclick="prevProfileMedia()" style="background:rgba(255,255,255,0.1);border:none;color:rgba(255,255,255,0.8);width:52px;height:52px;border-radius:50%;font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
            <i class="fas fa-step-backward"></i>
        </button>
        <div id="profileMediaMeta" style="text-align:center;color:rgba(240,238,248,0.5);font-size:13px;min-width:80px;"></div>
        <button onclick="nextProfileMedia()" style="background:rgba(255,255,255,0.1);border:none;color:rgba(255,255,255,0.8);width:52px;height:52px;border-radius:50%;font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
            <i class="fas fa-step-forward"></i>
        </button>
    </div>
</div>

<script>
const profileContentList = <?php echo json_encode(array_values($db_content)); ?>;
let currentProfileIndex = 0;

function openProfileMedia(index) {
    if (!profileContentList || !profileContentList.length) return;
    currentProfileIndex = index;
    const item = profileContentList[index];
    const container = document.getElementById('profileMediaContent');
    container.innerHTML = '';
    
    if (item.tipe === 'video') {
        if (item.file_url) {
            container.innerHTML = `<video src="${item.file_url}" controls autoplay playsinline style="width:100%;max-height:75vh;border-radius:12px;background:#000;box-shadow:0 10px 30px rgba(0,0,0,0.5);"></video>`;
        } else if (item.media_id) {
            container.innerHTML = `<iframe src="https://www.youtube.com/embed/${item.media_id}?autoplay=1" frameborder="0" allowfullscreen style="width:100%;aspect-ratio:16/9;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,0.5);"></iframe>`;
        }
    } else if (item.tipe === 'music') {
        const cover = item.cover_url ? item.cover_url : item.thumb;
        container.innerHTML = `
            <div style="background:#1a1a2e;padding:32px;border-radius:16px;text-align:center;width:100%;max-width:400px;box-shadow:0 10px 30px rgba(0,0,0,0.5);">
                <img src="${cover}" style="width:200px;height:200px;border-radius:12px;object-fit:cover;margin:0 auto 20px;box-shadow:0 8px 24px rgba(0,0,0,0.4);">
                <h3 style="color:#fff;margin-bottom:8px;font-size:20px;">${item.judul}</h3>
                <p style="color:rgba(255,255,255,0.6);margin-bottom:24px;font-size:14px;">${item.artist || item.sumber || 'Unknown'}</p>
                <audio src="${item.file_url}" controls autoplay style="width:100%;"></audio>
            </div>
        `;
    } else if (item.tipe === 'quote') {
        if (item.file_url) {
            container.innerHTML = `<img src="${item.file_url}" style="max-width:100%;max-height:75vh;border-radius:12px;object-fit:contain;box-shadow:0 10px 30px rgba(0,0,0,0.5);">`;
        } else {
            container.innerHTML = `<div style="background:#2a2a3a;padding:48px;border-radius:16px;text-align:center;max-width:600px;color:#fff;box-shadow:0 10px 30px rgba(0,0,0,0.5);">
                <h2 style="font-family:'Playfair Display', serif;font-size:32px;margin-bottom:16px;line-height:1.4;">"${item.judul}"</h2>
                <p style="font-size:16px;opacity:0.8;">— ${item.sumber || 'Unknown'}</p>
            </div>`;
        }
    }
    
    document.getElementById('profileMediaMeta').textContent = (index + 1) + ' / ' + profileContentList.length;
    document.getElementById('profileMediaModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeProfileMedia(e, force) {
    const modal = document.getElementById('profileMediaModal');
    if (!force && e && e.target !== modal) return;
    modal.style.display = 'none';
    document.getElementById('profileMediaContent').innerHTML = '';
    document.body.style.overflow = '';
}

function prevProfileMedia() {
    const newIndex = (currentProfileIndex - 1 + profileContentList.length) % profileContentList.length;
    openProfileMedia(newIndex);
}

function nextProfileMedia() {
    const newIndex = (currentProfileIndex + 1) % profileContentList.length;
    openProfileMedia(newIndex);
}

document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('profileMediaModal');
    if (!modal || modal.style.display !== 'flex') return;
    if (e.key === 'Escape') closeProfileMedia(null, true);
    if (e.key === 'ArrowLeft') prevProfileMedia();
    if (e.key === 'ArrowRight') nextProfileMedia();
});
</script>

<script src="script.js?v=<?php echo time(); ?>"></script>
</body>
</html>
