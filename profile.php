<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require 'koneksi.php';

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

if (isset($_GET['u'])) {
    $search_username = $_GET['u'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR username LIKE ?");
    $like_username = '%' . $search_username . '%';
    $stmt->bind_param("ss", $search_username, $like_username);
    $stmt->execute();
    $result = $stmt->get_result();
    $profile_user = $result->fetch_assoc();
    $stmt->close();
} else {
    $profile_id = isset($_GET['id']) ? (int)$_GET['id'] : $me['id'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $profile_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $profile_user = $result->fetch_assoc();
    $stmt->close();
}

if (!$profile_user) {
    die("User not found");
}

$profile_id = $profile_user['id'];

$is_owner = ($me['id'] === $profile_user['id']);
$profile_role = $profile_user['role'];
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : ($profile_role == 'creator' ? 'content' : 'favorites');

$db_content = [];
if ($activeTab === 'content' && $profile_user['role'] === 'creator') {
    $stmt_content = $conn->prepare(
        "SELECT * FROM konten_mood WHERE uploaded_by = ? ORDER BY created_at DESC LIMIT 20"
    );
    $stmt_content->bind_param("i", $profile_user['id']);
    $stmt_content->execute();
    $res_content = $stmt_content->get_result();
    while ($row_content = $res_content->fetch_assoc()) {
        if ($row_content['tipe'] === 'video') {
            $row_content['thumb'] = "https://img.youtube.com/vi/" . $row_content['media_id'] . "/hqdefault.jpg";
        } else {
            $row_content['thumb'] = "https://i.scdn.co/image/ab67616d00001e028b17b6a1888a7edaa622ff6b";
        }
        $row_content['views'] = rand(100, 5000);
        $db_content[] = $row_content;
    }
    $stmt_content->close();
}

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

$activeMood = 'joy';
$currentMoodColor = $moodColors[$activeMood];
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
    <style>
        :root {
            --mood-color: <?php echo $currentMoodColor; ?>;
            --mood-color-rgb: 255, 214, 0;
        }
    </style>
</head>
<body class="mood-active profile-page-body">

    <nav class="ms-navbar" id="main-navbar">
        <div class="ms-navbar__left">
            <a href="index.php" title="MoodSpace Home">
                <img src="assets/logo.png" alt="MoodSpace Logo" class="ms-navbar__logo" style="height:32px;width:auto;max-width:160px;">
            </a>
        </div>

        <div class="ms-navbar__right">
            <a href="form.html" class="ms-navbar__icon-btn" title="Contact">
                <i class="fas fa-headset"></i>
            </a>
            <a href="about.html" class="ms-navbar__icon-btn" title="About Us">
                <i class="fas fa-info-circle"></i>
            </a>
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
                        <strong><?php echo number_format($profile_user['followers_count']); ?></strong> <span>Followers</span>
                    </div>
                    <div class="ms-profile-stat">
                        <strong><?php echo number_format($profile_user['likes_count']); ?></strong> <span>Likes</span>
                    </div>
                </div>
                
                <div class="ms-profile-actions">
                    <?php if ($is_owner): ?>
                        <button class="ms-btn ms-btn-outline" onclick="openEditModal()">Edit profile</button>
                    <?php else: ?>
                        <button class="ms-btn ms-btn-primary">Follow</button>
                        <?php if ($profile_user['role'] === 'creator'): ?>
                            <button class="ms-btn ms-btn-outline">Message</button>
                        <?php endif; ?>
                        <button class="ms-btn ms-btn-icon" title="Share"><i class="fas fa-share"></i></button>
                    <?php endif; ?>
                </div>
                
                <p class="ms-profile-bio"><?php echo htmlspecialchars($profile_user['bio']); ?></p>
            </div>
        </header>

        <?php if ($is_owner && $profile_user['role'] === 'creator'): ?>
        <div style="display:flex;justify-content:flex-end;padding:0 0 12px 0;max-width:1200px;margin:0 auto;">
            <a href="upload_konten.php" 
               style="display:inline-flex;align-items:center;gap:8px;background:#6C5CE7;color:#fff;
                      text-decoration:none;border-radius:10px;padding:10px 20px;font-size:14px;font-weight:600;
                      transition:background 0.2s;">
                <i class="fas fa-plus"></i> Upload Konten
            </a>
        </div>
        <?php endif; ?>

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
                    <?php foreach ($db_content as $item): ?>
                    <div class="ms-profile-card group">
                        <img src="<?php echo htmlspecialchars($item['thumb']); ?>" alt="Thumbnail">
                        <div class="ms-profile-card-overlay">
                            <i class="fas fa-play"></i> <?php echo number_format($item['views']); ?>
                        </div>
                        <div class="ms-profile-card-privacy">
                            <i class="fas fa-lock text-xs"></i>
                        </div>
                    </div>
                    <?php endforeach; ?>
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

</body>
</html>
