<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to Photobooth!</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; font-family: 'Poppins', sans-serif; }

        .welcome-hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            overflow: hidden;
        }
        .bg-layer { position:absolute; inset:0; background-size: cover; background-position: center; background-repeat:no-repeat; transition: opacity .8s ease; }
        .bg1 { opacity: 1; }
        .bg2 { opacity: 0; }
        .bg-overlay { position:absolute; inset:0; background: linear-gradient(rgba(0,0,0,.45), rgba(0,0,0,.45)); }
        .controls { position:absolute; top:16px; right:16px; display:flex; gap:8px; z-index:3; }
        .ctrl-btn { background: rgba(255,255,255,0.85); color:#111827; border:none; padding:8px 12px; border-radius:10px; cursor:pointer; font-weight:600; }
        .ctrl-select { background: rgba(255,255,255,0.85); color:#111827; border:none; padding:8px 10px; border-radius:10px; }

        .welcome-container {
            max-width: 650px;
            width: 100%;
            padding: 3rem;
            background: rgba(255, 255, 255, 0.15); /* More transparent for a sleeker look */
            backdrop-filter: blur(12px);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
            color: white;
        }

        h1 {
            font-family: 'Pacifico', cursive;
            font-weight: 700;
            font-size: 3.5rem; /* Larger for more impact */
            text-shadow: 0 4px 15px rgba(0,0,0,0.4);
            margin: 0;
        }

        p.subtitle {
            font-size: 1.25rem;
            font-weight: 400;
            margin-top: 1rem;
            margin-bottom: 2.5rem;
            text-shadow: 0 2px 8px rgba(0,0,0,0.5);
        }

        .action-button {
            display: inline-block;
            text-decoration: none;
            background: linear-gradient(45deg, #F97316, #EA580C);
            color: white;
            border: none;
            padding: 16px 40px;
            font-size: 1.3rem;
            font-weight: 600;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(234, 88, 12, 0.5);
        }
        .action-button:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 8px 25px rgba(234, 88, 12, 0.7);
        }

        /* Responsive adjustments */
        @media (max-width: 640px) {
            h1 {
                font-size: 2.8rem;
            }
            .welcome-container {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>

    <div class="welcome-hero">
        <div class="bg-layer bg1" id="bg1"></div>
        <div class="bg-layer bg2" id="bg2"></div>
        <div class="bg-overlay"></div>
        <div class="controls" aria-label="Slideshow controls">
            <button id="bg-toggle" class="ctrl-btn" type="button">Pause</button>
            <button id="bg-next" class="ctrl-btn" type="button">Next</button>
            <select id="bg-speed" class="ctrl-select" aria-label="Speed">
                <option value="3000">3s</option>
                <option value="5000" selected>5s</option>
                <option value="8000">8s</option>
            </select>
        </div>
        <div class="welcome-container">
            <h1>Capture the Moment!</h1>
            <p class="subtitle">Ready to create some fun memories? Let's get started.</p>
            <a href="{{ route('photobooth.start') }}" class="action-button">
                Start Photobooth
            </a>
        </div>
    </div>
    <script>
        (function bgSlideshow(){
            const photos = @json($bgPhotos ?? []);
            const fallbacks = [
                'https://placehold.co/1920x1080/ffb3c7/ffffff?text=Photobooth&font=poppins',
                'https://placehold.co/1920x1080/fed7aa/111111?text=Smile!&font=poppins',
                'https://placehold.co/1920x1080/c7d2fe/111111?text=Say+Cheese!&font=poppins'
            ];
            const sourcesRaw = (photos && photos.length ? photos : fallbacks).slice(0, 20);
            // Shuffle (Fisher–Yates)
            const sources = sourcesRaw.slice();
            for (let i = sources.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [sources[i], sources[j]] = [sources[j], sources[i]];
            }
            const a = document.getElementById('bg1');
            const b = document.getElementById('bg2');
            const btnToggle = document.getElementById('bg-toggle');
            const btnNext = document.getElementById('bg-next');
            const selSpeed = document.getElementById('bg-speed');
            let idx = 0, useA = true, timer = null, paused = false, interval = parseInt(selSpeed.value, 10) || 5000;
            function setBg(el, url){ el.style.backgroundImage = `url('${url}')`; }
            function tick(){
                const next = sources[idx % sources.length];
                if (useA) { setBg(b, next); b.style.opacity = '1'; a.style.opacity = '0'; }
                else { setBg(a, next); a.style.opacity = '1'; b.style.opacity = '0'; }
                useA = !useA; idx++;
            }
            function start(){ stop(); timer = setInterval(()=>{ if(!paused) tick(); }, interval); }
            function stop(){ if (timer) { clearInterval(timer); timer = null; } }
            function setSpeed(ms){ interval = ms; start(); }
            // Preload first two
            if (sources[0]) setBg(a, sources[0]);
            if (sources[1]) setBg(b, sources[1]);
            idx = 2; useA = false; // next will fade to a
            start();
            // Controls
            btnToggle?.addEventListener('click', () => {
                paused = !paused;
                btnToggle.textContent = paused ? 'Play' : 'Pause';
                if (!paused) { tick(); }
            });
            btnNext?.addEventListener('click', () => { tick(); });
            selSpeed?.addEventListener('change', (e) => { const ms = parseInt(e.target.value,10)||5000; setSpeed(ms); });
        })();
    </script>
</body>
</html>
