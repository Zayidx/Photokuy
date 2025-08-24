<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Photobooth Photo Strip</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif; color:#111827; }
        .btn { display:inline-block; padding:12px 18px; background:#2563eb; color:#ffffff; text-decoration:none; border-radius:8px; font-weight:600; }
        .wrap { max-width: 640px; margin: 0 auto; }
        .img { max-width: 100%; border-radius: 12px; }
    </style>
    </head>
<body>
    <div class="wrap">
        <h1>Here's Your Photo Strip!</h1>
        <p>Thanks for using our photobooth. Click the image or the button below to see and download your photo.</p>
        <p>
            <a href="{{ $downloadUrl }}">
                <img class="img" src="{{ $photoUrl }}" alt="Your Photo Strip">
            </a>
        </p>
        <p>
            <a class="btn" href="{{ $downloadUrl }}">View & Download</a>
        </p>
        <p style="color:#6b7280">If you didn't request this, you can ignore this email.</p>
    </div>
</body>
</html>

