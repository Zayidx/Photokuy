<div>
    <style>
        /* Reusing the same font import */
        @import url('https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins:wght@400;600;700&display=swap');

        /* Custom CSS for the Email Entry Screen with Orange Theme */
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
            max-width: 600px;
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

        h1 {
            font-family: 'Pacifico', cursive;
            font-weight: 700;
            font-size: 2.5rem;
            color: #C2410C; /* Dark Orange */
        }

        p.subtitle {
            font-size: 1.2rem;
            color: #9A3412; /* Darker Orange */
            margin-top: 0.5rem;
            margin-bottom: 2.5rem;
        }

        .email-form {
            margin-top: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .email-form input {
            width: 100%;
            max-width: 400px;
            padding: 15px;
            font-size: 1.1rem;
            border: 2px solid #FDBA74; /* Light Orange */
            border-radius: 12px;
            transition: all 0.2s ease;
        }
        .email-form input:focus {
            outline: none;
            border-color: #EA580C; /* Main Orange */
            box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.2);
        }

        .email-form .error {
            color: #D32F2F;
            font-weight: 600;
            text-align: left;
            width: 100%;
            max-width: 400px;
        }

        .action-button {
            background: linear-gradient(45deg, #F97316, #EA580C);
            color: white;
            border: none;
            padding: 15px 30px;
            width: 100%;
            max-width: 400px;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(234, 88, 12, 0.4);
            margin-top: 0.5rem;
        }
        .action-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(234, 88, 12, 0.6);
        }
        .action-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
    </style>
    <div class="photobooth-body">
        <div class="photobooth-container">
            <h1>Welcome to the Photobooth!</h1>
            <p class="subtitle">Enter your email to begin</p>
            <form wire:submit.prevent="saveEmail" class="email-form">
                <label for="email" class="sr-only">Email address</label>
                <input id="email" type="email" wire:model.lazy="email" placeholder="your.email@example.com" required aria-label="Email address">
                @error('email')
                    <div class="error" role="alert">{{ $message }}</div>
                @enderror
                <button type="submit"
                        wire:loading.attr="disabled" wire:target="saveEmail"
                        class="action-button">
                    <span wire:loading.remove wire:target="saveEmail">Next</span>
                    <span wire:loading wire:target="saveEmail">Processing…</span>
                </button>
            </form>
        </div>
    </div>
</div>
