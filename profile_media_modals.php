<?php

?>

<style>
#videoModal {
    background: var(--bg-primary);
}
body.moodspace-mode #videoModal::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.85) 100%);
    z-index: -1;
    pointer-events: none;
}
#videoModal .ms-player-container {
    transition: transform 0.3s, max-width 0.3s;
    position: relative;
    z-index: 1; 
}
#videoModal.ms-video-expanded .video-comment-panel {
    display: block !important;
}
@media (min-width: 861px) {
    #videoModal.ms-video-expanded .ms-player-container {
        transform: translateX(-180px);
    }
}
@media (max-width: 860px) {
    #videoModal.ms-video-expanded .ms-player-container {
        max-width: 540px !important;
    }
    #videoModal .video-comment-panel {
        position: relative !important;
        right: 0 !important;
        width: 100% !important;
        margin-top: 20px;
        height: auto !important;
        max-height: 400px !important;
    }
    #videoModal .video-comment-panel > div {
        height: auto !important;
        max-height: 400px !important;
    }
}
</style>

<div id="videoModal" style="
    display:none;
    position:fixed;inset:0;
    background:var(--bg-primary);
    z-index:10000;
    align-items:center;justify-content:center;
    overflow-y:auto;
    padding: 20px 0;
" onclick="closeVideoModal(event)">



    <button onclick="closeVideoModal(null, true)" style="
        position:fixed;top:24px;left:24px;
        background:none;border:none;color:var(--text-secondary);cursor:pointer;
        font-size:15px;padding:0;display:inline-flex;align-items:center;gap:8px;
        font-family:inherit;z-index:10001;font-weight:500;
    ">
        <i class="fas fa-chevron-left"></i> Kembali
    </button>

    <div class="ms-player-container" style="width:90vw; max-width:960px; transition:max-width 0.3s; margin:auto;" id="videoPlayerContainer">
        <div class="ms-player-left" style="display:flex;flex-direction:column;align-items:center;width:100%;">
            
            <div id="videoCreatorBar" style="display:flex;align-items:center;gap:10px;margin-bottom:12px;width:100%;">
                <a id="videoCreatorLink" href="#" style="display:flex;align-items:center;gap:10px;text-decoration:none;flex:1;">
                    <img id="videoCreatorAvatar" src="" alt=""
                         style="width:38px;height:38px;border-radius:50%;object-fit:cover;background:#333;flex-shrink:0;">
                    <div>
                        <div id="videoCreatorName" style="color:var(--text-primary);font-size:14px;font-weight:600;"></div>
                    </div>
                </a>
                <button id="videoFollowBtn"
                        onclick="handleVideoFollow()"
                        style="background:#6C5CE7;border:none;border-radius:20px;color:#fff;
                               font-size:12px;font-weight:600;padding:7px 18px;cursor:pointer;display:inline-block;">
                    Follow
                </button>
            </div>

            <div id="videoModalTitle" style="
                color:var(--text-primary);font-size:16px;font-weight:600;
                margin-bottom:16px;text-align:center;
                width:100%;opacity:0.9;
            "></div>

            <div style="position:relative;width:100%;">
                <video id="modalVideo" controls style="
                    width:100%;border-radius:12px;
                    max-height:75vh;background:#000;
                    display:block;
                "></video>
            </div>

            <div style="display:grid;grid-template-columns:1fr auto 1fr;align-items:center;margin-top:20px;width:100%;">
                <div style="display:flex;align-items:center;justify-content:flex-end;gap:14px;">
                    <button id="videoFavBtn" onclick="handleVideoFav()"
                            style="background:var(--bg-surface);border:none;border-radius:50%;
                                   width:46px;height:46px;color:var(--text-secondary);font-size:14px;cursor:pointer;
                                   display:flex;align-items:center;justify-content:center;transition:all 0.2s;"
                            title="Favorit">
                        <i class="fas fa-bookmark"></i>
                    </button>
                    <button onclick="prevVideo()"
                            style="background:var(--bg-surface);border:none;color:var(--text-secondary);
                                   width:46px;height:46px;border-radius:50%;font-size:16px;cursor:pointer;
                                   display:flex;align-items:center;justify-content:center;transition:all 0.2s;">
                        <i class="fas fa-step-backward"></i>
                    </button>
                </div>

                <div style="display:flex;justify-content:center;padding:0 14px;">
                    <button id="videoLikeBtn" onclick="handleVideoLike()"
                            style="background:var(--bg-surface);border:none;border-radius:50%;
                                   width:52px;height:52px;color:var(--text-secondary);font-size:20px;cursor:pointer;
                                   display:flex;align-items:center;justify-content:center;transition:all 0.2s;"
                            title="Like">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>

                <div style="display:flex;align-items:center;justify-content:flex-start;gap:14px;">
                    <button onclick="nextVideo()"
                            style="background:var(--bg-surface);border:none;color:var(--text-secondary);
                                   width:46px;height:46px;border-radius:50%;font-size:16px;cursor:pointer;
                                   display:flex;align-items:center;justify-content:center;transition:all 0.2s;">
                        <i class="fas fa-step-forward"></i>
                    </button>
                    <button onclick="toggleVideoCommentPanel()"
                            style="background:var(--bg-surface);border:none;border-radius:50%;
                                   width:46px;height:46px;color:var(--text-secondary);font-size:18px;cursor:pointer;
                                   display:flex;align-items:center;justify-content:center;transition:all 0.2s;"
                            title="Komentar">
                        <i class="fas fa-comment-dots"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="ms-player-right video-comment-panel" style="display:none; position:absolute; right:-380px; top:0; width:360px; height:100%; z-index:100;">
            <div style="background:var(--bg-secondary);border:1px solid var(--border-color);border-radius:16px;padding:20px;display:flex;flex-direction:column;height:100%;max-height:75vh;">
                <div style="font-size:15px;font-weight:600;color:var(--text-primary);margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-comment"></i>
                    <span id="videoCommentCount">Komentar</span>
                </div>
                <div id="videoCommentList" style="flex:1;overflow-y:auto;margin-bottom:16px;padding-right:8px;"></div>
                <div style="display:flex;gap:10px;align-items:center;margin-top:auto;">
                    <input id="videoCommentInput" type="text" placeholder="Tulis komentar..."
                           maxlength="500"
                           style="flex:1;background:var(--bg-surface);border:1px solid var(--border-color);
                                  border-radius:24px;padding:12px 16px;color:var(--text-primary);font-size:13px;
                                  outline:none;font-family:inherit;"
                           onkeydown="if(event.key==='Enter') submitVideoComment()">
                    <button onclick="submitVideoComment()"
                            style="background:#6C5CE7;border:none;border-radius:50%;width:42px;height:42px;
                                   color:#fff;font-size:15px;cursor:pointer;flex-shrink:0;
                                   display:flex;align-items:center;justify-content:center;transition:background 0.2s;"
                            onmouseover="this.style.background='#5a4bcf'" onmouseout="this.style.background='#6C5CE7'">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
const currentUserId = <?php echo isset($me['id']) ? $me['id'] : 0; ?>;
const videoList = <?php echo json_encode(array_values(array_filter(array_map(function($v) {
    if (empty($v['file_url'])) return null;
    return [
        'id'             => $v['id'],
        'judul'          => $v['judul'],
        'sumber'         => $v['sumber'],
        'file_url'       => $v['file_url'],
        'uploader_id'    => $v['uploaded_by'],
        'uploader_name'  => $v['uploader_name'] ?? $v['sumber'],
        'uploader_avatar'=> $v['uploader_avatar'] ?? '',
        'uploader_role'  => $v['uploader_role'] ?? '',
        'user_liked'     => !empty($v['user_liked']) ? 1 : 0,
        'user_favorited' => !empty($v['user_favorited']) ? 1 : 0,
        'user_followed'  => !empty($v['user_followed']) ? 1 : 0,
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
    if (document.getElementById('videoModalMeta')) {
        document.getElementById('videoModalMeta').textContent = (index + 1) + ' / ' + videoList.length;
    }

    
    const creatorProfileUrl = 'profile.php?id=' + v.uploader_id;
    document.getElementById('videoCreatorLink').href = creatorProfileUrl;
    document.getElementById('videoCreatorName').innerHTML = v.uploader_name + (v.uploader_role === 'creator' ? ' <i class="fas fa-check-circle" style="color:#6C5CE7;font-size:12px;"></i>' : '');
    const defAv = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='50' fill='%23555'/%3E%3C/svg%3E";
    document.getElementById('videoCreatorAvatar').src = v.uploader_avatar || defAv;
    
    
    const likeBtn = document.getElementById('videoLikeBtn');
    const favBtn = document.getElementById('videoFavBtn');
    likeBtn.style.color = v.user_liked ? '#E84040' : 'var(--text-secondary)';
    likeBtn.style.background = v.user_liked ? 'rgba(232,64,64,0.2)' : 'var(--bg-surface)';
    favBtn.style.color = v.user_favorited ? '#FFD600' : 'var(--text-secondary)';
    favBtn.style.background = v.user_favorited ? 'rgba(255,214,0,0.15)' : 'var(--bg-surface)';

    
    const followBtn = document.getElementById('videoFollowBtn');
    const isFollowedInitial = v.user_followed === 1;
    const isSelf = Number(v.uploader_id) === Number(currentUserId);
    if (followBtn) {
        if (isSelf) {
            followBtn.style.display = 'none';
        } else {
            followBtn.style.display = 'inline-block';
            followBtn.textContent = isFollowedInitial ? 'Followed' : 'Follow';
            followBtn.style.background = isFollowedInitial ? 'var(--bg-surface-active)' : '#6C5CE7';
            followBtn.style.color = isFollowedInitial ? 'var(--text-primary)' : '#fff';
            followBtn.style.border = 'none';
        }
    }
    
    loadVideoComments(v.id);
    
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

function toggleVideoCommentPanel() {
    const modal = document.getElementById('videoModal');
    modal.classList.toggle('ms-video-expanded');
}

async function loadVideoComments(kontenId) {
    const list = document.getElementById('videoCommentList');
    list.innerHTML = '<div style="text-align:center;padding:16px;color:var(--text-muted);font-size:13px;"><i class="fas fa-spinner fa-spin"></i></div>';
    
    const res  = await fetch('api_komentar.php?konten_id=' + kontenId);
    const data = await res.json();
    
    document.getElementById('videoCommentCount').textContent = 'Komentar (' + data.length + ')';
    
    if (!data.length) {
        list.innerHTML = '<div style="text-align:center;padding:16px;color:var(--text-muted);font-size:13px;">Belum ada komentar. Jadilah yang pertama!</div>';
        return;
    }
    
    list.innerHTML = data.map(c => renderCommentItem(c, currentUserId)).join('');
    list.scrollTop = list.scrollHeight;
}

async function submitVideoComment() {
    const input = document.getElementById('videoCommentInput');
    const text = input.value.trim();
    if (!text) return;
    
    const v = videoList[currentVideoIndex];
    if (!v) return;
    
    input.disabled = true;
    const res = await fetch('api_komentar.php', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({konten_id: v.id, teks: text})
    });
    const data = await res.json();
    
    input.value = '';
    input.disabled = false;
    
    if (data.success) {
        loadVideoComments(v.id);
    }
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

function handleVideoLike() {
    const v = videoList[currentVideoIndex];
    if (!v) return;
    const fd = new FormData();
    fd.append('action', 'like');
    fd.append('konten_id', v.id);
    fetch('api_action.php', {
        method: 'POST',
        body: fd
    }).then(r => r.json()).then(data => {
        if (data.success) {
            v.user_liked = (data.status === 'added') ? 1 : 0;
            const btn = document.getElementById('videoLikeBtn');
            btn.style.color = v.user_liked ? '#E84040' : 'rgba(255,255,255,0.4)';
            btn.style.background = v.user_liked ? 'rgba(232,64,64,0.2)' : 'rgba(255,255,255,0.1)';
        }
    });
}
function handleVideoFav() {
    const v = videoList[currentVideoIndex];
    if (!v) return;
    const fd = new FormData();
    fd.append('action', 'favorite');
    fd.append('konten_id', v.id);
    fetch('api_action.php', {
        method: 'POST',
        body: fd
    }).then(r => r.json()).then(data => {
        if (data.success) {
            v.user_favorited = (data.status === 'added') ? 1 : 0;
            const btn = document.getElementById('videoFavBtn');
            btn.style.color = v.user_favorited ? '#FFD600' : 'rgba(255,255,255,0.4)';
            btn.style.background = v.user_favorited ? 'rgba(255,214,0,0.15)' : 'rgba(255,255,255,0.08)';
        }
    });
}
function handleVideoDislike() {
    const btn = document.getElementById('videoDislikeBtn');
    const active = btn.style.color === 'rgb(108, 92, 231)';
    btn.style.color = active ? 'rgba(255,255,255,0.45)' : '#6C5CE7';
    btn.style.background = active ? 'rgba(255,255,255,0.08)' : 'rgba(108,92,231,0.2)';
}
function handleVideoFollow() {
    const v = videoList[currentVideoIndex];
    if (!v) return;
    const fd = new FormData();
    fd.append('action', 'follow');
    fd.append('target_id', v.uploader_id);
    fetch('api_action.php', { method: 'POST', body: fd })
      .then(res => res.json())
      .then(data => {
          v.user_followed = data.status === 'followed' ? 1 : 0;
          const followBtn = document.getElementById('videoFollowBtn');
          const followBtnMobile = document.getElementById('videoCreatorMobile').querySelector('button');
          if (followBtn) {
              followBtn.textContent = v.user_followed ? 'Followed' : 'Follow';
              followBtn.style.background = v.user_followed ? 'rgba(255,255,255,0.15)' : '#6C5CE7';
              followBtn.style.color = '#fff';
              followBtn.style.border = 'none';
          }
          if (followBtnMobile) {
              followBtnMobile.textContent = v.user_followed ? '✓' : '+';
          }
      }).catch(err => {
        console.error('Follow error:', err);
    });
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

<style>
.ms-player-container {
    display: flex;
    gap: 40px;
    align-items: stretch;
    max-width: 540px;
    margin: 0 auto;
    transition: max-width 0.3s ease;
}
.ms-player-left {
    flex: 1;
    width: 100%;
    margin: 0 auto;
}
.ms-player-right {
    display: none;
    width: 360px;
    flex-shrink: 0;
}

#commentList::-webkit-scrollbar { width: 6px; }
#commentList::-webkit-scrollbar-track { background: rgba(255,255,255,0.02); border-radius: 4px; }
#commentList::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }
#commentList::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

#fullPlayerView.ms-player-expanded .ms-player-container {
    max-width: 960px !important;
}
#fullPlayerView.ms-player-expanded .ms-player-right {
    display: block;
}

@media (max-width: 860px) {
    #fullPlayerView.ms-player-expanded .ms-player-container {
        max-width: 540px !important;
    }
    .ms-player-container {
        flex-direction: column;
        align-items: center;
        gap: 24px;
    }
    .ms-player-right {
        width: 100%;
    }
}
</style>

<div id="fullPlayerView" style="display:none; position:fixed; inset:0; background:var(--bg-primary); z-index:10000; overflow-y:auto; padding:24px 16px;" onclick="closeMusicModal(event)">

    
    <button onclick="closeMusicModal(null, true)"
            style="background:none;border:none;color:var(--text-secondary);cursor:pointer;
                   font-size:15px;padding:0;display:inline-flex;align-items:center;gap:8px;
                   font-family:inherit;z-index:10;font-weight:500;margin-bottom:24px;margin-left:8px;">
        <i class="fas fa-chevron-left"></i> Kembali
    </button>

    <div class="ms-player-container">
        
        <div class="ms-player-left">
            
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
                <a id="musicCreatorLink" href="#" style="display:flex;align-items:center;gap:12px;text-decoration:none;flex:1;">
                    <img id="musicCreatorAvatar" src="" alt="Creator"
                         style="width:48px;height:48px;border-radius:50%;object-fit:cover;background:#333;flex-shrink:0;">
                    <div>
                        <div id="musicCreatorName" style="color:var(--text-primary);font-size:16px;font-weight:600;"></div>
                    </div>
                </a>
                <button id="musicFollowBtn" onclick="handleMusicFollow()"
                        style="background:#6C5CE7;border:none;
                               border-radius:24px;color:#fff;font-size:14px;font-weight:600;
                               padding:8px 24px;cursor:pointer;white-space:nowrap;">
                    Follow
                </button>
            </div>

            
            <div style="margin-bottom:24px;">
                <img id="playerCover" src="" alt="Cover"
                     style="width:100%;max-width:300px;aspect-ratio:1/1;border-radius:16px;object-fit:cover;
                            box-shadow:0 24px 64px rgba(0,0,0,0.6); display:block; margin:0 auto;">
            </div>

            
            <div style="text-align:center;margin-bottom:20px;">
                <div id="playerTitle" style="font-size:26px;font-weight:700;color:var(--text-primary);margin-bottom:4px;"></div>
                <div id="playerArtist" style="font-size:16px;color:var(--text-secondary);"></div>
            </div>

            <audio id="mainAudio" style="display:none;"></audio>

            
            <div style="margin-bottom:20px;">
                <input type="range" id="progressBar" value="0" min="0" step="0.1"
                       style="width:100%;accent-color:#F0EEF8;cursor:pointer;height:4px;">
                <div style="display:flex;justify-content:space-between;margin-top:8px;">
                    <span id="currentTime" style="font-size:13px;color:var(--text-muted);font-weight:500;">0:00</span>
                    <span id="totalTime" style="font-size:13px;color:var(--text-muted);font-weight:500;">0:00</span>
                </div>
            </div>

            
            <div style="display:grid;grid-template-columns:1fr auto 1fr;align-items:center;">
                
                <div style="display:flex;align-items:center;justify-content:flex-end;gap:16px;">
                    <button id="musicLikeBtn" onclick="handleMusicLike()"
                            style="background:none;border:none;cursor:pointer;font-size:24px;
                                   color:var(--text-secondary);transition:all 0.2s;padding:12px;"
                            title="Like">
                        <i class="fas fa-heart"></i>
                    </button>
                    <button onclick="prevTrack()"
                            style="background:none;border:none;color:var(--text-secondary);cursor:pointer;font-size:24px;padding:12px;">
                        <i class="fas fa-step-backward"></i>
                    </button>
                </div>
                
                
                <div style="display:flex;justify-content:center;padding:0 24px;">
                    <button id="playPauseBtn" onclick="togglePlay()"
                            style="width:72px;height:72px;border-radius:50%;background:var(--text-primary);border:none;
                                   cursor:pointer;font-size:26px;color:var(--bg-primary);display:flex;
                                   align-items:center;justify-content:center;box-shadow:0 8px 24px rgba(0,0,0,0.15);">
                        <i class="fas fa-play" style="margin-left:4px;"></i>
                    </button>
                </div>

                
                <div style="display:flex;align-items:center;justify-content:flex-start;gap:16px;">
                    <button onclick="nextTrack()"
                            style="background:none;border:none;color:var(--text-secondary);cursor:pointer;font-size:24px;padding:12px;">
                        <i class="fas fa-step-forward"></i>
                    </button>
                    <button id="musicFavBtn" onclick="handleMusicFav()"
                            style="background:none;border:none;cursor:pointer;font-size:24px;
                                   color:var(--text-secondary);transition:all 0.2s;padding:12px;"
                            title="Favorit">
                        <i class="fas fa-bookmark"></i>
                    </button>
                    <button onclick="toggleCommentPanel()"
                            style="background:none;border:none;cursor:pointer;font-size:24px;
                                   color:var(--text-secondary);transition:all 0.2s;padding:12px;"
                            title="Komentar">
                        <i class="fas fa-comment-dots"></i>
                    </button>
                </div>
            </div>
        </div>

        
        <div class="ms-player-right">
            <div id="commentSection" style="background:var(--bg-secondary);border:1px solid var(--border-color);border-radius:16px;padding:20px;display:flex;flex-direction:column;max-height:500px;">
                <div style="font-size:15px;font-weight:600;color:var(--text-primary);margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-comment"></i>
                    <span id="commentCount">Komentar</span>
                </div>
                <div id="commentList" style="flex:1;max-height:460px;overflow-y:auto;margin-bottom:16px;padding-right:8px;"></div>
                <div style="display:flex;gap:10px;align-items:center;margin-top:auto;">
                    <input id="commentInput" type="text" placeholder="Tulis komentar..."
                           maxlength="500"
                           style="flex:1;background:var(--bg-surface);border:1px solid var(--border-color);
                                  border-radius:24px;padding:12px 16px;color:var(--text-primary);font-size:13px;
                                  outline:none;font-family:inherit;"
                           onkeydown="if(event.key==='Enter') submitComment()">
                    <button onclick="submitComment()"
                            style="background:#6C5CE7;border:none;border-radius:50%;width:42px;height:42px;
                                   color:#fff;font-size:15px;cursor:pointer;flex-shrink:0;
                                   display:flex;align-items:center;justify-content:center;transition:background 0.2s;"
                            onmouseover="this.style.background='#5a4bcf'" onmouseout="this.style.background='#6C5CE7'">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentKontenId = null;
const tracks = <?php echo json_encode(array_map(function($m) {
    return [
        'id'             => $m['id'],
        'judul'          => $m['judul'],
        'artist'         => $m['artist'],
        'file_url'       => $m['file_url'] ?? '',
        'cover_url'      => $m['cover_url'] ?? '',
        'durasi'         => $m['durasi'] ?? '',
        'uploader_id'    => $m['uploaded_by'],
        'uploader_name'  => $m['uploader_name'] ?? '',
        'uploader_avatar'=> $m['uploader_avatar'] ?? '',
        'uploader_role'  => $m['uploader_role'] ?? '',
        'user_liked'     => !empty($m['user_liked']) ? 1 : 0,
        'user_favorited' => !empty($m['user_favorited']) ? 1 : 0,
        'user_followed'  => !empty($m['user_followed']) ? 1 : 0,
    ];
}, $musik)); ?>;

let currentIndex = 0;
const audio = document.getElementById('mainAudio');
const defaultCover = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' fill='%232a2a3a'/%3E%3Ccircle cx='50' cy='50' r='20' fill='%23444'/%3E%3Ccircle cx='50' cy='50' r='8' fill='%232a2a3a'/%3E%3C/svg%3E";

function toggleCommentPanel() {
    const view = document.getElementById('fullPlayerView');
    view.classList.toggle('ms-player-expanded');
}

function playTrack(index) {
    currentIndex = index;
    const t = tracks[index];
    document.body.style.overflow = 'hidden';
    document.getElementById('fullPlayerView').style.display = 'block';
    document.getElementById('playerTitle').textContent = t.judul;
    document.getElementById('playerArtist').textContent = t.artist;
    document.getElementById('playerCover').src = t.cover_url || defaultCover;

    
    const defAv = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='50' fill='%23555'/%3E%3C/svg%3E";
    document.getElementById('musicCreatorAvatar').src = t.uploader_avatar || defAv;
    document.getElementById('musicCreatorName').innerHTML = t.uploader_name + (t.uploader_role === 'creator' ? ' <i class="fas fa-check-circle" style="color:#6C5CE7;font-size:12px;"></i>' : '');
    document.getElementById('musicCreatorLink').href = 'profile.php?id=' + t.uploader_id;
  
    
    const likeBtn = document.getElementById('musicLikeBtn');
    const favBtn  = document.getElementById('musicFavBtn');
    likeBtn.style.color = t.user_liked ? '#E84040' : 'var(--text-secondary)';
    favBtn.style.color  = t.user_favorited ? '#FFD600' : 'var(--text-secondary)';
    
    
    const followBtn = document.getElementById('musicFollowBtn');
    if (followBtn) {
        if (Number(t.uploader_id) === Number(currentUserId)) {
            followBtn.style.display = 'none';
        } else {
            followBtn.style.display = 'inline-block';
            followBtn.textContent = t.user_followed ? 'Followed' : 'Follow';
            followBtn.style.background = t.user_followed ? 'rgba(255,255,255,0.15)' : '#6C5CE7';
            followBtn.style.color = '#fff';
        }
    }
    audio.src = t.file_url;
    audio.play();
    updatePlayBtn(true);
    
    currentKontenId = t.id;
    loadComments(t.id);
}

function closeMusicModal(e, force) {
    if (!force && e && e.target !== document.getElementById('fullPlayerView')) return;
    audio.pause();
    document.getElementById('fullPlayerView').style.display = 'none';
    document.body.style.overflow = '';
}
function backToList() {
    closeMusicModal(null, true);
}

function togglePlay() {
    if (audio.paused) { audio.play(); updatePlayBtn(true); }
    else { audio.pause(); updatePlayBtn(false); }
}

function updatePlayBtn(playing) {
    document.getElementById('playPauseBtn').innerHTML =
        playing ? '<i class="fas fa-pause"></i>' : '<i class="fas fa-play" style="margin-left:4px;"></i>';
}

function prevTrack() {
    playTrack((currentIndex - 1 + tracks.length) % tracks.length);
}
function nextTrack() {
    playTrack((currentIndex + 1) % tracks.length);
}

function handleMusicLike() {
    const t = tracks[currentIndex];
    const fd = new FormData();
    fd.append('action', 'like');
    fd.append('konten_id', t.id);
    fetch('api_action.php', {
        method: 'POST',
        body: fd
    }).then(r => r.json()).then(data => {
        if (data.success) {
            t.user_liked = (data.status === 'added') ? 1 : 0;
            document.getElementById('musicLikeBtn').style.color = t.user_liked ? '#E84040' : 'var(--text-secondary)';
        }
    });
}

function handleMusicFav() {
    const t = tracks[currentIndex];
    const fd = new FormData();
    fd.append('action', 'favorite');
    fd.append('konten_id', t.id);
    fetch('api_action.php', {
        method: 'POST',
        body: fd
    }).then(r => r.json()).then(data => {
        if (data.success) {
            t.user_favorited = (data.status === 'added') ? 1 : 0;
            document.getElementById('musicFavBtn').style.color = t.user_favorited ? '#FFD600' : 'var(--text-secondary)';
        }
    });
}

function handleMusicFollow() {
    const t = tracks[currentIndex];
    const fd = new FormData();
    fd.append('action', 'follow');
    fd.append('target_id', t.uploader_id);
    fetch('api_action.php', { method: 'POST', body: fd }).then(r => r.json()).then(data => {
        if (data.success) {
            t.user_followed = data.status === 'followed' ? 1 : 0;
            const followBtn = document.getElementById('musicFollowBtn');
            if (followBtn) {
                followBtn.textContent = t.user_followed ? 'Followed' : 'Follow';
                followBtn.style.background = t.user_followed ? 'var(--bg-surface-active)' : '#6C5CE7';
                followBtn.style.color = t.user_followed ? 'var(--text-primary)' : '#fff';
            }
        }
    });
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

document.addEventListener('keydown', function(e) {
    const fullPlayer = document.getElementById('fullPlayerView');
    if (fullPlayer && fullPlayer.style.display === 'block') {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
            if (e.key === 'Escape') e.target.blur();
            return;
        }
        if (e.key === 'Escape') backToList();
        if (e.key === 'ArrowLeft') prevTrack();
        if (e.key === 'ArrowRight') nextTrack();
    }
});

async function loadComments(kontenId) {
    const list = document.getElementById('commentList');
    list.innerHTML = '<div style="text-align:center;padding:16px;color:var(--text-muted);font-size:13px;"><i class="fas fa-spinner fa-spin"></i></div>';
    
    const res  = await fetch('api_komentar.php?konten_id=' + kontenId);
    const data = await res.json();
    
    document.getElementById('commentCount').textContent = 'Komentar (' + data.length + ')';
    
    if (!data.length) {
        list.innerHTML = '<div style="text-align:center;padding:16px;color:var(--text-muted);font-size:13px;">Belum ada komentar. Jadilah yang pertama!</div>';
        return;
    }
    
    list.innerHTML = data.map(c => renderCommentItem(c, currentUserId)).join('');
    list.scrollTop = list.scrollHeight;
}

async function submitComment() {
    const input = document.getElementById('commentInput');
    const teks  = input.value.trim();
    if (!teks || !currentKontenId) return;
    
    input.disabled = true;
    const res  = await fetch('api_komentar.php', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({konten_id: currentKontenId, teks})
    });
    const data = await res.json();
    
    input.value    = '';
    input.disabled = false;
    if (data.success) loadComments(currentKontenId);
}
</script>
