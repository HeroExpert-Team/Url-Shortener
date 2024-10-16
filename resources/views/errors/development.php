<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exception Caught</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #34495e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #e74c3c;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .message {
            background-color: #e74c3c;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 1.2rem;
            word-wrap: break-word;
        }

        .info {
            margin-bottom: 20px;
        }

        .info strong {
            color: #1abc9c;
        }

        pre {
            background-color: #2c3e50;
            padding: 15px;
            border: 1px solid #7f8c8d;
            border-radius: 5px;
            overflow-x: auto;
        }

        .trace {
            margin-top: 30px;
        }

        .trace h2 {
            color: #3498db;
            font-size: 1.5rem;
        }

        .trace pre {
            background-color: #34495e;
            color: #bdc3c7;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.9rem;
            color: #95a5a6;
            text-align: center;
        }

    </style>
</head>
<body>

    <div class="container">
        <h1>Oops! An Exception Occurred</h1>
        <div class="message">
            <strong>Message:</strong> <?php echo htmlspecialchars($data->getMessage()); ?>
        </div>

        <div class="info">
            <p><strong>File:</strong> <?php echo htmlspecialchars($data->getFile()); ?></p>
            <p><strong>Line:</strong> <?php echo htmlspecialchars($data->getLine()); ?></p>
            <p><strong>Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
        </div>

        <div class="trace">
            <h2>Stack Trace</h2>
            <pre><?php echo htmlspecialchars($data->getTraceAsString()); ?></pre>
        </div>

        <div class="footer">
            <p>Generated by your custom error handler</p>
        </div>
    </div>

</body>
</html>