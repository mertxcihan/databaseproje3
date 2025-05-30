<?php
// DepartmentDetails.php: List doctors and labs with links to their details

// Include the database connection
require_once 'db_connect.php';

// Check if department ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: departments.php');
    exit;
}

$dept_id = $_GET['id'];

// Get department details
$sql = "SELECT d.*, u1.fullName as medManagerName, u2.fullName as adManagerName 
       FROM department d 
       JOIN user u1 ON d.medManagerId = u1.userId 
       JOIN user u2 ON d.adManagerId = u2.userId 
       WHERE d.departmentId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $dept_id);
$stmt->execute();
$result = $stmt->get_result();
$department = $result->fetch_assoc();
$stmt->close();

if (!$department) {
    header('Location: departments.php');
    exit;
}

// Get doctors in this department
$sql = "SELECT d.userId, d.specialty, u.fullName 
       FROM doctor d 
       JOIN user u ON d.userId = u.userId 
       WHERE d.workingIn = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $dept_id);
$stmt->execute();
$result = $stmt->get_result();
$doctors = [];
while ($row = $result->fetch_assoc()) {
    $doctors[] = $row;
}
$stmt->close();

// Get labs in this department
$sql = "SELECT * FROM laboratory WHERE connectedDept = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $dept_id);
$stmt->execute();
$result = $stmt->get_result();
$labs = [];
while ($row = $result->fetch_assoc()) {
    $labs[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Department Details - <?php echo $department['name']; ?></title>
    <style>
        .nav-links { margin-bottom: 20px; }
        .nav-links a { margin-right: 15px; }
    </style>
</head>
<body>
    <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="departments.php">Back</a>
    </div>
    <h1>Department Details - <?php echo $department['name']; ?></h1>
    <h2>Doctors</h2>
    <?php if (count($doctors) > 0): ?>
    <ul>
        <?php foreach ($doctors as $doctor): ?>
        <li><a href="doctorDetails.php?id=<?php echo $doctor['userId']; ?>">
            <?php echo $doctor['fullName']; ?> (<?php echo $doctor['specialty']; ?>)
        </a></li>
        <?php endforeach; ?>
    </ul>
    <?php else: ?>
    <p>No doctors found in this department.</p>
    <?php endif; ?>
    
    <h2>Labs</h2>
    <?php if (count($labs) > 0): ?>
    <ul>
        <?php foreach ($labs as $lab): ?>
        <li><a href="labDetails.php?id=<?php echo $lab['LabId']; ?>">
            <?php echo $lab['name']; ?>
        </a></li>
        <?php endforeach; ?>
    </ul>
    <?php else: ?>
    <p>No labs found in this department.</p>
    <?php endif; ?>
</body>
</html>
