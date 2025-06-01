<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'event_management');

// Get all events with faculty names
$events = $conn->query("
    SELECT e.*, u.name as faculty_name
    FROM events e
    JOIN users u ON e.faculty_id = u.id
");

// Get all registrations with student and event details
$registrations = $conn->query("
    SELECT r.*, s.name as student_name, s.jntu_no, e.name as event_name, f.name as faculty_name
    FROM registrations r
    JOIN users s ON r.student_id = s.id
    JOIN events e ON r.event_id = e.id
    JOIN users f ON e.faculty_id = f.id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | EventHub</title>
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
        .btn-logout {
            background: white;
            color: #6e48aa;
            border: none;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
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
    </style>
</head>
<body>
    <div class="header">
        <div class="container header-content">
            <h1>Welcome, Admin <?php echo $_SESSION['name']; ?></h1>
            <form action="logout.php" method="post">
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <h2>All Events</h2>
            <?php if($events->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Description</th>
                            <th>Fee</th>
                            <th>Faculty Coordinator</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($event = $events->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $event['name']; ?></td>
                                <td><?php echo $event['description']; ?></td>
                                <td>₹<?php echo $event['fee']; ?></td>
                                <td><?php echo $event['faculty_name']; ?></td>
                                <td><?php echo $event['created_at']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No events found.</p>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2>All Registrations</h2>
            <?php if($registrations->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>JNTU No</th>
                            <th>Event Name</th>
                            <th>Faculty</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($reg = $registrations->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $reg['student_name']; ?></td>
                                <td><?php echo $reg['jntu_no']; ?></td>
                                <td><?php echo $reg['event_name']; ?></td>
                                <td><?php echo $reg['faculty_name']; ?></td>
                                <td><?php echo ucfirst($reg['payment_status']); ?></td>
                                <td><?php echo $reg['registered_at']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No registrations found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>