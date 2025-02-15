echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Congratulations!</title>
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background-color: #f0f0f0;
                font-family: Arial, sans-serif;
                margin: 0;
                overflow: hidden;
            }
            .container {
                text-align: center;
            }
            .congratulations {
                font-size: 2em;
                color: #28a745;
                animation: celebrate 2s ease-in-out;
            }
            @keyframes celebrate {
                0% { transform: scale(1); }
                50% { transform: scale(1.2); }
                100% { transform: scale(1); }
            }
            .loading-bar {
                width: 300px;
                height: 20px;
                background-color: #e0e0e0;
                border-radius: 10px;
                margin-top: 20px;
                overflow: hidden;
                position: relative;
            }
            .loading-bar::before {
                content: \'\';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, #3498db, transparent);
                animation: loading 2s linear infinite;
            }
            @keyframes loading {
                0% { left: -100%; }
                100% { left: 100%; }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <!-- GIF จาก Tenor -->
            <div class="tenor-gif-embed" data-postid="27027530" data-share-method="host" data-aspect-ratio="1.02564" data-width="100%">
                <a href="https://tenor.com/view/very-good-gif-27027530">Very Good Sticker</a> from 
                <a href="https://tenor.com/search/very+good-stickers">Very Good Stickers</a>
            </div>
            <script type="text/javascript" async src="https://tenor.com/embed.js"></script>

            <!-- ข้อความ Congratulations -->
            <div class="congratulations">Congratulations!</div>

            <!-- Loading Bar -->
            <div class="loading-bar"></div>
        </div>

        <script>
            // Redirect หลังจาก 5 วินาที
            setTimeout(function() {
                window.location.href = "../scoreboard.php";
            }, 5000); // 5 วินาที
        </script>
    </body>
    </html>
    ';
    ?>