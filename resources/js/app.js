import { gsap } from 'gsap';

document.addEventListener('DOMContentLoaded', () => {
    // 1. Animasi Halaman Welcome
    if (document.querySelector('#hero-body')) {
        const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

        tl.fromTo(
            '#hero-body',
            { backgroundPosition: 'center bottom -35vh' },
            { backgroundPosition: 'center bottom -11vh', duration: 1.4, ease: 'expo.out' }
        )
        .from('.gsap-nav', { y: -30, opacity: 0, duration: 0.8, clearProps: 'all' }, '-=1.1')
        .from(
            '.gsap-hero-item',
            {
                y: 35,
                opacity: 0,
                scale: 0.96,
                duration: 0.8,
                stagger: 0.15,
                ease: 'back.out(1.4)',
                clearProps: 'all'
            },
            '-=0.8'
        );
    }

    // 2. Animasi Halaman Buyer (Dengan clearProps: 'all' Anti Opacity Stuck)
    if (document.querySelector('#buyer-body')) {
        const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

        tl.from('.gsap-buyer-nav', { 
            y: -24, 
            opacity: 0, 
            duration: 0.7, 
            clearProps: 'all' 
        })
        .from(
            '.gsap-buyer-item',
            { 
                y: 28, 
                opacity: 0, 
                duration: 0.65, 
                stagger: 0.12, 
                clearProps: 'all' // <-- Membersihkan inline style setelah animasi masuk
            },
            '-=0.45'
        )
        .from(
            '.gsap-buyer-card',
            { 
                y: 20, 
                opacity: 0, 
                duration: 0.55, 
                stagger: 0.05, 
                clearProps: 'all' // <-- Membersihkan inline style kartu merchandise
            },
            '-=0.4'
        );
    }

    // 3. Animasi Halaman Login & Register
    if (document.querySelector('#auth-body')) {
        gsap.timeline({ defaults: { ease: 'power3.out' } })
            .from('.gsap-auth-item', { y: 26, opacity: 0, duration: 0.7, stagger: 0.12, clearProps: 'all' });
    }

    // 4. Navigasi Bento Skeleton
    initBentoSkeletonNavigation();

    // 5. Handler Global Modal Sewa Booth & QRIS
    initRentalModalGlobalHandlers();

    // 6. Handler Pop-up Notifikasi Lonceng Navbar
    initNotificationPopup();
});

// Handler Interaktif Modal Sewa Booth (Event Delegation Global)
function initRentalModalGlobalHandlers() {
    document.addEventListener('click', (e) => {
        // A. Buka Modal Sewa Booth
        const openBtn = e.target.closest('.btn-open-rental-modal');
        if (openBtn) {
            const modal = document.getElementById('rental-modal');
            if (!modal) return;

            document.getElementById('modal-booth-num').textContent = '#' + openBtn.dataset.booth + ' (' + openBtn.dataset.event + ')';
            document.getElementById('modal-base-price').textContent = openBtn.dataset.base;
            document.getElementById('modal-platform-fee').textContent = openBtn.dataset.fee;
            document.getElementById('modal-total-price').textContent = openBtn.dataset.total;
            document.getElementById('qris-total-display').textContent = openBtn.dataset.total;
            document.getElementById('rental-form').action = '/admin/booth-rentals/' + openBtn.dataset.id + '/book';

            // Reset tampilan ke Step 1
            const step1 = document.getElementById('step-1-form');
            const step2 = document.getElementById('step-2-qris');
            if (step1) step1.classList.remove('hidden');
            if (step2) step2.classList.add('hidden');

            modal.classList.remove('hidden');
            return;
        }

        // B. Tutup Modal
        const closeBtn = e.target.closest('.btn-close-rental-modal');
        if (closeBtn) {
            const modal = document.getElementById('rental-modal');
            if (modal) modal.classList.add('hidden');
            return;
        }

        // C. Lanjut ke Step 2 (QRIS)
        const toQrisBtn = e.target.closest('#btn-go-to-qris');
        if (toQrisBtn) {
            const circleInput = document.getElementById('input-circle-name');
            if (!circleInput || !circleInput.value.trim()) {
                alert('Silakan isi Nama Circle / Brand Studio terlebih dahulu!');
                if (circleInput) circleInput.focus();
                return;
            }
            const step1 = document.getElementById('step-1-form');
            const step2 = document.getElementById('step-2-qris');
            if (step1) step1.classList.add('hidden');
            if (step2) step2.classList.remove('hidden');
            return;
        }

        // D. Kembali ke Step 1 (Ubah Data)
        const backBtn = e.target.closest('#btn-back-to-step1');
        if (backBtn) {
            const step1 = document.getElementById('step-1-form');
            const step2 = document.getElementById('step-2-qris');
            if (step1) step1.classList.remove('hidden');
            if (step2) step2.classList.add('hidden');
            return;
        }
    });
}

// Logika Interaktif Pop-up Notifikasi Lonceng
function initNotificationPopup() {
    document.addEventListener('click', (e) => {
        const toggleBtn = e.target.closest('#btn-toggle-notif');
        const popup = document.getElementById('notif-popup');

        // 1. Toggle Buka / Tutup Pop-up saat lonceng diklik
        if (toggleBtn) {
            e.stopPropagation();
            if (popup) popup.classList.toggle('hidden');
            return;
        }

        // 2. Tutup Pop-up jika klik di luar area notifikasi
        if (popup && !popup.contains(e.target) && !popup.classList.contains('hidden')) {
            popup.classList.add('hidden');
        }

        // 3. Tombol Hapus Satuan (✕) pada item notifikasi
        const dismissBtn = e.target.closest('.btn-dismiss-item');
        if (dismissBtn) {
            e.stopPropagation();
            const item = dismissBtn.closest('.notif-item');
            if (item) {
                item.remove();
                checkRemainingNotifs();
            }
            return;
        }

        // 4. Tombol "Tandai Dibaca" (Bersihkan Semua Notifikasi)
        const clearAllBtn = e.target.closest('#btn-clear-all-notif');
        if (clearAllBtn) {
            document.querySelectorAll('.notif-item').forEach(el => el.remove());
            checkRemainingNotifs();
            return;
        }
    });

    // Helper untuk mengecek sisa notifikasi & update badge
    function checkRemainingNotifs() {
        const remaining = document.querySelectorAll('.notif-item').length;
        const badge = document.getElementById('notif-badge');
        const badgePill = document.getElementById('notif-badge-pill');
        const emptyState = document.getElementById('notif-empty-state');

        if (badge) {
            if (remaining > 0) {
                badge.textContent = remaining;
                if (badgePill) badgePill.textContent = remaining + ' Baru';
            } else {
                badge.remove();
                if (badgePill) badgePill.textContent = '0 Baru';
                if (emptyState) emptyState.classList.remove('hidden');
            }
        }
    }
}

// Skeleton Template
function getBentoSkeletonHTML() {
    return `
        <div class="animate-pulse flex flex-col gap-3.5 h-full">
            <div class="flex items-center justify-between mb-1">
                <div class="space-y-2">
                    <div class="h-5 w-48 bg-[#010310]/10 rounded-full"></div>
                    <div class="h-3 w-64 bg-[#010310]/5 rounded-full"></div>
                </div>
                <div class="flex gap-2">
                    <div class="h-7 w-28 bg-white border border-[#010310]/15 rounded-full"></div>
                    <div class="h-7 w-24 bg-white border border-[#010310]/15 rounded-full"></div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5">
                <div class="lg:col-span-7 bg-white border-[1.5px] border-[#010310]/15 rounded-2xl p-4 h-56"></div>
                <div class="lg:col-span-5 bg-[#010310]/90 rounded-2xl p-4 h-56"></div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 flex-1">
                <div class="lg:col-span-8 bg-white border-[1.5px] border-[#010310]/15 rounded-2xl p-4"></div>
                <div class="lg:col-span-4 bg-white border-[1.5px] border-[#010310]/15 rounded-2xl p-4"></div>
            </div>
        </div>
    `;
}

function initBentoSkeletonNavigation() {
    document.addEventListener('click', async (e) => {
        const link = e.target.closest('aside a, main a[href*="/admin/"]');
        if (!link) return;

        const url = link.getAttribute('href');
        if (!url || url.startsWith('#') || !url.includes('/admin/')) return;

        e.preventDefault();

        const mainContainer = document.querySelector('main');
        if (!mainContainer) {
            window.location.href = url;
            return;
        }

        // Pindahkan highlight sidebar
        if (link.closest('aside nav')) {
            document.querySelectorAll('aside nav a').forEach((navLink) => {
                navLink.className = 'w-full flex items-center justify-between px-3.5 py-2 rounded-full text-[11px] transition font-semibold text-[#010310]/75 hover:bg-[#F8F9FA] hover:text-[#23A9BD]';
            });
            link.className = 'w-full flex items-center justify-between px-3.5 py-2 rounded-full text-[11px] transition bg-[#010310] text-white font-bold shadow-[2px_2px_0px_#23A9BD]';
        }

        gsap.killTweensOf(mainContainer);
        mainContainer.style.opacity = '1';
        mainContainer.style.transform = 'none';
        mainContainer.innerHTML = getBentoSkeletonHTML();

        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const htmlText = await response.text();

            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlText, 'text/html');

            const newMain = doc.querySelector('main');
            const newSidebar = doc.querySelector('aside');

            if (newMain && newSidebar) {
                mainContainer.innerHTML = newMain.innerHTML;
                document.querySelector('aside').innerHTML = newSidebar.innerHTML;
                document.title = doc.title;
                window.history.pushState({}, doc.title, url);

                gsap.fromTo(
                    mainContainer.children,
                    { opacity: 0, y: 8 },
                    { opacity: 1, y: 0, duration: 0.25, stagger: 0.04, ease: 'power2.out', clearProps: 'all' }
                );
            } else {
                window.location.href = url;
            }
        } catch (err) {
            window.location.href = url;
        }
    });

    window.addEventListener('popstate', () => {
        window.location.reload();
    });
}