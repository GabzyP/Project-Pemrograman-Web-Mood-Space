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
    'joy' => 'joy.jpg',
    'sadness' => 'sadness.jpg',
    'anger' => 'anger.png',
    'disgust' => 'disgust.jpg',
    'fear' => 'fear.jpg',
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
    $stmt = $conn->prepare("SELECT * FROM konten_mood WHERE mood = ?");
    $stmt->bind_param("s", $activeMood);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        if ($row['tipe'] == 'music') $musik[] = $row;
        elseif ($row['tipe'] == 'video') $video[] = $row;
        elseif ($row['tipe'] == 'quote') $quotes[] = $row;
    }
    $stmt->close();
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
            <form action="profile.php" method="GET" class="ms-search">
                <input type="text" name="u" class="ms-search__input" id="search-input" placeholder="Cari pengguna" required>
                <button type="submit" class="ms-search__btn" title="Search">
                    <i class="fas fa-search"></i>
                </button>
            </form>
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
            <a href="?mood=<?php echo htmlspecialchars($activeMood); ?>&tab=video" class="ms-sidebar__item <?php echo $activeTab == 'video' ? 'active' : ''; ?>">
                <span class="ms-sidebar__item-icon"><i class="fas fa-play"></i></span>
                <span>Video</span>
            </a>
            <a href="?mood=<?php echo htmlspecialchars($activeMood); ?>&tab=music" class="ms-sidebar__item <?php echo $activeTab == 'music' ? 'active' : ''; ?>">
                <span class="ms-sidebar__item-icon"><i class="fas fa-music"></i></span>
                <span>Musik</span>
            </a>
            <a href="?mood=<?php echo htmlspecialchars($activeMood); ?>&tab=quote" class="ms-sidebar__item <?php echo $activeTab == 'quote' ? 'active' : ''; ?>">
                <span class="ms-sidebar__item-icon"><i class="fas fa-quote-right"></i></span>
                <span>Quotes</span>
            </a>
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
                <div class="ms-mood-select-card__bg" style="background: linear-gradient(135deg, <?php echo $mColor; ?>33 0%, <?php echo $mColor; ?>88 100%); "></div>
                <img src="assets/mood/<?php echo $moodImg; ?>" alt="<?php echo ucfirst($moodName); ?>" class="ms-mood-select-card__avatar">
                <div class="ms-mood-select-card__overlay">
                    <div class="ms-mood-select-card__name"><?php echo ucfirst($moodName); ?></div>
                    <div class="ms-mood-select-card__tagline"><?php echo $moodTaglines[$moodName]; ?></div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

    <?php else: ?>

        <div class="ms-mood-header">
            <img src="assets/mood/<?php echo $moodList[$activeMood] ?? 'joy.jpg'; ?>" 
                 alt="<?php echo ucfirst($activeMood); ?>" 
                 class="ms-mood-header__avatar">
            <div class="ms-mood-header__info">
                <h1 class="ms-mood-header__title"><?php echo htmlspecialchars($activeMood); ?></h1>
                <p class="ms-mood-header__subtitle"><?php echo $moodTaglines[$activeMood] ?? 'Explore this mood'; ?> • MoodSpace</p>
            </div>

        </div>

        <?php if ($activeTab == 'video'): ?>

            <?php if (empty($video)): ?>
                <div class="ms-empty">
                    <div class="ms-empty__icon"><i class="fas fa-film"></i></div>
                    <p class="ms-empty__text">Belum ada konten video untuk mood ini. Hubungi tim kreator untuk menambahkan media baru.</p>
                </div>
            <?php else: ?>
                <div class="ms-grid">
                    <?php foreach ($video as $v): ?>
                    <div class="ms-card" onclick="playVideo(this, '<?php echo htmlspecialchars($v['media_id']); ?>')">
                        <div class="ms-card__thumb" id="thumb-<?php echo htmlspecialchars($v['media_id']); ?>">
                            <img src="https://img.youtube.com/vi/<?php echo htmlspecialchars($v['media_id']); ?>/hqdefault.jpg" 
                                 alt="<?php echo htmlspecialchars($v['judul']); ?>">
                            <div class="ms-card__play-overlay">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                        <div class="ms-card__meta">
                            <img src="assets/mood/<?php echo $moodList[$activeMood] ?? 'joy.jpg'; ?>" 
                                 alt="<?php echo ucfirst($activeMood); ?>" 
                                 class="ms-card__channel-avatar">
                            <div class="ms-card__info">
                                <div class="ms-card__title"><?php echo htmlspecialchars($v['judul']); ?></div>
                                <div class="ms-card__channel-name">
                                    <?php echo htmlspecialchars($v['sumber']); ?>
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php elseif ($activeTab == 'music'): ?>

            <?php if (empty($musik)): ?>
                <div class="ms-empty">
                    <div class="ms-empty__icon"><i class="fas fa-compact-disc"></i></div>
                    <p class="ms-empty__text">Belum ada konten musik untuk mood ini. Hubungi tim kreator untuk menambahkan media baru.</p>
                </div>
            <?php else: ?>
                <div class="ms-grid">
                    <?php foreach ($musik as $m): ?>
                    <div class="ms-music-card">
                        <div class="ms-music-card__embed">
                            <iframe 
                                src="https://open.spotify.com/embed/<?php echo htmlspecialchars($m['media_id']); ?>?utm_source=generator&theme=0" 
                                allowfullscreen 
                                allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" 
                                loading="lazy">
                            </iframe>
                        </div>
                        <div class="ms-music-card__body">
                            <div class="ms-music-card__title"><?php echo htmlspecialchars($m['judul']); ?></div>
                            <div class="ms-music-card__artist"><?php echo htmlspecialchars($m['sumber']); ?></div>
                            <a href="https://open.spotify.com/<?php echo htmlspecialchars($m['media_id']); ?>" 
                               target="_blank" 
                               class="ms-music-card__link">
                                <i class="fab fa-spotify"></i> Dengarkan
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php elseif ($activeTab == 'quote'): ?>

            <?php if (empty($quotes)): ?>
                <div class="ms-empty">
                    <div class="ms-empty__icon"><i class="fas fa-comment-dots"></i></div>
                    <p class="ms-empty__text">Belum ada kutipan untuk mood ini. Hubungi tim kreator untuk menambahkan konten baru.</p>
                </div>
            <?php else: ?>
                <div class="ms-masonry">
                    <?php foreach ($quotes as $q): ?>
                    <div class="ms-quote-card">
                        <?php if (!empty($q['media_id'])): ?>
                        <img src="<?php echo htmlspecialchars($q['media_id']); ?>" alt="Quote Image" class="ms-quote-card__image">
                        <?php endif; ?>
                        <div class="ms-quote-card__body">
                            <div class="ms-quote-card__text"><?php echo htmlspecialchars($q['judul']); ?></div>
                            <div class="ms-quote-card__author">— <?php echo htmlspecialchars($q['sumber']); ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    <?php endif; ?>

    </main>


    <script>
    function playVideo(card, id) {
        var thumb = card.querySelector('.ms-card__thumb');
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
        thumb.appendChild(iframe);
    }
    </script>

</body>
</html>
