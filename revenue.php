<?php
$i = 0;
session_start();
// echo '<pre>';print_r($_SESSION);exit;
if (isset($_SESSION['loggedin']) || $_SESSION['loggedin']) {
    if(isset($_POST['monthly_data']) && isset($_POST['month'])){
    $indices = [];
    $values = [];
        $monthly_data = $_POST['monthly_data'];
        $month_inc = $_POST['month_inc'];
        $i = $month_inc+1;
        $monthly = $_POST['month'];
        $_SESSION['monthly_data_'.$i.'_'.$monthly] = $monthly_data; 
        $_SESSION['month'.$i.'_'.$monthly] = $monthly;
        $organizedData =[];
       foreach ($_SESSION as $key => $value) {
        if (preg_match('/_(\d+)$/', $key, $matches)) {
            $month = $matches[1];
            if (!isset($organizedData[$month])) {
                $organizedData[$month] = [];
            }
            $organizedData[$month][$key] = $value;
        }
    }

    $sums = [];

    foreach ($organizedData as $month => $data) {
        $sum = 0;
        foreach ($data as $key => $value) {
            if (strpos($key, 'monthly_data_') === 0) {
                $sum += (int)$value;
            }
        }
        $sums[$month] = $sum;
    }

    for ($month = 1; $month <= 12; $month++) {
        $indices[] = $month;
        if (isset($sums[$month])) {
            $values[] = $sums[$month];
        } else {
            $values[] = 0;
        }
    }
    
    $indices_str = json_encode($indices);
    $values_str = json_encode($values);
    $_SESSION['values_str'] = $values_str;
    // $vloume_arch = $_SESSION['values_str'];
    }
} elseif (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: index.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenue Dashboard</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: #f5f5f5;
            overflow-x: hidden !important;
        }

        .sidebar {
            background-color: #2c2c2c;
            color: white;
            height: 100vh;
            padding: 20px;
            position: fixed;
            width: 250px;
        }

        .header {
            background-color: #ffffff;
            padding: 15px;
            margin-left: 250px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #dee2e6;
            position: fixed;
            width: calc(100% - 250px);
            z-index: 1000;
        }

        .content {
            margin-left: 250px;
            padding: 80px 20px 20px 20px;
        }

        .revenue-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .revenue-stats .card {
            flex: 1;
            margin: 0 10px;
        }

        .total-revenue-chart {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .footer-cards {
            display: flex;
            justify-content: space-between;
        }

        .footer-cards .card {
            flex: 1;
            text-align: center;
            margin: 0 10px;
            padding: 15px;
        }

        .chart {
            height: 300px;
        }

        .nav .nav-link {
            color: white;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .nav .nav-link:hover {
            color: #ccc;
        }

        .logo h2 {
            margin: 0;
            text-align: center;
            font-size: 24px;
            margin-bottom: 30px;
        }
        .modal.bottom .modal-dialog {
            position: fixed;
            bottom: 0;
            margin: 0;
            width: 100%;
            height: 50%;
        }
        .modal.bottom .modal-content {
            height: 100%;
            border-radius: 0;
            width: 220%;
        }
        .modal-header, .modal-body, .modal-footer {
            height: auto;
            overflow-y: auto;
        }
        .modal-body {
            flex: 1;
            overflow-y: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .modal-body .form-group {
            width: 100%;
            max-width: 400px;
        }
        .modal-body .btn {
            width: 100%;
            max-width: 200px;
        }
        .profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-left: 1570px;
        }
         .nav-link {
            opacity: 0.5;
            transition: opacity 0.5s ease;
        }

        .nav-link:hover {
            opacity: 1;
            color: white;
        }

    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar">
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
                    <a class="nav-link" href="logout.php" style="margin-top:190px;"> <img src="Logout.svg" style="margin-right: 10px;">Logout</a>
                </nav>
            </div>
        </div>
    </div>

    <div class="header">
     <div class="profile">
        <img src="Customer.svg" alt="Profile Picture">
    </div>
    </div>
    <div class="content">
            <h4>TOTAL REVENUE GENERATED FROM ALL YOUR CUSTOMERS</h4>
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
            <button class="btn btn-dark"  data-toggle="modal" data-target="#bottomModal">Add Monthly Achieved</button>
        </div>
        <div class="revenue-stats">
            <div class="card mb-3">
                <div class="card-header">Monthly Achieved <h5>Rs.<span  id="monthly_arch"></span></h5></div>
                <div class="card-body">
                    <p class="card-text">
                       <button type="button" class="btn btn-success" style="border-radius: 50px;"><img src="Upselling.svg"> 2.2%</button>
                        <span class="vs-last-year">vs Last Year</span>
                    </p>
                </div>
            </div>            
            <div class="card mb-3">
                <div class="card-header">Monthly Target<h5>Rs.<span  id="monthly_traget"></span></h5></div>
                <div class="card-body">
                    <p class="card-text">
                       <button type="button" class="btn btn-success" style="border-radius: 50px;"><img src="Upselling.svg"> 2.2%</button>
                        <span class="vs-last-year">vs Last Year</span>
                    </p>
                </div>
            </div>            
            <div class="card mb-3">
                <div class="card-header">Monthly Difference <h5>Rs.<span  id="monthly_diff"></span></h5></div>
                <div class="card-body">
                    <p class="card-text">
                       <button type="button" class="btn btn-success" style="border-radius: 50px;"><img src="Upselling.svg"> 2.2%</button>
                        <span class="vs-last-year">vs Last Year</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="total-revenue-chart">
            <h5>Total Revenue</h5>
            <canvas id="revenueChart"></canvas>
        </div>

        <div class="footer-cards">
            <div class="card">
                <h6>Rs.17,00,000</h6>
                <p>March</p>
            </div>
            <div class="card">
                <h6>Rs.19,00,000</h6>
                <p>April</p>
            </div>
            <div class="card">
                <h6>Rs.36,00,000</h6>
                <p>This Quarter</p>
            </div>
            <div class="card">
                <h6>Rs.25,00,000</h6>
                <p>Last Quarter</p>
            </div>
            <div class="card">
                <h6>Rs.26,00,000</h6>
                <p>This Year</p>
            </div>
        </div>
    </div>
    

<!-- Bottom Modal -->
<div class="modal fade bottom" id="bottomModal" tabindex="-1" role="dialog" aria-labelledby="bottomModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <h5 class="modal-title" id="bottomModalLabel">Bottom Modal</h5> -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <form id="modalForm" action="revenue.php" method="POST">
                    <div class="form-group">
                        <label for="inputField">Input</label>
                        <input type="text" class="form-control form-control-md" id="inputField" placeholder="Enter your input" style="width: 270% !important;" name="monthly_data">
                        <input type="hidden" id="hiddenInputField" name="month" value="">
                        <input type="hidden" name="month_inc" value="<?php echo $i; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
           
        </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        var ctx = document.getElementById('revenueChart').getContext('2d');
        var value_arachived;
        var value_traget = [1000000, 2000000, 3000000, 4000000, 5000000, 6000000, 7000000, 8000000, 3000000, 2000000, 6000000, 1000000];
        <?php if(isset($_SESSION['values_str'])){?>
            value_arachived= <?php echo $_SESSION['values_str']; ?>;
       <?php }else{?>
              value_arachived = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
       <?php }?>
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Achieved',
                    data: value_arachived,
                    backgroundColor: 'black'
                }, {
                    label: 'Target',
                    data:  value_traget,
                    backgroundColor: 'gray'
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
                var data_traget = parseInt(value_traget[selectedValue-1]).toLocaleString();
                var data_diff = parseInt((value_traget[selectedValue-1] ) - value_arachived[selectedValue-1]).toLocaleString();

                $('#hiddenInputField').val(selectedValue);
                $('#monthly_arch').html(data_arch);
                $('#monthly_traget').html(data_traget);
                $('#monthly_diff').html(data_diff);
            });

            $('#monthSelect').trigger('change');
        });
    </script>
</body>
</html>
    