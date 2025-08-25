<?php
session_start();

if (isset($_GET['p_id'])) {
    $p_id = $_GET['p_id'];
} else {
    die("Product ID is not provided");
}

$hostname = "localhost";
$username = "root";
$password = "";
$database = "dbms";

$mysqli = new mysqli($hostname, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];

    $start_datetime = date('Y-m-d H:i:s', strtotime($start_date));
    $end_datetime = date('Y-m-d H:i:s', strtotime($end_date));

    $stmt = $mysqli->prepare("INSERT INTO auction (start_date, end_date, p_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $start_datetime, $end_datetime, $p_id);

    if ($stmt->execute()) {
        $message = "Auction data saved successfully.";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
} 

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auction Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #349dff;
            color: #fff;
        }
        .logo {
            width: 100px; /* Adjust logo size */
            border-radius: 50%; /* Make the logo round */
            margin-left: 5%;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
        }
        input[type="datetime-local"],
        input[type="submit"] {
            padding: 10px 15px;
            margin-bottom: 10px;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .message {
            padding: 10px 20px;
            margin: 20px 0;
            border: 1px solid #4CAF50;
            color: #4CAF50;
            background-color: #e7f9e7;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <img src="bidbay.jpeg" alt="Logo" class="logo">
    </header>
    <div class="container">
        <h2>Auction Form</h2>
        <?php if ($message): ?>
            <div class="message">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form action="auction.php?p_id=<?php echo htmlspecialchars($p_id); ?>" method="POST">
            <label for="start_date">Start Date and Time:</label>
            <input type="datetime-local" id="start_date" name="start_date" required><br>
            
            <label for="end_datetime">End Date and Time:</label>
            <input type="datetime-local" id="end_date" name="end_date" required><br>
            
            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
