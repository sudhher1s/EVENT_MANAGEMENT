<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'faculty') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['event_id'])) {
    header("Location: faculty.php");
    exit();
}

$event_id = $_GET['event_id'];
$conn = new mysqli('localhost', 'root', '', 'event_management');

// Verify faculty owns the event
$check = $conn->query("SELECT id FROM events WHERE id = $event_id AND faculty_id = {$_SESSION['user_id']}");
if ($check->num_rows == 0) {
    header("Location: faculty.php");
    exit();
}

// Get event details
$event = $conn->query("SELECT * FROM events WHERE id = $event_id")->fetch_assoc();

// Get registrations for this event
$registrations = $conn->query("
    SELECT r.*, u.name as student_name, u.jntu_no
    FROM registrations r
    JOIN users u ON r.student_id = u.id
    WHERE r.event_id = $event_id
");

// Handle payment status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_payment'])) {
    $reg_id = $conn->real_escape_string($_POST['reg_id']);
    $conn->query("UPDATE registrations SET payment_status = 'paid' WHERE id = $reg_id");
    header("Location: event_registrations.php?event_id=$event_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registrations | EventHub</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background-color: #f8f9fa;
        }
        .header {
            background: linear-gradient(135deg, #6e48aa, #9d50bb);
            color: white;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            font-size: 24px;
        }
        .btn-back {
            background: white;
            color: #6e48aa;
            border: none;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .card h2 {
            color: #6e48aa;
            margin-bottom: 20px;
            font-size: 22px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            color: #555;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .btn {
            background: linear-gradient(135deg, #6e48aa, #9d50bb);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container header-content">
            <h1><?php echo $event['name']; ?> Registrations</h1>
            <a href="faculty.php" class="btn-back">Back to Dashboard</a>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <h2>Event Details</h2>
            <p><strong>Description:</strong> <?php echo $event['description']; ?></p>
            <p><strong>Fee:</strong> ₹<?php echo $event['fee']; ?></p>
        </div>

        <div class="card">
            <h2>Student Registrations</h2>
            <?php if($registrations->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>JNTU No</th>
                            <th>Status</th>
                            <th>Registered At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($reg = $registrations->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $reg['student_name']; ?></td>
                                <td><?php echo $reg['jntu_no']; ?></td>
                                <td><?php echo ucfirst($reg['payment_status']); ?></td>
                                <td><?php echo $reg['registered_at']; ?></td>
                                <td>
                                    <?php if($reg['payment_status'] == 'pending'): ?>
                                        <form method="POST" action="">
                                            <input type="hidden" name="reg_id" value="<?php echo $reg['id']; ?>">
                                            <button type="submit" name="update_payment" class="btn">Mark as Paid</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No students have registered for this event yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>