<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8fafc; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { text-align: center; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 20px; }
        .title { color: #0f172a; font-size: 24px; font-weight: bold; }
        .content { color: #475569; font-size: 16px; line-height: 1.6; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #0d9488; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 20px; }
        .footer { margin-top: 30px; text-align: center; color: #94a3b8; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #0d9488; margin:0;">CODE VERSE</h1>
        </div>
        <div class="content">
            <p class="title">{{ $title }}</p>
            <p>{{ $messageBody }}</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/') }}" class="btn">Kunjungi Code Verse</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Code Verse Academy.<br>Teruslah belajar dan berkarya!
        </div>
    </div>
</body>
</html>