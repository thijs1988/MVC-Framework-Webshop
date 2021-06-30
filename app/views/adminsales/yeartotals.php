<?php use Core\H;?>
<?php $this->start('head')?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css">
<script scr="<?=PROOT?>js/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<?php $this->end() ?>
<?php $this->start('body');?>
<h2>YearTotals</h2><hr>

<div class="row">
  <div class="col-12">
    <div class="form-group col-2 offset-md-10">
    <select class="form-control form-control-sm" id="yearSelector" value="2020">
      <?php
         $thisYear = date("Y");
        for($i=$thisYear; $i>=2000; $i--){
          echo "<option value=".$i.">".$i."</option>";
        }
      ?>
    </select>
  </div>
  </div>
  <div class="col-md-12">
    <canvas id="yearTotalsChart" width="800" height="200" class="chart-js"></canvas>
  </div>
</div>
<script>
  function loadYearTotalsChart(){
    var year = jQuery('#yearSelector').val();
    jQuery.ajax({
      url: '<?=PROOT?>adminsales/getYearTotals',
      method: "POST",
      data: {year:year},
      success: function(resp){console.log(resp);
        var ctx = document.getElementById('yearTotalsChart');
        var data = {
          labels: resp.labels,
          datasets: [
            {
            "label":"YearTotals",
            "data": resp.data,
            "fill": false,
            "backgroundColor":["rgb(255, 99, 132)","rgb(54, 162, 235)","rgb(255, 205, 86)", "rgb(3, 252, 211)", "rgb(59, 110, 35)", "rgb(252, 3, 3)", "rgb(252, 3, 132)", "rgb(161, 3, 252)", "rgb(3, 252, 231)", "rgb(3, 49, 252)", "rgb(3, 244, 252)", "rgb(34, 142, 163)"]
          }
        ]};
        var options = {};

        if(window.myChart != undefined)
        window.myChart.destroy();
        window.myChart = new Chart(ctx, {
          type: 'doughnut',
          data: data,
          options: options
      });
      }
    });

  }

  document.getElementById('yearSelector').addEventListener("change", function(){
    loadYearTotalsChart();
  });

  $('document').ready(function(){
    loadYearTotalsChart();
  });
</script>
<?php $this->end() ?>
