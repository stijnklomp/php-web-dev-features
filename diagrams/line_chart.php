<style>
#testDiv{
  position: relative;
  width: calc(100% - 50px);
  height: calc(100% - 50px);
  top: 25px;
  left: 25px;
  margin: 0;
  padding: 0;
  border: 1px solid black;
}

.line-diagram-point{
  background-color: grey;
}

.line-diagram-line{
  stroke-width: 2px !important;
  stroke: rgb(0, 0, 0) !important;
}
</style>

<!-- Style the chart //////////////////////////////////////////// -->
<?php
$lineChartTotalPoints = array(
  array('59'),
  array('20'),
  array('14'),
  array('54'),
  array('48'),
  array('22'),
  array('84'),
  array('100'),
  array('82'),
  array('68'),
  array('16'),
  array('52'),
  array('70'),
  array('68'),
  array('53'),
  array('58'),
  array('65'),
  array('68'),
  array('73'),
  array('79'),
  array('80'),
  array('74')
);
$lineChartPointWidth = 10;
$lineChartPointHeight = 10;
// ////////////////////////////////////////////////////////////// -->
?>

<div id="testDiv">
  <div id="line-chart">
    <?php
    for($i = 0; $i < count($lineChartTotalPoints); $i++) {
      ?>
      <div class="line-diagram-point" style="top: calc(<?= (100 - $lineChartTotalPoints[$i][0]); ?>% - 5px);left: <?= (($i / (count($lineChartTotalPoints) - 1)) * 100); ?>%;"></div>
      <?php
      if(($i + 1) < count($lineChartTotalPoints)) {
          ?>
          <svg class="svg">
            <line class="line-diagram-line" x2="calc(<?= (($i / (count($lineChartTotalPoints) - 1)) * 100); ?>% + <?= ($lineChartPointWidth / 2); ?>px)" y1="<?= (100 - $lineChartTotalPoints[($i + 1)][0]); ?>%" x1="calc(<?= ((($i + 1) / (count($lineChartTotalPoints) - 1)) * 100); ?>% + <?= ($lineChartPointWidth / 2); ?>px)" y2="<?= (100 - $lineChartTotalPoints[$i][0]); ?>%">
          </svg>
          <?php
      }
    }
    ?>
  </div>
</div>
<style>
#line-chart{
  position: relative;
  display: inline-block;
  width: calc(100% - <?= $lineChartPointWidth; ?>px);
  height: calc(100% - <?= $lineChartPointHeight; ?>px);
  margin-right: <?= $lineChartPointWidth; ?>px;
  margin-top: <?= ($lineChartPointWidth / 2); ?>px;
  margin-bottom: <?= ($lineChartPointWidth / 2); ?>px;
}

.svg{
  position: absolute;
  width: 100%;
  height: 100%;
}

.line-diagram-line{
  stroke-width: 1px;
  stroke: rgb(0, 0, 0);
}

.line-diagram-point{
  position: absolute;
  width: <?= $lineChartPointWidth; ?>px;
  height: <?= $lineChartPointHeight; ?>px;
  z-index: 1;
}
</style>
