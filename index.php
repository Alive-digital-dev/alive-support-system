<?php
// ALIVE Support System - Home Page
require_once 'backend/config.php';
?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SYSTEM_NAME; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        
        .container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 600px;
        }
        
        h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .subtitle {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .feature {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }
        
        .feature:hover {
            transform: translateY(-5px);
        }
        
        .btn {
            display: inline-block;
            background: #4CAF50;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            margin: 10px;
            transition: all 0.3s ease;
            font-weight: bold;
        }
        
        .btn:hover {
            background: #45a049;
            transform: scale(1.05);
        }
        
        .status {
            margin-top: 30px;
            padding: 15px;
            background: rgba(76, 175, 80, 0.2);
            border-radius: 10px;
            border: 1px solid rgba(76, 175, 80, 0.5);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 <?php echo SYSTEM_NAME; ?></h1>
        <p class="subtitle">מערכת תמיכה מתקדמת עם AI</p>
        
        <div class="features">
            <div class="feature">
                <h3>🤖 בוט AI</h3>
                <p>תמיכה אוטומטית חכמה</p>
            </div>
            <div class="feature">
                <h3>💬 צ'אט לייב</h3>
                <p>שיחה עם נציגים</p>
            </div>
            <div class="feature">
                <h3>📊 ניהול</h3>
                <p>ממשק ניהול מתקדם</p>
            </div>
        </div>
        
        <div>
            <a href="/chatbot/" class="btn">🤖 בוט תמיכה</a>
            <a href="/portal/" class="btn">💬 פורטל לקוחות</a>
            <a href="/admin/" class="btn">🔧 ניהול</a>
        </div>
        
        <div class="status">
            <h3>✅ מערכת פעילה</h3>
            <p>כל המודולים עובדים בצורה תקינה</p>
            <small>גרסה: <?php echo SYSTEM_VERSION; ?> | עודכן: <?php echo date('d/m/Y H:i'); ?></small>
        </div>
    </div>
</body>
</html>
