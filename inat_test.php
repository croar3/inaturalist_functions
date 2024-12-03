<!DOCTYPE html>
<html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<body>
<script>
    var xValues = ['January', 'Feburary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
</script>
<?php

include('../inat/inat_functions.php');



// $results = getYearlyObservations(21, $_GET['taxon_id']);

// print_r($results);

$results = getStateObservations($_GET['taxon_id']);

for ($i = 0; $i < count($results); $i++) {

$each_state_data = json_encode($results[$i][2]);
$each_state_name = json_encode($results[$i][0]);


echo <<<EOD
<canvas id="myChart-$i" style="width:100%;max-width:600px"></canvas>


<script>
new Chart("myChart-$i", {
  type: "bar",
  data: {
    labels: xValues,
    datasets: [{
      backgroundColor: "green",
      data: $each_state_data
    }]
  },
  options: {
    legend: {display: false},
    title: {
      display: true,
      text: $each_state_name
    }
  }
});
</script>


EOD;

}

?>



</body>
</html>