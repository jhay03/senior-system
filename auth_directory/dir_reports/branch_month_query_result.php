<?php
  include('../../connection.php');
  $fmonth = date('n',strtotime($_GET['fmo']));
  $fyear = $_GET['fyear'];
  $tmonth = date('n',strtotime($_GET['tmo']));
  $tyear = $_GET['tyear'];
  $mdname = $_GET['md'];



  // function
  function getAmountLba($mdname,$lbacode,$month,$year){
		$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME) or die("Couldn't make connection.");
    $amount = 0.00;
    $amountQuery = $mysqli -> query("SELECT SUM(data_amount) as total FROM mdc_senior.senior_data as a INNER JOIN mdc_senior.mst_database as b ON a.data_branchcode=b.mst_branchcode
                                     WHERE data_doctor='$mdname' AND mst_lburebate='$lbacode'
                                     AND data_year='$year' AND data_month='$month'
                                     GROUP BY mst_lburebate");
      while($amountRes = $amountQuery -> fetch_assoc()){
        $amount = number_format((float)$amountRes['total'],2);
      }
    return $amount;
  }
  function getAmountTotalLba($lbacode,$month,$year){
		$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME) or die("Couldn't make connection.");
    $amount = 0.00;
    $amountQuery = $mysqli -> query("SELECT SUM(data_amount) as total FROM mdc_senior.senior_data as a INNER JOIN mdc_senior.mst_database as b ON a.data_branchcode=b.mst_branchcode
                                     WHERE mst_lburebate='$lbacode'
                                     AND data_year='$year' AND data_month='$month'
                                     GROUP BY mst_lburebate");
      while($amountRes = $amountQuery -> fetch_assoc()){
        $amount = $amountRes['total'];
      }
    return $amount;
  }

  // Per BRANCH
  function getAmountBranch($mdname,$branchcode,$month,$year){
		$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME) or die("Couldn't make connection.");
    $amount = 0.00;
    $amountQuery = $mysqli -> query("SELECT SUM(data_amount) as total FROM mdc_senior.senior_data
                                     WHERE data_doctor='$mdname' AND data_branchcode='$branchcode'
                                     AND data_year='$year' AND data_month='$month'
                                     GROUP BY data_branchcode");
      while($amountRes = $amountQuery -> fetch_assoc()){
        $amount = number_format((float)$amountRes['total'],2);
      }
    return $amount;
  }
  function getAmountTotalBranch($branchcode,$month,$year){
		$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME) or die("Couldn't make connection.");
    $amount = 0.00;
    $amountQuery = $mysqli -> query("SELECT SUM(data_amount) as total FROM mdc_senior.senior_data
                                     WHERE data_branchcode='$branchcode'
                                     AND data_year='$year' AND data_month='$month'
                                     GROUP BY data_branchcode");
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
        <button type="button" class="btn btn-default btn-sm bg-gray" onclick="showResultMonth();">By LBA</button>
        <button type="button" class="btn btn-success btn-sm" onclick="showResultMonthBranch();">By Branch</button>
        <button type="button" class="btn btn-default btn-sm bg-gray" onclick="showResultMonthProd();">By Product</button>
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
            <input type="text" id="monthOnsite" class="form-control" style="display:inline-block;width:55%" placeholder="Search..."></input>
          </h4>
          <span style="display: inline-block;float:right;" id="exportOnsite"></span>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in">
          <div class="box-body no-padding" style="font-size:12px;">
            <div class="table-responsive">
            <?php
              // Same Year
              if($tyear == $fyear){
            ?>
            <table id="example1" class="table table-bordered table-striped" style="margin-top:0 !important;" width="100%">
              <thead style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                  <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                      <th style="white-space: nowrap;text-align:center;" rowspan="2">LBA CODE / LBA NAME</th>
                      <th style="white-space: nowrap;text-align:center;" rowspan="2">BRANCH NAME</th>
                      <?php
                        for($i = $fmonth; $i <= $tmonth; $i++){
                      ?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2"><?php echo strtoupper(date('M', mktime(0, 0, 0, $i, 10)))."-".$tyear;?></th>
                      <?php }?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2">TOTAL</th>
                  </tr>
                  <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                      <?php
                        for($i = $fmonth; $i <= $tmonth; $i++){
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
                        $sumMDLba = array();
                        $sumAreaLba = array();
                        $lbacode = $lbaRes['lbu_code'];
                        $lbaArray[] = $lbacode;
                  ?>

                  <tr style="font-weight:bold;background-color:#AED6F1 !important;">
                      <td style="white-space: nowrap;"><?php echo $lbacode.' ( '.$lbaRes['lbu_area'].' )';?></td>
                      <td style="white-space: nowrap;"></td>
                      <?php
                        for($i = $fmonth; $i <= $tmonth; $i++){
                          $totalMDLba = getAmountLba($mdname,$lbacode,date('F', mktime(0, 0, 0, $i, 10)),$tyear);
                          $totalAreaLba = getAmountTotalLba($lbacode,date('F', mktime(0, 0, 0, $i, 10)),$tyear);
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
                        $branchQuery = $mysqli -> query("SELECT DISTINCT data_branchcode,mst_branchname FROM mdc_senior.senior_data as a INNER JOIN mdc_senior.mst_database as b ON a.data_branchcode=b.mst_branchcode
                          WHERE data_doctor='$mdname' AND mst_lburebate='$lbacode' GROUP BY data_branchcode");
                          while($branchRes = $branchQuery -> fetch_assoc()){
                            $branchcode = $branchRes['data_branchcode'];
                            $sumMD = array();
                            $sumArea = array();
                  ?>
                  <tr>
                      <td style="white-space: nowrap;"></td>
                      <td style="white-space: nowrap;"><a href="#" data-toggle="modal" data-target="#prodModal" data-branchcode="<?php echo $branchcode;?>" data-branchname="<?php echo $branchRes['mst_branchname'];?>"  onclick="showProdDetails(this)"><?php echo $branchRes['mst_branchname'];?></a></td>
                      <?php
                        for($i = $fmonth; $i <= $tmonth; $i++){
                          $totalMD = getAmountBranch($mdname,$branchcode,date('F', mktime(0, 0, 0, $i, 10)),$tyear);
                          $totalArea = getAmountTotalBranch($branchcode,date('F', mktime(0, 0, 0, $i, 10)),$tyear);
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
            <?php }?>
            <?php
              // More than 2 years
              if($fyear < $tyear && ($tyear-$fyear)>=2){
            ?>
            <table id="example1" class="table table-bordered table-striped" style="margin-top:0 !important;" width="100%">
              <thead style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                  <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                      <th style="white-space: nowrap;text-align:center;" rowspan="2">LBA CODE / LBA NAME</th>
                      <th style="white-space: nowrap;text-align:center;" rowspan="2">BRANCH NAME</th>
                      <?php
                         $month_start=$fmonth;
                         for($m=$month_start; $m<=12; $m++){
                           $month = date('M', mktime(0,0,0,$m,10));
                      ?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2"><?php echo strtoupper($month)."-".$fyear;?></th>
                      <?php }?>
                      <?php
                         $mid_s = $fyear+1;
                         $mid_e = $tyear-1;
                         for($year=$mid_s;$year<=$mid_e;$year++){
                           for($m=1; $m<=12; $m++){
                             $month_m=date('M', mktime(0,0,0,$m,10));
                      ?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2"><?php echo strtoupper($month_m)."-".$year;?></th>
                      <?php }}?>
                      <?php
                         $month_end=$tmonth;
                         for($m=1; $m<=$month_end; $m++){
                         $month_ends=date('M', mktime(0,0,0,$m,10));
                      ?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2"><?php echo strtoupper($month_ends)."-".$tyear;?></th>
                      <?php }?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2">TOTAL</th>
                  </tr>
                  <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                      <?php
                        $month_start=$fmonth;
                        for($m=$month_start; $m<=12; $m++){
                          $month = date('M', mktime(0,0,0,$m,10));
                      ?>
                      <th style="white-space: nowrap;text-align:center;">AMOUNT</th>
                      <th style="white-space: nowrap;text-align:center;">% SHARE</th>
                      <?php }?>
                      <?php
                        $mid_s = $fyear+1;
                        $mid_e = $tyear-1;
                        for($year=$mid_s;$year<=$mid_e;$year++){
                          for($m=1; $m<=12; $m++){
                            $month_m=date('F', mktime(0,0,0,$m,10));
                      ?>
                      <th style="white-space: nowrap;text-align:center;">AMOUNT</th>
                      <th style="white-space: nowrap;text-align:center;">% SHARE</th>
                      <?php }}?>
                      <?php
                        $month_end=$tmonth;
                        for($m=1; $m<=$month_end; $m++){
                        $month_ends=date('F', mktime(0,0,0,$m,10));
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
                        $sumMDLba = array();
                        $sumAreaLba = array();
                        $lbacode = $lbaRes['lbu_code'];
                        $lbaArray[] = $lbacode;
                  ?>
                  <tr style="font-weight:bold;background-color:#AED6F1 !important;">
                      <td style="white-space: nowrap;"><?php echo $lbacode.' ( '.$lbaRes['lbu_area'].' )';?></td>
                      <td style="white-space: nowrap;"></td>
                      <?php
                        $month_start=$fmonth;
                        for($m=$month_start; $m<=12; $m++){
                          $month = date('F', mktime(0,0,0,$m,10));
                          $totalMDLba = getAmountLba($mdname,$lbacode,date('F', mktime(0, 0, 0, $m, 10)),$fyear);
                          $totalAreaLba = getAmountTotalLba($lbacode,date('F', mktime(0, 0, 0, $m, 10)),$fyear);
                          $sumMDLba[] = str_replace(',','',$totalMDLba);
                          $sumAreaLba[] = $totalAreaLba;
                      ?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo $totalMDLba;?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalAreaLba,$totalMDLba);?></td>
                      <?php }?>
                      <?php
                        $mid_s = $fyear+1;
                        $mid_e = $tyear-1;
                        for($year=$mid_s;$year<=$mid_e;$year++){
                          for($m=1; $m<=12; $m++){
                            $month_m=date('F', mktime(0,0,0,$m,10));
                            $totalMDLba = getAmountLba($mdname,$lbacode,date('F', mktime(0, 0, 0, $m, 10)),$year);
                            $totalAreaLba = getAmountTotalLba($lbacode,date('F', mktime(0, 0, 0, $m, 10)),$year);
                            $sumMDLba[] = str_replace(',','',$totalMDLba);
                            $sumAreaLba[] = $totalAreaLba;
                      ?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo $totalMDLba;?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalAreaLba,$totalMDLba);?></td>
                      <?php }}?>
                      <?php
                        $month_end=$tmonth;
                        for($m=1; $m<=$month_end; $m++){
                        $month_ends=date('F', mktime(0,0,0,$m,10));
                        $totalMDLba = getAmountLba($mdname,$lbacode,date('F', mktime(0, 0, 0, $m, 10)),$tyear);
                        $totalAreaLba = getAmountTotalLba($lbacode,date('F', mktime(0, 0, 0, $m, 10)),$tyear);
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
                        $branchQuery = $mysqli -> query("SELECT DISTINCT data_branchcode,mst_branchname FROM mdc_senior.senior_data as a INNER JOIN mdc_senior.mst_database as b ON a.data_branchcode=b.mst_branchcode
                          WHERE data_doctor='$mdname' AND mst_lburebate='$lbacode' GROUP BY data_branchcode");
                          while($branchRes = $branchQuery -> fetch_assoc()){
                            $branchcode = $branchRes['data_branchcode'];
                            $sumMD = array();
                            $sumArea = array();
                  ?>
                  <tr>
                      <td style="white-space: nowrap;"></td>
                      <td style="white-space: nowrap;"><a href="#" data-toggle="modal" data-target="#prodModal" data-branchcode="<?php echo $branchcode;?>" data-branchname="<?php echo $branchRes['mst_branchname'];?>"  onclick="showProdDetails(this)"><?php echo $branchRes['mst_branchname'];?></a></td>
                      <?php
                        $month_start=$fmonth;
                        for($m=$month_start; $m<=12; $m++){
                          $month = date('F', mktime(0,0,0,$m,10));
                          $totalMD = getAmountBranch($mdname,$branchcode,$month,$fyear);
                          $totalArea = getAmountTotalBranch($branchcode,$month,$fyear);
                          $sumMD[] = str_replace(',','',$totalMD);
                          $sumArea[] = $totalArea;
                      ?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo $totalMD;?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalArea,$totalMD);?></td>
                      <?php }?>
                      <?php
                        $mid_s = $fyear+1;
                        $mid_e = $tyear-1;
                        for($year=$mid_s;$year<=$mid_e;$year++){
                          for($m=1; $m<=12; $m++){
                            $month_m=date('F', mktime(0,0,0,$m,10));
                            $totalMD = getAmountBranch($mdname,$branchcode,$month_m,$year);
                            $totalArea = getAmountTotalBranch($branchcode,$month_m,$year);
                            $sumMD[] = str_replace(',','',$totalMD);
                            $sumArea[] = $totalArea;
                      ?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo $totalMD;?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalArea,$totalMD);?></td>
                      <?php }}?>
                      <?php
                        $month_end=$tmonth;
                        for($m=1; $m<=$month_end; $m++){
                        $month_ends=date('F', mktime(0,0,0,$m,10));
                        $totalMD = getAmountBranch($mdname,$branchcode,$month_ends,$tyear);
                        $totalArea = getAmountTotalBranch($branchcode,$month_ends,$tyear);
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
            <?php }?>
            <?php
              // Between 2 Years
              if($fyear < $tyear && ($tyear-$fyear)==1){

            ?>
            <table id="example1" class="table table-bordered table-striped" style="margin-top:0 !important;" width="100%">
              <thead style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                  <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                      <th style="white-space: nowrap;text-align:center;" rowspan="2">LBA CODE / LBA NAME</th>
                      <th style="white-space: nowrap;text-align:center;" rowspan="2">BRANCH NAME</th>
                      <?php
                         $month_start=$fmonth;
                         for($m=$month_start; $m<=12; $m++){
                           $month = date('M', mktime(0,0,0,$m,10));
                      ?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2"><?php echo strtoupper($month)."-".$fyear;?></th>
                      <?php }?>
                      <?php
                         $month_end=$tmonth;
                         for($m=1; $m<=$month_end; $m++){
                         $month_ends=date('M', mktime(0,0,0,$m,10));
                      ?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2"><?php echo strtoupper($month_ends)."-".$tyear;?></th>
                      <?php }?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2">TOTAL</th>
                  </tr>
                  <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                      <?php
                        $month_start=$fmonth;
                        for($m=$month_start; $m<=12; $m++){
                          $month = date('M', mktime(0,0,0,$m,10));
                      ?>
                      <th style="white-space: nowrap;text-align:center;">AMOUNT</th>
                      <th style="white-space: nowrap;text-align:center;">% SHARE</th>
                      <?php }?>
                      <?php
                        $month_end=$tmonth;
                        for($m=1; $m<=$month_end; $m++){
                        $month_ends=date('F', mktime(0,0,0,$m,10));
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
                        $sumMDLba = array();
                        $sumAreaLba = array();
                        $lbacode = $lbaRes['lbu_code'];
                        $lbaArray[] = $lbacode;
                  ?>
                  <tr style="font-weight:bold;background-color:#AED6F1 !important;">
                      <td style="white-space: nowrap;"><?php echo $lbacode.' ( '.$lbaRes['lbu_area'].' )';?></td>
                      <td style="white-space: nowrap;"></td>
                      <?php
                        $month_start=$fmonth;
                        for($m=$month_start; $m<=12; $m++){
                          $month = date('F', mktime(0,0,0,$m,10));
                          $totalMDLba = getAmountLba($mdname,$lbacode,date('F', mktime(0, 0, 0, $m, 10)),$fyear);
                          $totalAreaLba = getAmountTotalLba($lbacode,date('F', mktime(0, 0, 0, $m, 10)),$fyear);
                          $sumMDLba[] = str_replace(',','',$totalMDLba);
                          $sumAreaLba[] = $totalAreaLba;
                      ?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo $totalMDLba;?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalAreaLba,$totalMDLba);?></td>
                      <?php }?>
                      <?php
                        $month_end=$tmonth;
                        for($m=1; $m<=$month_end; $m++){
                        $month_ends=date('F', mktime(0,0,0,$m,10));
                        $totalMDLba = getAmountLba($mdname,$lbacode,date('F', mktime(0, 0, 0, $m, 10)),$tyear);
                        $totalAreaLba = getAmountTotalLba($lbacode,date('F', mktime(0, 0, 0, $m, 10)),$tyear);
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
                        $branchQuery = $mysqli -> query("SELECT DISTINCT data_branchcode,mst_branchname FROM mdc_senior.senior_data as a INNER JOIN mdc_senior.mst_database as b ON a.data_branchcode=b.mst_branchcode
                          WHERE data_doctor='$mdname' AND mst_lburebate='$lbacode' GROUP BY data_branchcode");
                          while($branchRes = $branchQuery -> fetch_assoc()){
                            $branchcode = $branchRes['data_branchcode'];
                            $sumMD = array();
                            $sumArea = array();
                  ?>
                  <tr>
                      <td style="white-space: nowrap;"></td>
                      <td style="white-space: nowrap;"><a href="#" data-toggle="modal" data-target="#prodModal" data-branchcode="<?php echo $branchcode;?>" data-branchname="<?php echo $branchRes['mst_branchname'];?>"  onclick="showProdDetails(this)"><?php echo $branchRes['mst_branchname'];?></a></td>
                      <?php
                        $month_start=$fmonth;
                        for($m=$month_start; $m<=12; $m++){
                          $month = date('F', mktime(0,0,0,$m,10));
                          $totalMD = getAmountBranch($mdname,$branchcode,$month,$fyear);
                          $totalArea = getAmountTotalBranch($branchcode,$month,$fyear);
                          $sumMD[] = str_replace(',','',$totalMD);
                          $sumArea[] = $totalArea;
                      ?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo $totalMD;?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalArea,$totalMD);?></td>
                      <?php }?>
                      <?php
                        $month_end=$tmonth;
                        for($m=1; $m<=$month_end; $m++){
                        $month_ends=date('F', mktime(0,0,0,$m,10));
                        $totalMD = getAmountBranch($mdname,$branchcode,$month_ends,$tyear);
                        $totalArea = getAmountTotalBranch($branchcode,$month_ends,$tyear);
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
            <?php }?>
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
            <input type="text" id="monthOffsite" class="form-control" style="display:inline-block;width:55%" placeholder="Search..."></input>
          </h4>
          <span style="display: inline-block;float:right;" id="exportOffsite"></span>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse">
          <div class="box-body no-padding" style="font-size:13px;">

            <div class="table-responsive">
            <?php
              // Same Year
              if($tyear == $fyear){
            ?>
            <table id="example2" class="table table-bordered table-striped" style="margin-top:0 !important;" width="100%" >
              <thead style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                  <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                      <th style="white-space: nowrap;text-align:center;" rowspan="2">LBA CODE / LBA NAME</th>
                      <th style="white-space: nowrap;text-align:center;" rowspan="2">BRANCH NAME</th>
                      <?php
                        for($i = $fmonth; $i <= $tmonth; $i++){
                      ?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2"><?php echo strtoupper(date('M', mktime(0, 0, 0, $i, 10)))."-".$tyear;?></th>
                      <?php }?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2">TOTAL</th>
                  </tr>
                  <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                      <?php
                        for($i = $fmonth; $i <= $tmonth; $i++){
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
                    $num = 1;
                    $lbaQuery = $mysqli -> query("SELECT DISTINCT data_lburebate FROM mdc_senior.senior_data WHERE data_doctor='$mdname' AND data_lburebate NOT IN('".implode("','",$lbaArray)."') AND data_year='$tyear' GROUP BY data_lburebate");
                      while($lbaRes = $lbaQuery -> fetch_assoc()){
                        $sumMDLba = array();
                        $sumAreaLba = array();
                        $lbacode = $lbaRes['data_lburebate'];
                        $lbaArea = getLbaArea($lbacode);
                  ?>
                  <tr style="font-weight:bold;background-color:#AED6F1 !important;">
                      <td style="white-space: nowrap;"><?php echo $lbaRes['data_lburebate'].' ( '.$lbaArea.' )';?></td>
                      <td style="white-space: nowrap;"></td>
                      <?php
                        for($i = $fmonth; $i <= $tmonth; $i++){
                          $totalMDLba = getAmountLba($mdname,$lbacode,date('F', mktime(0, 0, 0, $i, 10)),$tyear);
                          $totalAreaLba = getAmountTotalLba($lbacode,date('F', mktime(0, 0, 0, $i, 10)),$tyear);
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
                        $branchQuery = $mysqli -> query("SELECT DISTINCT data_branchcode,mst_branchname FROM mdc_senior.senior_data as a INNER JOIN mdc_senior.mst_database as b ON a.data_branchcode=b.mst_branchcode
                          WHERE data_doctor='$mdname' AND mst_lburebate='$lbacode' GROUP BY data_branchcode");
                          while($branchRes = $branchQuery -> fetch_assoc()){
                            $branchcode = $branchRes['data_branchcode'];
                            $sumMD = array();
                            $sumArea = array();
                  ?>
                  <tr>
                      <td style="white-space: nowrap;"></td>
                      <td style="white-space: nowrap;"><a href="#" data-toggle="modal" data-target="#prodModal" data-branchcode="<?php echo $branchcode;?>" data-branchname="<?php echo $branchRes['mst_branchname'];?>"  onclick="showProdDetails(this)"><?php echo $branchRes['mst_branchname'];?></a></td>
                      <?php
                        for($i = $fmonth; $i <= $tmonth; $i++){
                          $totalMD = getAmountBranch($mdname,$branchcode,date('F', mktime(0, 0, 0, $i, 10)),$tyear);
                          $totalArea = getAmountTotalBranch($branchcode,date('F', mktime(0, 0, 0, $i, 10)),$tyear);
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
            <?php }?>

            <?php
              // More than 2 years
              if($fyear < $tyear && ($tyear-$fyear)>=2){
            ?>
            <table id="example2" class="table table-bordered table-striped" style="margin-top:0 !important;" width="100%">
              <thead style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                  <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                      <th style="white-space: nowrap;text-align:center;" rowspan="2">LBA CODE / LBA NAME</th>
                      <th style="white-space: nowrap;text-align:center;" rowspan="2">BRANCH NAME</th>
                      <?php
                         $month_start=$fmonth;
                         for($m=$month_start; $m<=12; $m++){
                           $month = date('M', mktime(0,0,0,$m,10));
                      ?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2"><?php echo strtoupper($month)."-".$fyear;?></th>
                      <?php }?>
                      <?php
                         $mid_s = $fyear+1;
                         $mid_e = $tyear-1;
                         for($year=$mid_s;$year<=$mid_e;$year++){
                           for($m=1; $m<=12; $m++){
                             $month_m=date('M', mktime(0,0,0,$m,10));
                      ?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2"><?php echo strtoupper($month_m)."-".$year;?></th>
                      <?php }}?>
                      <?php
                         $month_end=$tmonth;
                         for($m=1; $m<=$month_end; $m++){
                         $month_ends=date('M', mktime(0,0,0,$m,10));
                      ?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2"><?php echo strtoupper($month_ends)."-".$tyear;?></th>
                      <?php }?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2">TOTAL</th>
                  </tr>
                  <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                      <?php
                        $month_start=$fmonth;
                        for($m=$month_start; $m<=12; $m++){
                          $month = date('M', mktime(0,0,0,$m,10));
                      ?>
                      <th style="white-space: nowrap;text-align:center;">AMOUNT</th>
                      <th style="white-space: nowrap;text-align:center;">% SHARE</th>
                      <?php }?>
                      <?php
                        $mid_s = $fyear+1;
                        $mid_e = $tyear-1;
                        $yearArray = array();
                        for($year=$mid_s;$year<=$mid_e;$year++){
                          $yearArray[] = $year;
                          for($m=1; $m<=12; $m++){
                            $month_m=date('F', mktime(0,0,0,$m,10));
                      ?>
                      <th style="white-space: nowrap;text-align:center;">AMOUNT</th>
                      <th style="white-space: nowrap;text-align:center;">% SHARE</th>
                      <?php }}?>
                      <?php
                        $month_end=$tmonth;
                        for($m=1; $m<=$month_end; $m++){
                        $month_ends=date('F', mktime(0,0,0,$m,10));
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
                    $lbaQuery = $mysqli -> query("SELECT DISTINCT data_lburebate FROM mdc_senior.senior_data WHERE data_doctor='$mdname' AND data_lburebate NOT IN('".implode("','",$lbaArray)."') AND data_year IN('".implode(',',$yearArray)."') GROUP BY data_lburebate");
                      while($lbaRes = $lbaQuery -> fetch_assoc()){
                        $sumMDLba = array();
                        $sumAreaLba = array();
                        $lbacode = $lbaRes['data_lburebate'];
                        $lbaArea = getLbaArea($lbacode);
                  ?>
                  <tr style="font-weight:bold;background-color:#AED6F1 !important;">
                      <td style="white-space: nowrap;"><?php echo $lbacode.' ( '.$lbaArea.' )';?></td>
                      <td style="white-space: nowrap;"></td>
                      <?php
                        $month_start=$fmonth;
                        for($m=$month_start; $m<=12; $m++){
                          $month = date('F', mktime(0,0,0,$m,10));
                          $totalMDLba = getAmountLba($mdname,$lbacode,date('F', mktime(0, 0, 0, $m, 10)),$fyear);
                          $totalAreaLba = getAmountTotalLba($lbacode,date('F', mktime(0, 0, 0, $m, 10)),$fyear);
                          $sumMDLba[] = str_replace(',','',$totalMDLba);
                          $sumAreaLba[] = $totalAreaLba;
                      ?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo $totalMDLba;?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalAreaLba,$totalMDLba);?></td>
                      <?php }?>
                      <?php
                        $mid_s = $fyear+1;
                        $mid_e = $tyear-1;
                        for($year=$mid_s;$year<=$mid_e;$year++){
                          for($m=1; $m<=12; $m++){
                            $month_m = date('F', mktime(0,0,0,$m,10));
                            $totalMDLba = getAmountLba($mdname,$lbacode,date('F', mktime(0, 0, 0, $m, 10)),$year);
                            $totalAreaLba = getAmountTotalLba($lbacode,date('F', mktime(0, 0, 0, $m, 10)),$year);
                            $sumMDLba[] = str_replace(',','',$totalMDLba);
                            $sumAreaLba[] = $totalAreaLba;
                      ?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo $totalMDLba;?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalAreaLba,$totalMDLba);?></td>
                      <?php }}?>
                      <?php
                        $month_end=$tmonth;
                        for($m=1; $m<=$month_end; $m++){
                        $month_ends=date('F', mktime(0,0,0,$m,10));
                        $totalMDLba = getAmountLba($mdname,$lbacode,date('F', mktime(0, 0, 0, $m, 10)),$tyear);
                        $totalAreaLba = getAmountTotalLba($lbacode,date('F', mktime(0, 0, 0, $m, 10)),$tyear);
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
                        $branchQuery = $mysqli -> query("SELECT DISTINCT data_branchcode,mst_branchname FROM mdc_senior.senior_data as a INNER JOIN mdc_senior.mst_database as b ON a.data_branchcode=b.mst_branchcode
                          WHERE data_doctor='$mdname' AND mst_lburebate='$lbacode' GROUP BY data_branchcode");
                          while($branchRes = $branchQuery -> fetch_assoc()){
                            $branchcode = $branchRes['data_branchcode'];
                            $sumMD = array();
                            $sumArea = array();
                  ?>
                  <tr>
                      <td style="white-space: nowrap;"></td>
                      <td style="white-space: nowrap;"><a href="#" data-toggle="modal" data-target="#prodModal" data-branchcode="<?php echo $branchcode;?>" data-branchname="<?php echo $branchRes['mst_branchname'];?>"  onclick="showProdDetails(this)"><?php echo $branchRes['mst_branchname'];?></a></td>
                      <?php
                        $month_start=$fmonth;
                        for($m=$month_start; $m<=12; $m++){
                          $month = date('F', mktime(0,0,0,$m,10));
                          $totalMD = getAmountBranch($mdname,$branchcode,$month,$fyear);
                          $totalArea = getAmountTotalBranch($branchcode,$month,$fyear);
                          $sumMD[] = str_replace(',','',$totalMD);
                          $sumArea[] = $totalArea;
                      ?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo $totalMD;?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalArea,$totalMD);?></td>
                      <?php }?>
                      <?php
                        $mid_s = $fyear+1;
                        $mid_e = $tyear-1;
                        for($year=$mid_s;$year<=$mid_e;$year++){
                          for($m=1; $m<=12; $m++){
                            $month_m = date('F', mktime(0,0,0,$m,10));
                            $totalMD = getAmountBranch($mdname,$branchcode,$month_m,$year);
                            $totalArea = getAmountTotalBranch($branchcode,$month_m,$year);
                            $sumMD[] = str_replace(',','',$totalMD);
                            $sumArea[] = $totalArea;
                      ?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo $totalMD;?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalArea,$totalMD);?></td>
                      <?php }}?>
                      <?php
                        $month_end=$tmonth;
                        for($m=1; $m<=$month_end; $m++){
                        $month_ends=date('F', mktime(0,0,0,$m,10));
                        $totalMD = getAmountBranch($mdname,$branchcode,$month_ends,$tyear);
                        $totalArea = getAmountTotalBranch($branchcode,$month_ends,$tyear);
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
            <?php }?>

            <?php
              // Between 2 years
              if($fyear < $tyear && ($tyear-$fyear)==1){
            ?>
            <table id="example2" class="table table-bordered table-striped" style="margin-top:0 !important;" width="100%">
              <thead style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                  <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                      <th style="white-space: nowrap;text-align:center;" rowspan="2">LBA CODE / LBA NAME</th>
                      <th style="white-space: nowrap;text-align:center;" rowspan="2">BRANCH NAME</th>
                      <?php
                         $month_start=$fmonth;
                         for($m=$month_start; $m<=12; $m++){
                           $month = date('M', mktime(0,0,0,$m,10));
                      ?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2"><?php echo strtoupper($month)."-".$fyear;?></th>
                      <?php }?>
                      <?php
                         $month_end=$tmonth;
                         for($m=1; $m<=$month_end; $m++){
                         $month_ends=date('M', mktime(0,0,0,$m,10));
                      ?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2"><?php echo strtoupper($month_ends)."-".$tyear;?></th>
                      <?php }?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2">TOTAL</th>
                  </tr>
                  <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                      <?php
                        $month_start=$fmonth;
                        for($m=$month_start; $m<=12; $m++){
                          $month = date('M', mktime(0,0,0,$m,10));
                      ?>
                      <th style="white-space: nowrap;text-align:center;">AMOUNT</th>
                      <th style="white-space: nowrap;text-align:center;">% SHARE</th>
                      <th style="white-space: nowrap;text-align:center;">AMOUNT</th>
                      <th style="white-space: nowrap;text-align:center;">% SHARE</th>
                      <?php }?>
                      <?php
                        $month_end=$tmonth;
                        for($m=1; $m<=$month_end; $m++){
                        $month_ends=date('F', mktime(0,0,0,$m,10));
                      ?>
                      <th style="white-space: nowrap;text-align:center;">AMOUNT</th>
                      <th style="white-space: nowrap;text-align:center;">% SHARE</th>
                      <?php }?>
                  </tr>
              </thead>
              <tbody>
                  <?php
                    $lbaQuery = $mysqli -> query("SELECT DISTINCT data_lburebate FROM mdc_senior.senior_data WHERE data_doctor='$mdname' AND data_lburebate NOT IN('".implode("','",$lbaArray)."') AND data_year IN('$fyear','$tyear') GROUP BY data_lburebate");
                      while($lbaRes = $lbaQuery -> fetch_assoc()){
                        $sumMDLba = array();
                        $sumAreaLba = array();
                        $lbacode = $lbaRes['data_lburebate'];
                        $lbaArea = getLbaArea($lbacode);

                  ?>
                  <tr style="font-weight:bold;background-color:#AED6F1 !important;">
                      <td style="white-space: nowrap;"><?php echo $lbacode.' ( '.$lbaArea.' )';?></td>
                      <td style="white-space: nowrap;"></td>
                      <?php
                        $month_start=$fmonth;
                        for($m=$month_start; $m<=12; $m++){
                          $month = date('F', mktime(0,0,0,$m,10));
                          $totalMDLba = getAmountLba($mdname,$lbacode,date('F', mktime(0, 0, 0, $m, 10)),$fyear);
                          $totalAreaLba = getAmountTotalLba($lbacode,date('F', mktime(0, 0, 0, $m, 10)),$fyear);
                          $sumMDLba[] = str_replace(',','',$totalMDLba);
                          $sumAreaLba[] = $totalAreaLba;
                      ?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo $totalMDLba;?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalAreaLba,$totalMDLba);?></td>
                      <?php }?>
                      <?php
                        $month_end=$tmonth;
                        for($m=1; $m<=$month_end; $m++){
                        $month_ends=date('F', mktime(0,0,0,$m,10));
                        $totalMDLba = getAmountLba($mdname,$lbacode,date('F', mktime(0, 0, 0, $m, 10)),$tyear);
                        $totalAreaLba = getAmountTotalLba($lbacode,date('F', mktime(0, 0, 0, $m, 10)),$tyear);
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
                        $branchQuery = $mysqli -> query("SELECT DISTINCT data_branchcode,mst_branchname FROM mdc_senior.senior_data as a INNER JOIN mdc_senior.mst_database as b ON a.data_branchcode=b.mst_branchcode
                          WHERE data_doctor='$mdname' AND mst_lburebate='$lbacode' GROUP BY data_branchcode");
                          while($branchRes = $branchQuery -> fetch_assoc()){
                            $branchcode = $branchRes['data_branchcode'];
                            $sumMD = array();
                            $sumArea = array();
                  ?>
                  <tr>
                      <td style="white-space: nowrap;"></td>
                      <td style="white-space: nowrap;"><a href="#" data-toggle="modal" data-target="#prodModal" data-branchcode="<?php echo $branchcode;?>" data-branchname="<?php echo $branchRes['mst_branchname'];?>"  onclick="showProdDetails(this)"><?php echo $branchRes['mst_branchname'];?></a></td>
                      <?php
                        $month_start=$fmonth;
                        for($m=$month_start; $m<=12; $m++){
                          $month = date('F', mktime(0,0,0,$m,10));
                          $totalMD = getAmountBranch($mdname,$branchcode,$month,$fyear);
                          $totalArea = getAmountTotalBranch($branchcode,$month,$fyear);
                          $sumMD[] = str_replace(',','',$totalMD);
                          $sumArea[] = $totalArea;
                      ?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo $totalMD;?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalArea,$totalMD);?></td>
                      <?php }?>
                      <?php
                        $month_end=$tmonth;
                        for($m=1; $m<=$month_end; $m++){
                        $month_ends=date('F', mktime(0,0,0,$m,10));
                        $totalMD = getAmountBranch($mdname,$branchcode,$month_ends,$tyear);
                        $totalArea = getAmountTotalBranch($branchcode,$month_ends,$tyear);
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
            <?php }?>
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
    $('#monthOnsite').keyup(function(){
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
    $('#monthOffsite').keyup(function(){
       table2.search( $(this).val() ).draw();
    });
  })
</script>
