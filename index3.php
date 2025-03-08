<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>健康データ表示</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>健康データ一覧</h1>
        <div class="error-message" id="error-message"></div>

        <div class="data-container" id="data-container"></div>

        <div class="chart-container">
            <canvas id="stepsChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="weightChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="fatChart"></canvas>
        </div>
    </div>

    <script>
        function fetchHealthData() {
            fetch('get_health_data.php')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        document.getElementById('error-message').innerText = data.error;
                        document.getElementById('data-container').innerHTML = '';
                    } else {
                        let labels = [];
                        let stepsData = [];
                        let weightData = [];
                        let fatData = [];
                        let lastWeight = null;
                        let lastFat = null;
                        let lastSteps = null;

                        for (let i = 6; i >= 0; i--) {
                            let date = new Date();
                            date.setDate(date.getDate() - i);
                            let dateStr = date.toISOString().split('T')[0];
                            labels.push(dateStr);

                            let entry = data.find(row => row.days === dateStr);
                            if (entry) {
                                stepsData.push(entry.steps);
                                weightData.push(entry.weight);
                                fatData.push(entry.fat);
                                lastWeight = entry.weight;
                                lastFat = entry.fat;
                                lastSteps = entry.steps;
                            } else {
                                stepsData.push(lastSteps);
                                weightData.push(lastWeight);
                                fatData.push(lastFat);
                            }
                        }

                        createChart('stepsChart', '歩数', labels, stepsData, 'rgba(54, 162, 235, 0.6)');
                        createChart('weightChart', '体重 (kg)', labels, weightData, 'rgba(255, 99, 132, 0.6)');
                        createChart('fatChart', '体脂肪率 (%)', labels, fatData, 'rgba(75, 192, 192, 0.6)');
                    }
                })
                .catch(error => {
                    document.getElementById('error-message').innerText = 'データ取得エラー: ' + error;
                    document.getElementById('data-container').innerHTML = '';
                });
        }

        function createChart(canvasId, label, labels, data, color) {
            let ctx = document.getElementById(canvasId).getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        borderColor: color,
                        backgroundColor: color.replace('0.6', '0.2'),
                        fill: true,
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: false,
                            ticks: {
                                precision: 1
                            }
                        }
                    }
                }
            });
        }

        fetchHealthData();
    </script>
</body>
</html>
