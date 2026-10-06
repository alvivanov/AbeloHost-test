<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error {$statusCode}</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background: #f5f5f5;
            color: #333;
        }
        .error {
            text-align: center;
        }
        .error h1 {
            font-size: 4rem;
            margin: 0;
        }
        .error p {
            font-size: 1.2rem;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="error">
        <h1>{$statusCode}</h1>
        <p>{$message}</p>
    </div>
</body>
</html>
