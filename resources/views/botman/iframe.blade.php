<!DOCTYPE html>
<html>
<head>
    <title>RedWave Chatbot</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }
        .chat-container {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .chat-header {
            background: #B31312;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .chat-body {
            height: 400px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <h3>🩸 RedWave Assistant</h3>
            <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">
                Siap membantu informasi donor darah
            </p>
        </div>
        <div class="chat-body">
            <p>Ketik "halo" untuk memulai percakapan!</p>
        </div>
    </div>

    <script>
        var botmanWidget = {
            chatServer: '/botman',
            title: '🩸 RedWave Assistant',
            introMessage: 'Halo! Saya asisten RedWave. Ketik "help" untuk melihat menu bantuan! 😊',
            placeholderText: 'Ketik pesan Anda...',
            mainColor: '#B31312',
            bubbleBackground: '#B31312',
            headerTextColor: '#ffffff',
            bubbleAvatarUrl: '',
            desktopHeight: 450,
            desktopWidth: 370,
        };
    </script>
    <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
</body>
</html>
