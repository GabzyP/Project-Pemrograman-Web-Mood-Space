<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
require 'koneksi.php';
require 'cloudinary.config.php';
require 'vendor/autoload.php';

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

Configuration::instance([
    'cloud' => [
        'cloud_name' => CLOUDINARY_CLOUD_NAME, 
        'api_key' => CLOUDINARY_API_KEY, 
        'api_secret' => CLOUDINARY_API_SECRET
    ],
    'url' => [
        'secure' => true
    ]
]);

$stmt_role = $conn->prepare("SELECT role, username, display_name FROM users WHERE id = ?");
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
    $media_id = trim($_POST['media_id'] ?? '');
    $durasi   = trim($_POST['durasi'] ?? '');
    $sumber   = trim($_POST['sumber'] ?? '');

    if (empty($sumber)) {
        $sumber = $me['display_name'] ?? $me['username'];
    }

    if ($tipe === 'quote' && empty($judul)) {
        $judul = "Quote";
    }

    $valid_moods = ['joy','sadness','anger','disgust','fear','anxiety','ennui','embarrassment','envy'];
    $valid_types = ['video','music','quote'];

    if (!in_array($mood, $valid_moods)) $error = "Pilih mood yang valid.";
    elseif (!in_array($tipe, $valid_types)) $error = "Pilih tipe konten yang valid.";
    elseif (empty($judul)) $error = "Judul tidak boleh kosong.";
    else {
        $file_url = null;
        $public_id = null;
        $cover_url = null;

        if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
            try {
                $resource_type = ($tipe === 'video' || $tipe === 'music') ? 'video' : 'image';
                $uploadApi = new UploadApi();
                $result = $uploadApi->upload($_FILES['file_upload']['tmp_name'], [
                    'resource_type' => $resource_type,
                    'folder' => 'moodspace_konten'
                ]);
                $file_url = $result['secure_url'];
                $public_id = $result['public_id'];
                if (empty($durasi) && isset($result['duration'])) {
                    $total_seconds = round($result['duration']);
                    $minutes = floor($total_seconds / 60);
                    $seconds = $total_seconds % 60;
                    $durasi = sprintf("%d:%02d", $minutes, $seconds);
                }
            } catch (Exception $e) {
                $error = "Upload Cloudinary gagal: " . $e->getMessage();
            }
        }

        if ($tipe === 'music' && isset($_FILES['cover_image'])
            && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $allowed_cover = ['image/jpeg','image/png','image/webp'];
            $cover_mime = mime_content_type($_FILES['cover_image']['tmp_name']);
            if (in_array($cover_mime, $allowed_cover) && $_FILES['cover_image']['size'] <= 5*1024*1024) {
                try {
                    $cover_result = (new UploadApi())->upload(
                        $_FILES['cover_image']['tmp_name'],
                        ['resource_type'=>'image','folder'=>'moodspace/covers','unique_filename'=>true]
                    );
                    $cover_url = $cover_result['secure_url'];
                } catch (Exception $e) {
                    $error = "Upload cover gagal: " . $e->getMessage();
                }
            } else {
                $error = "Cover harus JPG/PNG/WEBP dan maksimal 5MB.";
            }
        }

        if ($tipe !== 'quote' && empty($media_id) && empty($file_url) && empty($error)) {
            $error = "Media ID atau File Upload harus diisi untuk konten Video/Musik.";
        }

        if (empty($durasi)) $durasi = null;

        if (empty($error)) {
            $stmt = $conn->prepare(
                "INSERT INTO konten_mood (uploaded_by, mood, tipe, judul, sumber, media_id, file_url, cover_url, public_id, durasi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("isssssssss", $_SESSION['user_id'], $mood, $tipe, $judul, $sumber, $media_id, $file_url, $cover_url, $public_id, $durasi);
            if ($stmt->execute()) {
                $success = true;
            } else {
                $error = "Gagal menyimpan konten. Coba lagi.";
            }
            $stmt->close();
        }
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
        .upload-section { background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.2); padding: 20px; border-radius: 10px; margin-bottom: 16px; text-align: center; }
        .upload-section input[type="file"] { display: none; }
        .upload-section label { display: inline-block; background: rgba(255,255,255,0.1); padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 13px; color: #F0EEF8; transition: background 0.2s; }
        .upload-section label:hover { background: rgba(255,255,255,0.2); }
        #fileName { display: block; margin-top: 10px; font-size: 12px; color: rgba(240,238,248,0.6); }
        .or-divider { text-align: center; color: rgba(240,238,248,0.3); font-size: 12px; margin: 16px 0; }
    </style>
</head>
<body>
    <div class="upload-card">
        <h1><i class="fas fa-plus-circle" style="color:#6C5CE7;margin-right:8px;"></i>Upload Konten</h1>
        <p class="sub">Bagikan musik, video, atau kutipan ke komunitas MoodSpace.</p>

        <?php if ($error): ?>
            <div class="alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" onsubmit="const btn = this.querySelector('.btn-submit'); btn.disabled = true; btn.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i> Mengupload...';">
            <div class="field">
                <label>Tipe Konten</label>
                <select name="tipe" id="tipeSelect" onchange="updateFormFields()">
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

            <div class="field" id="judulField">
                <label>Judul</label>
                <input type="text" name="judul" placeholder="Nama lagu / video / kutipan" value="<?php echo htmlspecialchars($_POST['judul']??''); ?>">
            </div>

            <div class="field" id="sumberField">
                <label>Sumber / Artis / Channel</label>
                <input type="text" name="sumber" placeholder="Nama artis, channel YouTube, dll." value="<?php echo htmlspecialchars($_POST['sumber']??''); ?>">
            </div>

            <div class="upload-section">
                <label for="fileUpload"><i class="fas fa-cloud-upload-alt"></i> Pilih File (Video/Audio/Gambar)</label>
                <input type="file" name="file_upload" id="fileUpload" accept="video/*,audio/*,image/*" onchange="document.getElementById('fileName').textContent = this.files[0]?.name || ''">
                <span id="fileName"></span>
            </div>

            <div class="field" id="coverField" style="display:none;">
                <label>Cover / Artwork <span style="color:rgba(240,238,248,0.35);font-size:11px;">(opsional)</span></label>
                <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp">
                <p class="hint">JPG, PNG, WEBP. Maks 5MB. Akan ditampilkan sebagai cover lagu.</p>
            </div>

            <div class="field" id="durasiField" style="display:none;">
                <label>Durasi</label>
                <input type="text" name="durasi" placeholder="Contoh: 3:45" maxlength="10" value="<?php echo htmlspecialchars($_POST['durasi']??''); ?>">
                <p class="hint">Format menit:detik. Akan ditampilkan di daftar lagu. Kosongkan untuk deteksi otomatis.</p>
            </div>

            <div class="or-divider">— ATAU GUNAKAN LINK / ID —</div>

            <div class="field">
                <label>Media ID</label>
                <input type="text" name="media_id" placeholder="Masukkan ID YouTube / Spotify" value="<?php echo htmlspecialchars($_POST['media_id']??''); ?>">
                <p id="mediaHelp">Pilih tipe konten dulu untuk melihat petunjuk.</p>
            </div>

            <div class="btn-row">
                <a href="profile.php?id=<?php echo $_SESSION['user_id']; ?>" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit"><i class="fas fa-upload"></i> Upload</button>
            </div>
        </form>

<?php if ($success): ?>
<div id="successModal" style="position:fixed;inset:0;background:rgba(0,0,0,0.8);z-index:999;
     display:flex;align-items:center;justify-content:center;animation:fadeInOverlay 0.3s ease;">
    <div style="background:#1a1a2e;border:1px solid rgba(255,255,255,0.1);border-radius:20px;padding:40px;
         text-align:center;max-width:380px;width:90%;animation:popIn 0.3s ease;">
        <i class="fas fa-check-circle" style="font-size:48px;color:#6C5CE7;margin-bottom:16px;display:block;"></i>
        <h2 style="color:#F0EEF8;font-size:20px;font-weight:700;margin-bottom:8px;">Konten Berhasil Diupload!</h2>
        <p style="color:rgba(240,238,248,0.5);font-size:14px;margin-bottom:24px;">Kamu akan diarahkan ke profil dalam 3 detik...</p>
        <div style="width:100%;height:4px;background:rgba(255,255,255,0.1);border-radius:4px;overflow:hidden;margin-bottom:20px;">
            <div style="height:100%;background:linear-gradient(90deg,#6C5CE7,#a29bfe);border-radius:4px;
                 animation:progressFill 3s linear forwards;width:0;"></div>
        </div>
        <a href="profile.php?id=<?php echo $_SESSION['user_id']; ?>"
           style="display:inline-block;background:#6C5CE7;color:#fff;text-decoration:none;
                  border-radius:10px;padding:10px 24px;font-size:14px;font-weight:600;
                  transition:background 0.2s;">Lihat Profil Sekarang</a>
    </div>
</div>
<style>
@keyframes fadeInOverlay { from { opacity: 0; } to { opacity: 1; } }
@keyframes popIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
@keyframes progressFill { from { width: 0; } to { width: 100%; } }
</style>
<script>
setTimeout(function() {
    window.location.href = 'profile.php?id=<?php echo $_SESSION['user_id']; ?>';
}, 3000);
</script>
<?php endif; ?>

    </div>

    <script>
    function updateFormFields() {
        var tipe = document.getElementById('tipeSelect').value;
        var help = document.getElementById('mediaHelp');
        var coverField = document.getElementById('coverField');
        var durasiField = document.getElementById('durasiField');
        var judulField = document.getElementById('judulField');
        var sumberField = document.getElementById('sumberField');

        if (tipe === 'video') help.textContent = 'YouTube Video ID — contoh: dari https://youtube.com/watch?v=kn69n6DFsp4 ambil "kn69n6DFsp4"';
        else if (tipe === 'music') help.textContent = 'Spotify Track URI — contoh: dari https://open.spotify.com/track/4cBm8rv2B5BJWU2pDaHVbF ambil "track/4cBm8rv2B5BJWU2pDaHVbF"';
        else if (tipe === 'quote') help.textContent = 'Teks kutipan lengkap, atau ID referensi kutipan. File upload akan berupa gambar background jika ada.';
        else help.textContent = 'Pilih tipe konten dulu untuk melihat petunjuk.';

        coverField.style.display = tipe === 'music' ? 'block' : 'none';
        durasiField.style.display = tipe === 'music' ? 'block' : 'none';
        
        sumberField.style.display = (tipe === 'video' || tipe === 'quote') ? 'none' : 'block';
        judulField.style.display = tipe === 'quote' ? 'none' : 'block';
    }
    updateFormFields();
    </script>
</body>
</html>
