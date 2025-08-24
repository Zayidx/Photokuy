<div>
    <style>
        /* Custom CSS for the Photobooth with Orange Theme */
        @import url('https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@400;600;700&display=swap');

        .photobooth-body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #FDBA74, #FB923C); /* Warm orange gradient background */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 2rem;
        }
        .alert {
            background: #FFF7ED;
            color: #9A3412;
            border: 1px solid #FDBA74;
            padding: 12px 16px;
            border-radius: 10px;
            margin: 10px auto;
            max-width: 520px;
        }
        .spinner {
            width: 32px; height: 32px;
            border: 3px solid rgba(255,255,255,0.5);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-top: 12px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .photobooth-container {
            max-width: 650px;
            width: 100%;
            margin: 2rem auto;
            padding: 3rem;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border: 1px solid rgba(255, 255, 255, 0.18);
            text-align: center;
        }

        h1, h2 {
            color: #333;
        }
        h1 {
            font-family: 'Pacifico', cursive;
            font-weight: 700;
            font-size: 2.5rem;
            color: #C2410C; /* Dark Orange */
            margin-bottom: 0.5rem;
        }
        h2 {
            font-weight: 400;
            font-size: 1.5rem;
            color: #9A3412; /* Darker Orange */
            margin-bottom: 1.5rem;
        }
        h3 {
            font-weight: 600;
            color: #9A3412;
            margin-bottom: 0.5rem;
        }
        p {
            color: #444;
            font-size: 1.1rem;
        }

        .action-button {
            background: linear-gradient(45deg, #F97316, #EA580C);
            color: white;
            border: none;
            padding: 15px 30px;
            margin: 10px;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(234, 88, 12, 0.4);
        }
        .action-button:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 6px 20px rgba(234, 88, 12, 0.6);
        }

        #camera-container {
            position: relative;
            width: 100%;
            max-width: 500px;
            aspect-ratio: 4 / 3;
            border: 5px solid #fff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            border-radius: 15px;
            margin: 1rem auto;
            background: #000;
            overflow: hidden;
        }

        #video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: filter 0.3s ease;
        }

        #overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            color: white;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.7);
            background: rgba(0,0,0,0.2);
        }

        #countdown {
            font-size: 6rem;
            font-weight: 700;
        }

        #photo-counter {
            font-size: 1.8rem;
            margin-top: 1rem;
            font-weight: 600;
        }

        /* Result section */
        #result { margin-top: 24px; }
        .result-wrap { display:grid; grid-template-columns: 1fr; gap:16px; }
        @media (min-width: 840px){ .result-wrap { grid-template-columns: 2fr 1fr; align-items:start; } }
        .result-card, .qr-card { background:#fff; border-radius:16px; padding:16px; box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        .result-card img { max-width:100%; border-radius:12px; display:block; }
        .actions { display:flex; gap:10px; flex-wrap:wrap; margin-top:12px; }
        .btn { border:none; padding:10px 14px; border-radius:10px; cursor:pointer; font-weight:600; }
        .btn-primary { background: linear-gradient(45deg, #F97316, #EA580C); color:#fff; }
        .btn-secondary { background:#f3f4f6; }
        #qr-code { display:flex; flex-direction:column; gap:8px; align-items:center; }

        /* Toast */
        #toast-container {
            position: fixed; bottom: 16px; right: 16px; z-index: 9999;
        }
        .toast {
            background: rgba(17,17,17,0.9); color: #fff; border-radius: 10px;
            padding: 10px 14px; margin-top: 8px; box-shadow: 0 4px 14px rgba(0,0,0,0.25);
            animation: fadein 200ms ease, fadeout 200ms ease 2800ms forwards;
        }
        @keyframes fadein { from { opacity: 0; transform: translateY(6px);} to { opacity: 1; transform: translateY(0);} }
        @keyframes fadeout { to { opacity: 0; transform: translateY(6px);} }

        /* Filters */
        #video.filter-bw { filter: grayscale(100%); }
        #video.filter-sepia { filter: sepia(100%); }
        #video.filter-vintage { filter: sepia(60%) contrast(110%) brightness(90%) saturate(120%); }
        #video.mirror { transform: scaleX(-1); }

        /* Flash Effect */
        #flash-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: white;
            opacity: 0;
            pointer-events: none; /* Allows clicks to pass through */
            z-index: 9998; /* Below toast, above everything else */
        }

        @keyframes camera-flash-animation {
            from { opacity: 0; }
            50% { opacity: 0.9; }
            to { opacity: 0; }
        }

        .flash-effect {
            animation: camera-flash-animation 300ms ease-out;
        }
    </style>

    <div class="photobooth-body">
        <div id="flash-overlay"></div> <!-- Element for the flash effect -->
        <div class="photobooth-container">
            <h1 class="font-black text-3xl md:text-4xl text-orange-700">Photobooth: {{ $layout }}</h1>

            @if ($step === 'capturing')
                <div id="camera-area">
                    <div id="js-error" class="alert" role="alert" style="display:none;"></div>
                    <h2 class="text-orange-900/80 text-xl md:text-2xl font-semibold">Get Ready!</h2>
                    <div style="display:flex;align-items:center;gap:8px;justify-content:center;margin:8px 0 4px 0;">
                        <label for="capture-camera-select" style="font-weight:600;color:#9A3412;">Camera</label>
                        <select id="capture-camera-select" style="padding:6px 10px;border-radius:8px;border:1px solid #FDBA74;min-width:220px;">
                            <option value="">Default (Auto)</option>
                        </select>
                        <button type="button" id="capture-camera-refresh" title="Refresh devices" style="padding:6px 10px;border-radius:8px;border:1px solid #EA580C;background:#FFF7ED;color:#9A3412;">Refresh</button>
                    </div>
                    <small id="capture-camera-hint" style="display:block;text-align:center;color:#6b7280;margin-top:6px;">Jika OBS Virtual Camera belum muncul, jalankan "Start Virtual Camera" di OBS, beri izin kamera di browser, lalu klik Refresh.</small>
                    <div id="camera-container">
                        <video id="video" autoplay muted playsinline @class([
                            'mirror' => $mirrorMode,
                            'filter-bw' => $filter === 'bw',
                            'filter-sepia' => $filter === 'sepia',
                            'filter-vintage' => $filter === 'vintage',
                        ])></video>
                        <div id="overlay">
                            <div id="countdown" aria-live="polite"></div>
                            <div id="photo-counter" aria-live="polite"></div>
                            <div id="camera-status" class="spinner" style="display:none;" aria-hidden="true"></div>
                        </div>
                    </div>
                    <button id="start-button" class="action-button" aria-disabled="true">Start</button>
                    <canvas id="canvas" style="display:none;"></canvas>
                </div>
            @endif

            @if ($step === 'sending_email')
                <div wire:poll="sendEmailAndFinish">
                    <h2 class="text-orange-900/80 text-xl md:text-2xl font-semibold">Processing...</h2>
                    <p>Generating your photo strip and sending it to your email. Please wait a moment.</p>
                    <div class="spinner" aria-hidden="true"></div>
                </div>
            @endif

            @if ($step === 'finished')
                @php $downloadUrl = route('photo.view', ['filename' => basename($finalStripUrl)]); @endphp
                <div id="result">
                    <h2 class="text-orange-900/80 text-xl md:text-2xl font-semibold">Your Photo Strip</h2>
                    <div class="result-wrap">
                        <div class="result-card">
                            <img src="{{ $finalStripUrl }}" alt="Final Photo Strip">
                            <div class="actions">
                                <a href="{{ $finalStripUrl }}" download class="btn btn-primary">Download</a>
                                <button type="button" class="btn btn-secondary" id="copy-link" data-url="{{ $downloadUrl }}">Copy Link</button>
                                <button wire:click="resetPhotobooth" class="btn btn-secondary">Take Another</button>
                            </div>
                        </div>
                        <div class="qr-card">
                            <h3 class="text-orange-800 text-lg font-semibold" style="margin-top:0;">Scan to Download</h3>
                            <div id="qr-code">
                                {!! QrCode::size(220)->generate($downloadUrl) !!}
                                <small style="color:#6b7280;">Use your phone camera to open the link.</small>
                            </div>
                        </div>
                    </div>
                    <p style="color:#6b7280;margin-top:12px;">A link has also been sent to your email address (if provided).</p>
                </div>
            @endif
        </div>
    </div>

    @assets
    <script>
    (function setupCapture(){
        function boot(){
            if (@json($step) !== 'capturing') return;
            // --- Elements ---
            const video = document.getElementById('video');
            if (!video) return;
            const canvas = document.getElementById('canvas');
            const countdownEl = document.getElementById('countdown');
            const photoCounterEl = document.getElementById('photo-counter');
            const startButton = document.getElementById('start-button');
            const cameraStatus = document.getElementById('camera-status');
            const cameraHint = document.getElementById('capture-camera-hint');
            const jsError = document.getElementById('js-error');
            const flashOverlay = document.getElementById('flash-overlay'); // Get flash element
            const toasts = document.getElementById('toast-container');

            // --- State ---
            let stream;
            let totalCaptures = @json($totalCaptures);
            let currentCapture = 0;
            let ready = false;

            function toast(msg) { photoboothToast(msg); }

            function startCountdown() {
                if (!ready) return; // Avoid starting before camera is ready
                startButton.setAttribute('disabled', 'disabled');
                startButton.setAttribute('aria-disabled', 'true');
                currentCapture++;
                photoCounterEl.textContent = `Photo ${currentCapture} of ${totalCaptures}`;
                
                let count = @json($countdown);
                countdownEl.textContent = count;

                const timer = setInterval(() => {
                    count--;
                    if (count > 0) {
                        countdownEl.textContent = count;
                    } else if (count === 0) {
                        countdownEl.textContent = 'Smile!';
                    } else {
                        clearInterval(timer);
                        captureImage();
                    }
                }, 1000);
            }

            function captureImage() {
                // --- 📸 TRIGGER FLASH EFFECT ---
                if (flashOverlay) {
                    flashOverlay.classList.add('flash-effect');
                    // Remove class after animation ends to allow re-triggering
                    setTimeout(() => {
                        flashOverlay.classList.remove('flash-effect');
                    }, 300);
                }
                
                // A tiny delay to let the flash appear before capturing the frame
                setTimeout(() => {
                    countdownEl.textContent = '';
                    photoCounterEl.textContent = 'Capturing...';
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    const context = canvas.getContext('2d');
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    const dataUrl = canvas.toDataURL('image/jpeg');
                    
                    @this.call('capture', dataUrl);

                    if (currentCapture < totalCaptures) {
                        setTimeout(startCountdown, 800);
                    }
                }, 50); // 50ms delay
            }

            startButton.addEventListener('click', startCountdown);

            function showError(msg) {
                if (jsError) {
                    jsError.textContent = msg;
                    jsError.style.display = 'block';
                } else {
                    alert(msg);
                }
            }
            function setHint(msg) {
                if (cameraHint) {
                    cameraHint.textContent = msg;
                }
            }

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                showError('Camera API not supported by this browser.');
                return;
            }
            cameraStatus.style.display = 'block';
            const selectedId = @json($cameraDeviceId ?? null);
            const mediaConstraints = selectedId ? { video: { deviceId: { exact: selectedId } }, audio: false } : { video: true, audio: false };
            navigator.mediaDevices.getUserMedia(mediaConstraints)
                .then(s => {
                    stream = s;
                    video.srcObject = stream;
                    video.muted = true;
                    const playPromise = video.play?.();
                    if (playPromise && typeof playPromise.then === 'function') {
                        playPromise.catch(() => {/* ignore autoplay errors */});
                    }
                    video.onloadedmetadata = () => {
                        ready = true;
                        cameraStatus.style.display = 'none';
                        startButton.removeAttribute('disabled');
                        startButton.setAttribute('aria-disabled', 'false');
                        toast('Camera ready');
                    };
                    return navigator.mediaDevices.enumerateDevices();
                })
                .then(devices => {
                    const select = document.getElementById('capture-camera-select');
                    if (!select) return;
                    const current = @json($cameraDeviceId ?? null);
                    select.innerHTML = '<option value="">Default (Auto)</option>';
                    const videoInputs = devices.filter(d => d.kind === 'videoinput' && d.deviceId && !/^default$|^communications$/.test(d.deviceId));
                    videoInputs.sort((a,b) => {
                        const av = /obs|virtual/i.test(a.label);
                        const bv = /obs|virtual/i.test(b.label);
                        if (av && !bv) return -1;
                        if (!av && bv) return 1;
                        return (a.label||'').localeCompare(b.label||'');
                    });
                    videoInputs.forEach(d => {
                        const opt = document.createElement('option');
                        opt.value = d.deviceId;
                        const name = d.label || 'Camera';
                        const isVirtual = /virtual|obs|snap|manycam|xsplit/i.test(name);
                        opt.textContent = isVirtual ? `${name} (Virtual)` : name;
                        if (current && d.deviceId === current) opt.selected = true;
                        select.appendChild(opt);
                    });
                    if (!current) {
                        const obs = Array.from(select.options).find(o => /obs/i.test(o.textContent));
                        if (obs && obs.value) {
                            obs.selected = true;
                            select.dispatchEvent(new Event('change'));
                        }
                    }
                })
                .catch(async err => {
                    console.error("Error accessing camera (first attempt): ", err);
                    try {
                        const s = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                        stream = s;
                        video.srcObject = stream;
                        await video.play?.();
                        ready = true;
                        cameraStatus.style.display = 'none';
                    } catch (e2) {
                        console.warn('Fallback getUserMedia failed:', e2);
                    }
                    try {
                        const devices = await navigator.mediaDevices.enumerateDevices();
                        const select = document.getElementById('capture-camera-select');
                        if (select) {
                            const current = @json($cameraDeviceId ?? null);
                            select.innerHTML = '<option value="">Default (Auto)</option>';
                            const videoInputs = devices.filter(d => d.kind === 'videoinput');
                            videoInputs.sort((a,b) => {
                                const av = /obs|virtual/i.test(a.label);
                                const bv = /obs|virtual/i.test(b.label);
                                if (av && !bv) return -1;
                                if (!av && bv) return 1;
                                return (a.label||'').localeCompare(b.label||'');
                            });
                            videoInputs.forEach(d => {
                                const opt = document.createElement('option');
                                opt.value = d.deviceId;
                                const name = d.label || 'Camera';
                                const isVirtual = /virtual|obs|snap|manycam|xsplit/i.test(name);
                                opt.textContent = isVirtual ? `${name} (Virtual)` : name;
                                if (current && d.deviceId === current) opt.selected = true;
                                select.appendChild(opt);
                            });
                        }
                    } catch (e3) {
                        console.warn('enumerateDevices failed after fallback:', e3);
                    }
                    if (!ready) {
                        showError('Could not access the camera. ' + (err && err.message ? err.message : 'Please allow camera access and ensure a camera is available.'));
                        cameraStatus.style.display = 'none';
                    }
                });

            document.addEventListener('livewire:navigating', () => {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
            }, { once: true });
            
            const select = document.getElementById('capture-camera-select');
            if (select) {
                select.addEventListener('change', async (e) => {
                    const id = e.target.value || null;
                    select.disabled = true;
                    setHint('Mengganti kamera...');
                    try {
                        if (stream) { stream.getTracks().forEach(t=>t.stop()); stream = null; }
                        video.srcObject = null;
                        await new Promise(r=>setTimeout(r,200));
                        const candidates = [];
                        if (id) {
                            candidates.push({ video: { deviceId: { exact: id }, width: { ideal: 1280 }, height: { ideal: 720 }, frameRate: { ideal: 30 } }, audio: false });
                            candidates.push({ video: { deviceId: { ideal: id }, width: { ideal: 1280 }, height: { ideal: 720 }, frameRate: { ideal: 30 } }, audio: false });
                        }
                        candidates.push({ video: true, audio: false });
                        let acquired = null, lastErr = null;
                        for (const c of candidates) {
                            try { acquired = await navigator.mediaDevices.getUserMedia(c); break; } catch (ee) { lastErr = ee; }
                        }
                        if (!acquired) throw lastErr || new Error('No camera available');
                        stream = acquired;
                        video.srcObject = stream;
                        const p = video.play?.(); if (p && p.catch) p.catch(()=>{});
                        @this.set('cameraDeviceId', id);
                        setHint('Kamera siap.');
                    } catch (e2) {
                        console.error('Gagal mengganti kamera', e2);
                        const name = e2?.name || '';
                        if (name === 'NotReadableError') {
                            showError('Kamera tidak dapat dimulai. Tutup aplikasi lain yang menggunakan kamera (Zoom/Discord/OBS) lalu coba lagi.');
                        } else {
                            showError('Gagal mengganti kamera. ' + (e2?.message || ''));
                        }
                        setHint('Gagal mengganti kamera. Pilih perangkat lain atau klik Refresh.');
                    } finally {
                        select.disabled = false;
                    }
                });
            }

            const refreshBtn = document.getElementById('capture-camera-refresh');
            if (refreshBtn && select) {
                refreshBtn.addEventListener('click', async () => {
                    try {
                        const devices = await navigator.mediaDevices.enumerateDevices();
                        const current = select.value || null;
                        select.innerHTML = '<option value="">Default (Auto)</option>';
                        const videoInputs = devices.filter(d => d.kind === 'videoinput' && d.deviceId && !/^default$|^communications$/.test(d.deviceId));
                        videoInputs.sort((a,b) => {
                            const av = /obs|virtual/i.test(a.label);
                            const bv = /obs|virtual/i.test(b.label);
                            if (av && !bv) return -1;
                            if (!av && bv) return 1;
                            return (a.label||'').localeCompare(b.label||'');
                        });
                        videoInputs.forEach(d => {
                            const opt = document.createElement('option');
                            opt.value = d.deviceId;
                            const name = d.label || 'Camera';
                            const isVirtual = /virtual|obs|snap|manycam|xsplit/i.test(name);
                            opt.textContent = isVirtual ? `${name} (Virtual)` : name;
                            if (current && d.deviceId === current) opt.selected = true;
                            select.appendChild(opt);
                        });
                    } catch (e) {
                        console.error('Failed to refresh devices', e);
                        showError('Failed to refresh camera list.');
                    }
                });
            }

            try {
                navigator.mediaDevices.addEventListener?.('devicechange', async () => {
                    try {
                        const devices = await navigator.mediaDevices.enumerateDevices();
                        const current = select?.value || null;
                        if (!select) return;
                        select.innerHTML = '<option value=\"\">Default (Auto)</option>';
                        const videoInputs = devices.filter(d => d.kind === 'videoinput' && d.deviceId && !/^default$|^communications$/.test(d.deviceId));
                        videoInputs.sort((a,b) => {
                            const av = /obs|virtual/i.test(a.label);
                            const bv = /obs|virtual/i.test(b.label);
                            if (av && !bv) return -1;
                            if (!av && bv) return 1;
                            return (a.label||'').localeCompare(b.label||'');
                        });
                        videoInputs.forEach(d => {
                            const opt = document.createElement('option');
                            opt.value = d.deviceId;
                            const name = d.label || 'Camera';
                            const isVirtual = /virtual|obs|snap|manycam|xsplit/i.test(name);
                            opt.textContent = isVirtual ? `${name} (Virtual)` : name;
                            if (current && d.deviceId === current) opt.selected = true;
                            select.appendChild(opt);
                        });
                    } catch {}
                });
            } catch {}
        }
        document.addEventListener('DOMContentLoaded', boot);
        document.addEventListener('livewire:init', boot);
        document.addEventListener('livewire:navigated', boot);
    })();
    
    (function copyHelper(){
        function init(){
            const btn = document.getElementById('copy-link');
            if (!btn) return;
            btn.addEventListener('click', async () => {
                const url = btn.getAttribute('data-url');
                try {
                    await navigator.clipboard.writeText(url);
                    photoboothToast('Link copied');
                } catch {
                    const t = document.createElement('textarea');
                    t.value = url; document.body.appendChild(t); t.select();
                    document.execCommand('copy'); document.body.removeChild(t);
                    photoboothToast('Link copied');
                }
            });
        }
        document.addEventListener('DOMContentLoaded', init);
        document.addEventListener('livewire:navigated', init);
    })();
    </script>
    @endassets
</div>
