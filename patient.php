<?php
// Patient.php: Display patient medical history

// Include the database connection
require_once 'db_connect.php';

// Get patient ID from GET or POST (if provided)
$patient_id = isset($_GET['id']) ? $_GET['id'] : '';
$patient_found = false;
$visits = [];

// Get patient visits if patient ID is provided
if (!empty($patient_id)) {
    // Get patient details
    $sql = "SELECT p.*, u.fullName, u.email, u.phone, u.address 
           FROM patient p
           JOIN user u ON p.userId = u.userId
           WHERE p.userId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();
    $stmt->close();
    
    if ($patient) {
        $patient_found = true;
        
        // Get patient visits
        $sql = "SELECT v.*, u.fullName as doctor_name, r.diagnosis, r.billing
               FROM visit v
               JOIN user u ON v.doctorId = u.userId
               LEFT JOIN report r ON v.reportId = r.reportId
               WHERE v.patientId = ?
               ORDER BY v.date DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $visits[] = $row;
        }
        $stmt->close();
        
        // Get patient allergies
        $sql = "SELECT a.allergyName
               FROM patient_has_allergies pha
               JOIN allergies a ON pha.allId = a.allId
               WHERE pha.userId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $allergies = [];
        while ($row = $result->fetch_assoc()) {
            $allergies[] = $row['allergyName'];
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Patient History</title>
    <style>
        .nav-links { margin-bottom: 20px; }
        .nav-links a { margin-right: 15px; }
        .search-form { margin-bottom: 20px; }
        .patient-info { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="getAppointment.php">Get Appointment</a>
    </div>
    
    <h1>Patient Medical History</h1>
    
    <!-- Patient Search Form -->
    <div class="search-form">
        <form method="GET" action="">
            <label for="id">Enter Patient ID:</label>
            <input type="number" name="id" id="id" value="<?php echo $patient_id; ?>" required>
            <input type="submit" value="Search">
        </form>
    </div>
    
    <?php if (!empty($patient_id) && !$patient_found): ?>
    <p>No patient found with ID <?php echo $patient_id; ?>.</p>
    <?php endif; ?>
    
    <?php if ($patient_found): ?>
    <div class="patient-info">
        <h2>Patient Information</h2>
        <p><strong>Name:</strong> <?php echo $patient['fullName']; ?></p>
        <p><strong>Email:</strong> <?php echo $patient['email']; ?></p>
        <p><strong>Phone:</strong> <?php echo $patient['phone']; ?></p>
        <p><strong>Address:</strong> <?php echo $patient['address']; ?></p>
        <p><strong>Birth Date:</strong> <?php echo $patient['birthDate']; ?></p>
        <p><strong>Gender:</strong> <?php echo $patient['gender']; ?></p>
        <p><strong>Insurance Policy ID:</strong> <?php echo $patient['insurencePolId']; ?></p>
        
        <?php if (!empty($allergies)): ?>
        <h3>Allergies</h3>
        <ul>
            <?php foreach ($allergies as $allergy): ?>
            <li><?php echo $allergy; ?></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>
    
    <h2>Medical Visit History</h2>
    <?php if (count($visits) > 0): ?>
    <ul>
        <?php foreach ($visits as $visit): ?>
        <li>
            <?php echo $visit['date']; ?> - 
            Doctor: <?php echo $visit['doctor_name']; ?>
            <?php if ($visit['status']): ?> (Status: <?php echo $visit['status']; ?>)<?php endif; ?>
            <?php if ($visit['diagnosis']): ?> - Diagnosis: <?php echo $visit['diagnosis']; ?><?php endif; ?>
            <?php if ($visit['billing']): ?> - Bill: $<?php echo $visit['billing']; ?><?php endif; ?> - 
            <a href="visit.php?doctor_id=<?php echo $visit['doctorId']; ?>&patient_id=<?php echo $visit['patientId']; ?>&date=<?php echo $visit['date']; ?>">View Details</a>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php else: ?>
    <p>No visit history found.</p>
    <?php endif; ?>
    <?php endif; ?>
</body>
</html>
