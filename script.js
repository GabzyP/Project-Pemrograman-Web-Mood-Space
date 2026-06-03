function pilihMood(mood) {
    localStorage.setItem('moodAktif', mood);
    document.body.className = mood;
}

let activeMood = localStorage.getItem('moodAktif') || 'joy';
document.body.className = activeMood;

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