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
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .welcome-hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            /* Background Image with a semi-transparent overlay */
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://placehold.co/1920x1080/orange/white?text=Photobooth+Collage+Here&font=poppins');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

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
        <div class="welcome-container">
            <h1>Capture the Moment!</h1>
            <p class="subtitle">Ready to create some fun memories? Let's get started.</p>
            <a href="{{ route('photobooth.start') }}" class="action-button">
                Start Photobooth
            </a>
        </div>
    </div>

</body>
</html>
