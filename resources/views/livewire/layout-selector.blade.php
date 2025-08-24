<div>
    <style>
        /* Reusing the same font import */
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

        .photobooth-container {
            width: 100%;
            max-width: 56rem; /* 896px */
            margin: 2rem auto;
            padding: 2.5rem;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border: 1px solid rgba(255, 255, 255, 0.18);
            text-align: center;
        }

        h1 {
            font-family: 'Pacifico', cursive;
            font-weight: 700;
            font-size: 2.5rem;
            color: #C2410C; /* Dark Orange */
            margin-bottom: 0.5rem;
        }

        h2 {
            font-weight: 600;
            font-size: 1.5rem;
            color: #9A3412; /* Darker Orange */
            margin-bottom: 1.5rem;
        }
        
        .layout-grid {
            margin-top: 1.5rem;
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        @media (min-width: 640px) {
            .layout-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (min-width: 1024px) {
            .layout-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .layout-button {
            border-radius: 1rem;
            background: linear-gradient(45deg, #F97316, #EA580C);
            color: white;
            font-weight: 600;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(234, 88, 12, 0.4);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .layout-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(234, 88, 12, 0.6);
        }

        .layout-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .button-spinner {
            width: 1.25rem; /* 20px */
            height: 1.25rem; /* 20px */
            border: 2px solid rgba(255,255,255,0.5);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

    </style>

    <div class="photobooth-body">
        <div class="photobooth-container">
            <h1>Laravel Livewire Photobooth</h1>
            <div>
                <h2>Choose Your Strip Layout</h2>
                <div class="layout-grid">
                    @foreach ($layouts as $key => $label)
                        <button wire:click="selectLayout('{{ $key }}')"
                                wire:loading.attr="disabled" wire:target="selectLayout('{{ $key }}')"
                                class="layout-button">
                            <span>{{ $label }}</span>
                            <div wire:loading wire:target="selectLayout('{{ $key }}')" class="button-spinner"></div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
