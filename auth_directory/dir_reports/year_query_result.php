<?php
  include('../../connection.php');
  $fyear = $_GET['fyear'];
  $tyear = $_GET['tyear'];
  $mdname = $_GET['md'];

  // function
  function getAmountLba($mdname,$lbacode,$year){
		$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME) or die("Couldn't make connection.");
    $amount = 0.00;
    $amountQuery = $mysqli -> query("SELECT SUM(data_amount) as total FROM mdc_senior.senior_data as a INNER JOIN mdc_senior.mst_database as b ON a.data_branchcode=b.mst_branchcode
                                     WHERE data_doctor='$mdname' AND mst_lburebate='$lbacode'
                                     AND data_year='$year'
                                     GROUP BY mst_lburebate");
      while($amountRes = $amountQuery -> fetch_assoc()){
        $amount = number_format((float)$amountRes['total'],2);
      }
    return $amount;
  }
  function getAmountTotalLba($lbacode,$year){
		$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME) or die("Couldn't make connection.");
    $amount = 0.00;
    $amountQuery = $mysqli -> query("SELECT SUM(data_amount) as total FROM mdc_senior.senior_data as a INNER JOIN mdc_senior.mst_database as b ON a.data_branchcode=b.mst_branchcode
                                     WHERE mst_lburebate='$lbacode'
                                     AND data_year='$year'
                                     GROUP BY mst_lburebate");
      while($amountRes = $amountQuery -> fetch_assoc()){
        $amount = $amountRes['total'];
      }
    return $amount;
  }

  function computeShare($totalArea,$totalMD){
    $totalMD = str_replace(',','',$totalMD);
    $share = 0.00;
    if($totalMD > 0){
      $share = number_format((float)(($totalMD/$totalArea)*100),2)."%";
    }
    return $share;
  }
  function getLbaArea($lbacode){
		$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME) or die("Couldn't make connection.");
    $lba_area = "";
    $lbaQuery = $mysqli -> query("SELECT DISTINCT lbu_area FROM mdc_senior.lbu_rebatedb WHERE lbu_code='$lbacode' ORDER BY ABS(lbu_year) LIMIT 1");
      while($lbaRes = $lbaQuery -> fetch_assoc()){
        $lba_area = $lbaRes['lbu_area'];
      }
    return $lba_area;
  }
?>
<div class="box box-solid">
  <div class="box-header with-border bg-teal">
    <h3 class="box-title text-blue"><b><?php echo strtoupper($mdname);?></b></h3>
    <div class="box-tools">
      <div class="btn-group">
        <button type="button" class="btn btn-success btn-sm" onclick="showResultYear();">By LBA</button>
        <button type="button" class="btn btn-default btn-sm bg-gray" onclick="showResultYearBranch();">By Branch</button>
        <button type="button" class="btn btn-default btn-sm bg-gray" onclick="showResultYearProd();">By Product</button>
      </div>
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body no-padding">
    <div class="box-group" id="accordion">
      <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
      <div class="panel box box-solid">
        <div class="box-header with-border bg-info" style="background-color:#AED6F1 !important;">
          <h4 class="box-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
              <b>On-Site Sale</b>
            </a>
          </h4>
          <span style="display: inline-block;float:right;" id="exportOnsite"></span>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in">
          <div class="box-body no-padding" style="font-size:12px;">
            <div class="table-responsive">
            <?php
              // On-Site Data
            ?>
            <table id="example1" class="table table-bordered table-striped" style="margin-top:0 !important;" width="100%">
              <thead style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                  <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                      <th style="white-space: nowrap;text-align:center;" rowspan="2">LBA CODE</th>
                      <th style="white-space: nowrap;text-align:center;" rowspan="2">LBA NAME</th>
                      <?php
                        for($i = $fyear; $i <= $tyear; $i++){
                      ?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2"><?php echo $i;?></th>
                      <?php }?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2">TOTAL</th>
                  </tr>
                  <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                      <?php
                        for($i = $fyear; $i <= $tyear; $i++){
                      ?>
                      <th style="white-space: nowrap;text-align:center;">AMOUNT</th>
                      <th style="white-space: nowrap;text-align:center;">% SHARE</th>
                      <?php }?>
                      <th style="white-space: nowrap;text-align:center;">AMOUNT</th>
                      <th style="white-space: nowrap;text-align:center;">% SHARE</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                    $lbaArray = array();
                    $lbaQuery = $mysqli -> query("SELECT DISTINCT lbu_code,lbu_area FROM mdc_senior.lbu_rebatedb WHERE lbu_dname='$mdname' AND lbu_year='2018' AND lbu_quarter='Q1'");
                      while($lbaRes = $lbaQuery -> fetch_assoc()){
                        $sumMD = array();
                        $sumArea = array();
                        $lbacode = $lbaRes['lbu_code'];
                        $lbaArray[] = $lbacode;
                  ?>
                  <tr>
                      <td style="white-space: nowrap;"><?php echo $lbacode;?></td>
                      <td style="white-space: nowrap;"><a href="#" data-toggle="modal" data-target="#detailsModal" data-lbucode="<?php echo $lbacode;?>" data-lbuarea="<?php echo $lbaRes['lbu_area'];?>"  onclick="showYearDetails(this)"><?php echo $lbaRes['lbu_area'];?></a></td>
                      <?php
                        for($i = $fyear; $i <= $tyear; $i++){
                          $totalMD = getAmountLba($mdname,$lbacode,$i);
                          $totalArea = getAmountTotalLba($lbacode,$i);
                          $sumMD[] = str_replace(',','',$totalMD);
                          $sumArea[] = $totalArea;
                      ?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo $totalMD;?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalArea,$totalMD);?></td>
                      <?php }?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo number_format((float)array_sum($sumMD),2);?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare(array_sum($sumArea),array_sum($sumMD));?></td>
                  </tr>
                  <?php }?>
              </tbody>
            </table>
            </div>

          </div>
        </div>
      </div>
      <div class="panel box box-solid" style="margin-bottom: 0 !important;">
        <div class="box-header with-border bg-warning disabled" style="background-color:#F8C471 !important;">
          <h4 class="box-title" style="display: inline-block;">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
              <b>Off-Site Sale</b>
            </a>
          </h4>
          <span style="display: inline-block;float:right;" id="exportOffsite"></span>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse">
          <div class="box-body no-padding" style="font-size:13px;">

            <div class="table-responsive">
            <?php
              // Off-Site Data
            ?>
            <table id="example2" class="table table-bordered table-striped" style="margin-top:0 !important;" width="100%" >
              <thead style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                  <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                      <th style="white-space: nowrap;text-align:center;" rowspan="2">LBA CODE</th>
                      <th style="white-space: nowrap;text-align:center;" rowspan="2">LBA NAME</th>
                      <?php
                        for($i = $fyear; $i <= $tyear; $i++){
                      ?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2"><?php echo $i;?></th>
                      <?php }?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2">TOTAL</th>
                  </tr>
                  <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                      <?php
                        for($i = $fyear; $i <= $tyear; $i++){
                      ?>
                      <th style="white-space: nowrap;text-align:center;">AMOUNT</th>
                      <th style="white-space: nowrap;text-align:center;">% SHARE</th>
                      <?php }?>
                      <th style="white-space: nowrap;text-align:center;">AMOUNT</th>
                      <th style="white-space: nowrap;text-align:center;">% SHARE</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                    $lbaQuery = $mysqli -> query("SELECT DISTINCT data_lburebate FROM mdc_senior.senior_data WHERE data_doctor='$mdname' AND data_lburebate NOT IN('".implode("','",$lbaArray)."') AND data_year='$tyear'");
                      while($lbaRes = $lbaQuery -> fetch_assoc()){
                        $lbacode = $lbaRes['data_lburebate'];
                        $lbaArea = getLbaArea($lbacode);
                        $sumMD = array();
                        $sumArea = array();
                  ?>
                  <tr>
                      <td style="white-space: nowrap;"><?php echo $lbaRes['data_lburebate'];?></td>
                      <td style="white-space: nowrap;"><a href="#" data-toggle="modal" data-target="#detailsModal" data-lbucode="<?php echo $lbacode;?>" data-lbuarea="<?php echo $lbaArea;?>"  onclick="showYearDetails(this)"><?php echo $lbaArea;?></a></td>
                      <?php
                        for($i = $fyear; $i <= $tyear; $i++){
                          $totalMD = getAmountLba($mdname,$lbacode,$i);
                          $totalArea = getAmountTotalLba($lbacode,$i);
                          $sumMD[] = str_replace(',','',$totalMD);
                          $sumArea[] = $totalArea;
                      ?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo $totalMD;?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalArea,$totalMD);?></td>
                      <?php }?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo number_format((float)array_sum($sumMD),2);?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare(array_sum($sumArea),array_sum($sumMD));?></td>
                  </tr>
                  <?php }?>
              </tbody>
            </table>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
  <!-- /.box-body -->
</div>
<!-- /.box -->
<script>
  $(function () {
    var table1 = $('#example1').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false,
        dom: 'Brtip',
        buttons: {
            dom: {
              button: {
                tag: 'button',
                className: ''
              }
            },
            buttons: [{
              extend: 'excel',
              className: 'btn btn-sm btn-success btn-rounded margin5',
              //title: 'SALES PERFORMANCE PER CHANNEL - JPI / KASS',
              text: '<span class="fa fa-file-excel"></span> Export to Excel',
              filename: 'excel-export',
              extension: '.xlsx'
            }]
        }
    })
    table1.buttons().containers().prependTo( '#exportOnsite' );
    var table2 = $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false,
        dom: 'Brtip',
        buttons: {
            dom: {
              button: {
                tag: 'button',
                className: ''
              }
            },
            buttons: [{
              extend: 'excel',
              className: 'btn btn-sm btn-success btn-rounded margin5',
              //title: 'SALES PERFORMANCE PER CHANNEL - JPI / KASS',
              text: '<span class="fa fa-file-excel"></span> Export to Excel',
              filename: 'excel-export',
              extension: '.xlsx'
            }]
        }
    })
    table2.buttons().containers().prependTo( '#exportOffsite' );
  })
</script>
