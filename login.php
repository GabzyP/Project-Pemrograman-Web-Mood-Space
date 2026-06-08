<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'koneksi.php';

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Email dan password harus diisi.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user) {
            $valid = password_verify($password, $user['password'])
                     || ($password === $user['password']);

            if ($valid) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: index.php");
                exit;
            } else {
                $error = "Email atau password salah.";
            }
        } else {
            $error = "Email atau password salah.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in — MoodSpace</title>
    <meta name="description" content="Log in to MoodSpace — your emotion-driven streaming platform. Explore music, videos, and quotes based on your mood.">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-deep: #080810;
            --bg-surface: rgba(255,255,255,0.04);
            --bg-surface-hover: rgba(255,255,255,0.07);
            --border-subtle: rgba(255,255,255,0.08);
            --border-focus: rgba(255,255,255,0.25);
            --text-primary: #F0EEF8;
            --text-secondary: rgba(240,238,248,0.5);
            --accent-joy: #FFD600;
            --accent-sadness: #4A90D9;
            --accent-anger: #E84040;
            --accent-purple: #8B6FF5;
            --btn-primary: #6C5CE7;
            --btn-primary-hover: #7D6FF0;
        }

        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Sora', sans-serif;
            background: var(--bg-deep);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
        }

        .auth-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .auth-bg__blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            animation: drift 18s ease-in-out infinite;
        }

        .auth-bg__blob--joy {
            width: 420px;
            height: 420px;
            background: var(--accent-joy);
            opacity: 0.14;
            top: -8%;
            left: -5%;
            animation-delay: 0s;
        }

        .auth-bg__blob--sadness {
            width: 380px;
            height: 380px;
            background: var(--accent-sadness);
            opacity: 0.13;
            bottom: -10%;
            right: -5%;
            animation-delay: -6s;
        }

        .auth-bg__blob--purple {
            width: 340px;
            height: 340px;
            background: var(--accent-purple);
            opacity: 0.12;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: -12s;
        }

        @keyframes drift {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -20px) scale(1.05); }
            66% { transform: translate(-20px, 15px) scale(0.97); }
        }

        .auth-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            background: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-radius: 20px;
            padding: 40px 36px;
            animation: slideUp 0.5s cubic-bezier(0.22,1,0.36,1) forwards;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .auth-card__logo {
            display: flex;
            justify-content: center;
        }

        .ms-logo {
            height: 38px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 4px 16px rgba(255,214,0,0.25));
        }

        .auth-card__heading {
            text-align: center;
            margin-top: 20px;
        }

        .auth-card__title {
            font-size: 26px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.3;
        }

        .auth-card__subtitle {
            font-size: 14px;
            color: var(--text-secondary);
            margin-top: 6px;
            line-height: 1.5;
        }

        .auth-form {
            margin-top: 28px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .ms-input-group {
            position: relative;
        }

        .ms-input {
            width: 100%;
            background: rgba(255,255,255,0.06);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 14px 16px;
            color: var(--text-primary);
            font-size: 15px;
            font-family: 'Sora', sans-serif;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .ms-input::placeholder {
            color: var(--text-secondary);
        }

        .ms-input:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.15);
        }

        .ms-input--password {
            padding-right: 48px;
        }

        .ms-toggle-pw {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 16px;
            padding: 4px;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ms-toggle-pw:hover {
            color: var(--text-primary);
        }

        .ms-btn-primary {
            width: 100%;
            height: 48px;
            background: var(--btn-primary);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Sora', sans-serif;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.15s ease, box-shadow 0.3s ease;
            margin-top: 4px;
        }

        .ms-btn-primary:hover {
            background: var(--btn-primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(108, 92, 231, 0.35);
        }

        .ms-btn-primary:active {
            transform: translateY(0);
        }

        .auth-card__forgot {
            text-align: center;
            margin-top: 12px;
        }

        .auth-card__forgot a {
            font-size: 13px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.2s;
        }

        .auth-card__forgot a:hover {
            color: var(--text-primary);
        }

        .auth-bottom {
            position: relative;
            z-index: 1;
            text-align: center;
            margin-top: 28px;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .auth-bottom a {
            color: var(--accent-anger);
            text-decoration: none;
            font-weight: 600;
            transition: opacity 0.2s;
        }

        .auth-bottom a:hover {
            opacity: 0.8;
        }

        .auth-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 32px;
            font-size: 12px;
            color: var(--text-secondary);
        }

        .auth-footer__lang {
            position: relative;
        }

        .auth-footer__lang-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border-subtle);
            border-radius: 20px;
            padding: 6px 14px;
            color: var(--text-secondary);
            font-size: 12px;
            font-family: 'Sora', sans-serif;
            cursor: pointer;
            transition: background 0.2s, border-color 0.2s;
        }

        .auth-footer__lang-btn:hover {
            background: var(--bg-surface-hover);
            border-color: var(--border-focus);
        }

        .auth-footer__lang-btn i {
            font-size: 14px;
        }

        .auth-footer__copy {
            color: var(--text-secondary);
        }

        @media (max-width: 520px) {
            .auth-card {
                margin: 0 16px;
                padding: 32px 24px;
            }

            .auth-footer {
                padding: 12px 16px;
            }

            .auth-bg__blob--joy {
                width: 260px;
                height: 260px;
            }

            .auth-bg__blob--sadness {
                width: 220px;
                height: 220px;
            }

            .auth-bg__blob--purple {
                width: 200px;
                height: 200px;
            }
        }
    </style>
    <!-- Libraries dari CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css">
</head>
<body>

    <div class="auth-bg">
        <div class="auth-bg__blob auth-bg__blob--joy"></div>
        <div class="auth-bg__blob auth-bg__blob--sadness"></div>
        <div class="auth-bg__blob auth-bg__blob--purple"></div>
    </div>

    <div class="auth-card">
        <div class="auth-card__logo">
            <img src="assets/logo.png" alt="MoodSpace" class="ms-logo">
        </div>

        <div class="auth-card__heading">
            <h1 class="auth-card__title">Welcome back</h1>
            <p class="auth-card__subtitle">Log in to continue your emotional journey</p>
        </div>

        <form class="auth-form" id="login-form" method="POST" action="login.php">
            <div class="ms-input-group">
                <input type="email" name="email" class="ms-input" id="login-email" placeholder="Email address" required autocomplete="email">
            </div>

            <div class="ms-input-group">
                <input type="password" name="password" class="ms-input ms-input--password" id="login-password" placeholder="Password" required autocomplete="current-password">
                <button type="button" class="ms-toggle-pw" id="toggle-password" aria-label="Show password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            <?php if (!empty($error)): ?>
                <p style="color:#E84040;font-size:13px;text-align:center;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <button type="submit" class="ms-btn-primary" id="login-btn">Log in</button>
        </form>

    </div>

    <div class="auth-bottom">
        Don't have an account? <a href="register.php">Sign up</a>
    </div>

    <footer class="auth-footer">
        <div class="auth-footer__lang">
            <button class="auth-footer__lang-btn">
                <i class="fas fa-globe"></i>
                English (US)
                <i class="fas fa-chevron-down" style="font-size:10px;"></i>
            </button>
        </div>
        <span class="auth-footer__copy">&copy; 2026 Mood Space</span>
    </footer>

    <script>
        const toggleBtn = document.getElementById('toggle-password');
        const pwInput = document.getElementById('login-password');

        toggleBtn.addEventListener('click', () => {
            const isPassword = pwInput.type === 'password';
            pwInput.type = isPassword ? 'text' : 'password';
            toggleBtn.querySelector('i').className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
        });
    </script>

</body>
</html>
