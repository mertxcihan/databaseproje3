<?php
// Departments.php: List departments with links to department details

// Include the database connection
require_once 'db_connect.php';


// Helper functions
function getDoctorName($conn, $userId) {
    $sql = "SELECT fullName FROM user WHERE userId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['fullName'];
    }
    return 'Unknown Doctor';
}

function getPatientName($conn, $userId) {
    $sql = "SELECT fullName FROM user WHERE userId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['fullName'];
    }
    return 'Unknown Patient';
}

function getDepartmentName($conn, $departmentId) {
    $sql = "SELECT name FROM department WHERE departmentId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $departmentId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['name'];
    }
    return 'Unknown Department';
}

// Fetch all departments with manager names
$sql = "SELECT d.*, 
       doc.userId as medManagerId, 
       s.userId as adManagerId, 
       u1.fullName as medManagerName, 
       u2.fullName as adManagerName 
       FROM department d 
       LEFT JOIN doctor doc ON d.medManagerId = doc.userId 
       LEFT JOIN staff s ON d.adManagerId = s.userId 
       LEFT JOIN user u1 ON doc.userId = u1.userId 
       LEFT JOIN user u2 ON s.userId = u2.userId";
$result = mysqli_query($conn, $sql);
$departments = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Departments</title>
    <style>
        .nav-links { margin-bottom: 20px; }
        .nav-links a { margin-right: 15px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="home.php">Back</a>
    </div>
    <h1>Departments</h1>
    <table border="1">
        <tr>
            <th>Department Name</th>
            <th>Specialty</th>
            <th>Medical Manager</th>
            <th>Administrative Manager</th>
        </tr>
        <?php foreach ($departments as $dept): ?>
        <tr>
            <td><a href="departmentDetails.php?id=<?php echo $dept['departmentId']; ?>"><?php echo $dept['name']; ?></a></td>
            <td><?php echo $dept['specialty']; ?></td>
            <td><?php echo $dept['medManagerName']; ?></td>
            <td><?php echo $dept['adManagerName']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
