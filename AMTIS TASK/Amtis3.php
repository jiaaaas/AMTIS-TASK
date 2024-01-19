<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELECTRIC CALCULATOR</title>
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="Amtis3.css">
    
</head>
<body>

<?php
    date_default_timezone_set('Asia/Kuala_Lumpur');
    $currentDateTime = date('d-m-Y || h:i:s A');
    echo "<h3> $currentDateTime</h3>";
?>


<div class="container mt-5">
    <b><h2 class="mb-4 text-center" >Calculator</h2></b>
    <form method="post" action="">
        <div class="form-group">
            <b><label for="voltage">Voltage</label></b>
            <input type="number" class="form-control" name="voltage" step="0.01" required>
            <label for="voltage">Voltage (V)</label>
        </div>

        <div class="form-group">
            <b><label for="current">Current</label></b>
            <input type="number" class="form-control" name="current" step="0.01" required>
            <label for="current">Ampere (A)</label>
        </div>

        <div class="form-group">
            <b><label for="rate">CURRENT RATE</label></b>
            <input type="number" class="form-control" name="rate" step="0.01" required>
            <label for="rate">sen/kWh</label>
        </div>

        <div class="text-center">
        <button type="submit" class="btn btn-primary">Calculate</button>
        </div>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $voltage = $_POST["voltage"];
        $current = $_POST["current"];
        $rate = $_POST["rate"];

        if (!empty($voltage) && !empty($current) && !empty($rate)) {
            $power = $voltage * $current;
            $energy = $power / 1000; 

            $totalCharge = calculateElectricityCharge($energy, $rate/100);


            echo "<div class='card mt-4'>";
            echo "<div class='card-body'>";
            echo "<p class='card-text'>POWER : $power kw</p>";
            echo "<p class='card-text'>RATE : RM  $totalCharge</p>";
            echo "</div>";
            echo "</div>";

            echo "<table class='table'>";
            echo "<thead class = 'text-center'>";
            echo "<tr>";
            echo "<th scope='col' >Hour</th>";
            echo "<th scope='col' >Energy (kWh)</th>";
            echo "<th scope='col' >TOTAL (RM)</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            

            
            for ($hour = 1; $hour <= 24; $hour++) {
                $hourlyEnergy = $energy * $hour;
                $hourlyTotalCharge = calculateElectricityCharge($hourlyEnergy, $rate/100);

                echo "<tr>";
                echo "<td class='text-center'>$hour</td>";
                echo "<td class='text-center'>" . number_format($hourlyEnergy, 5) . "</td>"; 
                echo "<td class='text-center'>" . number_format($hourlyTotalCharge, 2) . "</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p class='text-danger mt-4'>Please fill in all the fields.</p>";
        }
    }

    function calculateElectricityCharge($energy, $rate) {
        $rates = [
            ['limit' => 200, 'rate' => 21.80],
            ['limit' => 300, 'rate' => 33.40],
            ['limit' => 600, 'rate' => 51.60],
            ['limit' => 900, 'rate' => 54.60],
            ['limit' => PHP_INT_MAX, 'rate' => 57.10],
        ];

      
        $totalCharge = 0;

        foreach ($rates as $tier) {
            if ($energy > 0) {
                $consumed = min($energy, $tier['limit']);
                $totalCharge += ($consumed * $rate);
                $energy -= $consumed;
            } else {
                break;
            }
        }

        return number_format($totalCharge, 2);
    }
    ?>
</div>

<!-- Bootstrap 4 JS and Popper.js (for dropdowns) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>