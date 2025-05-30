<?php
// Visit.php: Display visit report including tests, prescriptions, and bills

// Include the database connection
$conn = require_once 'db_connect.php';

// Check if visit information is provided
if (!isset($_GET['doctor_id']) || !isset($_GET['patient_id']) || !isset($_GET['date'])) {
    header('Location: home.php');
    exit;
}

$doctor_id = $_GET['doctor_id'];
$patient_id = $_GET['patient_id'];
$date = $_GET['date'];

// Get visit details with patient and doctor information
$sql = "SELECT v.*, 
       ud.fullName as doctor_name, ud.email as doctor_email, ud.phone as doctor_phone,
       up.fullName as patient_name, up.email as patient_email, up.phone as patient_phone,
       d.specialty as doctor_specialty,
       p.birthDate, p.gender, p.insurencePolId
       FROM visit v
       JOIN user ud ON v.doctorId = ud.userId
       JOIN user up ON v.patientId = up.userId
       JOIN doctor d ON v.doctorId = d.userId
       JOIN patient p ON v.patientId = p.userId
       WHERE v.doctorId = ? AND v.patientId = ? AND v.date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $doctor_id, $patient_id, $date);
$stmt->execute();
$result = $stmt->get_result();
$visit = $result->fetch_assoc();
$stmt->close();

if (!$visit) {
    header('Location: home.php');
    exit;
}

// Get report details if it exists
$report = null;
if ($visit['reportId']) {
    $sql = "SELECT * FROM report WHERE reportId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $visit['reportId']);
    $stmt->execute();
    $result = $stmt->get_result();
    $report = $result->fetch_assoc();
    $stmt->close();
}

// Get any prescriptions for this visit
$prescriptions = [];
if ($visit['reportId']) {
    $sql = "SELECT p.*, m.medicationName 
           FROM prescription p 
           JOIN medications m ON p.medicationName = m.medicationName
           WHERE p.includedInReport = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $visit['reportId']);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $prescriptions[] = $row;
    }
    $stmt->close();
}

// Get any tests for this visit
$tests = [];
$sql = "SELECT t.* 
       FROM test t 
       WHERE t.doctorId = ? AND t.patientId = ? AND t.date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $doctor_id, $patient_id, $date);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $tests[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Visit Report</title>
    <style>
        .nav-links { margin-bottom: 20px; }
        .nav-links a { margin-right: 15px; }
        .visit-info { margin-bottom: 20px; }
        .visit-info p { margin: 5px 0; }
    </style>
</head>
<body>
    <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="doctorDetails.php?id=<?php echo $visit['doctorId']; ?>">Back</a>
    </div>
    <h1>Visit Report</h1>
    
    <h2>Visit Details</h2>
    <div class="visit-info">
        <p><strong>Status:</strong> <?php echo $visit['status']; ?></p>
        <p><strong>Date:</strong> <?php echo $visit['date']; ?></p>
    </div>
    
    <h2>Patient Information</h2>
    <div class="visit-info">
        <p><strong>Name:</strong> <?php echo $visit['patient_name']; ?></p>
        <p><strong>Email:</strong> <?php echo $visit['patient_email']; ?></p>
        <p><strong>Phone:</strong> <?php echo $visit['patient_phone']; ?></p>
        <p><strong>Birth Date:</strong> <?php echo $visit['birthDate']; ?></p>
        <p><strong>Gender:</strong> <?php echo $visit['gender']; ?></p>
        <p><strong>Insurance Policy ID:</strong> <?php echo $visit['insurencePolId']; ?></p>
    </div>
    
    <h2>Doctor Information</h2>
    <div class="visit-info">
        <p><strong>Name:</strong> <?php echo $visit['doctor_name']; ?></p>
        <p><strong>Specialty:</strong> <?php echo $visit['doctor_specialty']; ?></p>
        <p><strong>Email:</strong> <?php echo $visit['doctor_email']; ?></p>
        <p><strong>Phone:</strong> <?php echo $visit['doctor_phone']; ?></p>
    </div>
    
    <?php if ($report): ?>
    <h2>Medical Report</h2>
    <div class="visit-info">
        <p><strong>Report ID:</strong> <?php echo $report['reportId']; ?></p>
        <p><strong>Diagnosis:</strong> <?php echo $report['diagnosis']; ?></p>
        <p><strong>Billing:</strong> $<?php echo number_format($report['billing'], 2); ?></p>
    </div>
    <?php endif; ?>
    
    <?php if (count($tests) > 0): ?>
    <h2>Tests</h2>
    <div class="visit-info">
        <ul>
        <?php foreach ($tests as $test): ?>
            <li>
                <strong>Test ID:</strong> <?php echo $test['name']; ?> - 
                <strong>Result:</strong> <?php echo $test['result']; ?> - 
                <strong>Lab:</strong> <?php echo $test['labId']; ?>
            </li>
        <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <?php if (count($prescriptions) > 0): ?>
    <h2>Prescriptions</h2>
    <div class="visit-info">
        <ul>
        <?php foreach ($prescriptions as $prescription): ?>
            <li>
                <strong>Medication:</strong> <?php echo $prescription['medicationName']; ?> - 
                <strong>Dosage Instructions:</strong> <?php echo $prescription['dosageInstructions']; ?>
            </li>
        <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
</body>
</html>
