<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.fonts')
    @livewireStyles
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <style>
        /* Header + Theme */
        .pb-header { position: sticky; top: 0; z-index: 10000; display: flex; align-items: center; justify-content: space-between; gap: 8px; padding: 12px 16px; backdrop-filter: blur(8px); background: rgba(255,255,255,0.6); border-bottom: 1px solid rgba(0,0,0,0.06); transition: background .2s ease, border-color .2s ease; }
        .pb-header.scrolled { background: rgba(255,255,255,0.9); border-bottom-color: rgba(0,0,0,0.1); }
        .pb-brand { font-weight: 800; text-decoration: none; color: #111827; letter-spacing: .2px; }
        .pb-left { display:flex; align-items: center; gap:10px; }
        .pb-sub { font-size: .9rem; color: #6b7280; }
        .pb-nav { display: flex; align-items: center; gap: 10px; }
        .pb-link { color:#374151; text-decoration:none; font-weight:600; padding:8px 6px; border-radius:8px; position:relative; }
        .pb-link.active::after { content:''; position:absolute; left:10px; right:10px; bottom:4px; height:2px; background:#ec4899; border-radius:2px; }
        .theme-toggle { background: #111827; color: #fff; border: none; padding: 8px 12px; border-radius: 9999px; box-shadow: 0 4px 14px rgba(0,0,0,0.2); cursor: pointer; font-weight: 600; }
        .theme-toggle .icon { margin-right: 6px; }

        .theme-dark .photobooth-body { background: linear-gradient(135deg, #0f172a, #1f2937) !important; }
        .theme-dark .photobooth-container { background: rgba(17, 24, 39, 0.85) !important; color: #e5e7eb !important; border-color: rgba(255,255,255,0.08) !important; }
        .theme-dark h1, .theme-dark h2, .theme-dark h3, .theme-dark p { color: #e5e7eb !important; }
        .theme-dark .action-button { background: linear-gradient(45deg, #38bdf8, #0ea5e9) !important; }
        .theme-dark .alert { background: #1f2937 !important; color: #fca5a5 !important; border-color: #374151 !important; }
        /* Dark theme form and UI */
        .theme-dark input, .theme-dark select { background: #0b1220 !important; color: #e5e7eb !important; border-color: #334155 !important; }
        .theme-dark .layout-grid button { background: linear-gradient(45deg, #38bdf8, #0ea5e9) !important; color: #fff !important; }
        .theme-dark .btn-retake { background: #111827 !important; color: #e5e7eb !important; }
        .theme-dark .pb-header { background: rgba(17,24,39,0.6); border-bottom-color: rgba(255,255,255,0.06); }
        .theme-dark .pb-brand { color: #e5e7eb; }
        .theme-dark .pb-sub { color: #94a3b8; }
        .theme-dark .pb-link { color: #e5e7eb; }
        .theme-dark .pb-link.active::after { background:#0ea5e9; }

        /* Footer */
        .pb-footer { margin-top: 40px; padding: 24px 16px; border-top: 1px solid rgba(0,0,0,0.06); background: rgba(255,255,255,0.5); }
        .pb-footer-inner { max-width: 980px; margin: 0 auto; display: flex; gap: 10px; flex-wrap: wrap; align-items: center; color: #6b7280; }
        .pb-footer .sep { color: #9ca3af; }
        .pb-footer .muted { color: #9ca3af; }
        .theme-dark .pb-footer { background: rgba(17,24,39,0.6); border-top-color: rgba(255,255,255,0.06); }
        .theme-dark .pb-footer-inner { color: #94a3b8; }
        .theme-dark .pb-footer .sep, .theme-dark .pb-footer .muted { color: #64748b; }

        /* Generic modal for help */
        .pb-modal { position: fixed; inset: 0; display: none; align-items: center; justify-content: center; background: rgba(0,0,0,0.55); z-index: 11000; padding: 16px; }
        .pb-modal-card { background: #fff; color: #111827; border-radius: 16px; max-width: 720px; width: 100%; padding: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.25); }
        .pb-modal-header { display:flex; justify-content: space-between; align-items:center; margin-bottom: 12px; }
        .pb-modal-title { font-weight: 800; font-size: 1.2rem; }
        .pb-modal-close { background: transparent; border: none; font-size: 1.2rem; cursor: pointer; }
        .pb-modal-body ul { margin: 0; padding-left: 18px; }
        .pb-modal-body li { margin: 6px 0; }
        .theme-dark .pb-modal-card { background: #111827; color: #e5e7eb; }
    </style>
</head>
<body>
    <!-- Livewire navigate progress -->
    <div id="pb-progress" style="position:fixed;inset-inline:0;top:0;height:3px;background:linear-gradient(90deg,#ec4899,#f472b6);transform:scaleX(0);transform-origin:left;transition:transform .2s ease;z-index:100000"></div>

    @include('partials.header')
    <div id="toast-container" aria-live="polite" aria-atomic="true" style="position:fixed;bottom:16px;right:16px;z-index:9999"></div>

    {{ $slot }}
    @include('partials.footer')
    @livewireScripts

<script>
// Global toast helper
function photoboothToast(msg) {
    const toasts = document.getElementById('toast-container');
    if (!toasts) return;
    const el = document.createElement('div');
    el.className = 'toast';
    el.style.background = 'rgba(17,17,17,0.9)';
    el.style.color = '#fff';
    el.style.borderRadius = '10px';
    el.style.padding = '10px 14px';
    el.style.marginTop = '8px';
    el.style.boxShadow = '0 4px 14px rgba(0,0,0,0.25)';
    el.style.transition = 'opacity .2s ease, transform .2s ease';
    el.textContent = msg;
    toasts.appendChild(el);
    setTimeout(() => { el.style.opacity = '0'; el.style.transform = 'translateY(6px)'; }, 2800);
    setTimeout(() => el.remove(), 3200);
}

// Livewire toast listener
document.addEventListener('livewire:init', () => {
    if (window.Livewire && typeof Livewire.on === 'function') {
        Livewire.on('toast', (payload) => {
            const message = (payload && payload.message) ? payload.message : (payload?.[0]?.message ?? payload);
            photoboothToast(message || 'Done');
        });
    }
});

// Theme toggle with persistence
(function() {
    const root = document.documentElement;
    const btn = document.getElementById('theme-toggle');
    function apply(theme) {
        const dark = theme === 'dark';
        root.classList.toggle('theme-dark', dark);
        if (btn) {
            btn.querySelector('.icon').textContent = dark ? '☀️' : '🌙';
            btn.querySelector('.label').textContent = dark ? 'Light' : 'Dark';
        }
    }
    const saved = localStorage.getItem('pb-theme') || 'light';
    apply(saved);
    if (btn) {
        btn.addEventListener('click', () => {
            const next = (localStorage.getItem('pb-theme') || 'light') === 'dark' ? 'light' : 'dark';
            localStorage.setItem('pb-theme', next);
            apply(next);
        });
    }
})();

// One-shot toast from server (via session or query)
(function(){
    try {
        const msg = @json(session('toast') ?? request('toast'));
        if (msg) photoboothToast(msg);
    } catch (_) {}
})();

// Header scroll state
(function(){
    const header = document.getElementById('pb-header');
    if (!header) return;
    function onScroll(){
        header.classList.toggle('scrolled', window.scrollY > 4);
    }
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
})();

// Help modal
(function(){
    const openBtn = document.getElementById('help-open');
    const modal = document.getElementById('help-modal');
    const closeBtn = document.getElementById('help-close');
    if (!openBtn || !modal || !closeBtn) return;
    function open(){ modal.style.display = 'flex'; closeBtn.focus(); }
    function close(){ modal.style.display = 'none'; openBtn.focus(); }
    openBtn.addEventListener('click', open);
    closeBtn.addEventListener('click', close);
    modal.addEventListener('click', (e)=>{ if (e.target === modal) close(); });
    window.addEventListener('keydown', (e)=>{ if (e.key === 'Escape' && modal.style.display === 'flex') close(); });
})();

// Livewire navigate progress bar
(function(){
    const bar = document.getElementById('pb-progress');
    if (!bar) return;
    let active = 0; let timer;
    function start(){
        active++;
        bar.style.transform = 'scaleX(0.15)';
        clearInterval(timer);
        let p = 0.15;
        timer = setInterval(()=>{
            p = Math.min(p + 0.05, 0.9);
            bar.style.transform = `scaleX(${p})`;
        }, 200);
    }
    function done(){
        active = Math.max(0, active-1);
        if (active === 0){
            clearInterval(timer);
            bar.style.transform = 'scaleX(1)';
            setTimeout(()=>{ bar.style.transform = 'scaleX(0)'; }, 180);
        }
    }
    document.addEventListener('livewire:navigating', start);
    document.addEventListener('livewire:navigated', done);
})();
</script>

<div id="help-modal" class="pb-modal" role="dialog" aria-modal="true" aria-labelledby="help-title">
    <div class="pb-modal-card">
        <div class="pb-modal-header">
            <div id="help-title" class="pb-modal-title">Quick Help</div>
            <button id="help-close" class="pb-modal-close" aria-label="Close help">✕</button>
        </div>
        <div class="pb-modal-body">
            <ul>
                <li>Flow: Start → Layouts → Settings → Capture → Download/Email.</li>
                <li>Use Retake to review each shot before saving.</li>
                <li>Undo last removes the previous shot during a session.</li>
                <li>QR code appears on finish for quick mobile download.</li>
                <li>Toggle Dark/Light for better visibility in venues.</li>
                @if(!config('photobooth.email_enabled'))
                    <li>Email is disabled (QR-only mode). Admin can enable via <code>PHOTOBOOTH_EMAIL_ENABLED=true</code>.</li>
                @else
                    <li>Emails use Resend. Set <code>RESEND_API_KEY</code> in <code>.env</code>.</li>
                @endif
            </ul>
        </div>
    </div>
</div>
</body>
</html>
