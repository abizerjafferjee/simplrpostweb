<link href="https://urcommunitycares.com/assets/web_files/vendor/plugin_css/datepicker.css" rel="stylesheet">
<style>
  .apexcharts-menu-icon {
    display: none;
  }

  .apexcharts-legend.center.position-bottom {
    display: none;
  }

  .charts {
    width: 100%;
    height: 500px;
  }
</style>
<style>
  #loader-div {
      width: 100%;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      z-index: -1;
    }

  .loader {
    position: absolute;
    left: 50%;
    top: 50%;
    z-index: 1;
    width: 150px;
    height: 150px;
    margin: -75px 0 0 -75px;
    border: 16px solid #f3f3f3;
    border-radius: 50%;
    border-top: 16px solid #3498db;
    width: 120px;
    height: 120px;
    -webkit-animation: spin 2s linear infinite;
    animation: spin 2s linear infinite;
  }

  @-webkit-keyframes spin {
    0% {
      -webkit-transform: rotate(0deg);
    }

    100% {
      -webkit-transform: rotate(360deg);
    }
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }
</style>
<div id="loader-div">
  <div id="loader">

  </div>
</div>
<!-- Header -->
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
  <div class="container-fluid">
    <div class="header-body">
      <!-- Card stats -->
      <div class="row">
      </div>
    </div>
  </div>
</div>


<div class="container-fluid mt--7">
  <!-- Table -->
  <div class="row">
    <div class="col">
      <div class="card shadow">
        <div class="card-header border-0">
          <h3 class="mb-0">Charts</h3>
          <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Users Chart</h4>
                  <div id="chart">
                    <div id="usersBarChart" class="charts">

                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Businesses Chart</h4>
                  <div id="chart1">
                    <div id="businessesBarChart" class="charts">

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Users Status Chart</h4>
                  <div id="chart2">
                    <div id="usersStatusPieChart" class="charts">

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-12 grid-margin stretch-card">
                      <h4 class="card-title">Users Registered</h4>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-9 grid-margin stretch-card form-group">

                    </div>
                    <div class="col-md-3 grid-margin stretch-card form-group">
                      <div class="input-group input-group-alternative">
                        <select class="form-control" id="usersLineChartFilter">
                          <option value='showThisWeekUsers'>This Week Data</option>
                          <option value='showThisMonthUsers'>This Month Data</option>
                          <option value='showThisYearUsers'>This Year Data</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                      <div id="chart2">
                        <div id="usersLineChart" class="charts">

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-12 grid-margin stretch-card">
                      <h4 class="card-title">Businesses Registered</h4>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-9 grid-margin stretch-card form-group">

                    </div>
                    <div class="col-md-3 grid-margin stretch-card form-group">
                      <div class="input-group input-group-alternative">
                        <select class="form-control" id="businessLineChartFilter">
                          <option value='showThisWeekData'>This Week Data</option>
                          <option value='showThisMonthData'>This Month Data</option>
                          <option value='showThisYearData'>This Year Data</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                      <div id="chart2">
                        <div id="businessLineChart" class="charts">

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>
  <!-- </div> -->
  <!-- Dark table -->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://urcommunitycares.com/assets/web_files/vendor/jquery/datepicker.min.js"></script>
  <script type="text/javascript" src="https://urcommunitycares.com/assets/web_files/vendor/jquery/moment.js"></script>

  <script src="<?= BASE_URL ?>assets/charts/core.js"></script>
  <script src="<?= BASE_URL ?>assets/charts/charts.js"></script>
  <script src="<?= BASE_URL ?>assets/charts/animated.js"></script>

  <script>
    $(document).ready(function() {
      $(document).ajaxStart(function() {
        $('#loader').addClass('loader');
        $('#loader-div').css('z-index', '10');
      });

      $(document).ajaxComplete(function() {
        $('#loader').removeClass('loader');
        $('#loader-div').css('z-index', '-1');
      });

      // get users data for usersBarChart
      $.ajax({
        url: '<?=SITE_URL?>Admin/Admin/getChartDataFromUser',
        success: function(data) {
          data = JSON.parse(data);
          am4core.useTheme(am4themes_animated);

          var chart = am4core.create("usersBarChart", am4charts.XYChart);
          chart.scrollbarX = new am4core.Scrollbar();

          chart.data = data;

          var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
          categoryAxis.dataFields.category = "monthName";
          categoryAxis.renderer.grid.template.location = 0;
          categoryAxis.renderer.minGridDistance = 30;
          categoryAxis.renderer.labels.template.horizontalCenter = "right";
          categoryAxis.renderer.labels.template.verticalCenter = "middle";
          categoryAxis.renderer.labels.template.rotation = 270;
          categoryAxis.tooltip.disabled = true;
          categoryAxis.renderer.minHeight = 110;


          var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
          valueAxis.renderer.minWidth = 10;

          var series = chart.series.push(new am4charts.ColumnSeries());
          series.sequencedInterpolation = true;
          series.dataFields.valueY = "registeredUser";
          series.dataFields.categoryX = "monthName";
          series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
          series.columns.template.strokeWidth = 0;

          series.tooltip.pointerOrientation = "vertical";

          series.columns.template.column.cornerRadiusTopLeft = 10;
          series.columns.template.column.cornerRadiusTopRight = 10;
          series.columns.template.column.fillOpacity = 0.8;

          var hoverState = series.columns.template.column.states.create("hover");
          hoverState.properties.cornerRadiusTopLeft = 0;
          hoverState.properties.cornerRadiusTopRight = 0;
          hoverState.properties.fillOpacity = 1;

          series.columns.template.adapter.add("fill", function(fill, target) {
            return chart.colors.getIndex(target.dataItem.index);
          });

          chart.cursor = new am4charts.XYCursor();
        }
      })

      // get business data for businessesBarChart
      $.ajax({
        url: '<?=SITE_URL?>Admin/Admin/getChartDataFromPublicAddresses',
        success: function(data) {
          data = JSON.parse(data);
          am4core.useTheme(am4themes_animated);

          var chart = am4core.create("businessesBarChart", am4charts.XYChart);
          chart.scrollbarX = new am4core.Scrollbar();

          chart.data = data;

          var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
          categoryAxis.dataFields.category = "monthName";
          categoryAxis.renderer.grid.template.location = 0;
          categoryAxis.renderer.minGridDistance = 30;
          categoryAxis.renderer.labels.template.horizontalCenter = "right";
          categoryAxis.renderer.labels.template.verticalCenter = "middle";
          categoryAxis.renderer.labels.template.rotation = 270;
          categoryAxis.tooltip.disabled = true;
          categoryAxis.renderer.minHeight = 110;


          var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
          valueAxis.renderer.minWidth = 10;

          var series = chart.series.push(new am4charts.ColumnSeries());
          series.sequencedInterpolation = true;
          series.dataFields.valueY = "registeredBusinesses";
          series.dataFields.categoryX = "monthName";
          series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
          series.columns.template.strokeWidth = 0;

          series.tooltip.pointerOrientation = "vertical";

          series.columns.template.column.cornerRadiusTopLeft = 10;
          series.columns.template.column.cornerRadiusTopRight = 10;
          series.columns.template.column.fillOpacity = 0.8;

          var hoverState = series.columns.template.column.states.create("hover");
          hoverState.properties.cornerRadiusTopLeft = 0;
          hoverState.properties.cornerRadiusTopRight = 0;
          hoverState.properties.fillOpacity = 1;

          series.columns.template.adapter.add("fill", function(fill, target) {
            return chart.colors.getIndex(target.dataItem.index);
          });

          chart.cursor = new am4charts.XYCursor();
        }
      })

      // users pie chart according to their status
      $.ajax({
        url: '<?=SITE_URL?>Admin/Admin/getStatusDataFromUser',
        success: function(data) {
          data = JSON.parse(data);

          var chart = am4core.create("usersStatusPieChart", am4charts.PieChart);
          chart.hiddenState.properties.opacity = 0;

          // Add data
          chart.data = data;

          // Add and configure Series
          var series = chart.series.push(new am4charts.PieSeries());
          series.dataFields.value = "userCount";
          series.dataFields.radiusValue = "userCount";
          series.dataFields.category = "statusName";
          series.slices.template.cornerRadius = 6;
          series.colors.step = 3;

          series.hiddenState.properties.endAngle = -60;

          chart.legend = new am4charts.Legend();
        }
      })

      getLineChartDataFromUser('getDataFromUserForThisWeek')
      // total users line chart
      function getLineChartDataFromUser($method) {
        $.ajax({
          url: '<?=SITE_URL?>Admin/Admin/' + $method,
          success: function(data) {
            data = JSON.parse(data);

            am4core.useTheme(am4themes_animated);
            // Themes end

            // Create chart instance
            var chart = am4core.create("usersLineChart", am4charts.XYChart);

            // Add data
            chart.data = data;

            // Set input format for the dates
            chart.dateFormatter.inputDateFormat = "yyyy-MM-dd";

            // Create axes
            var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

            // Create series
            var series = chart.series.push(new am4charts.LineSeries());
            series.dataFields.valueY = "registeredUsers";
            series.dataFields.dateX = "date";
            series.tooltipText = "{value}"
            series.strokeWidth = 2;
            series.minBulletDistance = 15;

            // Drop-shaped tooltips
            series.tooltip.background.cornerRadius = 20;
            series.tooltip.background.strokeOpacity = 0;
            series.tooltip.pointerOrientation = "vertical";
            series.tooltip.label.minWidth = 40;
            series.tooltip.label.minHeight = 40;
            series.tooltip.label.textAlign = "middle";
            series.tooltip.label.textValign = "middle";

            // Make bullets grow on hover
            var bullet = series.bullets.push(new am4charts.CircleBullet());
            bullet.circle.strokeWidth = 2;
            bullet.circle.radius = 4;
            bullet.circle.fill = am4core.color("#fff");

            var bullethover = bullet.states.create("hover");
            bullethover.properties.scale = 1.3;

            // Make a panning cursor
            chart.cursor = new am4charts.XYCursor();
            chart.cursor.behavior = "panXY";
            chart.cursor.xAxis = dateAxis;
            chart.cursor.snapToSeries = series;

            // Create vertical scrollbar and place it before the value axis
            chart.scrollbarY = new am4core.Scrollbar();
            chart.scrollbarY.parent = chart.leftAxesContainer;
            chart.scrollbarY.toBack();

            // Create a horizontal scrollbar with previe and place it underneath the date axis
            chart.scrollbarX = new am4charts.XYChartScrollbar();
            chart.scrollbarX.series.push(series);
            chart.scrollbarX.parent = chart.bottomAxesContainer;

            chart.events.on("ready", function() {
              dateAxis.zoom({
                start: 0,
                end: 1
              });
            });
          }
        })
      }
      $('#usersLineChartFilter').on('change', function() {
        console.log($(this).val());
        if ($(this).val() == 'showThisWeekUsers') {
          getLineChartDataFromUser('getDataFromUserForThisWeek');
        } else if ($(this).val() == 'showThisMonthUsers') {
          getLineChartDataFromUser('getDataFromUserForThisMonth');
        } else if ($(this).val() == 'showThisYearUsers') {
          getLineChartDataFromUser('getDataFromUserForThisYear');
        }
      })


      getLineChartDataFromBusiness('getDataFromBusinessForThisWeek');
      // total users line chart
      function getLineChartDataFromBusiness($method) {
        $.ajax({
          url: '<?=SITE_URL?>Admin/Admin/' + $method,
          success: function(data) {
            data = JSON.parse(data);

            am4core.useTheme(am4themes_animated);
            // Themes end

            // Create chart instance
            var chart = am4core.create("businessLineChart", am4charts.XYChart);

            // Add data
            chart.data = data;

            // Set input format for the dates
            chart.dateFormatter.inputDateFormat = "yyyy-MM-dd";

            // Create axes
            var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

            // Create series
            var series = chart.series.push(new am4charts.LineSeries());
            series.dataFields.valueY = "registeredBusinesses";
            series.dataFields.dateX = "date";
            series.tooltipText = "{value}"
            series.strokeWidth = 2;
            series.minBulletDistance = 15;

            // Drop-shaped tooltips
            series.tooltip.background.cornerRadius = 20;
            series.tooltip.background.strokeOpacity = 0;
            series.tooltip.pointerOrientation = "vertical";
            series.tooltip.label.minWidth = 40;
            series.tooltip.label.minHeight = 40;
            series.tooltip.label.textAlign = "middle";
            series.tooltip.label.textValign = "middle";

            // Make bullets grow on hover
            var bullet = series.bullets.push(new am4charts.CircleBullet());
            bullet.circle.strokeWidth = 2;
            bullet.circle.radius = 4;
            bullet.circle.fill = am4core.color("#fff");

            var bullethover = bullet.states.create("hover");
            bullethover.properties.scale = 1.3;

            // Make a panning cursor
            chart.cursor = new am4charts.XYCursor();
            chart.cursor.behavior = "panXY";
            chart.cursor.xAxis = dateAxis;
            chart.cursor.snapToSeries = series;

            // Create vertical scrollbar and place it before the value axis
            chart.scrollbarY = new am4core.Scrollbar();
            chart.scrollbarY.parent = chart.leftAxesContainer;
            chart.scrollbarY.toBack();

            // Create a horizontal scrollbar with previe and place it underneath the date axis
            chart.scrollbarX = new am4charts.XYChartScrollbar();
            chart.scrollbarX.series.push(series);
            chart.scrollbarX.parent = chart.bottomAxesContainer;

            chart.events.on("ready", function() {
              dateAxis.zoom({
                start: 0,
                end: 1
              });
            });
          }
        })
      }
      $('#businessLineChartFilter').on('change', function() {
        console.log($(this).val());
        if ($(this).val() == 'showThisWeekData') {
          getLineChartDataFromBusiness('getDataFromBusinessForThisWeek');
        } else if ($(this).val() == 'showThisMonthData') {
          getLineChartDataFromBusiness('getDataFromBusinessForThisMonth');
        } else if ($(this).val() == 'showThisYearData') {
          getLineChartDataFromBusiness('getDataFromBusinessForThisYear');
        }
      })
    })
  </script>