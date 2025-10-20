<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My PHP Page</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
        }
        .container {
            text-align: center;
            padding: 2rem;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        p {
            color: #555;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>
            <?php
                // This is a simple PHP echo statement.
                echo "Hello, World!";
            ?>
        </h1>
        <p>
            <?php
                // This line displays the current date and time.
                echo "The current date and time is: " . date("Y-m-d H:i:s");
            ?>
        </p>
    </div>

</body>
</html>
