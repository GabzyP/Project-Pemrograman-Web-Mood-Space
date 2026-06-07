const moodColorsCycle = [
    { rgb: '255, 214, 0', hex: '#FFD600' },
    { rgb: '21, 101, 192', hex: '#1565C0' },
    { rgb: '198, 40, 40', hex: '#C62828' },
    { rgb: '46, 125, 50', hex: '#2E7D32' },
    { rgb: '106, 27, 154', hex: '#6A1B9A' },
    { rgb: '239, 108, 0', hex: '#EF6C00' },
    { rgb: '97, 97, 97', hex: '#616161' },
    { rgb: '216, 27, 96', hex: '#D81B60' },
    { rgb: '0, 172, 193', hex: '#00ACC1' }
];

let moodCycleInterval = null;

function toggleTheme() {
    let theme = localStorage.getItem('theme') || 'dark';
    if (theme === 'dark') theme = 'light';
    else if (theme === 'light') theme = 'moodspace';
    else theme = 'dark';
    
    setTheme(theme);
}

function setTheme(theme) {
    const body = document.body;
    body.classList.remove('light-mode', 'moodspace-mode');
    
    if (theme === 'light') body.classList.add('light-mode');
    else if (theme === 'moodspace') body.classList.add('moodspace-mode');
    
    localStorage.setItem('theme', theme);
    updateThemeIcon(theme);
    handleMoodspaceCycle(theme);
}

function updateThemeIcon(theme) {
    document.querySelectorAll('.theme-toggle i').forEach(icon => {
        icon.classList.remove('fa-moon', 'fa-sun', 'fa-palette');
        if (theme === 'light') icon.classList.add('fa-sun');
        else if (theme === 'moodspace') icon.classList.add('fa-palette');
        else icon.classList.add('fa-moon');
    });
}

function handleMoodspaceCycle(theme) {
    clearInterval(moodCycleInterval);
    const body = document.body;
    
    if (theme === 'moodspace' && !body.classList.contains('mood-active')) {
        let cycleIndex = 0;
        const updateColor = () => {
            const color = moodColorsCycle[cycleIndex];
            document.documentElement.style.setProperty('--mood-color-rgb', color.rgb);
            document.documentElement.style.setProperty('--mood-color', color.hex);
            cycleIndex = (cycleIndex + 1) % moodColorsCycle.length;
        };
        updateColor();
        moodCycleInterval = setInterval(updateColor, 2000);
    } else {
        document.documentElement.style.removeProperty('--mood-color-rgb');
        document.documentElement.style.removeProperty('--mood-color');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme') || 'dark';
    setTheme(savedTheme);
});
document.addEventListener('DOMContentLoaded', () => {
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const namaInput = document.getElementById('namaLengkap');
            const nama = namaInput ? namaInput.value : "Agen";
            alert(`Laporan diterima, ${nama}! ✅ Status emosi sedang diproses.`);
            contactForm.reset();
        });
    }
});
document.addEventListener('click', async function(e) {
    const btn = e.target.closest('.btn-action');
    if (!btn) return;
    
    e.preventDefault();
    e.stopPropagation();
    
    const id = btn.getAttribute('data-id');
    const type = btn.getAttribute('data-type');
    const icon = btn.querySelector('i');
    const countSpan = btn.querySelector('.count-text');
    
    if (!id || !type) return;
    
    try {
        const formData = new FormData();
        formData.append('action', type);
        formData.append('konten_id', id);
        
        const response = await fetch('api_action.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            if (countSpan) {
                countSpan.textContent = data.count;
            }
            if (data.status === 'added') {
                if (type === 'like') {
                    if (btn.tagName === 'BUTTON') {
                        btn.style.background = 'rgba(232,64,64,0.3)';
                        btn.style.color = '#E84040';
                    } else {
                        icon.classList.add('active');
                        icon.style.color = '#E84040';
                    }
                } else if (type === 'favorite') {
                    if (btn.tagName === 'BUTTON') {
                        btn.style.background = 'rgba(255,214,0,0.3)';
                        btn.style.color = '#FFD600';
                    } else {
                        icon.classList.add('active');
                        icon.style.color = '#FFD600';
                    }
                }
            } else if (data.status === 'removed') {
                if (btn.tagName === 'BUTTON') {
                    btn.style.background = 'rgba(255,255,255,0.15)';
                    btn.style.color = '#fff';
                } else {
                    icon.classList.remove('active');
                    icon.style.color = 'var(--text-muted)';
                }
            }
        } else {
            if (data.message === 'Unauthorized') {
                window.location.href = 'login.php';
            } else {
                alert(data.message);
            }
        }
    } catch (err) {
        console.error('Action failed', err);
    }
});

const followBtn = document.getElementById('followBtn');
if (followBtn) {
    followBtn.addEventListener('click', async function() {
        const targetId = this.getAttribute('data-target-id');
        const isFollowing = this.getAttribute('data-following') === '1';
        
        this.disabled = true;
        this.textContent = '...';
        
        try {
            const formData = new FormData();
            formData.append('action', 'follow');
            formData.append('target_id', targetId);
            
            const response = await fetch('api_action.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            
            if (data.success) {
                const nowFollowing = data.status === 'followed';
                this.setAttribute('data-following', nowFollowing ? '1' : '0');
                this.textContent = nowFollowing ? 'Followed' : 'Follow';
                this.className = 'ms-btn ' + (nowFollowing ? 'ms-btn-outline' : 'ms-btn-primary') + ' btn-follow';
                
                const followersEl = document.getElementById('followersCountText');
                if (followersEl) followersEl.textContent = Number(data.followers_count).toLocaleString();
            } else {
                alert(data.message || 'Gagal melakukan follow');
                this.textContent = isFollowing ? 'Followed' : 'Follow';
            }
        } catch (err) {
            console.error('Follow error:', err);
            this.textContent = isFollowing ? 'Followed' : 'Follow';
        } finally {
            this.disabled = false;
        }
    });
}
