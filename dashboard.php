<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['heading'])) {
        $_SESSION['dashboard_heading'] = $_POST['heading'];
       $heading = $_SESSION['dashboard_heading'];
    }
    if(isset($_SESSION['dashboard_heading']) &&  $_SESSION['dashboard_heading'] != ''){
       $heading = $_SESSION['dashboard_heading'];
    }else{
        $heading="Jay's Dashboard";
    }
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Dummy authentication
    if ($email === 'user@example.com' && $password === 'password') {
        $_SESSION['loggedin'] = true;
    } else {
        header('Location: index.html?error=Incorrect email or password. Please try again.');
        exit();
    }
} elseif (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: index.html');
    exit();
}
if (isset($_SESSION['dashboard_heading'])) {
 $heading = $_SESSION['dashboard_heading'];}else{
 $heading="Jay's Dashboard";
}
$dateString = date("l, F j");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overview</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <style type="text/css">
        body {
            font-family: Poppins, sans-serif;
            margin: 0;
            background-color: #f5f5f5;
            overflow-x: hidden !important;
        }

        .container-fluid {
            padding: 0;
        }

        .sidebar {
            background-color: #2c2c2c;
            color: #FAFAFA;
            height: 100vh;
            padding: 20px;
        }

        .logo h2 {
            margin: 0;
            text-align: center;
            font-size: 24px;
            margin-bottom: 30px;
        }

        .nav .nav-link {
            color: #FAFAFA;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .nav .nav-link:hover {
            color: #ccc;
        }

        .main-content {
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
        }

        .header-info {
            display: flex;
            align-items: center;
        }

        .date {
            margin-right: 20px;
            font-size: 18px;
        }

        .profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .overview .widget {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 10px 0;
        }
        .overview_data .widget{
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 10px -15px;
        }

        .widget-dark {
            background-color: #333 !important;
            color: #FAFAFA;
        }

        .widget h3 {
            margin: 0 0 10px;
            font-size: 18px;
        }

        .widget p {
            margin: 0 0 5px;
            font-size: 24px;
            font-weight: bold;
        }

        .status {
            display: block;
            margin-top: 10px;
            font-size: 14px;
        }

        .status.green {
            color: green;
        }

        .status.red {
            color: red;
        }

        .chart-section {
            margin-bottom: 40px;
        }

        .feedbacks .widget {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 10px 0;
        }

        .btn-download {
            background-color: #4CAF50;
            color: #FAFAFA;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
            font-size: 16px;
        }
        .nav-link {
            opacity: 0.5;
            transition: opacity 0.5s ease;
        }

        .nav-link:hover {
            opacity: 1;
            color: #FAFAFA;
        }
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="row">
            <aside class="col-md-2 sidebar">
                <div class="logo">
                   <img src="logo.svg" alt="Logo Picture" style="width: 65%;">
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="dashboard.php"> <img src="Overview.svg" style="margin-right: 10px;">Overview</a>
                    <a class="nav-link" href="revenue.php"> <img src="Revenue.svg" style="margin-right: 10px;">Revenue</a>
                    <a class="nav-link" href="#"> <img src="Divison.svg" style="margin-right: 10px;">Division</a>
                    <a class="nav-link" href="#"> <img src="Client.svg" style="margin-right: 10px;">Client</a>
                    <a class="nav-link" href="#"> <img src="Customer.svg" style="margin-right: 10px;">Customer</a>
                    <a class="nav-link" href="#"> <img src="Feedbacks.svg" style="margin-right: 10px;">Feedbacks</a>
                    <a class="nav-link" href="#" ><img src="Upselling.svg" style="margin-right: 10px;">Upselling</a>
                    <a class="nav-link" href="#"> <img src="Settings.svg" style="margin-right: 10px;">Settings</a>
                    <a class="nav-link" href="logout.php"  style="margin-top:190px;"> <img src="Logout.svg" style="margin-right: 10px;">Logout</a>
                </nav>
            </aside>
            <main class="col-md-10 main-content">
                <header class="header">
                    <h1><span  id="dashboardHeading"><?php echo $heading; ?></span>  <img src="Edit.svg" style="margin-right: 10px;" id="editHeadingIcon"></h1>

                    <div class="header-info">
                        <span class="date"><?php echo $dateString; ?></span>
                        <div class="profile">
                            <img src="Customer.svg" alt="Profile Picture">
                        </div>
                    </div>
                </header>
                <div style="margin-left: 1246px;margin-bottom: 16px;">
                    <select id="monthSelect" name="monthSelect" class="custom-select" style="width: auto; display: inline-block;">
                        <option value="1">January, 2024</option>
                        <option value="2">February, 2024</option>
                        <option value="3">March, 2024</option>
                        <option value="4">April, 2024</option>
                        <option value="5" selected>May, 2024</option>
                        <option value="6">June, 2024</option>
                        <option value="7">July, 2024</option>
                        <option value="8">August, 2024</option>
                        <option value="9">September, 2024</option>
                        <option value="10">October, 2024</option>
                        <option value="11">November, 2024</option>
                        <option value="12">December, 2024</option>
                    </select>
                </div>
                <section class="overview row">
                    <div class="col-md-3">
                        <div class="card text-white bg-dark mb-3">
                            <div class="card-header">Total Revenue Generated<h5>Rs.<span  id="monthly_arch"></span></h5></div>
                            <div class="card-body">
                                <p class="card-text">
                                    <button type="button" class="btn btn-success" style="border-radius: 50px;"><img src="Upselling.svg"> 2.2%</button>
                                    <span class="vs-last-year">vs Last Year</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mb-3">
                            <div class="card-header">Average Deal Size <h5>Rs.12,00,000</h5></div>
                            <div class="card-body">
                                <p class="card-text">
                                    <button type="button" class="btn btn-success" style="border-radius: 50px;"><img src="Upselling.svg"> 2.2%</button>
                                    <span class="vs-last-year">vs Last Year</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mb-3">
                            <div class="card-header">Total No of Clients <h5>12</h5></div>
                            <div class="card-body">
                                <p class="card-text">
                                    <button type="button" class="btn btn-success" style="border-radius: 50px;"><img src="Upselling.svg"> 2.2%</button>
                                    <span class="vs-last-year">vs Last Year</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mb-3">
                            <div class="card-header">Total No of Customers <h5>24</h5></div>
                            <div class="card-body">
                                <p class="card-text">
                                    <button type="button" class="btn btn-success" style="border-radius: 50px;"><img src="Upselling.svg"> 2.2%</button>
                                    <span class="vs-last-year">vs Last Year</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="overview row chart-section">
                    <div class="col-md-6">
                        <div class="widget">
                            <h3>Total Revenue by Division</h3>
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="col-md-12 widget" style="height: 196px;">
                            <h3>No. of Feedbacks Received</h3>
                            <div class="col-md-12" style="display:flex; margin-top: 8px;">
                                <div class="widget" style="background-color: #edecec !important;padding: 30px !important;margin-right: 10px;margin-left: -22px;">
                                    <div class="col-md-6">
                                        12 <span style="color: #00c500;">Positive</span>
                                    </div>
                                </div>
                                <div class="widget" style="background-color: #edecec !important; padding: 30px !important;">
                                    <div class="col-md-6">
                                        12 <span style="color: red;">Negative</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="overview_data col-md-12" style="display: flex; margin-top: 10px;">
                            <div class="widget col-md-6" style="margin-right: 48px;">
                                <h3>No. of new referrals received</h3>
                                <p>12</p>
                                <button type="button" class="btn btn-success" style="border-radius: 50px;"><img src="Upselling.svg"> 2.2%</button>
                                    <span class="vs-last-year">vs Last Year</span>
                            </div>
                            <div class="widget col-md-6">
                                <h3>No. of offline meetings with Client</h3>
                                <p>12</p>
                                <button type="button" class="btn btn-success" style="border-radius: 50px;"><img src="Upselling.svg"> 2.2%</button>
                                    <span class="vs-last-year">vs Last Year</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="widget">
                            <button class="btn-download" style="margin-top: 352px;">Download Report</button>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script>
        function formatValue(value) {
        if (value >= 10000000) {
            return (value / 10000000).toFixed(1) + 'Cr'; // Crores
        } else if (value >= 100000) {
            return (value / 100000).toFixed(1) + 'L'; // Lakhs
        } else {
            return value;
        }
    }
    <?php if(isset($_SESSION['values_str'])){?>
        value_arachived= <?php echo $_SESSION['values_str']; ?>;
    <?php }else{?>
        value_arachived = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    <?php }?>
    var ctx = document.getElementById('revenueChart').getContext('2d');
    var revenueDivisionChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['KDD', 'GLO', 'VYB', 'ARK'],
            datasets: [{
                label: 'Achieved',
                data: [2000000, 3500000, 2500000, 3000000],
                backgroundColor: 'black',
                borderWidth: 1
            }, {
                label: 'Target',
                data: [3000000, 4000000, 3000000, 3500000],
                backgroundColor: 'gray',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    beginAtZero: true,
                    stacked: true,
                    ticks: {
                        callback: function(value) {
                            return formatValue(value);
                        }
                    }
                }
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem) {
                        return formatValue(tooltipItem.yLabel);
                    }
                }
            }
        }
    });
    $(document).ready(function() {
        $('#monthSelect').on('change', function() {
            var selectedValue = $(this).val();
            var data_arch = parseInt(value_arachived[selectedValue-1]).toLocaleString();
            $('#monthly_arch').html(data_arch); 
        });

        $('#monthSelect').trigger('change');
         $('#editHeadingIcon').on('click', function() {
        var newHeading = prompt("Enter new heading:");
        if (newHeading !== null && newHeading.trim() !== '') {
            $('#dashboardHeading').text(newHeading);
            saveHeadingToSession(newHeading);
        }
    });

    function saveHeadingToSession(newHeading) {
        $.ajax({
            type: "POST",
            url: "dashboard.php",
            data: { heading: newHeading },
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    });
    </script>
</body>
</html>
