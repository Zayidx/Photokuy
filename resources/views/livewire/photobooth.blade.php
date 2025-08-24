<div>
    <style>
        /* Custom CSS for the Photobooth with Pink Theme */
        .photobooth-body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #FFC0CB, #F8BBD0); /* Soft pink gradient background */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 2rem;
        }
        .alert {
            background: #fde2e7;
            color: #7f1d1d;
            border: 1px solid #f8b4c0;
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
            color: #E91E63; /* Hot Pink */
            margin-bottom: 0.5rem;
        }
        h2 {
            font-weight: 400;
            font-size: 1.5rem;
            color: #AD1457; /* Darker Pink */
            margin-bottom: 1.5rem;
        }
        h3 {
            font-weight: 600;
            color: #AD1457;
            margin-bottom: 0.5rem;
        }
        p {
            color: #444;
            font-size: 1.1rem;
        }

        .action-button {
            background: linear-gradient(45deg, #EC407A, #D81B60);
            color: white;
            border: none;
            padding: 15px 30px;
            margin: 10px;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.4);
        }
        .action-button:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 6px 20px rgba(233, 30, 99, 0.6);
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

        #result { margin-top: 20px; text-align: center; }
        #result img {
            max-width: 100%;
            border: 5px solid #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        #qr-code { margin-top: 20px; }

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

        /* Retake modal */
        #retake-modal {
            position: absolute; inset: 0; background: rgba(0,0,0,0.6);
            display: none; align-items: center; justify-content: center; padding: 1rem;
        }
        #retake-card {
            background: #fff; border-radius: 16px; padding: 1rem; max-width: 520px; width: 100%; text-align: center;
        }
        #retake-preview { max-width: 100%; border-radius: 10px; }
        .btn-row { display: flex; gap: 10px; justify-content: center; margin-top: 12px; }
        .btn {
            border: none; padding: 12px 16px; border-radius: 10px; cursor: pointer; font-weight: 600;
        }
        .btn-keep { background: linear-gradient(45deg, #22c55e, #16a34a); color: #fff; }
        .btn-retake { background: #f3f4f6; color: #111827; }

        /* Filters */
        #video.filter-bw { filter: grayscale(100%); }
        #video.filter-sepia { filter: sepia(100%); }
        #video.filter-vintage { filter: sepia(60%) contrast(110%) brightness(90%) saturate(120%); }
        #video.mirror { transform: scaleX(-1); }
    </style>

    <div class="photobooth-body">
        <div class="photobooth-container">
            <h1 class="font-black text-3xl md:text-4xl text-rose-600">Photobooth: {{ $layout }}</h1>

            @if ($step === 'capturing')
                <div id="camera-area">
                    <div id="js-error" class="alert" role="alert" style="display:none;"></div>
                    <h2 class="text-rose-800/80 text-xl md:text-2xl font-semibold">Get Ready!</h2>
                    <div style="display:flex;align-items:center;gap:8px;justify-content:center;margin:8px 0 4px 0;">
                        <label for="capture-camera-select" style="font-weight:600;color:#AD1457;">Camera</label>
                        <select id="capture-camera-select" style="padding:6px 10px;border-radius:8px;border:1px solid #F48FB1;min-width:220px;">
                            <option value="">Default (Auto)</option>
                        </select>
                    </div>
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
                        <div id="retake-modal" aria-modal="true" role="dialog">
                            <div id="retake-card">
                                <img id="retake-preview" alt="Captured preview" />
                                <div class="btn-row">
                                    <button id="keep-btn" class="btn btn-keep">Keep</button>
                                    <button id="retake-btn" class="btn btn-retake">Retake</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button id="start-button" class="action-button" aria-disabled="true">Start</button>
                    <button id="undo-button" class="action-button" style="background: linear-gradient(45deg,#9ca3af,#6b7280); display:none;">Undo last</button>
                    <canvas id="canvas" style="display:none;"></canvas>
                </div>
            @endif

            @if ($step === 'sending_email')
                <div wire:poll="sendEmailAndFinish">
                    <h2 class="text-rose-800/80 text-xl md:text-2xl font-semibold">Processing...</h2>
                    <p>Generating your photo strip and sending it to your email. Please wait a moment.</p>
                    <div class="spinner" aria-hidden="true"></div>
                </div>
            @endif

            @if ($step === 'finished')
                <div id="result">
                    <h2 class="text-rose-800/80 text-xl md:text-2xl font-semibold">Your Photo Strip:</h2>
                    <img src="{{ $finalStripUrl }}" alt="Final Photo Strip">
                    <div id="qr-code">
                        <h3 class="text-rose-700 text-lg font-semibold">Scan to Download:</h3>
                        {!! QrCode::size(200)->generate(route('photo.view', ['filename' => basename($finalStripUrl)])) !!}
                    </div>
                    <p>A link has also been sent to your email address.</p>
                    <button wire:click="resetPhotobooth" class="action-button">Take Another</button>
                </div>
            @endif
        </div>
    </div>

    @assets
    <script>
    (function setupCapture(){
        function boot(){
            if (@json($step) !== 'capturing') return;
            const video = document.getElementById('video');
            if (!video) return;
            const canvas = document.getElementById('canvas');
            const countdownEl = document.getElementById('countdown');
            const photoCounterEl = document.getElementById('photo-counter');
            const startButton = document.getElementById('start-button');
            const cameraStatus = document.getElementById('camera-status');
            const undoButton = document.getElementById('undo-button');
            const retakeModal = document.getElementById('retake-modal');
            const retakePreview = document.getElementById('retake-preview');
            const keepBtn = document.getElementById('keep-btn');
            const retakeBtn = document.getElementById('retake-btn');
            const toasts = document.getElementById('toast-container');

            let stream;
            let totalCaptures = @json($totalCaptures);
            let currentCapture = 0;
            let ready = false;
            let pendingDataUrl = null;

            function toast(msg) { photoboothToast(msg); }

            function startCountdown() {
                if (!ready) return; // avoid starting before camera is ready
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
                countdownEl.textContent = '';
                photoCounterEl.textContent = 'Capturing...';
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                pendingDataUrl = canvas.toDataURL('image/jpeg');
                // Show preview modal for retake/keep decision
                retakePreview.src = pendingDataUrl;
                retakeModal.style.display = 'flex';

                if (currentCapture < totalCaptures) {
                    // Wait for keep/retake before proceeding
                }
            }

            startButton.addEventListener('click', startCountdown);
            undoButton.addEventListener('click', () => {
                if (currentCapture > 0) {
                    currentCapture--;
                    @this.call('undoLastCapture');
                    photoCounterEl.textContent = currentCapture > 0
                        ? `Photo ${currentCapture} of ${totalCaptures}`
                        : '';
                    if (currentCapture === 0) {
                        undoButton.style.display = 'none';
                    }
                    toast('Last capture undone');
                }
            });

            keepBtn.addEventListener('click', () => {
                if (!pendingDataUrl) return;
                @this.call('capture', pendingDataUrl);
                pendingDataUrl = null;
                retakeModal.style.display = 'none';
                undoButton.style.display = 'inline-block';
                undoButton.disabled = false;
                if (currentCapture < totalCaptures) {
                    setTimeout(startCountdown, 1000);
                }
            });
            retakeBtn.addEventListener('click', () => {
                retakeModal.style.display = 'none';
                pendingDataUrl = null;
                // rollback counter and retry
                currentCapture--;
                photoCounterEl.textContent = currentCapture > 0 ? `Photo ${currentCapture} of ${totalCaptures}` : '';
                setTimeout(startCountdown, 300);
            });

            const jsError = document.getElementById('js-error');
            function showError(msg) {
                if (jsError) {
                    jsError.textContent = msg;
                    jsError.style.display = 'block';
                } else {
                    alert(msg);
                }
            }

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                showError('Camera API not supported by this browser.');
                return;
            }
            cameraStatus.style.display = 'block';
            const selectedId = @json($cameraDeviceId ?? null);
            const mediaConstraints = selectedId ? { video: { deviceId: { exact: selectedId } }, audio: false } : { video: { facingMode: 'user' }, audio: false };
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
                    // Populate camera dropdown
                    return navigator.mediaDevices.enumerateDevices();
                })
                .then(devices => {
                    const select = document.getElementById('capture-camera-select');
                    if (!select) return;
                    const current = @json($cameraDeviceId ?? null);
                    select.innerHTML = '<option value="">Default (Auto)</option>';
                    devices.filter(d => d.kind === 'videoinput').forEach(d => {
                        const opt = document.createElement('option');
                        opt.value = d.deviceId;
                        const name = d.label || 'Camera';
                        const isVirtual = /virtual|obs|snap|manycam|xsplit/i.test(name);
                        opt.textContent = isVirtual ? `${name} (Virtual)` : name;
                        if (current && d.deviceId === current) opt.selected = true;
                        select.appendChild(opt);
                    });
                })
                .catch(err => {
                    console.error("Error accessing camera: ", err);
                    showError('Could not access the camera. ' + (err && err.message ? err.message : 'Please allow camera access and refresh the page.'));
                    cameraStatus.style.display = 'none';
                });

            document.addEventListener('livewire:navigating', () => {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
            }, { once: true });
            // Handle camera switching
            const select = document.getElementById('capture-camera-select');
            if (select) {
                select.addEventListener('change', async (e) => {
                    try {
                        const id = e.target.value || null;
                        @this.set('cameraDeviceId', id);
                        if (stream) stream.getTracks().forEach(t=>t.stop());
                        const next = id ? { video: { deviceId: { exact: id } }, audio: false } : { video: { facingMode: 'user' }, audio: false };
                        const s2 = await navigator.mediaDevices.getUserMedia(next);
                        stream = s2;
                        video.srcObject = stream;
                        await video.play?.();
                    } catch (e) {
                        console.error('Failed to switch camera', e);
                        showError('Failed to switch camera.');
                    }
                });
            }
        }
        document.addEventListener('DOMContentLoaded', boot);
        document.addEventListener('livewire:init', boot);
        document.addEventListener('livewire:navigated', boot);
    })();
    </script>
    @endassets
</div>
