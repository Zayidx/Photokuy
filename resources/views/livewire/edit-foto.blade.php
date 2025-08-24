
<div>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom CSS for the Pink Photobooth Theme */
        .photobooth-body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #FFC0CB, #F8BBD0); /* Soft pink gradient background */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 2rem;
        }

        .photobooth-container {
            max-width: 900px;
            width: 100%;
            margin: 2rem auto;
            padding: 2.5rem;
            background: rgba(255, 255, 255, 0.9); /* Slightly transparent white background */
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border: 1px solid rgba(255, 255, 255, 0.18);
            display: flex;
            flex-wrap: wrap; /* Allow wrapping on smaller screens */
            gap: 2.5rem;
        }

        .camera-preview {
            flex: 1;
            min-width: 300px; /* Minimum width for camera view */
            text-align: center;
        }

        .settings-panel {
            width: 280px;
        }

        /* Use the fun, cursive font for headings */
        h2, h3 {
            font-family: 'Pacifico', cursive;
            color: #E91E63; /* Hot Pink */
            margin-bottom: 1.5rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        h2 { font-size: 2.5rem; }
        h3 { font-size: 2rem; }

        #camera-wrapper {
            width: 100%;
            background: #111;
            border: 5px solid #fff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            border-radius: 15px;
            aspect-ratio: 4 / 3;
            position: relative;
            overflow: hidden; /* Hide parts of video that go outside the rounded corners */
        }

        #camera {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease, filter 0.3s ease;
        }

        .setting-group {
            margin-bottom: 1.5rem;
        }

        .setting-group > label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: #AD1457; /* Darker Pink */
        }

        /* Custom styling for select dropdown */
        .setting-group select {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 2px solid #F48FB1;
            font-size: 1rem;
            cursor: pointer;
            background-color: #fff;
            color: #333;
            appearance: none; /* Remove default arrow */
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23AD1457' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            transition: all 0.2s ease;
        }
        .setting-group select:focus {
            outline: none;
            border-color: #E91E63;
            box-shadow: 0 0 0 3px rgba(233, 30, 99, 0.2);
        }

        /* Filter buttons styling */
        .filter-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .filter-buttons button {
            padding: 10px;
            border-radius: 10px;
            border: 2px solid #F48FB1;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            background-color: #fff;
            color: #AD1457;
            transition: all 0.2s ease-in-out;
        }
        .filter-buttons button:hover {
            background-color: #FCE4EC; /* Light pink hover */
        }
        .filter-buttons button.active {
            background-color: #E91E63;
            color: white;
            border-color: #E91E63;
            transform: scale(1.05);
        }

        /* Custom Toggle Switch for Mirror Mode */
        .toggle-switch {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .toggle-switch .toggle-label {
            font-weight: 600;
            color: #AD1457;
        }
        .toggle-switch .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        .toggle-switch .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .toggle-switch .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #F48FB1;
            transition: .4s;
            border-radius: 34px;
        }
        .toggle-switch .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        .toggle-switch input:checked + .slider {
            background-color: #E91E63;
        }
        .toggle-switch input:checked + .slider:before {
            transform: translateX(26px);
        }


        /* Main Action Button */
        .action-button {
            background: linear-gradient(45deg, #EC407A, #D81B60);
            color: white;
            border: none;
            padding: 18px 25px;
            width: 100%;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 15px;
            cursor: pointer;
            margin-top: 1rem;
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.4);
            transition: all 0.3s ease;
            letter-spacing: 1px;
        }
        .action-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(233, 30, 99, 0.6);
        }

        /* CSS classes for camera effects */
        #camera.mirror { transform: scaleX(-1); }
        #camera.filter-bw { filter: grayscale(100%); }
        #camera.filter-sepia { filter: sepia(100%); }
        #camera.filter-vintage { filter: sepia(60%) contrast(110%) brightness(90%) saturate(120%); }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .photobooth-container {
                flex-direction: column;
                padding: 1.5rem;
            }
            .settings-panel {
                width: 100%;
                padding-left: 0;
                border-left: none;
            }
        }
    </style>

    <!-- The main component is wrapped in a body-like div for styling -->
    <div class="photobooth-body">
        <div class="photobooth-container">
            <div class="camera-preview">
                <h2 class="font-black text-3xl md:text-4xl text-rose-600">Photobooth Fun!</h2>
                <div id="camera-wrapper">
                    <video id="camera" autoplay muted playsinline
                        @class([
                            'mirror' => $mirrorMode,
                            'filter-bw' => $filter === 'bw',
                            'filter-sepia' => $filter === 'sepia',
                            'filter-vintage' => $filter === 'vintage',
                        ])>
                    </video>
                </div>
            </div>
            <div class="settings-panel">
                <h3 class="text-rose-800/80 text-2xl font-semibold">Settings</h3>
                <div class="setting-group">
                    <label for="camera-select">Camera</label>
                    <select id="camera-select">
                        <option value="">Default (Auto)</option>
                    </select>
                </div>
                <div class="setting-group">
                    <label for="countdown">Countdown</label>
                    <select id="countdown" wire:model="countdown">
                        <option value="3">3 seconds</option>
                        <option value="5">5 seconds</option>
                        <option value="10">10 seconds</option>
                    </select>
                </div>
                <div class="setting-group">
                    <div class="toggle-switch">
                        <span class="toggle-label">Mirror Mode</span>
                        <!-- Custom toggle switch for a better UX -->
                        <label class="switch" for="mirrorModeToggle">
                            <input type="checkbox" id="mirrorModeToggle" wire:click="$toggle('mirrorMode')" {{ $mirrorMode ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
                <div class="setting-group">
                    <label>Filter</label>
                    <div class="filter-buttons">
                        @foreach($filters as $key => $name)
                            <button wire:click="$set('filter', '{{ $key }}')"
                                    class="{{ $filter === $key ? 'active' : '' }}">
                                {{ $name }}
                            </button>
                        @endforeach
                    </div>
                </div>
                <hr style="border-color: #F8BBD0; margin: 2rem 0;">
                <button wire:click="startShooting" wire:loading.attr="disabled" wire:target="startShooting" class="action-button disabled:opacity-70 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="startShooting">Start Shooting</span>
                    <span wire:loading wire:target="startShooting">Starting…</span>
                </button>
            </div>
        </div>
    </div>

    @assets
    <script>
        (function setupPreview(){
            function boot(){
                const video = document.getElementById('camera');
                if (!video) return;
                const select = document.getElementById('camera-select');
                let stream;
                const storedId = @json($cameraDeviceId ?? null);
                const constraints = storedId ? { video: { deviceId: { exact: storedId } }, audio: false } : { video: { facingMode: 'user' }, audio: false };
                navigator.mediaDevices.getUserMedia(constraints)
                    .then(s => {
                        stream = s;
                        video.srcObject = stream;
                        video.muted = true;
                        const playPromise = video.play?.();
                        if (playPromise && typeof playPromise.then === 'function') {
                            playPromise.catch(()=>{});
                        }
                        // Populate device list after permission granted
                        return navigator.mediaDevices.enumerateDevices();
                    })
                    .then(devices => {
                        if (!select) return;
                        const current = storedId;
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
                        alert('Could not access the camera. Please allow camera access and refresh the page.');
                    });
                document.addEventListener('livewire:navigating', () => {
                    if (stream) stream.getTracks().forEach(t=>t.stop());
                }, { once: true });

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
                            alert('Failed to switch camera.');
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
