/* globals Chart:false, feather:false */

(function () {
  'use strict'

  feather.replace()

  // Graphs
  var ctx = document.getElementById('myChart')

  var getChartData = function(url, sensorName) {
    return new Promise(function(resolve, reject) {
      const xhr = new XMLHttpRequest();
      xhr.open("GET", url + "/" + sensorName);
      xhr.onload = () => resolve(xhr.responseText);
      xhr.onerror = () => reject(xhr.statusText);
      xhr.send();
    }).then(function(result) {
      let data = JSON.parse(result);

      return new Chart(ctx, {
        type: 'line',
        data: {
          labels: data.labels,
          datasets: [{
            data: data.values,
            lineTension: 0,
            backgroundColor: 'transparent',
            borderColor: '#007bff',
            borderWidth: 4,
            pointBackgroundColor: '#007bff'
          }]
        },
        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: false
              }
            }]
          },
          legend: {
            display: false
          }
        }
      })

    }).catch(function(reject) {
      console.log("Erreur : " + reject);
    });
  }

  let button0 = document.querySelector("#page0");
  let button1 = document.querySelector("#page1");
  var myChart;

  if(page === 0) {
    myChart = getChartData("http://localhost:8000/api/getChart", "TEMP");
  } 
  else {
    myChart = getChartData("http://localhost:8000/api/getChart", "HUM");
  }

  button0.addEventListener("click", function() {
    myChart = getChartData("http://localhost:8000/api/getChart", "TEMP");
  });

  
  button1.addEventListener("click", function() {
    myChart = getChartData("http://localhost:8000/api/getChart", "HUM");
  })

}())
