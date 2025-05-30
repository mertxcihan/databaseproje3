<?php
require_once 'db_connect.php';

// ID kontrolü
if (!isset($_GET['id'])) {
    die("Hatalı istek: Laboratuvar ID'si eksik.");
}

$labId = intval($_GET['id']);

// Laboratuvar bilgilerini çek
$sql = "SELECT l.*, 
            u1.fullName AS doctorName, 
            u2.fullName AS staffName,
            d.name AS departmentName
        FROM laboratory l
        LEFT JOIN doctor doc ON l.respDoctorID = doc.userId
        LEFT JOIN user u1 ON doc.userId = u1.userId
        LEFT JOIN staff s ON l.respSID = s.userId
        LEFT JOIN user u2 ON s.userId = u2.userId
        LEFT JOIN department d ON l.connectedDept = d.departmentId
        WHERE l.LabId = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $labId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Bu ID ile eşleşen laboratuvar bulunamadı.");
}

$lab = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lab Details</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .nav-links a { margin-right: 15px; }
    </style>
</head>
<body>
    <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="departments.php">Departments</a>
    </div>

    <h1>Laboratuvar Detayı: <?php echo htmlspecialchars($lab['name']); ?></h1>

    <ul>
        <li><strong>Bağlı Departman:</strong> <?php echo htmlspecialchars($lab['departmentName']); ?></li>
        <li><strong>Sorumlu Doktor:</strong> <?php echo htmlspecialchars($lab['doctorName']); ?></li>
        <li><strong>Sorumlu Personel:</strong> <?php echo htmlspecialchars($lab['staffName']); ?></li>
    </ul>

</body>
</html>
