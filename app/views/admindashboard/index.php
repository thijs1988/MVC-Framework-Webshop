<?php $this->start('head')?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css">
<script scr="<?=PROOT?>js/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<?php $this->end() ?>
<?php $this->start('body');?>
<h2>Dashboard</h2><hr>

<div class="row">
  <div class="col-12">
    <div class="form-group col-2 offset-md-10">
    <select class="form-control form-control-sm" id="dateRangeSelector" value="last-28">
      <option value="last-1">Today</option>
      <option value="last-7">Last 7 Days</option>
      <option value="last-28" selected="selected">Last 28 Days</option>
      <option value="last-90">Last 90 Days</option>
      <option value="last-365">Last 365 Days</option>
    </select>
  </div>
  </div>
  <div class="col-md-12">
    <canvas id="dailySalesChart" width="400" height="80" class="chart-js"></canvas>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="form-group col-2 offset-md-10">
    <select class="form-control form-control-sm" id="salesBySourceSelector" value"brandname">
      <option value="brandname" selected="selected">Brands</option>
      <option value="items">Items</option>
    </select>
  </div>
  </div>
<div class="col-md-12">
  <canvas id="salesByChart" class="chart-js" width="400" height="auto"></canvas>
</div>
</div>


<script>
  function loadDailySalesChart(){
    var range = jQuery('#dateRangeSelector').val();
    jQuery.ajax({
      url: '<?=PROOT?>admindashboard/getDailySales',
      method: "POST",
      data: {range:range},
      success: function(resp){console.log(resp);
        var ctx = document.getElementById('dailySalesChart');
        var data = {
          labels: resp.labels,
          datasets: [
            {
            "label":"Daily Sales",
            "data": resp.data,
            "fill": false,
            "borderColor":"rgb(75, 192, 192)",
            "lineTension": 0.1
          }
        ]
        };
        var options = {};
        var myLineChart = new Chart(ctx, {
          type: 'line',
          data: data,
          options: options
        });
      }
    });

  }


  document.getElementById('dateRangeSelector').addEventListener("change", function(){
    loadDailySalesChart();
  });

  $('document').ready(function(){
    loadDailySalesChart();
  });


  function loadSalesBy(){
    var source = jQuery('#salesBySourceSelector').val();
    jQuery.ajax({
      url: '<?=PROOT?>admindashboard/getSoldItems',
      method: "POST",
      data: {source:source},
      success: function(resp){console.log(resp);
        var ctx = document.getElementById('salesByChart');
        var data = {
          labels: resp.labels,
          datasets: [
            {
            "label":"Sales By",
            "data": resp.data,
            "fill": false,
            "borderColor":"rgb(75, 192, 192)",
          }
        ]
        };

        var options = {
          "scales":{
            "xAxes":[{
              "ticks":{"beginAtZero":true}
            }]}};

          if(window.myCharts != undefined)
          window.myCharts.destroy();
          window.myCharts = new Chart(ctx, {
          type: 'horizontalBar',
          data: data,
          options: options
      });
      }
    });
  }

  document.getElementById('salesBySourceSelector').addEventListener("change", function(){
    loadSalesBy();
  });

  $('document').ready(function(){
    loadSalesBy();
  });

</script>
<?php $this->end(); ?>
