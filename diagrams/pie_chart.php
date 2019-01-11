<style>
.pie-chart{
  background: #BFBFBF;
  width: 200px;
  height: 200px;
}
</style>
<!-- Style the chart //////////////////////////////////////////// -->
<?php
// Chart progress
$pieChartProcent = 88;
// Chart progression color
$pieChartColor = '#3D6785';
// ////////////////////////////////////////////////////////////// -->
?>
<div class="pie-chart"></div>
<style>
.pie-chart{
  background-image: linear-gradient(to right, transparent 50%, <?= $pieChartColor ?> 0);
  overflow: hidden;
  border-radius: 50%;
}

<?php
if($pieChartProcent > 0) {
  ?>
  .pie-chart::before {
    <?php
    if($pieChartProcent > 50) {
      ?>
      background: <?= $pieChartColor ?>;
      transform: rotate(<?= (($pieChartProcent - 50) / 100) ?>turn);
      <?php
    } else {
      ?>
      background-color: inherit;
      transform: rotate(<?= ($pieChartProcent / 100 * 360) ?>deg);
      <?php
    }
    ?>
    content: '';
    display: block;
    margin-left: 50%;
    height: 100%;
    transform-origin: left;
  }
  <?php
}
?>
</style>
