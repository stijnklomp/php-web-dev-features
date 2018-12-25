<style>
#bar-chart{
  background-color: grey;
  position: absolute;
  width: 800px;
  height: 300px;
  top: calc(50% - 151px);
  left: calc(50% - 401px);
}

.bar-chart-bar{
  background-color: purple;
}
</style>
<!-- Style the chart //////////////////////////////////////////// -->
<?php
$barChartTotalBars = array(
  array('28%'),
  array('20%'),
  array('74%'),
  array('10%'),
  array('81%'),
  array('58%'),
  array('44%'),
  array('20%'),
  array('22%'),
  array('29%'),
  array('39%'),
  array('58%'),
  array('89%'),
  array('77%')
);
// Chart position
// 1 = up
// 2 = down
$barChartPosition = 2;
// Chart bar spacing
$barChartSpacing = 1;
// ////////////////////////////////////////////////////////////// -->
?>
<div id="bar-chart">
  <?php
  for($i = 0; $i < count($barChartTotalBars); $i++) {
    ?>
    <div class="bar-chart-bar" style="width: calc(<?= (100 / count($barChartTotalBars)).'% - '.$barChartSpacing ?>px);height: <?= $barChartTotalBars[$i][0] ?>;left: calc(<?= ((100 / count($barChartTotalBars)) * $i).'% + '.($barChartSpacing / 2) ?>px);">
    </div>
    <?php
  }
  ?>
</div>
<style>
.bar-chart-bar{
  position: absolute;
  <?php
  if($barChartPosition == 2) {
    echo 'bottom: 0;';
  }
  ?>
}
</style>
