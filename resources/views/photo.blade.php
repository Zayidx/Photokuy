<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Photo</title>
    <!-- Google Fonts for a clean, modern look -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Pacifico&display=swap" rel="stylesheet">
    <style>
        /* Custom CSS for the Photo View Screen with Pink Theme */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #FFC0CB, #F8BBD0); /* Soft pink gradient background */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 2rem;
        }

        .container {
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

        h1 {
            font-family: 'Pacifico', cursive;
            font-weight: 700;
            font-size: 2.8rem;
            color: #E91E63; /* Hot Pink */
            margin-bottom: 2rem;
        }

        img {
            max-width: 100%;
            max-height: 70vh;
            border: 5px solid #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .action-button {
            display: inline-block;
            text-decoration: none;
            background: linear-gradient(45deg, #EC407A, #D81B60);
            color: white;
            border: none;
            padding: 15px 30px;
            margin-top: 2rem;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Photo</h1>
        <img src="{{ $photoUrl }}" alt="Your captured photo">
        <br>
        <a href="{{ $photoUrl }}" download class="action-button">Download Photo</a>
    </div>
</body>
</html>
