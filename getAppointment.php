<?php
// Include database connection
require_once 'db_connect.php';

// Initialize variables
$departments = [];
$doctors = [];
$message = '';
$selectedDept = '';
$selectedDoctor = '';
$selectedDate = '';
$selectedTime = '';

// Fetch all departments
$sql = "SELECT * FROM department";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
}

// If department is selected, fetch doctors from that department
if (isset($_POST['department']) && !empty($_POST['department'])) {
    $selectedDept = $_POST['department'];
    $sql = "SELECT d.userId, d.specialty, u.fullName, u.email, u.phone 
           FROM doctor d 
           JOIN user u ON d.userId = u.userId 
           WHERE d.workingIn = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $selectedDept);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }
    }
    $stmt->close();
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Validate and sanitize input
    $doctor_id = $_POST['doctor'];
    $appointment_date = $_POST['date'];
    $patient_name = $_POST['patient_name'];
    $patient_email = $_POST['patient_email'];
    $patient_phone = $_POST['patient_phone'];
    $patient_address = $_POST['patient_address'];
    $patient_gender = $_POST['patient_gender'];
    $patient_birthdate = $_POST['patient_birthdate'];
    $patient_insurance = $_POST['patient_insurance'];
    
    // Check if patient already exists with this email
    $sql = "SELECT userId FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $patient_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $existing_user = $result->fetch_assoc();
    $stmt->close();
    
    $patient_id = 0;
    
    // Begin transaction
    $conn->begin_transaction();
    
    try {
        if ($existing_user) {
            // Use existing user ID
            $patient_id = $existing_user['userId'];
        } else {
            // Insert new user
            // Get the next user ID (maximum + 1)
            $sql = "SELECT MAX(userId) as maxId FROM user";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $patient_id = $row['maxId'] + 1;
            
            // Create user record
            $sql = "INSERT INTO user (userId, email, password, fullName, phone, address, isPatient, isDoctor, isStaff) 
                   VALUES (?, ?, 'pass123', ?, ?, ?, 1, 0, 0)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issss", $patient_id, $patient_email, $patient_name, $patient_phone, $patient_address);
            $stmt->execute();
            $stmt->close();
            
            // Create patient record
            $sql = "INSERT INTO patient (userId, birthDate, gender, insurencePolId) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $patient_id, $patient_birthdate, $patient_gender, $patient_insurance);
            $stmt->execute();
            $stmt->close();
        }
        
        // Create visit record (appointment)
        $sql = "INSERT INTO visit (doctorId, patientId, date, status, reportId) VALUES (?, ?, ?, 'Scheduled', NULL)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $doctor_id, $patient_id, $appointment_date);
        
        if ($stmt->execute()) {
            $conn->commit();
            $message = "<div class='success'>Appointment booked successfully!</div>";
        } else {
            throw new Exception("Error booking appointment");
        }
        $stmt->close();
    } catch (Exception $e) {
        $conn->rollback();
        $message = "<div class='error'>Error booking appointment: " . $e->getMessage() . ". Please try again.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Get Appointment</title>
    <style>
        .form-group { margin-bottom: 15px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .nav-links { margin-bottom: 20px; }
        .nav-links a { margin-right: 15px; }
    </style>
</head>
<body>
    <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="patient.php">Back</a>
    </div>
    
    <h1>Book an Appointment</h1>
    
    <?php echo $message; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="department">Select Department:</label>
            <select name="department" id="department" onchange="this.form.submit()">
                <option value="">-- Select Department --</option>
                <?php foreach ($departments as $dept): ?>
                <option value="<?php echo $dept['departmentId']; ?>" <?php echo ($selectedDept == $dept['departmentId']) ? 'selected' : ''; ?>>
                    <?php echo $dept['name']; ?> (<?php echo $dept['specialty']; ?>)
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <?php if (!empty($doctors)): ?>
        <div class="form-group">
            <label for="doctor">Select Doctor:</label>
            <select name="doctor" id="doctor" required>
                <option value="">-- Select Doctor --</option>
                <?php foreach ($doctors as $doc): ?>
                <option value="<?php echo $doc['userId']; ?>">
                    <?php echo $doc['fullName']; ?> (<?php echo $doc['specialty']; ?>)
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="date">Select Date:</label>
            <input type="date" name="date" id="date" min="<?php echo date('Y-m-d'); ?>" required>
        </div>
        
        <div class="form-group">
            <h3>Patient Information</h3>
            <label for="patient_name">Full Name:</label>
            <input type="text" name="patient_name" id="patient_name" required><br><br>
            
            <label for="patient_email">Email:</label>
            <input type="email" name="patient_email" id="patient_email" required><br><br>
            
            <label for="patient_phone">Phone:</label>
            <input type="tel" name="patient_phone" id="patient_phone" required><br><br>
            
            <label for="patient_address">Address:</label>
            <input type="text" name="patient_address" id="patient_address" required><br><br>
            
            <label for="patient_birthdate">Birth Date:</label>
            <input type="date" name="patient_birthdate" id="patient_birthdate" required><br><br>
            
            <label for="patient_gender">Gender:</label>
            <select name="patient_gender" id="patient_gender" required>
                <option value="">-- Select Gender --</option>
                <option value="M">Male</option>
                <option value="F">Female</option>
                <option value="N">Non-Binary</option>
            </select><br><br>
            
            <label for="patient_insurance">Insurance Policy ID:</label>
            <input type="text" name="patient_insurance" id="patient_insurance" required>
        </div>
        
        <div class="form-group">
            <input type="submit" name="submit" value="Approve Appointment">
        </div>
        <?php endif; ?>
    </form>
    
    <script>
        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>
