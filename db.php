// Include the database connection file
include 'db.php';

// Use the PDO object to query the database
$stmt = $pdo->query("SELECT * FROM submissions");

// Fetch and display the data
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Name: " . $row['name'] . "<br>";
    echo "Email: " . $row['email'] . "<br>";
    echo "Message: " . $row['message'] . "<br>";
}
