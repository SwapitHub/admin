<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Average Order Value Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div style="width: 50%; margin: auto;">
        <canvas id="aovChart"></canvas>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('aovChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($averageOrderValues->pluck('year')) !!},
                    datasets: [{
                        label: 'Average Order Value ($)',
                        data: {!! json_encode($averageOrderValues->pluck('average_order_value')) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
