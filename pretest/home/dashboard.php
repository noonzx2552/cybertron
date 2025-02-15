<?php
session_start();
// ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// เชื่อมต่อฐานข้อมูล
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "user_db";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงค่า pretest_score, posttest_score, และสถานะของแต่ละบท
$sql = "SELECT pretest_score, posttest_score,
               Unit1, Unit2, Unit3, Unit4, Unit5, Unit6,
               end_date, start_date
        FROM users WHERE username = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/home.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* พื้นหลังแบบ Gradient ที่มีการเคลื่อนไหว */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(270deg, #6a11cb, #2575fc, #6a11cb, #2575fc);
            background-size: 400% 400%;
            animation: gradientBG 10s ease infinite;
            color: #fff;
            overflow: hidden;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .dashboard-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        h1 {
            color: #fff;
            font-size: 2.5em;
            margin-bottom: 10px;
            animation: fadeIn 2s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .date-info {
            font-size: 1.2em;
            color: #e0e0e0;
        }

        .date-info strong {
            color: #ffcc00;
        }

        .content {
            display: flex;
            justify-content: space-around;
            width: 100%;
            max-width: 1200px;
        }

        .chart-container {
            width: 45%;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .chart-container:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        canvas {
            max-width: 100%;
            height: auto !important;
        }
        .centertext {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            width: 100%; /* ทำให้กว้างเต็ม container */
        }

    </style>
</head>
<body>
    <header class="navbar">
        <div class="nav-left"><?php echo htmlspecialchars($username); ?></div>
        <nav class="nav-right">
        <a href="home.php">Home</a>
            <a href="setting.html">Setting</a>
            <a onclick="location.href='/animated-login-form/logout.php'">Logout</a>
            </nav>
    </header>
    <div class="dashboard-container">
        <!-- Header: Dashboard for [username] -->
        <div class="header">
            <h1>Dashboard for <?php echo htmlspecialchars($username); ?></h1>
            <div class="date-info">

            </div>
        </div>

        <!-- Content: กราฟและข้อมูล -->
        <div class="content">
            <!-- กราฟเปรียบเทียบคะแนน Pretest และ Posttest -->
            <div class="chart-container">
                <div class="centertext">
                    <p><strong>Start Date:</strong> <?php echo htmlspecialchars($row['start_date']); ?></p>
                    <p><strong>End Date:</strong> <?php echo htmlspecialchars($row['end_date']); ?></p>
                </div>
            <canvas id="lineChart"></canvas>
            </div>

            <!-- กราฟใยแมงมุมแสดงความสามารถ -->
            <div class="chart-container">
                <canvas id="radarChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // ข้อมูลจาก PHP
        const pretestScore = <?php echo $row['pretest_score']; ?>;
        const posttestScore = <?php echo $row['posttest_score']; ?>;
        const unitScores = [
            <?php echo $row['Unit1']; ?>,
            <?php echo $row['Unit2']; ?>,
            <?php echo $row['Unit3']; ?>,
            <?php echo $row['Unit4']; ?>,
            <?php echo $row['Unit5']; ?>,
            <?php echo $row['Unit6']; ?>
        ];

        // กราฟเส้น (Line Chart)
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: ['Pretest', 'Posttest'],
                datasets: [{
                    label: 'Score Comparison',
                    data: [pretestScore, posttestScore],
                    borderColor: 'rgba(255, 255, 255, 1)',
                    backgroundColor: 'rgba(255, 255, 255, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: {
                            color: '#fff'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: {
                            color: '#fff'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#fff'
                        }
                    }
                }
            }
        });

        // กราฟใยแมงมุม (Radar Chart)
        const radarCtx = document.getElementById('radarChart').getContext('2d');
        new Chart(radarCtx, {
            type: 'radar',
            data: {
                labels: ['Unit 1', 'Unit 2', 'Unit 3', 'Unit 4', 'Unit 5', 'Unit 6'],
                datasets: [{
                    label: 'Unit Scores',
                    data: unitScores,
                    backgroundColor: 'rgba(255, 255, 255, 0.2)',
                    borderColor: 'rgba(255, 255, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    r: {
                        angleLines: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        pointLabels: {
                            color: '#fff'
                        },
                        ticks: {
                            color: '#fff',
                            backdropColor: 'transparent'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#fff'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>