<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Fetch submissions
$stmt = $pdo->query("SELECT * FROM submissions");
$submissions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h2>Form Submissions</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Message</th>
                <th>File</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($submissions as $submission): ?>
                <tr>
                    <td><?php echo htmlspecialchars($submission['name']); ?></td>
                    <td><?php echo htmlspecialchars($submission['email']); ?></td>
                    <td><?php echo htmlspecialchars($submission['mobile']); ?></td>
                    <td><?php echo htmlspecialchars($submission['message']); ?></td>
                    <td><img src="<?php echo $submission['file_path']; ?>" alt="Uploaded Image" width="100"></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
