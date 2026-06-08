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
    $confirm  = $_POST['confirm_password'] ?? '';

    if (empty($email) || empty($password) || empty($confirm)) {
        $error = "Semua field harus diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif ($password !== $confirm) {
        $error = "Password tidak cocok.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Email sudah terdaftar.";
        } else {
            $username = strtolower(explode('@', $email)[0]) . rand(10, 99);
            $hashed   = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (email, password, role, username, display_name) VALUES (?, ?, 'user', ?, ?)");
            $display = $username;
            $stmt->bind_param("ssss", $email, $hashed, $username, $display);

            if ($stmt->execute()) {
                $_SESSION['user_id'] = $conn->insert_id;
                $_SESSION['username'] = $username;
                header("Location: index.php");
                exit;
            } else {
                $error = "Registrasi gagal. Coba lagi.";
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up — MoodSpace</title>
    <meta name="description" content="Create your MoodSpace account — join our emotion-driven streaming community. Explore music, videos, and quotes based on your mood.">
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

        .auth-topbar {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 32px;
            width: 100%;
        }

        .auth-topbar__logo {
            height: 34px;
            width: auto;
            object-fit: contain;
        }

        .auth-topbar__help {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text-secondary);
            font-size: 13px;
            text-decoration: none;
            transition: color 0.2s;
        }

        .auth-topbar__help:hover {
            color: var(--text-primary);
        }

        .auth-topbar__help i {
            font-size: 15px;
        }

        .auth-main {
            position: relative;
            z-index: 1;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0 24px;
        }

        .auth-content {
            width: 100%;
            max-width: 460px;
            animation: fadeIn 0.5s cubic-bezier(0.22,1,0.36,1) forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .auth-content__title {
            font-size: 30px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.3;
        }

        .auth-content__subtitle {
            font-size: 14px;
            color: var(--text-secondary);
            margin-top: 10px;
            line-height: 1.7;
        }

        .auth-form {
            margin-top: 32px;
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
            background: #3a3a4a;
            color: rgba(255,255,255,0.4);
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Sora', sans-serif;
            cursor: not-allowed;
            transition: background-color 0.3s ease, color 0.3s ease, transform 0.15s ease, box-shadow 0.3s ease;
            margin-top: 4px;
        }

        .ms-btn-primary.active {
            background: var(--btn-primary);
            color: #fff;
            cursor: pointer;
        }

        .ms-btn-primary.active:hover {
            background: var(--btn-primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(108, 92, 231, 0.35);
        }

        .ms-btn-primary.active:active {
            transform: translateY(0);
        }

        .auth-footer-area {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 460px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .auth-footer-area__divider {
            width: 100%;
            height: 1px;
            background: var(--border-subtle);
            margin: 36px 0 20px;
        }

        .auth-footer-area__link {
            text-align: center;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .auth-footer-area__link a {
            color: var(--accent-anger);
            text-decoration: none;
            font-weight: 600;
            transition: opacity 0.2s;
        }

        .auth-footer-area__link a:hover {
            opacity: 0.8;
        }

        .auth-footer {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 32px;
            font-size: 12px;
            color: var(--text-secondary);
            width: 100%;
            margin-top: auto;
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
            .auth-topbar {
                padding: 16px 16px;
            }

            .auth-main {
                padding: 0 16px;
            }

            .auth-content__title {
                font-size: 24px;
            }

            .auth-footer {
                padding: 16px;
            }

            .auth-footer-area {
                padding: 0 16px;
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

    <nav class="auth-topbar">
        <a href="index.php">
            <img src="assets/logo.png" alt="MoodSpace" class="auth-topbar__logo">
        </a>
        <a href="#" class="auth-topbar__help">
            <i class="fas fa-question-circle"></i>
            Feedback and help
        </a>
    </nav>

    <main class="auth-main">
        <div class="auth-content">
            <h1 class="auth-content__title">Sign up for MoodSpace</h1>
            <p class="auth-content__subtitle">
                Join our minimalist community and creative space.<br>
                Create your profile to connect and share.
            </p>

            <form class="auth-form" id="register-form" method="POST" action="register.php">
                <div class="ms-input-group">
                    <input type="email" name="email" class="ms-input" id="reg-email" placeholder="Email address" required autocomplete="email">
                </div>

                <div class="ms-input-group">
                    <input type="password" name="password" class="ms-input ms-input--password" id="reg-password" placeholder="Password" required autocomplete="new-password">
                    <button type="button" class="ms-toggle-pw" data-target="reg-password" aria-label="Show password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <div class="ms-input-group">
                    <input type="password" name="confirm_password" class="ms-input ms-input--password" id="reg-confirm" placeholder="Confirm password" required autocomplete="new-password">
                    <button type="button" class="ms-toggle-pw" data-target="reg-confirm" aria-label="Show password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <?php if (!empty($error)): ?>
                    <p style="color:#E84040;font-size:13px;text-align:center;"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>

                <button type="submit" class="ms-btn-primary" id="signup-btn" disabled>Sign up</button>
            </form>
        </div>

        <div class="auth-footer-area">
            <div class="auth-footer-area__divider"></div>
            <p class="auth-footer-area__link">
                Already have an account? <a href="login.php">Log in</a>
            </p>
        </div>
    </main>

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
        document.querySelectorAll('.ms-toggle-pw').forEach(btn => {
            btn.addEventListener('click', () => {
                const targetId = btn.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                btn.querySelector('i').className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
            });
        });

        const emailInput = document.getElementById('reg-email');
        const pwInput = document.getElementById('reg-password');
        const confirmInput = document.getElementById('reg-confirm');
        const signupBtn = document.getElementById('signup-btn');

        function checkFields() {
            const allFilled = emailInput.value.trim() !== '' &&
                              pwInput.value.trim() !== '' &&
                              confirmInput.value.trim() !== '';
            if (allFilled) {
                signupBtn.classList.add('active');
                signupBtn.disabled = false;
            } else {
                signupBtn.classList.remove('active');
                signupBtn.disabled = true;
            }
        }

        emailInput.addEventListener('input', checkFields);
        pwInput.addEventListener('input', checkFields);
        confirmInput.addEventListener('input', checkFields);
    </script>

</body>
</html>
