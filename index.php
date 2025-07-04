<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALIVE Support System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            direction: rtl;
        }
        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            max-width: 600px;
        }
        h1 { color: #333; margin-bottom: 20px; }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 25px;
            margin: 10px;
            transition: transform 0.3s;
        }
        .button:hover { transform: translateY(-3px); }
        .status {
            background: #e8f5e8;
            color: #2d5a2d;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 ALIVE Support System</h1>
        
        <div class="status">
            ✅ המערכת פועלת בהצלחה!<br>
            📅 <?php echo date('d/m/Y H:i'); ?>
        </div>
        
        <h3>בחר מה תרצה לעשות:</h3>
        
        <a href="/chatbot/" class="button">
            🤖 בוט תמיכה חכם
        </a>
        
        <a href="/api/health" class="button">
            🔧 בדיקת מערכת
        </a>
        
        <a href="/api/stats" class="button">
            📊 סטטיסטיקות
        </a>
        
        <hr style="margin: 30px 0;">
        
        <p style="color: #666;">
            <strong>ALIVE Support System v1.0</strong><br>
            מערכת תמיכה מתקדמת עם בוט AI
        </p>
    </div>
</body>
</html>