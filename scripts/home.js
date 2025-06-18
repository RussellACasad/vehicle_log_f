var barColors = [
    "rgb(255, 179, 186)", "rgb(255, 223, 186)", "rgb(255, 255, 186)", 
    "rgb(186, 255, 201)", "rgb(186, 225, 255)", "rgb(255, 204, 229)", 
    "rgb(204, 255, 229)", "rgb(229, 204, 255)", "rgb(255, 229, 204)", 
    "rgb(204, 229, 255)", "rgb(255, 186, 186)", "rgb(186, 255, 186)", 
    "rgb(186, 186, 255)", "rgb(255, 186, 255)", "rgb(186, 255, 255)", 
    "rgb(255, 240, 186)", "rgb(186, 255, 240)", "rgb(240, 186, 255)", 
    "rgb(255, 186, 240)", "rgb(240, 255, 186)"
];


new Chart("gas-graph", {
    type: "line",
    data: {
        labels: GasDates,
        datasets: [{
            data: GasValues,
            borderColor: "rgb(255, 179, 186)",
            fill: false
        }]
    },
    options: {
        legend: { display: false },
        maintainAspectRatio: false
    }
});

new Chart("mpg-graph", {
    type: "bar",
    data: {
        labels: mpgCars,
        datasets: [{
            backgroundColor: barColors,
            data: mpg
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        },
        legend: {display: false},
        maintainAspectRatio: false
    }
});

new Chart("maintCost-graph", {
    type: "bar",
    data: {
        labels: maintCarArray,
        datasets: [{
            backgroundColor: barColors,
            data: maintTotalsArray
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        },
        legend: {display: false},
        maintainAspectRatio: false
    }
});

new Chart("stationChart", {
    type: "doughnut",
    data: {
      labels: gasFrequencySource,
      datasets: [{
        backgroundColor: barColors, 
        data: gasFrequencyAmount
      }]
    },
    options: {
        legend: {display: true},
        maintainAspectRatio: false
    }
  });