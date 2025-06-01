<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'faculty') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'event_management');

// Get faculty's events
$events = $conn->query("
    SELECT e.*, COUNT(r.id) as registrations_count
    FROM events e
    LEFT JOIN registrations r ON e.id = r.event_id
    WHERE e.faculty_id = {$_SESSION['user_id']}
    GROUP BY e.id
");

// Handle payment status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_payment'])) {
    $reg_id = $conn->real_escape_string($_POST['reg_id']);
    $conn->query("UPDATE registrations SET payment_status = 'paid' WHERE id = $reg_id");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard | EventHub</title>
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
        .btn-small {
            padding: 5px 10px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container header-content">
            <h1>Welcome, <?php echo $_SESSION['name']; ?></h1>
            <form action="login.php" method="post">
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <h2>Your Events</h2>
            <?php if($events->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Description</th>
                            <th>Fee</th>
                            <th>Registrations</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($event = $events->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $event['name']; ?></td>
                                <td><?php echo $event['description']; ?></td>
                                <td>₹<?php echo $event['fee']; ?></td>
                                <td><?php echo $event['registrations_count']; ?></td>
                                <td>
                                    <a href="event_registrations.php?event_id=<?php echo $event['id']; ?>" class="btn btn-small">View</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You haven't created any events yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>