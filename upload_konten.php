<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
require 'koneksi.php';

$stmt_role = $conn->prepare("SELECT role, username FROM users WHERE id = ?");
$stmt_role->bind_param("i", $_SESSION['user_id']);
$stmt_role->execute();
$me = $stmt_role->get_result()->fetch_assoc();
$stmt_role->close();

if ($me['role'] !== 'creator') { header("Location: profile.php?id=" . $_SESSION['user_id']); exit; }

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mood     = trim($_POST['mood'] ?? '');
    $tipe     = trim($_POST['tipe'] ?? '');
    $judul    = trim($_POST['judul'] ?? '');
    $sumber   = trim($_POST['sumber'] ?? '');
    $media_id = trim($_POST['media_id'] ?? '');

    $valid_moods = ['joy','sadness','anger','disgust','fear','anxiety','ennui','embarrassment','envy'];
    $valid_types = ['video','music','quote'];

    if (!in_array($mood, $valid_moods)) $error = "Pilih mood yang valid.";
    elseif (!in_array($tipe, $valid_types)) $error = "Pilih tipe konten yang valid.";
    elseif (empty($judul)) $error = "Judul tidak boleh kosong.";
    elseif (empty($sumber)) $error = "Sumber tidak boleh kosong.";
    elseif (empty($media_id)) $error = "Media ID tidak boleh kosong.";
    else {
        $stmt = $conn->prepare(
            "INSERT INTO konten_mood (uploaded_by, mood, tipe, judul, sumber, media_id) VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("isssss", $_SESSION['user_id'], $mood, $tipe, $judul, $sumber, $media_id);
        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = "Gagal menyimpan konten. Coba lagi.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Konten — MoodSpace</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <style>
        body { font-family: 'Sora', sans-serif; background: #080810; color: #F0EEF8; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 24px; }
        .upload-card { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 36px; width: 100%; max-width: 520px; }
        .upload-card h1 { font-size: 22px; font-weight: 700; margin-bottom: 8px; }
        .upload-card p.sub { color: rgba(240,238,248,0.5); font-size: 13px; margin-bottom: 28px; }
        .field { margin-bottom: 16px; }
        .field label { display: block; color: rgba(240,238,248,0.6); font-size: 13px; margin-bottom: 6px; }
        .field input, .field select, .field textarea {
            width: 100%; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px; padding: 12px 14px; color: #F0EEF8; font-size: 14px; font-family: inherit;
            outline: none; transition: border-color 0.2s;
        }
        .field input:focus, .field select:focus, .field textarea:focus { border-color: rgba(255,255,255,0.3); }
        .field select option { background: #1a1a2e; }
        .hint { font-size: 11px; color: rgba(240,238,248,0.35); margin-top: 5px; }
        .btn-row { display: flex; gap: 12px; justify-content: flex-end; margin-top: 8px; }
        .btn-cancel { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 10px 20px; color: #F0EEF8; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-block; }
        .btn-submit { background: #6C5CE7; border: none; border-radius: 10px; padding: 10px 28px; color: #fff; cursor: pointer; font-size: 14px; font-weight: 600; }
        .alert-success { background: rgba(46,125,50,0.15); border: 1px solid rgba(46,125,50,0.4); border-radius: 10px; padding: 12px 16px; color: #81c784; margin-bottom: 20px; font-size: 14px; }
        .alert-error { background: rgba(198,40,40,0.15); border: 1px solid rgba(198,40,40,0.4); border-radius: 10px; padding: 12px 16px; color: #ef9a9a; margin-bottom: 20px; font-size: 14px; }
        #mediaHelp { font-size: 11px; color: rgba(240,238,248,0.35); margin-top: 5px; }
    </style>
</head>
<body>
    <div class="upload-card">
        <h1><i class="fas fa-plus-circle" style="color:#6C5CE7;margin-right:8px;"></i>Upload Konten</h1>
        <p class="sub">Bagikan musik, video, atau kutipan ke komunitas MoodSpace.</p>

        <?php if ($success): ?>
            <div class="alert-success"><i class="fas fa-check-circle"></i> Konten berhasil diupload! <a href="profile.php?id=<?php echo $_SESSION['user_id']; ?>" style="color:#81c784;">Lihat profil</a></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="field">
                <label>Tipe Konten</label>
                <select name="tipe" id="tipeSelect" onchange="updateMediaHelp()">
                    <option value="">-- Pilih tipe --</option>
                    <option value="video" <?php echo ($_POST['tipe']??'')==='video'?'selected':''; ?>>Video</option>
                    <option value="music" <?php echo ($_POST['tipe']??'')==='music'?'selected':''; ?>>Musik</option>
                    <option value="quote" <?php echo ($_POST['tipe']??'')==='quote'?'selected':''; ?>>Quote</option>
                </select>
            </div>

            <div class="field">
                <label>Mood</label>
                <select name="mood">
                    <option value="">-- Pilih mood --</option>
                    <?php foreach(['joy','sadness','anger','disgust','fear','anxiety','ennui','embarrassment','envy'] as $m): ?>
                    <option value="<?php echo $m; ?>" <?php echo ($_POST['mood']??'')===$m?'selected':''; ?>><?php echo ucfirst($m); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label>Judul</label>
                <input type="text" name="judul" placeholder="Nama lagu / video / kutipan" value="<?php echo htmlspecialchars($_POST['judul']??''); ?>">
            </div>

            <div class="field">
                <label>Sumber / Artis / Channel</label>
                <input type="text" name="sumber" placeholder="Nama artis, channel YouTube, dll." value="<?php echo htmlspecialchars($_POST['sumber']??''); ?>">
            </div>

            <div class="field">
                <label>Media ID</label>
                <input type="text" name="media_id" placeholder="Masukkan ID" value="<?php echo htmlspecialchars($_POST['media_id']??''); ?>">
                <p id="mediaHelp">Pilih tipe konten dulu untuk melihat petunjuk.</p>
            </div>

            <div class="btn-row">
                <a href="profile.php?id=<?php echo $_SESSION['user_id']; ?>" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit"><i class="fas fa-upload"></i> Upload</button>
            </div>
        </form>
    </div>

    <script>
    function updateMediaHelp() {
        const tipe = document.getElementById('tipeSelect').value;
        const help = document.getElementById('mediaHelp');
        if (tipe === 'video') help.textContent = 'YouTube Video ID — contoh: dari https://youtube.com/watch?v=kn69n6DFsp4 ambil "kn69n6DFsp4"';
        else if (tipe === 'music') help.textContent = 'Spotify Track URI — contoh: dari https://open.spotify.com/track/4cBm8rv2B5BJWU2pDaHVbF ambil "track/4cBm8rv2B5BJWU2pDaHVbF"';
        else if (tipe === 'quote') help.textContent = 'Teks kutipan lengkap, atau ID referensi kutipan.';
        else help.textContent = 'Pilih tipe konten dulu untuk melihat petunjuk.';
    }
    </script>
</body>
</html>
