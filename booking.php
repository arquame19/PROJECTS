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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and collect form data
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $movie = $_POST["movie"];
    $time = $_POST["time"];
    $seats = isset($_POST["seats"]) ? $_POST["seats"] : [];

    // Check if required fields are filled
    if (empty($name) || empty($email) || empty($movie) || empty($time) || empty($seats)) {
        echo "<h2 style='color:red;'>All fields are required. Please go back and fill the form.</h2>";
        exit;
    }

    // Convert seats array to comma-separated string
    $seatString = implode(", ", $seats);

    // Insert data into database
    $stmt = $conn->prepare("INSERT INTO bookings (name, email, movie, time, seats) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $movie, $time, $seatString);

   if ($stmt->execute()) {
    echo "
    <div style='max-width: 600px; margin: 40px auto; padding: 25px; border-radius: 10px; background-color: #e8f5e9; box-shadow: 0 0 15px rgba(0,0,0,0.1); font-family: Arial, sans-serif;'>
        <h1 style='color: #2e7d32; text-align: center;'>🎉 Booking Confirmed!</h1>
        <hr style='border: 1px solidrgb(215, 200, 230); margin: 20px 0;'>
        <p style='font-size: 16px;'><strong>Name:</strong> $name</p>
        <p style='font-size: 16px;'><strong>Email:</strong> $email</p>
        <p style='font-size: 16px;'><strong>Movie:</strong> $movie</p>
        <p style='font-size: 16px;'><strong>Show Time:</strong> $time</p>
        <p style='font-size: 16px;'><strong>Seats:</strong> $seatString</p>
        <hr style='border: 1px solidrgb(215, 200, 230); margin: 20px 0;'>
        <p style='text-align: center; font-size: 16px; color: #388e3c;'>Thank you for booking with <strong>MovieCom</strong>! 🎬</p>
    </div>
    ";
} else {
    echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
}

$stmt->close();
$conn->close();
} else {
    echo "<h2>Access Denied</h2>";
}
?>
