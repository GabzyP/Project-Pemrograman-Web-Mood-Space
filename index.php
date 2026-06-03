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

$stmt_me = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt_me->bind_param("i", $_SESSION['user_id']);
$stmt_me->execute();
$me = $stmt_me->get_result()->fetch_assoc();
$stmt_me->close();

$activeMood = isset($_GET['mood']) ? $_GET['mood'] : '';
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'video';

$moodList = [
    'joy' => 'joy.png',
    'sadness' => 'sadness.png',
    'anger' => 'anger.png',
    'disgust' => 'disgust.png',
    'fear' => 'fear.png',
    'anxiety' => 'anxiety.png',
    'ennui' => 'ennui.png',
    'embarrassment' => 'embarrassement.png',
    'envy' => 'envy.png',
];

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

$moodColorsRGB = [
    'joy'           => '255, 214, 0',
    'sadness'       => '21, 101, 192',
    'anger'         => '198, 40, 40',
    'disgust'       => '46, 125, 50',
    'fear'          => '106, 27, 154',
    'anxiety'       => '239, 108, 0',
    'ennui'         => '97, 97, 97',
    'embarrassment' => '216, 27, 96',
    'envy'          => '0, 172, 193',
];

$moodTaglines = [
    'joy'           => 'Happiness is the best feeling!',
    'sadness'       => 'It\'s okay to feel blue sometimes',
    'anger'         => 'Let the fire fuel your strength',
    'disgust'       => 'Standards are everything',
    'fear'          => 'Be brave, even when scared',
    'anxiety'       => 'Breathe in, breathe out',
    'ennui'         => 'Finding meaning in the mundane',
    'embarrassment' => 'Everyone has awkward moments',
    'envy'          => 'Turn desire into motivation',
];

$musik = [];
$video = [];
$quotes = [];

if ($activeMood != '') {
    $current_user_id = $_SESSION['user_id'] ?? 0;
    $stmt = $conn->prepare("
        SELECT k.*,
               (SELECT COUNT(*) FROM likes WHERE konten_id = k.id) as like_count,
               (SELECT COUNT(*) FROM favorites WHERE konten_id = k.id) as fav_count,
               (SELECT COUNT(*) FROM likes WHERE konten_id = k.id AND user_id = ?) as user_liked,
               (SELECT COUNT(*) FROM favorites WHERE konten_id = k.id AND user_id = ?) as user_favorited
        FROM konten_mood k 
        WHERE k.mood = ?
    ");
    $stmt->bind_param("iis", $current_user_id, $current_user_id, $activeMood);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        if ($row['tipe'] == 'music') $musik[] = $row;
        elseif ($row['tipe'] == 'video') $video[] = $row;
        elseif ($row['tipe'] == 'quote') $quotes[] = $row;
    }
    $stmt->close();
}

if (!empty($musik)) {
    foreach ($musik as &$m) {
        $stmt_up = $conn->prepare(
            "SELECT display_name, username, profile_picture FROM users WHERE id = ?"
        );
        $stmt_up->bind_param("i", $m['uploaded_by']);
        $stmt_up->execute();
        $uploader = $stmt_up->get_result()->fetch_assoc();
        $stmt_up->close();
        
        $m['artist'] = $m['sumber'];
        $m['uploader_name'] = $uploader['display_name'] ?? $uploader['username'] ?? 'Unknown';
        $m['uploader_avatar'] = getAvatar($uploader['profile_picture'] ?? '');
    }
    unset($m);
}

if (!empty($video)) {
    foreach ($video as &$v) {
        $stmt_up = $conn->prepare(
            "SELECT display_name, username, profile_picture FROM users WHERE id = ?"
        );
        $stmt_up->bind_param("i", $v['uploaded_by']);
        $stmt_up->execute();
        $uploader = $stmt_up->get_result()->fetch_assoc();
        $stmt_up->close();
        
        $v['uploader_name'] = $uploader['display_name'] ?? $uploader['username'] ?? 'Unknown';
        $v['uploader_avatar'] = getAvatar($uploader['profile_picture'] ?? '');
    }
    unset($v);
}

$currentMoodColor = $moodColors[$activeMood] ?? '#FFD600';
$currentMoodRGB = $moodColorsRGB[$activeMood] ?? '255, 214, 0';
$isMoodActive = $activeMood !== '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MoodSpace — A premium emotion-driven streaming platform inspired by Inside Out 2. Explore music, videos, and quotes based on your mood.">
    <title>MoodSpace — <?php echo $activeMood ? ucfirst(htmlspecialchars($activeMood)) : 'Explore Your Emotions'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

    <?php if ($isMoodActive): ?>
    <style>
        :root {
            --mood-color: <?php echo $currentMoodColor; ?>;
            --mood-color-rgb: <?php echo $currentMoodRGB; ?>;
        }
    </style>
    <?php endif; ?>
</head>
<body class="<?php echo $isMoodActive ? 'mood-active' : ''; ?>">

    <nav class="ms-navbar" id="main-navbar">
        <div class="ms-navbar__left">
            <a href="index.php" title="MoodSpace Home">
                <img src="assets/logo.png" alt="MoodSpace Logo" class="ms-navbar__logo" style="height:32px;width:auto;max-width:160px;">
            </a>
        </div>

        <div class="ms-navbar__center">
            <div class="ms-search" style="position:relative;">
                <input 
                    type="text" 
                    class="ms-search__input" 
                    id="search-input" 
                    placeholder="Cari pengguna"
                    autocomplete="off"
                >
                <button type="button" class="ms-search__btn" id="search-btn">
                    <i class="fas fa-search"></i>
                </button>
                <div id="search-dropdown" style="
                    display: none;
                    position: absolute;
                    top: calc(100% + 6px);
                    left: 0;
                    right: 0;
                    background: var(--bg-secondary);
                    border: 1px solid var(--border-color);
                    border-radius: 12px;
                    box-shadow: 0 8px 32px rgba(0,0,0,0.2);
                    z-index: 9999;
                    overflow: hidden;
                    max-height: 360px;
                    overflow-y: auto;
                "></div>
            </div>
        </div>

        <div class="ms-navbar__right">
            <button type="button" class="ms-navbar__icon-btn theme-toggle" id="theme-toggle" title="Ubah Tema" onclick="toggleTheme()" style="background:transparent;border:none;cursor:pointer;color:var(--text-secondary);font-size:1.15rem;margin-right:10px;">
                <i class="fas fa-moon" id="theme-icon"></i>
            </button>
            <a href="logout.php" class="ms-navbar__icon-btn" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </a>
            <a href="profile.php?id=<?php echo $me['id']; ?>" class="ms-navbar__avatar" title="Profile">
                <img src="<?php echo getAvatar($me['profile_picture']); ?>" alt="Avatar" style="width:100%;height:100%;object-fit:cover;">
            </a>
        </div>
    </nav>

    <aside class="ms-sidebar" id="main-sidebar">
        <div class="ms-sidebar__section">
            <a href="index.php" class="ms-sidebar__item <?php echo !$isMoodActive ? 'active' : ''; ?>">
                <span class="ms-sidebar__item-icon"><i class="fas fa-home"></i></span>
                <span>Beranda</span>
            </a>
            <?php if ($isMoodActive): ?>
            <div class="ms-sidebar__tabs-row">
                <a href="?mood=<?php echo htmlspecialchars($activeMood); ?>&tab=video" class="ms-sidebar__tab-icon <?php echo $activeTab == 'video' ? 'active' : ''; ?>" title="Video">
                    <div class="logo-ring"></div>
                    <i class="fas fa-play"></i>
                </a>
                <a href="?mood=<?php echo htmlspecialchars($activeMood); ?>&tab=music" class="ms-sidebar__tab-icon <?php echo $activeTab == 'music' ? 'active' : ''; ?>" title="Musik">
                    <div class="logo-ring"></div>
                    <i class="fas fa-music"></i>
                </a>
                <a href="?mood=<?php echo htmlspecialchars($activeMood); ?>&tab=quote" class="ms-sidebar__tab-icon <?php echo $activeTab == 'quote' ? 'active' : ''; ?>" title="Quotes">
                    <div class="logo-ring"></div>
                    <i class="fas fa-quote-right"></i>
                </a>
            </div>
            <?php endif; ?>
        </div>

        <div class="ms-sidebar__divider"></div>

        <div class="ms-sidebar__label">Moods</div>
        <div class="ms-sidebar__section">
            <?php foreach ($moodList as $moodName => $moodImg): ?>
            <a href="?mood=<?php echo $moodName; ?>&tab=<?php echo $isMoodActive ? htmlspecialchars($activeTab) : 'video'; ?>" 
               class="ms-sidebar__item <?php echo $activeMood === $moodName ? 'active' : ''; ?>">
                <img src="assets/mood/<?php echo $moodImg; ?>" alt="<?php echo ucfirst($moodName); ?>" class="ms-sidebar__mood-avatar">
                <span><?php echo ucfirst($moodName); ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </aside>

    <main class="ms-main" id="main-content">

    <?php if (!$isMoodActive): ?>

        <div class="ms-mood-grid">
            <?php foreach ($moodList as $moodName => $moodImg): 
                $mColor = $moodColors[$moodName];
            ?>
            <a href="?mood=<?php echo $moodName; ?>&tab=video" class="ms-mood-select-card">
                <div class="ms-mood-select-card__bg" style="background: <?php echo $mColor; ?>;"></div>
                <img src="assets/mood/<?php echo $moodImg; ?>?v=<?php echo time(); ?>" alt="<?php echo ucfirst($moodName); ?>" class="ms-mood-select-card__avatar">
                <div class="ms-mood-select-card__overlay">
                    <div class="ms-mood-select-card__name"><?php echo ucfirst($moodName); ?></div>
                    <div class="ms-mood-select-card__tagline"><?php echo $moodTaglines[$moodName]; ?></div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

    <?php else: ?>



        <?php if ($activeTab == 'video'): ?>

                <div class="ms-grid">
                    <?php $loop_index = 0; foreach ($video as $v): ?>
                    <div class="ms-card">
                        <?php if (!empty($v['file_url'])): ?>
                            <div class="ms-video-thumb" 
                                 data-src="<?php echo htmlspecialchars($v['file_url']); ?>"
                                 data-index="<?php echo $loop_index; ?>"
                                 onclick="openVideoModal(<?php echo $loop_index; ?>)"
                                 style="position:relative;width:100%;padding-top:56.25%;background:#111;border-radius:12px;overflow:hidden;cursor:pointer;">
                                <video 
                                    src="<?php echo htmlspecialchars($v['file_url']); ?>#t=0.5"
                                    style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;"
                                    preload="metadata"
                                    muted
                                    playsinline></video>
                                <div style="position:absolute;inset:0;background:rgba(0,0,0,0.3);transition:background 0.2s;" class="video-overlay"></div>
                                <div style="
                                    position:absolute;
                                    top:50%;left:50%;
                                    transform:translate(-50%,-50%);
                                    display:flex;align-items:center;justify-content:center;
                                    transition:transform 0.2s;
                                " class="play-btn-big">
                                    <i class="fas fa-play" style="color:#000000;font-size:32px;"></i>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="ms-card__thumb" id="thumb-<?php echo htmlspecialchars($v['media_id']); ?>" onclick="playVideo(this, '<?php echo htmlspecialchars($v['media_id']); ?>')">
                                <img src="https://img.youtube.com/vi/<?php echo htmlspecialchars($v['media_id']); ?>/mqdefault.jpg" alt="<?php echo htmlspecialchars($v['judul']); ?>" style="width: 100%; height: auto; display: block;">
                                <div class="ms-card__play-overlay"><i class="fas fa-play"></i></div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="ms-card__meta" style="display:flex;align-items:flex-start;">
                            <a href="profile.php?id=<?php echo $v['uploaded_by']; ?>">
                                <img src="<?php echo htmlspecialchars($v['uploader_avatar'] ?? ''); ?>" alt="<?php echo htmlspecialchars($v['uploader_name'] ?? ''); ?>" class="ms-card__channel-avatar">
                            </a>
                            <div class="ms-card__info" style="flex:1;">
                                <div class="ms-card__title"><?php echo htmlspecialchars($v['judul']); ?></div>
                                <div class="ms-card__channel-name">
                                    <a href="profile.php?id=<?php echo $v['uploaded_by']; ?>" style="text-decoration:none; color:inherit; margin-right: 4px;">
                                        <?php echo htmlspecialchars($v['uploader_name'] ?? $v['sumber']); ?>
                                    </a>
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:16px;margin-left:auto;padding-left:12px;">
                                <span class="btn-action btn-like" data-id="<?php echo $v['id']; ?>" data-type="like" style="cursor:pointer;padding:8px;" title="Like">
                                    <i class="fas fa-heart <?php echo !empty($v['user_liked']) ? 'active' : ''; ?>" style="<?php echo !empty($v['user_liked']) ? 'color:#E84040;' : 'color:var(--text-muted);'; ?>transition:color 0.2s;font-size:20px;"></i>
                                </span>
                                <span class="btn-action btn-favorit" data-id="<?php echo $v['id']; ?>" data-type="favorite" style="cursor:pointer;padding:8px;" title="Favorite">
                                    <i class="fas fa-bookmark <?php echo !empty($v['user_favorited']) ? 'active' : ''; ?>" style="<?php echo !empty($v['user_favorited']) ? 'color:#FFD600;' : 'color:var(--text-muted);'; ?>transition:color 0.2s;font-size:20px;"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php $loop_index++; endforeach; ?>
                </div>

        <?php elseif ($activeTab == 'music'): ?>

<?php if (!empty($musik)): ?>
<div id="trackListView">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="border-bottom:1px solid var(--border-color);color:var(--text-muted);
                       font-size:12px;text-transform:uppercase;letter-spacing:0.05em;">
                <th style="padding:8px 16px;text-align:left;width:40px;">#</th>
                <th style="padding:8px 16px;text-align:left;width:40%;">Judul</th>
                <th style="padding:8px 16px;text-align:left;width:20%;">Added by</th>
                <th style="padding:8px 16px;text-align:left;width:15%;">Date added</th>
                <th style="padding:8px 16px;text-align:right;width:60px;"><i class="far fa-clock" style="font-size: 14px;"></i></th>
                <th style="padding:8px 16px;text-align:right;width:80px;"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($musik as $i => $m): ?>
            <?php
                $trackMoodColors = [
                    'joy'=>'#8B7A00','sadness'=>'#1a3a5c','anger'=>'#5c1a1a',
                    'disgust'=>'#1a4a1a','fear'=>'#3a1a5c','anxiety'=>'#5c3a1a',
                    'ennui'=>'#2a2a3a','embarrassment'=>'#5c2a3a','envy'=>'#1a4a4a'
                ];
                $bgColor = $trackMoodColors[$activeMood] ?? '#2a2a3a';
            ?>
            <tr class="track-row"
                onclick="if(!event.target.closest('.btn-action')){ playTrack(<?php echo $i; ?>); }"
                data-index="<?php echo $i; ?>"
                style="cursor:pointer;border-radius:8px;transition:background 0.15s;"
                onmouseover="this.style.background='var(--border-color-hover)'"
                onmouseout="this.style.background='transparent'">
                <td style="padding:12px 16px;color:var(--text-muted);font-size:14px;">
                    <span class="track-num"><?php echo $i + 1; ?></span>
                    <i class="fas fa-play track-play-icon" style="display:none;font-size:12px;color:var(--text-primary);"></i>
                </td>
                <td style="padding:12px 16px;">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <?php if (!empty($m['cover_url'])): ?>
                            <img src="<?php echo htmlspecialchars($m['cover_url']); ?>"
                                 style="width:48px;height:48px;border-radius:4px;object-fit:cover;flex-shrink:0;">
                        <?php else: ?>
                            <div style="width:48px;height:48px;border-radius:4px;background:<?php echo $bgColor; ?>;
                                        display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-music" style="color:var(--text-muted);font-size:16px;"></i>
                            </div>
                        <?php endif; ?>
                        <div>
                            <div style="color:var(--text-primary);font-size:15px;font-weight:500;margin-bottom:2px;">
                                <?php echo htmlspecialchars($m['judul']); ?>
                            </div>
                            <div style="color:var(--text-secondary);font-size:13px;">
                                <?php echo htmlspecialchars($m['artist']); ?>
                            </div>
                        </div>
                    </div>
                </td>
                <td style="padding:12px 16px;text-align:left;">
                    <a href="profile.php?id=<?php echo $m['uploaded_by']; ?>" style="display:flex;align-items:center;gap:8px;text-decoration:none;">
                        <img src="<?php echo htmlspecialchars($m['uploader_avatar']); ?>" style="width:24px;height:24px;border-radius:50%;object-fit:cover;">
                        <span style="color:var(--text-secondary);font-size:13px;font-weight:500;">
                            <?php echo htmlspecialchars($m['uploader_name']); ?>
                        </span>
                    </a>
                </td>
                <td style="padding:12px 16px;color:var(--text-muted);font-size:14px;text-align:left;">
                    <?php 
                        echo !empty($m['created_at']) ? date('M j, Y', strtotime($m['created_at'])) : '—'; 
                    ?>
                </td>
                <td style="padding:12px 16px;color:var(--text-muted);font-size:14px;text-align:right;" class="track-duration">
                    <?php 
                        $durasi = trim($m['durasi'] ?? '');
                        echo htmlspecialchars($durasi !== '' ? $durasi : '—'); 
                    ?>
                </td>
                <td style="padding:12px 16px;text-align:right;">
                    <div style="display:flex;align-items:center;justify-content:flex-end;gap:16px;">
                        <span class="btn-action btn-like" data-id="<?php echo $m['id']; ?>" data-type="like" style="cursor:pointer;padding:8px;" title="Like">
                            <i class="fas fa-heart <?php echo !empty($m['user_liked']) ? 'active' : ''; ?>" style="<?php echo !empty($m['user_liked']) ? 'color:#E84040;' : 'color:var(--text-muted);'; ?>transition:color 0.2s;font-size:20px;"></i>
                        </span>
                        <span class="btn-action btn-favorit" data-id="<?php echo $m['id']; ?>" data-type="favorite" style="cursor:pointer;padding:8px;" title="Favorite">
                            <i class="fas fa-bookmark <?php echo !empty($m['user_favorited']) ? 'active' : ''; ?>" style="<?php echo !empty($m['user_favorited']) ? 'color:#FFD600;' : 'color:var(--text-muted);'; ?>transition:color 0.2s;font-size:20px;"></i>
                        </span>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="fullPlayerView" style="display:none;max-width:480px;margin:0 auto;padding:24px 0;">
    <button onclick="backToList()"
            style="background:none;border:none;color:rgba(240,238,248,0.6);cursor:pointer;
                   font-size:14px;margin-bottom:32px;padding:0;display:flex;align-items:center;gap:8px;
                   font-family:inherit;">
        <i class="fas fa-chevron-left"></i> Daftar Lagu
    </button>

    <div style="text-align:center;margin-bottom:28px;">
        <img id="playerCover" src="" alt="Cover"
             style="width:280px;height:280px;border-radius:12px;object-fit:cover;
                    box-shadow:0 20px 60px rgba(0,0,0,0.5);">
    </div>

    <div style="margin-bottom:20px;">
        <div id="playerTitle" style="font-size:22px;font-weight:700;color:#F0EEF8;margin-bottom:4px;"></div>
        <div id="playerArtist" style="font-size:15px;color:rgba(240,238,248,0.55);"></div>
    </div>

    <audio id="mainAudio" style="display:none;"></audio>

    <div style="margin-bottom:16px;">
        <input type="range" id="progressBar" value="0" min="0" step="0.1"
               style="width:100%;accent-color:#F0EEF8;cursor:pointer;height:4px;">
        <div style="display:flex;justify-content:space-between;margin-top:4px;">
            <span id="currentTime" style="font-size:12px;color:rgba(240,238,248,0.4);">0:00</span>
            <span id="totalTime" style="font-size:12px;color:rgba(240,238,248,0.4);">0:00</span>
        </div>
    </div>

    <div style="display:flex;align-items:center;justify-content:center;gap:32px;">
        <button onclick="prevTrack()"
                style="background:none;border:none;color:rgba(240,238,248,0.7);cursor:pointer;font-size:20px;">
            <i class="fas fa-step-backward"></i>
        </button>
        <button id="playPauseBtn" onclick="togglePlay()"
                style="width:56px;height:56px;border-radius:50%;background:#F0EEF8;border:none;
                       cursor:pointer;font-size:20px;color:#080810;display:flex;align-items:center;
                       justify-content:center;">
            <i class="fas fa-play"></i>
        </button>
        <button onclick="nextTrack()"
                style="background:none;border:none;color:rgba(240,238,248,0.7);cursor:pointer;font-size:20px;">
            <i class="fas fa-step-forward"></i>
        </button>
    </div>
</div>

<script>
const tracks = <?php echo json_encode(array_map(function($m) {
    return [
        'judul'     => $m['judul'],
        'artist'    => $m['artist'],
        'file_url'  => $m['file_url'] ?? '',
        'cover_url' => $m['cover_url'] ?? '',
        'durasi'    => $m['durasi'] ?? '',
    ];
}, $musik)); ?>;

let currentIndex = 0;
const audio = document.getElementById('mainAudio');
const defaultCover = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' fill='%232a2a3a'/%3E%3Ccircle cx='50' cy='50' r='20' fill='%23444'/%3E%3Ccircle cx='50' cy='50' r='8' fill='%232a2a3a'/%3E%3C/svg%3E";

function playTrack(index) {
    currentIndex = index;
    const t = tracks[index];
    document.getElementById('trackListView').style.display = 'none';
    document.getElementById('fullPlayerView').style.display = 'block';
    document.getElementById('playerTitle').textContent = t.judul;
    document.getElementById('playerArtist').textContent = t.artist;
    document.getElementById('playerCover').src = t.cover_url || defaultCover;
    audio.src = t.file_url;
    audio.play();
    updatePlayBtn(true);
}

function backToList() {
    audio.pause();
    document.getElementById('fullPlayerView').style.display = 'none';
    document.getElementById('trackListView').style.display = 'block';
}

function togglePlay() {
    if (audio.paused) { audio.play(); updatePlayBtn(true); }
    else { audio.pause(); updatePlayBtn(false); }
}

function updatePlayBtn(playing) {
    document.getElementById('playPauseBtn').innerHTML =
        playing ? '<i class="fas fa-pause"></i>' : '<i class="fas fa-play"></i>';
}

function prevTrack() {
    playTrack((currentIndex - 1 + tracks.length) % tracks.length);
}
function nextTrack() {
    playTrack((currentIndex + 1) % tracks.length);
}

audio.addEventListener('timeupdate', function() {
    if (audio.duration) {
        document.getElementById('progressBar').max = audio.duration;
        document.getElementById('progressBar').value = audio.currentTime;
        document.getElementById('currentTime').textContent = formatTime(audio.currentTime);
        document.getElementById('totalTime').textContent = formatTime(audio.duration);
    }
});
document.getElementById('progressBar').addEventListener('input', function() {
    audio.currentTime = this.value;
});
audio.addEventListener('ended', nextTrack);
audio.addEventListener('pause', function() { updatePlayBtn(false); });
audio.addEventListener('play', function() { updatePlayBtn(true); });

function formatTime(s) {
    var m = Math.floor(s / 60);
    var sec = Math.floor(s % 60);
    return m + ':' + (sec < 10 ? '0' : '') + sec;
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.track-row').forEach(function(row) {
        var idx = row.getAttribute('data-index');
        var t = tracks[idx];
        var durasiCell = row.querySelector('.track-duration');
        
        if (!t.durasi || String(t.durasi).indexOf(':') === -1) {
            if (t.file_url) {
                var tempAudio = new Audio(t.file_url);
                tempAudio.addEventListener('loadedmetadata', function() {
                    durasiCell.textContent = formatTime(tempAudio.duration);
                });
            }
        }
    });
});
</script>

<?php endif; ?>

        <?php elseif ($activeTab == 'quote'): ?>

<style>
.ms-masonry {
    columns: 3 240px;
    gap: 16px;
    padding: 0;
}
.ms-quote-card {
    display: inline-block;
    width: 100%;
    break-inside: avoid;
    margin-bottom: 16px;
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
}
.ms-quote-card__image {
    width: 100%;
    height: auto;
    display: block;
}
.ms-quote-card__hover {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.35);
    opacity: 0;
    transition: opacity 0.2s;
    display: flex;
    align-items: flex-end;
    justify-content: flex-end;
    padding: 12px;
    gap: 8px;
}
.ms-quote-card:hover .ms-quote-card__hover {
    opacity: 1;
}
.ms-quote-card__hover button {
    width: auto;
    padding: 0 12px;
    height: 36px;
    border-radius: 18px;
    border: none;
    background: rgba(255,255,255,0.15);
    color: #fff;
    font-size: 15px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    backdrop-filter: blur(4px);
}
.ms-quote-card__hover .btn-like:hover {
    background: rgba(232,64,64,0.3);
    color: #E84040;
}
.ms-quote-card__hover .btn-favorit:hover {
    background: rgba(255,214,0,0.3);
    color: #FFD600;
}
@media (max-width: 768px) {
    .ms-masonry { columns: 2; }
}
@media (max-width: 480px) {
    .ms-masonry { columns: 1; }
}
</style>

                <div class="ms-masonry">
                    <?php foreach ($quotes as $q): ?>
                    <div class="ms-quote-card">
                        <?php if (!empty($q['file_url'])): ?>
                            <img src="<?php echo htmlspecialchars($q['file_url']); ?>" alt="Quote Image" class="ms-quote-card__image">
                        <?php elseif (!empty($q['media_id'])): ?>
                            <img src="<?php echo htmlspecialchars($q['media_id']); ?>" alt="Quote Image" class="ms-quote-card__image">
                        <?php endif; ?>
                        <div class="ms-quote-card__hover">
                            <button class="btn-action btn-like" data-id="<?php echo $q['id']; ?>" data-type="like" title="Like" style="<?php echo !empty($q['user_liked']) ? 'background:rgba(232,64,64,0.3);color:#E84040;' : ''; ?>">
                                <i class="fas fa-heart"></i>
                                <span style="font-size:12px;margin-left:4px;" class="count-text"><?php echo number_format($q['like_count'] ?? 0); ?></span>
                            </button>
                            <button class="btn-action btn-favorit" data-id="<?php echo $q['id']; ?>" data-type="favorite" title="Favorit" style="<?php echo !empty($q['user_favorited']) ? 'background:rgba(255,214,0,0.3);color:#FFD600;' : ''; ?>">
                                <i class="fas fa-bookmark"></i>
                                <span style="font-size:12px;margin-left:4px;" class="count-text"><?php echo number_format($q['fav_count'] ?? 0); ?></span>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
        <?php endif; ?>

    <?php endif; ?>

    </main>

    <script>
    function playVideo(card, id) {
        var thumb = card;
        if (thumb.classList.contains('playing')) return;
        thumb.classList.add('playing');
        var img = thumb.querySelector('img');
        if (img) img.style.display = 'none';
        var overlay = thumb.querySelector('.ms-card__play-overlay');
        if (overlay) overlay.style.display = 'none';
        var iframe = document.createElement('iframe');
        iframe.src = 'https://www.youtube.com/embed/' + id + '?autoplay=1&controls=1&modestbranding=1&rel=0';
        iframe.setAttribute('frameborder', '0');
        iframe.setAttribute('allowfullscreen', '');
        iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
        iframe.style.position = 'absolute';
        iframe.style.top = '0';
        iframe.style.left = '0';
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        thumb.style.position = 'relative';
        thumb.style.paddingTop = '56.25%';
        thumb.appendChild(iframe);
    }
    </script>

<!-- Video Fullscreen Modal -->
<div id="videoModal" style="
    display:none;
    position:fixed;inset:0;
    background:rgba(0,0,0,0.95);
    z-index:10000;
    align-items:center;justify-content:center;
    flex-direction:column;
" onclick="closeVideoModal(event)">

    <button onclick="closeVideoModal(null, true)" style="
        position:absolute;top:20px;right:20px;
        background:rgba(255,255,255,0.1);border:none;
        color:#fff;width:44px;height:44px;border-radius:50%;
        font-size:20px;cursor:pointer;display:flex;
        align-items:center;justify-content:center;
        transition:background 0.2s;z-index:10001;
    " onmouseover="this.style.background='rgba(255,255,255,0.2)'"
       onmouseout="this.style.background='rgba(255,255,255,0.1)'">
        <i class="fas fa-times"></i>
    </button>

    <div id="videoModalTitle" style="
        color:#F0EEF8;font-size:16px;font-weight:600;
        margin-bottom:16px;text-align:center;
        max-width:80%;opacity:0.9;
    "></div>

    <div style="position:relative;width:90vw;max-width:960px;">
        <video id="modalVideo" controls style="
            width:100%;border-radius:12px;
            max-height:75vh;background:#000;
            display:block;
        "></video>
    </div>

    <div style="
        display:flex;align-items:center;gap:32px;
        margin-top:24px;
    ">
        <button onclick="prevVideo()" style="
            background:rgba(255,255,255,0.1);border:none;
            color:rgba(255,255,255,0.8);
            width:52px;height:52px;border-radius:50%;
            font-size:18px;cursor:pointer;
            display:flex;align-items:center;justify-content:center;
            transition:all 0.2s;
        " onmouseover="this.style.background='rgba(255,255,255,0.2)'"
           onmouseout="this.style.background='rgba(255,255,255,0.1)'">
            <i class="fas fa-step-backward"></i>
        </button>

        <div id="videoModalMeta" style="
            text-align:center;
            color:rgba(240,238,248,0.5);
            font-size:13px;
            min-width:80px;
        "></div>

        <button onclick="nextVideo()" style="
            background:rgba(255,255,255,0.1);border:none;
            color:rgba(255,255,255,0.8);
            width:52px;height:52px;border-radius:50%;
            font-size:18px;cursor:pointer;
            display:flex;align-items:center;justify-content:center;
            transition:all 0.2s;
        " onmouseover="this.style.background='rgba(255,255,255,0.2)'"
           onmouseout="this.style.background='rgba(255,255,255,0.1)'">
            <i class="fas fa-step-forward"></i>
        </button>
    </div>
</div>

<script>
const videoList = <?php echo json_encode(array_values(array_filter(array_map(function($v) {
    if (empty($v['file_url'])) return null;
    return [
        'judul'    => $v['judul'],
        'sumber'   => $v['sumber'],
        'file_url' => $v['file_url'],
    ];
}, $video)))); ?>;

let currentVideoIndex = 0;
const modalEl = document.getElementById('videoModal');
const modalVideo = document.getElementById('modalVideo');
const modalTitle = document.getElementById('videoModalTitle');
const modalMeta = document.getElementById('videoModalMeta');

function openVideoModal(index) {
    if (!videoList || !videoList.length) return;
    document.querySelectorAll('.ms-video-thumb video').forEach(v => v.pause());
    
    currentVideoIndex = index;
    const v = videoList[index];
    if (!v) return;
    
    modalVideo.src = v.file_url;
    modalTitle.textContent = v.judul;
    modalMeta.textContent = (index + 1) + ' / ' + videoList.length;
    
    modalEl.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    modalVideo.play().catch(() => {});
}

function closeVideoModal(e, force) {
    if (!force && e && e.target !== modalEl) return;
    if (modalVideo) {
        modalVideo.pause();
        modalVideo.src = '';
    }
    if (modalEl) {
        modalEl.style.display = 'none';
    }
    document.body.style.overflow = '';
}

function prevVideo() {
    if (!videoList || !videoList.length) return;
    const newIndex = (currentVideoIndex - 1 + videoList.length) % videoList.length;
    openVideoModal(newIndex);
}

function nextVideo() {
    if (!videoList || !videoList.length) return;
    const newIndex = (currentVideoIndex + 1) % videoList.length;
    openVideoModal(newIndex);
}

document.addEventListener('keydown', function(e) {
    if (!modalEl || modalEl.style.display !== 'flex') return;
    if (e.key === 'Escape') closeVideoModal(null, true);
    if (e.key === 'ArrowLeft') prevVideo();
    if (e.key === 'ArrowRight') nextVideo();
});

if (modalVideo) {
    modalVideo.addEventListener('ended', nextVideo);
}
</script>

<script>
(function() {
    const searchInput = document.getElementById('search-input');
    const dropdown = document.getElementById('search-dropdown');
    if (!searchInput || !dropdown) return;
    
    let debounceTimer = null;
    
    const defaultAvatar = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='50' fill='%23555'/%3E%3Ccircle cx='50' cy='38' r='18' fill='%23888'/%3E%3Cellipse cx='50' cy='85' rx='28' ry='20' fill='%23888'/%3E%3C/svg%3E";
    
    function renderDropdown(users, query) {
        if (!users.length) {
            dropdown.innerHTML = '<div style="padding:16px;text-align:center;color:var(--text-muted);font-size:14px;">Tidak ada pengguna ditemukan</div>';
            dropdown.style.display = 'block';
            return;
        }
        
        dropdown.innerHTML = users.map(u => {
            const avatar = u.avatar || defaultAvatar;
            const isCreator = u.role === 'creator';
            const nameHighlighted = highlightMatch(u.display_name || u.username, query);
            const usernameHighlighted = highlightMatch('@' + u.username, query);
            
            return `
                <a href="${u.profile_url}" style="
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    padding: 12px 16px;
                    text-decoration: none;
                    border-bottom: 1px solid var(--border-color);
                    transition: background 0.15s;
                " 
                onmouseover="this.style.background='var(--border-color-hover)'"
                onmouseout="this.style.background='transparent'">
                    <img src="${avatar}" style="
                        width: 40px; height: 40px;
                        border-radius: 50%;
                        object-fit: cover;
                        flex-shrink: 0;
                        background: #333;
                    " onerror="this.src='${defaultAvatar}'">
                    <div style="flex:1;min-width:0;">
                        <div style="
                            color: var(--text-primary);
                            font-size: 14px;
                            font-weight: 600;
                            display: flex;
                            align-items: center;
                            gap: 6px;
                        ">
                            ${nameHighlighted}
                            ${isCreator ? '<i class="fas fa-check-circle" style="color:#6C5CE7;font-size:12px;"></i>' : ''}
                        </div>
                        <div style="color:var(--text-secondary);font-size:12px;margin-top:2px;">
                            ${usernameHighlighted}
                        </div>
                    </div>
                </a>
            `;
        }).join('');
        
        dropdown.style.display = 'block';
    }
    
    function highlightMatch(text, query) {
        if (!query) return text;
        const regex = new RegExp('(' + query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
        return text.replace(regex, '<mark style="background:rgba(108,92,231,0.4);color:var(--text-primary);border-radius:2px;padding:0 1px;">$1</mark>');
    }
    
    async function doSearch(q) {
        if (q.length < 1) {
            dropdown.style.display = 'none';
            return;
        }
        try {
            const res = await fetch('api_search.php?q=' + encodeURIComponent(q));
            const users = await res.json();
            renderDropdown(users, q);
        } catch (err) {
            console.error('Search error:', err);
        }
    }
    
    searchInput.addEventListener('input', function() {
        const q = this.value.trim();
        clearTimeout(debounceTimer);
        if (q.length === 0) {
            dropdown.style.display = 'none';
            return;
        }
        debounceTimer = setTimeout(() => doSearch(q), 250);
    });
    
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });
    
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            dropdown.style.display = 'none';
            this.blur();
        }
    });
})();
</script>

    <script src="script.js?v=<?php echo time(); ?>"></script>
</body>
</html>
