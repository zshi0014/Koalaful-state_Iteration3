<?php
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
<canvas id="myChart" style="box-shadow: inset 0 0 0 1000px rgba(0,0,0,.5);"></canvas>
<script>
var ctx = document.getElementById('myChart').getContext('2d');
echo '<script>';
echo 'var javaScriptVar= ' . json_encode($death3) . ';';
echo '</script>';
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['1994','1995','1996','1997','1998','1999','2000','2001','2002','2003','2004','2005','2006','2007','2008','2009','2010','2011','2012','2013','2014' ],
        datasets: [{
            label: 'Number of Death',
            data: javaScriptVar,
            backgroundColor: [
                'rgba(159, 172, 186, 0.2)'
            ],
            borderColor: [
                '#9FACBA'
            ],
            borderWidth: 2
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>
    ?>