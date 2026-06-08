<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
require 'koneksi.php';

$me_stmt = $conn->prepare("SELECT id, username, display_name, profile_picture FROM users WHERE id=?");
$me_stmt->bind_param("i", $_SESSION['user_id']);
$me_stmt->execute();
$me = $me_stmt->get_result()->fetch_assoc();

function getAvatar($pic) {
    if ($pic && file_exists($pic)) return $pic;
    if ($pic && (strpos($pic,'http')===0 || strpos($pic,'data:')===0)) return $pic;
    return "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='50' fill='%23555'/%3E%3C/svg%3E";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan — MoodSpace</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <style>

        body { background-color: var(--bg-primary); color: var(--text-primary); margin: 0; padding: 0; font-family: 'Inter', sans-serif; overflow: hidden; }
        .ms-navbar { height: 64px; display: flex; align-items: center; justify-content: space-between; padding: 0 24px; background: var(--bg-secondary); border-bottom: 1px solid var(--border-color); position: relative; z-index: 100; }
        .ms-logo { font-size: 20px; font-weight: 700; color: #fff; text-decoration: none; display: flex; align-items: center; gap: 8px; }
        .ms-logo i { color: #6C5CE7; }
        .ms-navbar-actions { display: flex; align-items: center; gap: 16px; }
        .ms-navbar__icon-btn { color: var(--text-primary); text-decoration: none; font-size: 18px; padding: 8px; border-radius: 50%; transition: background 0.2s; display: inline-flex; align-items: center; justify-content: center; }
        .ms-navbar__icon-btn:hover { background: var(--bg-surface); }
        .ms-messages-layout { display: grid; grid-template-columns: 320px 1fr; height: calc(100vh - 64px); overflow: hidden; }
        
        .ms-inbox-panel { border-right: 1px solid var(--border-color); display: flex; flex-direction: column; background: var(--bg-secondary); overflow: hidden; }
        .ms-inbox-panel__header { padding: 20px 16px 16px; border-bottom: 1px solid var(--border-color); flex-shrink: 0; }
        .ms-inbox-panel__title { font-size: 18px; font-weight: 700; color: var(--text-primary); margin-bottom: 12px; }
        .ms-inbox-panel__search { width: 100%; background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-full); padding: 9px 16px; color: var(--text-primary); font-size: 13px; font-family: inherit; outline: none; transition: border-color 0.2s; }
        .ms-inbox-panel__search:focus { border-color: rgba(108,92,231,0.5); }
        .ms-inbox-list { flex: 1; overflow-y: auto; padding: 8px; }
        .ms-inbox-list::-webkit-scrollbar { width: 4px; }
        .ms-inbox-list::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius: 4px; }

        .ms-inbox-item { display: flex; align-items: center; gap: 12px; padding: 10px 12px; border-radius: var(--radius-md); cursor: pointer; transition: background 0.15s; position: relative; }
        .ms-inbox-item:hover { background: var(--bg-surface); }
        .ms-inbox-item.active { background: var(--bg-surface-active); }
        .ms-inbox-item__avatar-wrap { position: relative; flex-shrink: 0; }
        .ms-inbox-item__avatar { width: 46px; height: 46px; border-radius: var(--radius-full); object-fit: cover; background: var(--bg-surface-active); }
        .ms-inbox-item__online-dot { position: absolute; bottom: 2px; right: 2px; width: 10px; height: 10px; border-radius: var(--radius-full); background: #2ecc71; border: 2px solid var(--bg-secondary); }
        .ms-inbox-item__info { flex: 1; min-width: 0; }
        .ms-inbox-item__name { font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .ms-inbox-item__preview { font-size: 12px; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .ms-inbox-item__preview.unread { color: var(--text-primary); font-weight: 500; }
        .ms-inbox-item__meta { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; flex-shrink: 0; }
        .ms-inbox-item__time { font-size: 11px; color: var(--text-muted); }
        .ms-inbox-item__badge { background: #6C5CE7; color: #fff; font-size: 10px; font-weight: 700; min-width: 18px; height: 18px; border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; padding: 0 5px; }

        .ms-chat-panel { display: flex; flex-direction: column; background: var(--bg-primary); overflow: hidden; height: 100%; }
        .ms-chat-panel--empty { align-items: center; justify-content: center; gap: 12px; color: var(--text-muted); display: flex; flex-direction: column; height: 100%; }
        .ms-chat-panel--empty i { font-size: 48px; opacity: 0.3; }
        .ms-chat-panel--empty p { font-size: 14px; }

        .ms-chat-header { padding: 14px 20px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; gap: 12px; background: var(--bg-secondary); flex-shrink: 0; }
        .ms-chat-header__avatar { width: 38px; height: 38px; border-radius: var(--radius-full); object-fit: cover; }
        .ms-chat-header__name { font-size: 14px; font-weight: 600; color: var(--text-primary); }
        .ms-chat-header__status { font-size: 12px; color: var(--text-muted); }
        .ms-chat-header__actions { margin-left: auto; display: flex; gap: 8px; }
        .ms-chat-header__btn { background: var(--bg-surface); border: none; width: 34px; height: 34px; border-radius: var(--radius-full); color: var(--text-secondary); cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 14px; transition: background 0.2s, color 0.2s; text-decoration: none; }
        .ms-chat-header__btn:hover { background: var(--bg-surface-hover); color: var(--text-primary); }
        
        .ms-mobile-back-btn { display: none; background: none; border: none; color: var(--text-primary); font-size: 18px; padding: 8px; cursor: pointer; margin-right: 8px; }

        .ms-chat-thread { flex: 1; overflow-y: auto; padding: 20px 16px; display: flex; flex-direction: column; gap: 2px; }
        .ms-chat-thread::-webkit-scrollbar { width: 4px; }
        .ms-chat-thread::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.06); border-radius: 4px; }

        .ms-chat-date-sep { text-align: center; font-size: 11px; color: var(--text-muted); margin: 12px 0 4px; position: relative; }
        .ms-chat-date-sep::before { content: ''; position: absolute; top: 50%; left: 0; right: 0; height: 1px; background: var(--border-color); z-index: 0; }
        .ms-chat-date-sep span { position: relative; background: var(--bg-primary); padding: 0 10px; z-index: 1; }

        .ms-bubble { display: flex; align-items: flex-end; gap: 8px; max-width: 72%; animation: fadeInUp 0.2s ease both; margin-bottom: 6px; }
        .ms-bubble.sent { margin-left: auto; flex-direction: row-reverse; }
        .ms-bubble__avatar { width: 28px; height: 28px; border-radius: var(--radius-full); object-fit: cover; flex-shrink: 0; background: var(--bg-surface-active); }
        .ms-bubble.sent .ms-bubble__avatar { display: none; }
        .ms-bubble__content { display: flex; flex-direction: column; gap: 2px; }
        .ms-bubble.sent .ms-bubble__content { align-items: flex-end; }
        .ms-bubble__text { padding: 10px 14px; border-radius: 18px; font-size: 13px; line-height: 1.5; word-break: break-word; }
        .ms-bubble.received .ms-bubble__text { background: var(--bg-surface); color: var(--text-primary); border-bottom-left-radius: 4px; }
        .ms-bubble.sent .ms-bubble__text { background: #6C5CE7; color: #fff; border-bottom-right-radius: 4px; }
        .ms-bubble.received + .ms-bubble.received .ms-bubble__text { border-top-left-radius: 4px; }
        .ms-bubble.sent + .ms-bubble.sent .ms-bubble__text { border-top-right-radius: 4px; }

        .ms-bubble__time { font-size: 10px; color: var(--text-muted); padding: 0 4px; }
        .ms-bubble__read-indicator { font-size: 11px; color: rgba(108,92,231,0.8); }

        .ms-chat-input-area { padding: 12px 16px; border-top: 1px solid var(--border-color); display: flex; align-items: flex-end; gap: 10px; background: var(--bg-secondary); flex-shrink: 0; }
        .ms-chat-input-wrap { flex: 1; display: flex; align-items: center; gap: 8px; background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 20px; padding: 10px 14px; transition: border-color 0.2s; }
        .ms-chat-input-wrap:focus-within { border-color: rgba(108,92,231,0.5); }
        .ms-chat-textarea { flex: 1; background: transparent; border: none; outline: none; color: var(--text-primary); font-size: 14px; font-family: inherit; resize: none; line-height: 1.5; max-height: 120px; overflow-y: auto; margin-top: 2px; }
        .ms-chat-textarea::placeholder { color: var(--text-muted); }
        .ms-chat-send-btn { background: #6C5CE7; border: none; border-radius: var(--radius-full); width: 38px; height: 38px; color: #fff; font-size: 14px; cursor: pointer; flex-shrink: 0; display: flex; align-items: center; justify-content: center; transition: background 0.2s, transform 0.1s; align-self: flex-end; }
        .ms-chat-send-btn:hover { background: #5a4bcf; }
        .ms-chat-send-btn:active { transform: scale(0.92); }
        .ms-chat-send-btn:disabled { opacity: 0.5; cursor: not-allowed; }

        @media (max-width: 768px) {
            .ms-messages-layout { grid-template-columns: 1fr; position: relative; }
            .ms-inbox-panel { position: absolute; width: 100%; height: 100%; z-index: 10; transition: transform 0.3s ease; }
            .ms-chat-panel { position: absolute; width: 100%; height: 100%; z-index: 20; transform: translateX(100%); transition: transform 0.3s ease; }
            .ms-chat-panel.active { transform: translateX(0); }
            .ms-mobile-back-btn { display: block; }
        }
    </style>
</head>
<body>
    <header class="ms-navbar" id="main-navbar">
        <a href="index.php" class="ms-logo">
            <img src="assets/logo.png" alt="MoodSpace Logo" style="height:32px;width:auto;max-width:160px;">
        </a>
        <div class="ms-navbar-actions">
            <button class="ms-navbar__icon-btn theme-toggle" onclick="toggleTheme()" style="background:none;border:none;cursor:pointer;" data-tippy-content="Ganti Tema">
                <i class="fas fa-moon"></i>
            </button>
            <a href="messages.php" class="ms-navbar__icon-btn" style="position:relative;" data-tippy-content="Pesan">
                <i class="fas fa-paper-plane"></i>
                <span id="msgBadge" style="display:none;position:absolute;top:-4px;right:-4px;background:#E84040;color:#fff;font-size:9px;font-weight:700;min-width:16px;height:16px;border-radius:8px;align-items:center;justify-content:center;padding:0 3px;"></span>
            </a>
            <a href="profile.php" class="ms-navbar__avatar" data-tippy-content="Profil">
                <img src="<?php echo getAvatar($me['profile_picture']); ?>" alt="Profile" style="width:100%;height:100%;object-fit:cover;">
            </a>
        </div>
    </header>

    <div class="ms-messages-layout">
        <div class="ms-inbox-panel" id="inboxPanel">
            <div class="ms-inbox-panel__header">
                
                <input type="text" class="ms-inbox-panel__search" placeholder="Cari percakapan...">
            </div>
            <div class="ms-inbox-list" id="inboxList">
                <div style="text-align:center;padding:32px;color:var(--text-muted);"><i class="fas fa-spinner fa-spin"></i></div>
            </div>
            <div class="ms-inbox-panel__footer" style="padding: 16px; border-top: 1px solid var(--border-color); flex-shrink: 0;">
                <button onclick="history.back()" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 10px; background: var(--bg-surface); color: var(--text-primary); border: none; cursor: pointer; border-radius: 20px; font-size: 13px; font-weight: 600; transition: background 0.2s;" onmouseover="this.style.background='var(--bg-surface-hover)'" onmouseout="this.style.background='var(--bg-surface)'">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
            </div>
        </div>

        <div class="ms-chat-panel" id="chatPanel">
            <div class="ms-chat-panel--empty" id="chatEmpty">
                <i class="fas fa-paper-plane"></i>
                <p>Pilih percakapan untuk mulai mengirim pesan</p>
            </div>

            <div id="chatActive" style="display:none; flex-direction:column; height:100%;">
                <div class="ms-chat-header">
                    <button class="ms-mobile-back-btn" onclick="closeThread()"><i class="fas fa-arrow-left"></i></button>
                    <img class="ms-chat-header__avatar" id="chatPartnerAvatar" src="" alt="" style="cursor:pointer;" onclick="if(activePartnerId) window.location.href='profile.php?id=' + activePartnerId">
                    <div>
                        <div class="ms-chat-header__name" id="chatPartnerName" style="cursor:pointer;" onclick="if(activePartnerId) window.location.href='profile.php?id=' + activePartnerId">Username</div>
                        <div class="ms-chat-header__status">Active</div>
                    </div>
                    <div class="ms-chat-header__actions">
                        <button id="chatFollowBtn" class="ms-btn btn-follow ms-btn-primary" style="padding:6px 14px;font-size:12px;border-radius:12px;margin-right:8px;font-weight:600;" onclick="handleChatFollow()" data-target-id="" data-following="0">Follow</button>
                    </div>
                </div>

                <div class="ms-chat-thread" id="chatThread"></div>

                <div class="ms-chat-input-area">
                    <div class="ms-chat-input-wrap">
                        <textarea id="chatTextarea" class="ms-chat-textarea" placeholder="Tulis pesan..." maxlength="1000" rows="1"></textarea>
                        <button class="ms-chat-send-btn" id="chatSendBtn" onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js?v=<?php echo time(); ?>"></script>
    <script>
        const MY_ID = <?php echo $me['id']; ?>;
        const MY_AVATAR = '<?php echo getAvatar($me['profile_picture']); ?>';
        let activePartnerId = null;
        let pollingInterval = null;

        async function loadInbox() {
            const res = await fetch('api_messages.php?action=inbox');
            const list = await res.json();
            renderInbox(list);
            checkUnreadBadge();
        }

        function renderInbox(list) {
            const el = document.getElementById('inboxList');
            if (!list.length) {
                el.innerHTML = '<div style="padding:24px;text-align:center;color:var(--text-muted);font-size:13px;">Belum ada percakapan</div>';
                return;
            }
            el.innerHTML = list.map(item => {
                const unread = parseInt(item.unread_count) > 0;
                return `
                <div class="ms-inbox-item ${activePartnerId == item.partner_id ? 'active' : ''}"
                     onclick="openThread(${item.partner_id}, '${escHtml(item.display_name || item.username)}', '${item.profile_picture}')">
                    <div class="ms-inbox-item__avatar-wrap">
                        <img class="ms-inbox-item__avatar" src="${item.profile_picture}">
                    </div>
                    <div class="ms-inbox-item__info">
                        <div class="ms-inbox-item__name">${escHtml(item.display_name || item.username)}</div>
                        <div class="ms-inbox-item__preview ${unread ? 'unread' : ''}">${escHtml(item.last_teks)}</div>
                    </div>
                    <div class="ms-inbox-item__meta">
                        <span class="ms-inbox-item__time">${timeAgo(item.last_at)}</span>
                        ${unread ? `<span class="ms-inbox-item__badge">${item.unread_count}</span>` : ''}
                    </div>
                </div>`;
            }).join('');
        }

        async function openThread(partnerId, partnerName, partnerAvatar) {
            activePartnerId = partnerId;
            document.getElementById('chatPartnerAvatar').src = partnerAvatar;
            document.getElementById('chatPartnerName').textContent = partnerName;
            
            document.getElementById('chatEmpty').style.display = 'none';
            document.getElementById('chatActive').style.display = 'flex';
            document.getElementById('chatPanel').classList.add('active');

            await loadThread();
            if (pollingInterval) clearInterval(pollingInterval);
            pollingInterval = setInterval(loadThread, 4000);
            
            document.querySelectorAll('.ms-inbox-item').forEach(el => el.classList.remove('active'));
            if(event && event.currentTarget) event.currentTarget.classList.add('active');
        }

        function closeThread() {
            document.getElementById('chatPanel').classList.remove('active');
            activePartnerId = null;
            if (pollingInterval) clearInterval(pollingInterval);
        }

        async function loadThread() {
            if (!activePartnerId) return;
            const res = await fetch(`api_messages.php?action=thread&with=${activePartnerId}`);
            const data = await res.json();
            
            if (data.is_following !== undefined) {
                const btn = document.getElementById('chatFollowBtn');
                if (btn) {
                    btn.setAttribute('data-following', data.is_following ? '1' : '0');
                    btn.textContent = data.is_following ? 'Following' : 'Follow';
                    btn.className = 'ms-btn btn-follow ' + (data.is_following ? 'ms-btn-outline' : 'ms-btn-primary');
                }
            }
            
            if (data.partner) {
                document.getElementById('chatPartnerAvatar').src = data.partner.avatar;
                if (document.getElementById('chatPartnerName').textContent === 'Pengguna' || !document.getElementById('chatPartnerName').textContent) {
                    document.getElementById('chatPartnerName').textContent = data.partner.name;
                }
            }
            
            renderThread(data.messages || data);
        }

        function renderThread(msgs) {
            const thread = document.getElementById('chatThread');
            const wasAtBottom = thread.scrollHeight - thread.scrollTop <= thread.clientHeight + 60;
            let lastDate = null;
            let html = '';

            msgs.forEach(m => {
                const msgDate = new Date(m.created_at).toLocaleDateString('id-ID', {day:'numeric',month:'long',year:'numeric'});
                if (msgDate !== lastDate) {
                    html += `<div class="ms-chat-date-sep"><span>${msgDate}</span></div>`;
                    lastDate = msgDate;
                }
                const isSent = String(m.sender_id) === String(MY_ID);
                const timeStr = new Date(m.created_at).toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'});
                html += `
                <div class="ms-bubble ${isSent ? 'sent' : 'received'}">
                    <img class="ms-bubble__avatar" src="${m.profile_picture}" alt="">
                    <div class="ms-bubble__content">
                        <div class="ms-bubble__text">${escHtml(m.teks)}</div>
                        <div class="ms-bubble__time">
                            ${timeStr}
                            ${isSent ? `<i class="fas fa-check${m.is_read ? '-double' : ''} ms-bubble__read-indicator"></i>` : ''}
                        </div>
                    </div>
                </div>`;
            });

            thread.innerHTML = html || '<div style="text-align:center;padding:32px;color:var(--text-muted);font-size:13px;">Mulai percakapan!</div>';
            if (wasAtBottom) thread.scrollTop = thread.scrollHeight;
        }

        async function sendMessage() {
            const textarea = document.getElementById('chatTextarea');
            const teks = textarea.value.trim();
            if (!teks || !activePartnerId) return;

            const sendBtn = document.getElementById('chatSendBtn');
            sendBtn.disabled = true;
            textarea.value = '';
            textarea.style.height = 'auto';

            const res = await fetch('api_messages.php', {
                method: 'POST',
                headers: {'Content-Type':'application/json'},
                body: JSON.stringify({to: activePartnerId, teks})
            });
            const data = await res.json();
            sendBtn.disabled = false;

            if (data.success) {
                await loadThread();
                await loadInbox();
            }
        }

                async function handleChatFollow() {
            const btn = document.getElementById('chatFollowBtn');
            const isFollowing = btn.getAttribute('data-following') === '1';
            btn.disabled = true;
            btn.textContent = '...';
            try {
                const formData = new FormData();
                formData.append('action', 'follow');
                formData.append('target_id', activePartnerId);
                const res = await fetch('api_action.php', { method: 'POST', body: formData });
                const data = await res.json();
                if(data.success) {
                    const nowFollowing = data.status === 'followed';
                    btn.setAttribute('data-following', nowFollowing ? '1' : '0');
                    btn.textContent = nowFollowing ? 'Following' : 'Follow';
                    btn.className = 'ms-btn btn-follow ' + (nowFollowing ? 'ms-btn-outline' : 'ms-btn-primary');
                    btn.style.padding = '6px 14px';
                    btn.style.fontSize = '12px';
                    btn.style.borderRadius = '12px';
                    btn.style.marginRight = '8px';
                    btn.style.fontWeight = '600';
                } else {
                    alert(data.message);
                    btn.textContent = isFollowing ? 'Following' : 'Follow';
                }
            } catch(e) {
                btn.textContent = isFollowing ? 'Following' : 'Follow';
            } finally {
                btn.disabled = false;
            }
        }

        function escHtml(str) {
            return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        loadInbox();

        const urlParams = new URLSearchParams(window.location.search);
        const chatWithId = urlParams.get('chat_with');
        const chatWithName = urlParams.get('name');
        if (chatWithId) {
            const defAv = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='50' fill='%23555'/%3E%3C/svg%3E";
            openThread(parseInt(chatWithId), chatWithName || 'Pengguna', defAv);
        }

        document.getElementById('chatTextarea').addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
        document.getElementById('chatTextarea').addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
        });
        
        async function checkUnreadBadge() {
            const res = await fetch('api_messages.php?action=unread_count');
            const data = await res.json();
            const badge = document.getElementById('msgBadge');
            if (badge) {
                if (data.count > 0) {
                    badge.textContent = data.count > 99 ? '99+' : data.count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }
        }
    </script>
</body>
</html>



