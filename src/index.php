<?php
session_start();
if (isset($_POST['accept_terms'])) {
    $_SESSION['accepted_terms'] = true;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
if (!isset($_SESSION['accepted_terms'])) {
    // Show the usage agreement form first
    echo '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Accept Terms</title>
        <style>
            body { font-family: sans-serif; padding: 40px; background: #f9f9f9; }
            form { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
            input[type="submit"] { padding: 10px 20px; font-size: 16px; }
        </style>
    </head>
    <body>
        <form method="post" action="">
            <h2>Usage Agreement</h2>
            <p>By continuing, you agree to use this demo tool responsibly. Data may be temporarily processed or stored. Do not use this tool in public spaces or without the knowledge of anyone it may record. Do not submit sensitive or personal information.</p>
            <input type="submit" name="accept_terms" value="I Agree">
        </form>
    </body>
    </html>';
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Azure STT Demo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Welcome to the Azure STT Demo</h1>
    <!-- Your existing layout goes here -->
</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Azure STT Demo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Speech to Text</h1>

    <div class="button-group">
        <button id="startBtn">Start Recording</button>
        <button id="stopBtn">Stop</button>
        <button id="getSummary">Download Summary</button>
        <button id="getFullNotes">Download Full Transcript</button>
    </div>

    <h2>Notes:</h2>
    <main id="transcript">
        <!-- Notes will populate here -->
        
    </main>

    <script src="script.js"></script>
</body>
</html>