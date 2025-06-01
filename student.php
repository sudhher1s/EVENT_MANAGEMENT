<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'event_management');

// Get all events
$events = $conn->query("SELECT * FROM events");

// Get student's registrations
$registrations = $conn->query("
    SELECT r.*, e.name as event_name, e.fee 
    FROM registrations r
    JOIN events e ON r.event_id = e.id
    WHERE r.student_id = {$_SESSION['user_id']}
");

// Handle registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $event_id = $conn->real_escape_string($_POST['event_id']);
    
    // Check if already registered
    $check = $conn->query("SELECT id FROM registrations WHERE student_id = {$_SESSION['user_id']} AND event_id = $event_id");
    
    if ($check->num_rows == 0) {
        $conn->query("INSERT INTO registrations (student_id, event_id) VALUES ({$_SESSION['user_id']}, $event_id)");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | EventHub</title>
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
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        .form-group select, .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn {
            background: linear-gradient(135deg, #6e48aa, #9d50bb);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
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
            <h1>Welcome, <?php echo $_SESSION['name']; ?></h1>
            <form action="logout.php" method="post">
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <h2>Register for Events</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="event_id">Select Event</label>
                    <select id="event_id" name="event_id" required>
                        <option value="">-- Select Event --</option>
                        <?php while($event = $events->fetch_assoc()): ?>
                            <option value="<?php echo $event['id']; ?>" data-fee="<?php echo $event['fee']; ?>">
                                <?php echo $event['name']; ?> (₹<?php echo $event['fee']; ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fee">Fee</label>
                    <input type="text" id="fee" name="fee" readonly>
                </div>
                <button type="submit" name="register" class="btn">Register</button>
            </form>
        </div>

        <div class="card">
            <h2>Your Registrations</h2>
            <?php if($registrations->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Fee</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($reg = $registrations->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $reg['event_name']; ?></td>
                                <td>₹<?php echo $reg['fee']; ?></td>
                                <td><?php echo ucfirst($reg['payment_status']); ?></td>
                                <td><?php echo $reg['registered_at']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You haven't registered for any events yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.getElementById('event_id').addEventListener('change', function() {
            var fee = this.options[this.selectedIndex].getAttribute('data-fee');
            document.getElementById('fee').value = '₹' + fee;
        });
    </script>
</body>
</html>