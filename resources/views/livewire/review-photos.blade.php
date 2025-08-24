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
            max-width: 950px; /* Wider container for review */
            width: 100%;
            margin: 2rem auto;
            padding: 2rem 3rem;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border: 1px solid rgba(255, 255, 255, 0.18);
            text-align: center;
        }

        h1, h2, h3 {
            color: #333;
            text-align: left;
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

        .action-button, .btn-primary {
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
        .action-button:hover, .btn-primary:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 6px 20px rgba(234, 88, 12, 0.6);
        }

        .btn { border:none; padding:10px 14px; border-radius:10px; cursor:pointer; font-weight:600; }
        .btn-secondary { background:#f3f4f6; color: #374151; }
        .btn-secondary:hover { background: #e5e7eb; }

        /* --- Review Screen Specific Styles --- */
        .top-bar { display:flex; justify-content: space-between; align-items:center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .top-bar-info { text-align: left; }
        .summary { color:#78350F; font-size: 1rem; margin-top: -0.25rem; }
        .photo-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem; }
        .photo-tile { background: #fff; border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.08); transition: transform .2s ease, box-shadow .2s ease; overflow: hidden; }
        .photo-tile:hover { transform: translateY(-4px); box-shadow: 0 10px 24px rgba(0,0,0,0.12); }
        .photo-tile img { width: 100%; display:block; aspect-ratio: 4 / 3; object-fit: cover; cursor: pointer; }
        .tile-actions { padding: 1rem; background-color: #FFF7ED; text-align: center; }
        .tile-actions .btn-retake { width: 100%; background: #fff; color: #EA580C; border: 2px solid #FDBA74; padding: 10px; font-size: 1rem; }
        .tile-actions .btn-retake:hover { background: #FEF3C7; }
        
        /* --- Modals --- */
        .modal { position:fixed; inset:0; background:rgba(0,0,0,0.8); display:none; align-items:center; justify-content:center; z-index:50; backdrop-filter: blur(5px); }
        .modal.active { display: flex; }
        .modal-card { background:#fff; border-radius:16px; padding:1.5rem; width:min(94vw, 700px); position:relative; box-shadow: 0 12px 32px rgba(0,0,0,0.25); }
        .modal-video-wrap { position:relative; margin-top: 1rem; }
        .modal-video-wrap video { width:100%; border-radius:12px; background:#000; display: block; }
        .modal-overlay { position:absolute; inset:0; display:flex; align-items:center; justify-content:center; pointer-events:none; }
        #cam-countdown { font-size: clamp(48px, 8vw, 120px); font-weight: 900; color: #fff; text-shadow: 0 4px 20px rgba(0,0,0,0.6); }
        .modal-card .modal-actions { display:flex; gap:10px; justify-content:space-between; margin-top:1.5rem; align-items:center; }
        
        .fs-modal-content { position: relative; }
        .fs-modal-content img { max-width: 90vw; max-height: 90vh; border-radius: 12px; box-shadow: 0 12px 32px rgba(0,0,0,0.35); display: block; }
        .fs-modal-close { position: absolute; top: -15px; right: -15px; background: white; color: #EA580C; border-radius: 50%; width: 40px; height: 40px; border: none; font-size: 2rem; cursor: pointer; display: flex; align-items: center; justify-content: center; line-height: 1; box-shadow: 0 4px 12px rgba(0,0,0,0.2); }

        /* --- Flash Effect --- */
        #flash-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: white; opacity: 0; pointer-events: none; z-index: 9998; }
        @keyframes camera-flash-animation { from { opacity: 0; } 50% { opacity: 0.9; } to { opacity: 0; } }
        .flash-effect { animation: camera-flash-animation 300ms ease-out; }
    </style>

    <div class="photobooth-body">
        <div id="flash-overlay"></div> <!-- Element for the flash effect -->
        <div class="photobooth-container">

            @if ($step === 'review')
                <div class="top-bar">
                    <div class="top-bar-info">
                        <h1>Review Your Photos</h1>
                        <p class="summary">Layout: <strong>{{ $layout }}</strong> | Tap image to preview, or retake any shot.</p>
                    </div>
                    <div style="display:flex; gap:1rem;">
                        <button wire:click="restartShoot" class="btn btn-secondary">Start Over</button>
                        <button wire:click="finalize" class="btn btn-primary">Looks Good, Finalize!</button>
                    </div>
                </div>
                <div class="photo-grid">
                    @foreach($photoUrls as $i => $url)
                        <div class="photo-tile">
                            <img src="{{ $url }}" alt="Photo {{ $i + 1 }}" data-index="{{ $i }}" class="thumb">
                            <div class="tile-actions">
                                <button class="btn btn-retake" data-idx="{{ $i }}">Retake Photo {{ $i + 1 }}</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Fullscreen preview modal -->
                <div id="fs-modal" class="modal">
                    <div class="fs-modal-content">
                        <img id="fs-img" alt="Preview" />
                        <button id="fs-close" class="fs-modal-close">&times;</button>
                    </div>
                </div>

                <!-- Camera modal for retake -->
                <div id="cam-modal" class="modal">
                    <div class="modal-card">
                        <h3>Retake Photo</h3>
                        <div class="modal-video-wrap">
                            <video id="cam-video" autoplay muted playsinline></video>
                            <div id="cam-overlay" class="modal-overlay"><div id="cam-countdown"></div></div>
                        </div>
                        <div class="modal-actions">
                            <button id="cam-cancel" class="btn btn-secondary">Cancel</button>
                            <div style="display:flex; gap:8px; align-items:center;">
                                <span id="cam-status" style="color:#6b7280;"></span>
                                <button id="cam-start" class="btn btn-primary">Start Retake</button>
                            </div>
                        </div>
                        <canvas id="cam-canvas" style="display:none;"></canvas>
                    </div>
                </div>
            @endif

            @if ($step === 'finalizing' || $step === 'sending_email')
                <div style="text-align:center; padding: 4rem 0;">
                    <h2 style="text-align:center;">Processing...</h2>
                    <p>Please wait while we create your photo strip!</p>
                    <div class="spinner" style="margin: 1rem auto;" aria-hidden="true"></div>
                </div>
            @endif

            @if ($step === 'finished')
                 @php $downloadUrl = route('photo.view', ['filename' => basename($finalStripUrl)]); @endphp
                <div id="result" style="padding: 2rem 0;">
                    <h2 style="text-align:center;">Your Photo Strip is Ready!</h2>
                    <div class="result-wrap" style="max-width: 500px; margin: 1.5rem auto 0; display:flex; flex-direction:column; gap: 1.5rem;">
                         <div class="result-card" style="background:#fff; border-radius:16px; padding:16px; box-shadow: 0 8px 24px rgba(0,0,0,0.08);">
                            <img src="{{ $finalStripUrl }}" alt="Final Photo Strip" style="max-width:100%; border-radius:12px; display:block;">
                            <div class="actions" style="display:flex; gap:10px; flex-wrap:wrap; margin-top:12px; justify-content: center;">
                                <a href="{{ $finalStripUrl }}" download class="btn btn-primary">Download</a>
                                <button type="button" class="btn btn-secondary" id="copy-link" data-url="{{ $downloadUrl }}">Copy Link</button>
                            </div>
                        </div>
                        <div class="qr-card" style="background:#fff; border-radius:16px; padding:16px; box-shadow: 0 8px 24px rgba(0,0,0,0.08);">
                            <h3 style="text-align:center; margin-top:0;">Scan to Download</h3>
                            <div id="qr-code" style="display:flex; flex-direction:column; gap:8px; align-items:center;">
                                {!! QrCode::size(200)->generate($downloadUrl) !!}
                            </div>
                        </div>
                    </div>
                     <button wire:click="resetPhotobooth" class="btn btn-secondary" style="margin-top:2rem;">Take Another</button>
                </div>
            @endif
        </div>
    </div>

    @assets
    <script>
        (function reviewUI(){
            function boot(){
                if (@json($step) !== 'review') return;

                // --- Elements ---
                const fsModal = document.getElementById('fs-modal');
                const fsImg = document.getElementById('fs-img');
                const fsClose = document.getElementById('fs-close');
                const camModal = document.getElementById('cam-modal');
                const camVideo = document.getElementById('cam-video');
                const camCanvas = document.getElementById('cam-canvas');
                const btnCancel = document.getElementById('cam-cancel');
                const btnStart = document.getElementById('cam-start');
                const camCountdown = document.getElementById('cam-countdown');
                const camStatus = document.getElementById('cam-status');
                const flashOverlay = document.getElementById('flash-overlay');

                // --- State ---
                let currentIndex = null;
                let stream;
                let ready = false;

                // --- Fullscreen Preview Logic ---
                function openFS(src) {
                    if (fsModal && fsImg) {
                        fsImg.src = src;
                        fsModal.classList.add('active');
                    }
                }
                function closeFS() {
                    if (fsModal && fsImg) {
                        fsModal.classList.remove('active');
                        fsImg.src = '';
                    }
                }
                document.querySelectorAll('.thumb').forEach(img => {
                    img.addEventListener('click', () => openFS(img.src));
                });
                fsModal?.addEventListener('click', (e) => {
                    if (e.target === fsModal) closeFS();
                });
                fsClose?.addEventListener('click', closeFS);
                
                // --- Retake Camera Logic ---
                function closeCamModal() {
                    if (camModal) camModal.classList.remove('active');
                    if (stream) {
                        stream.getTracks().forEach(t => t.stop());
                        stream = null;
                    }
                    ready = false;
                    camCountdown.textContent = '';
                    camStatus.textContent = '';
                    if (btnStart) btnStart.disabled = false;
                }

                document.querySelectorAll('.btn-retake').forEach(btn => {
                    btn.addEventListener('click', async (e) => {
                        currentIndex = parseInt(e.currentTarget.dataset.idx, 10);
                        if (camModal) camModal.classList.add('active');
                        try {
                            // Use session's deviceId if available
                            const deviceId = @json(session('photobooth_deviceId'));
                            const constraints = deviceId ? { video: { deviceId: { exact: deviceId } } } : { video: true };
                            stream = await navigator.mediaDevices.getUserMedia(constraints);
                            camVideo.srcObject = stream;
                            const p = camVideo.play?.();
                            if (p && p.catch) p.catch(() => {});
                            camVideo.onloadedmetadata = () => {
                                ready = true;
                                camStatus.textContent = 'Ready';
                            };
                        } catch (err) {
                            alert('Could not access camera for retake.');
                            closeCamModal();
                        }
                    });
                });

                btnCancel?.addEventListener('click', closeCamModal);
                
                btnStart?.addEventListener('click', async () => {
                    if (!stream || !ready || currentIndex === null) return;
                    btnStart.disabled = true;
                    camStatus.textContent = 'Get ready...';
                    let count = @json($countdown);
                    camCountdown.textContent = count;
                    const timer = setInterval(async () => {
                        count--;
                        if (count > 0) {
                            camCountdown.textContent = count;
                        } else if (count === 0) {
                            camCountdown.textContent = 'Smile!';
                        } else {
                            clearInterval(timer);
                            
                            // --- Trigger Flash ---
                            if (flashOverlay) {
                                flashOverlay.classList.add('flash-effect');
                                setTimeout(() => flashOverlay.classList.remove('flash-effect'), 300);
                            }

                            // --- Capture Image ---
                            setTimeout(async () => {
                                camCanvas.width = camVideo.videoWidth;
                                camCanvas.height = camVideo.videoHeight;
                                camCanvas.getContext('2d').drawImage(camVideo, 0, 0, camCanvas.width, camCanvas.height);
                                const dataUrl = camCanvas.toDataURL('image/jpeg');
                                
                                camStatus.textContent = 'Updating...';
                                await @this.call('retake', currentIndex, dataUrl);
                                
                                // Cleanup and close
                                closeCamModal();
                            }, 50);
                        }
                    }, 1000);
                });
                
                // --- Global Listeners ---
                document.addEventListener('keydown', (ev) => {
                    if (ev.key === 'Escape') {
                        closeFS();
                        closeCamModal();
                    }
                });
                document.addEventListener('livewire:navigating', () => {
                    if (stream) {
                        stream.getTracks().forEach(t => t.stop());
                        stream = null;
                    }
                }, { once: true });
            }
            document.addEventListener('DOMContentLoaded', boot);
            document.addEventListener('livewire:init', boot);
            document.addEventListener('livewire:navigated', boot);
        })();

        // Helper for finished view
        (function copyHelper(){
            function init(){
                const btn = document.getElementById('copy-link');
                if (!btn) return;
                btn.addEventListener('click', async () => {
                    const url = btn.getAttribute('data-url');
                    try {
                        await navigator.clipboard.writeText(url);
                        if(typeof photoboothToast === 'function') photoboothToast('Link copied');
                    } catch {
                        const t = document.createElement('textarea');
                        t.value = url; document.body.appendChild(t); t.select();
                        document.execCommand('copy'); document.body.removeChild(t);
                         if(typeof photoboothToast === 'function') photoboothToast('Link copied');
                    }
                });
            }
            document.addEventListener('DOMContentLoaded', init);
            document.addEventListener('livewire:navigated', init);
        })();
    </script>
    @endassets
</div>
