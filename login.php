<?php 
header("Content-Type: application/json; charset=UTF-8");

// Database configuration
$servername = "localhost";
$username_db = "root";
$password_db = "";
$db_name = "flutter_login";

// Establish connection
$koneksi = new mysqli($servername, $username_db, $password_db, $db_name);

// Check connection
if ($koneksi->connect_error) {
    die(json_encode(["status" => "error", "message" => "Koneksi Gagal: " . $koneksi->connect_error]));
}

// Get input data
$post_data = json_decode(file_get_contents("php://input"), true);

// Validate input data
if (!$post_data || !isset($post_data['username']) || !isset($post_data['password'])) {
    echo json_encode(["status" => "error", "message" => "Invalid request data"]);
    exit();
}

$username = $post_data['username'];
$password = $post_data['password'];

// Prepare SQL statement
$stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Verify password
    if ($password == $row['password']) { // password_hash digunakan disini
        echo json_encode(["status" => "success", "message" => "Berhasil login!!!"]);
    } else{
        echo json_encode(["status" => "error", "message" => "Password salah!!!"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "User tidak ditemukan!!!"]);
}

// Close the connection
$stmt->close();
$koneksi->close();
?>
