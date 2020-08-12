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

  // Per Product
  function getAmountProd($mdname,$lbacode,$product,$year){
		$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME) or die("Couldn't make connection.");
    $amount = 0.00;
    $amountQuery = $mysqli -> query("SELECT SUM(data_amount) as total FROM mdc_senior.senior_data as a INNER JOIN mdc_senior.mst_database as b ON a.data_branchcode=b.mst_branchcode
                                     WHERE data_doctor='$mdname' AND mst_lburebate='$lbacode' AND data_prodname='$product'
                                     AND data_year='$year'
                                     GROUP BY data_prodname");
      while($amountRes = $amountQuery -> fetch_assoc()){
        $amount = number_format((float)$amountRes['total'],2);
      }
    return $amount;
  }
  function getAmountTotalProd($lbacode,$product,$year){
		$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME) or die("Couldn't make connection.");
    $amount = 0.00;
    $amountQuery = $mysqli -> query("SELECT SUM(data_amount) as total FROM mdc_senior.senior_data as a INNER JOIN mdc_senior.mst_database as b ON a.data_branchcode=b.mst_branchcode
                                     WHERE mst_lburebate='$lbacode' AND data_prodname='$product'
                                     AND data_year='$year'
                                     GROUP BY data_prodname");
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
        <button type="button" class="btn btn-default btn-sm bg-gray" onclick="showResultYear();">By LBA</button>
        <button type="button" class="btn btn-default btn-sm bg-gray" onclick="showResultYearProd();">By Branch</button>
        <button type="button" class="btn btn-success btn-sm" onclick="showResultYearProd();">By Product</button>
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
            <span class="separator"></span>
            <input type="text" id="yearOnsite" class="form-control" style="display:inline-block;width:55%" placeholder="Search..."></input>
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
                      <th style="white-space: nowrap;text-align:center;" rowspan="2">LBA CODE / LBA NAME</th>
                      <th style="white-space: nowrap;text-align:center;" rowspan="2">PRODUCT NAME</th>
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
                    $lbaQuery = $mysqli -> query("SELECT DISTINCT lbu_code,lbu_area,(CASE WHEN data_year='2016' THEN SUM(data_amount) END) as data2016,
                                                            (CASE WHEN data_year='2017' THEN SUM(data_amount) END) as data2017,
                                                            (CASE WHEN data_year='2018' THEN SUM(data_amount) END) as data2018
                                                            FROM mdc_senior.senior_data as a INNER JOIN mdc_senior.lbu_rebatedb as b
                                                            ON b.lbu_code=a.data_lburebate
                                                            WHERE lbu_dname='$mdname'
                                                            AND lbu_year='2018' AND lbu_quarter='Q1'
                                                            GROUP BY data_year,data_lburebate,data_doctor");
                      while($lbaRes = $lbaQuery -> fetch_assoc()){
                        $sumMDLba = array();
                        $sumAreaLba = array();
                        $lbacode = $lbaRes['lbu_code'];
                        $lbaArray[] = $lbacode;
                  ?>
                  <tr style="font-weight:bold;background-color:#AED6F1 !important;">
                      <td style="white-space: nowrap;"><?php echo $lbacode.' ( '.$lbaRes['lbu_area'].' )';?></td>
                      <td style="white-space: nowrap;"></td>
                      <?php
                        for($i = $fyear; $i <= $tyear; $i++){
                          $totalMDLba = getAmountLba($mdname,$lbacode,$i);
                          $totalAreaLba = getAmountTotalLba($lbacode,$i);
                          $sumMDLba[] = str_replace(',','',$totalMDLba);
                          $sumAreaLba[] = $totalAreaLba;
                      ?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo $totalMDLba;?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalAreaLba,$totalMDLba);?></td>
                      <?php }?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo number_format((float)array_sum($sumMDLba),2);?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare(array_sum($sumAreaLba),array_sum($sumMDLba));?></td>
                  </tr>
                  <?php
                        // Query Branch
                        //$branchQuery = $mysqli -> query("SELECT DISTINCT data_prodname FROM mdc_senior.senior_data WHERE data_doctor='$mdname' AND data_lburebate='$lbacode'");
                        $branchQuery = $mysqli -> query("SELECT DISTINCT data_prodname,(CASE WHEN data_year='2016' THEN SUM(data_amount) END) as data2016,
                                                                (CASE WHEN data_year='2017' THEN SUM(data_amount) END) as data2017,
                                                                (CASE WHEN data_year='2018' THEN SUM(data_amount) END) as data2018 FROM mdc_senior.senior_data
                                                                WHERE `data_doctor`='$mdname' AND data_lburebate='$lbacode' GROUP BY data_year,data_prodname,data_doctor");
                          while($branchRes = $branchQuery -> fetch_assoc()){
                            $prodname = $branchRes['data_prodname'];
                            $sumMD = array();
                            $sumArea = array();
                  ?>
                  <tr>
                      <td style="white-space: nowrap;"></td>
                      <td style="white-space: nowrap;"><?php echo $branchRes['data_prodname'];?></td>
                      <?php
                        for($i = $fyear; $i <= $tyear; $i++){
                          $totalMD = $branchRes['data'.$i];
                          $totalArea = getAmountTotalProd($lbacode,$prodname,$i);
                          $sumMD[] = str_replace(',','',$totalMD);
                          $sumArea[] = $totalArea;
                      ?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo $totalMD;?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalArea,$totalMD);?></td>
                      <?php }?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo number_format((float)array_sum($sumMD),2);?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare(array_sum($sumArea),array_sum($sumMD));?></td>
                  </tr>
                  <?php }}?>
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
            <span class="separator"></span>
            <input type="text" id="yearOffsite" class="form-control" style="display:inline-block;width:55%" placeholder="Search..."></input>
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
                      <th style="white-space: nowrap;text-align:center;" rowspan="2">LBA CODE / LBA NAME</th>
                      <th style="white-space: nowrap;text-align:center;" rowspan="2">PRODUCT NAME</th>
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
                    $lbaQuery = $mysqli -> query("SELECT DISTINCT data_lburebate,(CASE WHEN data_year='2016' THEN SUM(data_amount) END) as data2016,
                                                            (CASE WHEN data_year='2017' THEN SUM(data_amount) END) as data2017,
                                                            (CASE WHEN data_year='2018' THEN SUM(data_amount) END) as data2018
                                                            FROM mdc_senior.senior_data
                                                            WHERE data_doctor='$mdname'
                                                            AND data_lburebate NOT IN('".implode("','",$lbaArray)."')
                                                            AND data_year='$tyear'
                                                            GROUP BY data_year,data_lburebate,data_doctor");
                      while($lbaRes = $lbaQuery -> fetch_assoc()){
                        $lbacode = $lbaRes['data_lburebate'];
                        $lbaArea = getLbaArea($lbacode);
                        $sumMDLba = array();
                        $sumAreaLba = array();
                  ?>
                  <tr style="font-weight:bold;background-color:#AED6F1 !important;">
                      <td style="white-space: nowrap;"><?php echo $lbacode.' ( '.$lbaArea.' )';?></td>
                      <td style="white-space: nowrap;"></td>
                      <?php
                        for($i = $fyear; $i <= $tyear; $i++){
                          $totalMDLba = $lbaRes['data'.$i];
                          $totalAreaLba = getAmountTotalLba($lbacode,$i);
                          $sumMDLba[] = str_replace(',','',$totalMDLba);
                          $sumAreaLba[] = $totalAreaLba;
                      ?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo $totalMDLba;?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalAreaLba,$totalMDLba);?></td>
                      <?php }?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo number_format((float)array_sum($sumMDLba),2);?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare(array_sum($sumAreaLba),array_sum($sumMDLba));?></td>
                  </tr>
                  <?php
                        // Query Branch
                        $branchQuery = $mysqli -> query("SELECT DISTINCT data_prodname,(CASE WHEN data_year='2016' THEN SUM(data_amount) END) as data2016,
                                                                (CASE WHEN data_year='2017' THEN SUM(data_amount) END) as data2017,
                                                                (CASE WHEN data_year='2018' THEN SUM(data_amount) END) as data2018 FROM mdc_senior.senior_data
                                                                WHERE `data_doctor`='$mdname' AND data_lburebate='$lbacode' GROUP BY data_year,data_prodname,data_doctor");
                          while($branchRes = $branchQuery -> fetch_assoc()){
                            $prodname = $branchRes['data_prodname'];
                            $sumMD = array();
                            $sumArea = array();
                  ?>
                  <tr>
                      <td style="white-space: nowrap;"></td>
                      <td style="white-space: nowrap;"><?php echo $branchRes['data_prodname'];?></td>
                      <?php
                        for($i = $fyear; $i <= $tyear; $i++){
                          $totalMD = $branchRes['data'.$i];
                          $totalArea = getAmountTotalProd($lbacode,$prodname,$i);
                          $sumMD[] = str_replace(',','',$totalMD);
                          $sumArea[] = $totalArea;
                      ?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo $totalMD;?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalArea,$totalMD);?></td>
                      <?php }?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo number_format((float)array_sum($sumMD),2);?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare(array_sum($sumArea),array_sum($sumMD));?></td>
                  </tr>
                  <?php }}?>
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
      'searching'   : true,
      'ordering'    : false,
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
    $('#yearOnsite').keyup(function(){
       table1.search( $(this).val() ).draw();
    });
    var table2 = $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : false,
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
    $('#yearOffsite').keyup(function(){
       table2.search( $(this).val() ).draw();
    });
  })
</script>
