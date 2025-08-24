
<div>
    <style>
        /* Custom CSS for the Email Entry Screen with Pink Theme */
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

        h1, h2 {
            color: #333;
        }
        h1 {
            font-weight: 700;
            font-size: 2.5rem;
            color: #E91E63; /* Hot Pink */
        }
        h2 {
            font-weight: 400;
            font-size: 1.2rem;
            color: #AD1457; /* Darker Pink */
            margin-bottom: 2.5rem;
        }

        .email-form {
            margin-top: 2rem;
        }

        .email-form input {
            width: 100%;
            max-width: 400px;
            padding: 15px;
            font-size: 1.1rem;
            border: 2px solid #F48FB1;
            border-radius: 10px;
            margin-bottom: 1rem;
            transition: all 0.2s ease;
        }
        .email-form input:focus {
            outline: none;
            border-color: #E91E63;
            box-shadow: 0 0 0 3px rgba(233, 30, 99, 0.2);
        }

        .email-form .error {
            color: #D32F2F; /* A more standard error red */
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .action-button {
            background: linear-gradient(45deg, #EC407A, #D81B60);
            color: white;
            border: none;
            padding: 15px 30px;
            width: 100%;
            max-width: 400px;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.4);
        }
        .action-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(233, 30, 99, 0.6);
        }
    </style>
    <div class="photobooth-body min-h-screen flex items-center justify-center p-8 bg-gradient-to-br from-pink-200 to-pink-100">
        <div class="photobooth-container w-full max-w-xl mx-auto text-center rounded-2xl border border-white/20 bg-white/90 backdrop-blur shadow-2xl p-10 space-y-4">
            <h1 class="font-black text-3xl md:text-4xl text-rose-600">Welcome to the Photobooth!</h1>
            <p class="text-rose-800/80">Enter your email to begin</p>
            <form wire:submit.prevent="saveEmail" class="flex flex-col items-center gap-3 mt-2 md:mt-4">
                <label for="email" class="sr-only">Email address</label>
                <input id="email" type="email" wire:model.lazy="email" placeholder="your.email@example.com" required aria-label="Email address"
                       class="w-full max-w-md rounded-xl border-2 border-pink-300 px-4 py-3 text-lg text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-4 focus:ring-rose-200 focus:border-rose-500 transition">
                @error('email')
                    <div class="w-full max-w-md text-left text-red-600 font-semibold" role="alert">{{ $message }}</div>
                @enderror
                <button type="submit"
                        wire:loading.attr="disabled" wire:target="saveEmail"
                        class="mt-2 w-full max-w-md rounded-xl bg-gradient-to-r from-rose-500 to-pink-600 text-white font-semibold py-3 shadow-lg hover:shadow-xl transition-transform hover:-translate-y-0.5 disabled:opacity-70 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="saveEmail">Next</span>
                    <span wire:loading wire:target="saveEmail">Processing…</span>
                </button>
            </form>
        </div>
    </div>
</div>
