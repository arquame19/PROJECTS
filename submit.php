<?php


$host = "localhost";
$user = "root";
$password = "";
$dbname = "arquame";

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Only process when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // removing extra spaces
    $name = trim($_POST['name'] ?? '');

    $message = trim($_POST['message'] ?? '');

    $errors = [];

    // error checking
    if (empty($name) || empty($message)) {
        $errors[] = "All fields are required.";
    }


    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
        echo "<a href='contact_form.html'>Go back</a>";
        exit();
    }

    // Inserting data
    $stmt = $conn->prepare("INSERT INTO Message (name, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $message);

    if ($stmt->execute()) {
        echo "
    <div style='
        max-width: 500px;
        margin: 40px auto;
        padding: 25px;
        border-radius: 12px;
        background-color: #e8f5e9;
        box-shadow: 0 12px 12px rgba(0, 128, 0, 0.2);
        text-align: center;
        font-family: Segoe UI, sans-serif;
        animation: popin 0.5s ease-out;
    '>
        <h2 style='color: #2e7d32; font-size: 24px;'>✅ Submitted</h2>
        <p style='color: #388e3c; font-size: 18px;'>Thank you for helping!</p>
    </div>

  
    ";
    } else {
        echo "Error: " . $stmt->error;
    }


    $stmt->close();
    $conn->close();
} else {
    echo "Please submit the form correctly.";
}
?>