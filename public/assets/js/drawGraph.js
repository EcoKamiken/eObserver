let date_filter = function (date) {
  if (date[0] == null) {
    return [];
  } else {
    return date[0].slice(0, 10);
  }
};

let drawGraph = function (name, id, jsonData, enable_tooltips) {
  // name: 発電所名
  // id  : 発電所ID (Unique)
  // jsonData: 温度などのデータをjson形式で記述したもの
  jsonData = JSON.parse(jsonData);
  var date = [];
  var temperature = [];
  var humidity = [];
  var wattage = [];

  jsonData.forEach(value => {
    date.push(value.times);
    temperature.push(value.temperature);
    humidity.push(value.humidity);
    wattage.push(value.wattage);
  });

  var ctx = document.getElementById(id).getContext("2d");
  var chart = new Chart(ctx, {
    type: "line",
    data: {
      labels: date,
      datasets: [{
          label: "温度[℃]",
          fill: false,
          borderColor: "#ff8989",
          backgroundColor: "#ff898980",
          data: temperature,
          borderWidth: 1,
          pointRadius: 0
        },
        {
          label: "発電量[kWh]",
          fill: true,
          borderColor: "#84ff84",
          backgroundColor: "#84ff8488",
          data: wattage,
          borderWidth: 1,
          pointRadius: 0
        },
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      title: {
        display: true,
        position: "top",
        fontSize: "16",
        fontColor: "black",
        text: name,
      },
      scales: {
        yAxes: [{
          gridLines: {
            drawBorder: false
          },
          ticks: {
            min: 0,
            suggestedMax: 50,
            stepSize: 0
          }
        }],
        xAxes: [{
          type: "time",
          bounds: "ticks",
          time: {
            displayFormats: {
              hour: "H:mm"
            },
            min: date_filter(date) + " 00:00:00",
            max: date_filter(date) + " 24:00:00"
          },
          gridLines: {
            display: false
          }
        }]
      },
      elements: {
        line: {
          tension: 0
        }
      },
      tooltips: {
        enabled: enable_tooltips,
        mode: "index",
        intersect: false,
        backgroundColor: 'rgba(0,0,0,0.5)'
      },
      animation: {
        duration: 0
      }
    }
  });
};