$(function() {
  /* ChartJS
   * -------
   * Data and config for chartjs
   */
  'use strict';
  var data = {
    labels: ["2013", "2014", "2014", "2015", "2016", "2017"],
    datasets: [{
      label: '# of Votes',
      data: [10, 19, 3, 5, 2, 3],
      backgroundColor: [
        'rgba(0, 0, 0, 0.2)', // Black
        'rgba(226, 109, 30, 0.2)', // Orange
        'rgba(246, 145, 36, 0.2)', // Lighter Orange
        'rgba(251, 170, 47, 0.2)', // Yellow
        'rgba(255, 206, 155, 0.2)', // Pale Yellow
        'rgba(226, 109, 30, 0.2)'  // Orange
      ],
      borderColor: [
        'rgba(0, 0, 0, 1)', // Black
        'rgba(226, 109, 30, 1)', // Orange
        'rgba(246, 145, 36, 1)', // Lighter Orange
        'rgba(251, 170, 47, 1)', // Yellow
        'rgba(255, 206, 155, 1)', // Pale Yellow
        'rgba(226, 109, 30, 1)'  // Orange
      ],
      borderWidth: 1,
      fill: false
    }]
  };
  
  var multiLineData = {
    labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
    datasets: [{
        label: 'Dataset 1',
        data: [12, 19, 3, 5, 2, 3],
        borderColor: ['rgba(226, 109, 30, 1)'], // Orange
        borderWidth: 2,
        fill: false
      },
      {
        label: 'Dataset 2',
        data: [5, 23, 7, 12, 42, 23],
        borderColor: ['rgba(251, 170, 47, 1)'], // Yellow
        borderWidth: 2,
        fill: false
      },
      {
        label: 'Dataset 3',
        data: [15, 10, 21, 32, 12, 33],
        borderColor: ['rgba(0, 0, 0, 1)'], // Black
        borderWidth: 2,
        fill: false
      }
    ]
  };
  
  var doughnutPieData = {
    datasets: [{
      data: [30, 40, 30],
      backgroundColor: [
        'rgba(0, 0, 0, 0.5)', // Black
        'rgba(226, 109, 30, 0.5)', // Orange
        'rgba(251, 170, 47, 0.5)'  // Yellow
      ],
      borderColor: [
        'rgba(0, 0, 0, 1)', // Black
        'rgba(226, 109, 30, 1)', // Orange
        'rgba(251, 170, 47, 1)'  // Yellow
      ],
    }],
    labels: [
      'Black',
      'Orange',
      'Yellow'
    ]
  };
  
  var multiAreaData = {
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    datasets: [{
        label: 'Facebook',
        data: [8, 11, 13, 15, 12, 13, 16, 15, 13, 19, 11, 14],
        borderColor: ['rgba(226, 109, 30, 0.5)'], // Orange
        backgroundColor: ['rgba(226, 109, 30, 0.5)'],
        borderWidth: 1,
        fill: true
      },
      {
        label: 'Twitter',
        data: [7, 17, 12, 16, 14, 18, 16, 12, 15, 11, 13, 9],
        borderColor: ['rgba(251, 170, 47, 0.5)'], // Yellow
        backgroundColor: ['rgba(251, 170, 47, 0.5)'],
        borderWidth: 1,
        fill: true
      },
      {
        label: 'Linkedin',
        data: [6, 14, 16, 20, 12, 18, 15, 12, 17, 19, 15, 11],
        borderColor: ['rgba(255, 206, 155, 0.5)'], // Pale Yellow
        backgroundColor: ['rgba(255, 206, 155, 0.5)'],
        borderWidth: 1,
        fill: true
      }
    ]
  };

  var scatterChartData = {
    datasets: [{
        label: 'First Dataset',
        data: [{ x: -10, y: 0 }, { x: 0, y: 3 }, { x: -25, y: 5 }, { x: 40, y: 5 }],
        backgroundColor: ['rgba(226, 109, 30, 0.2)'], // Orange
        borderColor: ['rgba(226, 109, 30, 1)'], // Orange
        borderWidth: 1
      },
      {
        label: 'Second Dataset',
        data: [{ x: 10, y: 5 }, { x: 20, y: -30 }, { x: -25, y: 15 }, { x: -10, y: 5 }],
        backgroundColor: ['rgba(0, 0, 0, 0.2)'], // Black
        borderColor: ['rgba(0, 0, 0, 1)'], // Black
        borderWidth: 1
      }
    ]
  };
  
  // Chart configurations
  if ($("#barChart").length) {
    var barChartCanvas = $("#barChart").get(0).getContext("2d");
    var barChart = new Chart(barChartCanvas, {
      type: 'bar',
      data: data,
      options: {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            }
          }]
        },
        legend: {
          display: false
        },
        elements: {
          point: {
            radius: 0
          }
        }
      }
    });
  }

  if ($("#lineChart").length) {
    var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
    var lineChart = new Chart(lineChartCanvas, {
      type: 'line',
      data: data,
      options: {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            }
          }]
        },
        legend: {
          display: false
        },
        elements: {
          point: {
            radius: 0
          }
        }
      }
    });
  }

  if ($("#linechart-multi").length) {
    var multiLineCanvas = $("#linechart-multi").get(0).getContext("2d");
    var lineChart = new Chart(multiLineCanvas, {
      type: 'line',
      data: multiLineData,
      options: {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            }
          }]
        },
        legend: {
          display: false
        },
        elements: {
          point: {
            radius: 0
          }
        }
      }
    });
  }

  if ($("#areachart-multi").length) {
    var multiAreaCanvas = $("#areachart-multi").get(0).getContext("2d");
    var multiAreaChart = new Chart(multiAreaCanvas, {
      type: 'line',
      data: multiAreaData,
      options: {
        plugins: {
          filler: {
            propagate: true
          }
        },
        elements: {
          point: {
            radius: 0
          }
        },
        scales: {
          xAxes: [{
            gridLines: {
              display: false
            }
          }],
          yAxes: [{
            gridLines: {
              display: false
            }
          }]
        }
      }
    });
  }

  if ($("#doughnutChart").length) {
    var doughnutChartCanvas = $("#doughnutChart").get(0).getContext("2d");
    var doughnutChart = new Chart(doughnutChartCanvas, {
      type: 'doughnut',
      data: doughnutPieData,
      options: {
        responsive: true,
        animation: {
          animateScale: true,
          animateRotate: true
        }
      }
    });
  }

  if ($("#pieChart").length) {
    var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
    var pieChart = new Chart(pieChartCanvas, {
      type: 'pie',
      data: doughnutPieData,
      options: {
        responsive: true,
        animation: {
          animateScale: true,
          animateRotate: true
        }
      }
    });
  }

  if ($("#areaChart").length) {
    var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
    var areaChart = new Chart(areaChartCanvas, {
      type: 'line',
      data: multiAreaData,
      options: {
        plugins: {
          filler: {
            propagate: true
          }
        }
      }
    });
  }

  if ($("#scatterChart").length) {
    var scatterChartCanvas = $("#scatterChart").get(0).getContext("2d");
    var scatterChart = new Chart(scatterChartCanvas, {
      type: 'scatter',
      data: scatterChartData,
      options: {
        scales: {
          xAxes: [{
            type: 'linear',
            position: 'bottom'
          }]
        }
      }
    });
  }
});
