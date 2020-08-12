<?php
  include('../../connection.php');
  $fmonth = date('n',strtotime($_GET['fmo']));
  $fyear = $_GET['fyear'];
  $tmonth = date('n',strtotime($_GET['tmo']));
  $tyear = $_GET['tyear'];
  $mdname = $_GET['md'];
  $lbucode = $_GET['lbucode'];

  // function
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
  function getAmountTotalLba($branchcode,$month,$year){
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
  function getLbaArea($branchcode){
		$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME) or die("Couldn't make connection.");
    $branch_area = "";
    $branchQuery = $mysqli -> query("SELECT DISTINCT lbu_area FROM mdc_senior.lbu_rebatedb WHERE lbu_code='$branchcode' ORDER BY ABS(lbu_year) LIMIT 1");
      while($branchRes = $branchQuery -> fetch_assoc()){
        $branch_area = $branchRes['lbu_area'];
      }
    return $branch_area;
  }
?>

            <div class="table-responsive">
            <?php
              // Same Year
              if($tyear == $fyear){
            ?>
            <table id="example3" class="table table-bordered table-striped" style="margin-top:0 !important;" width="100%">
              <thead style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                  <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
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
                    $branchQuery = $mysqli -> query("SELECT DISTINCT data_branchcode,mst_branchname FROM mdc_senior.senior_data as a INNER JOIN mdc_senior.mst_database as b ON a.data_branchcode=b.mst_branchcode
                      WHERE data_doctor='$mdname' AND mst_lburebate='$lbucode'");
                      while($branchRes = $branchQuery -> fetch_assoc()){
                        $branchcode = $branchRes['data_branchcode'];
                        $sumMD = array();
                        $sumArea = array();
                  ?>
                  <tr>
                      <td style="white-space: nowrap;"><a href="#" data-toggle="modal" data-target="#prodModal" data-branchcode="<?php echo $branchcode;?>" data-branchname="<?php echo $branchRes['mst_branchname'];?>"  onclick="showProdDetails(this)"><?php echo $branchRes['mst_branchname'];?></a></td>
                      <?php
                        for($i = $fmonth; $i <= $tmonth; $i++){
                           $totalMD = getAmountBranch($mdname,$branchcode,date('F', mktime(0, 0, 0, $i, 10)),$tyear);
                           $totalArea = getAmountTotalLba($branchcode,date('F', mktime(0, 0, 0, $i, 10)),$tyear);
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
            <?php }?>
            <?php
              // More than 2 years
              if($fyear < $tyear && ($tyear-$fyear)>=2){
            ?>
            <table id="example3" class="table table-bordered table-striped" style="margin-top:0 !important;" width="100%">
              <thead style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                  <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
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
                  $branchQuery = $mysqli -> query("SELECT DISTINCT data_branchcode,mst_branchname FROM mdc_senior.senior_data as a INNER JOIN mdc_senior.mst_database as b ON a.data_branchcode=b.mst_branchcode
                    WHERE data_doctor='$mdname' AND mst_lburebate='$lbucode'");
                    while($branchRes = $branchQuery -> fetch_assoc()){
                      $branchcode = $branchRes['data_branchcode'];
                      $sumMD = array();
                      $sumArea = array();
                  ?>
                  <tr>
                      <td style="white-space: nowrap;"><a href="#" data-toggle="modal" data-target="#prodModal" data-branchcode="<?php echo $branchcode;?>" data-branchname="<?php echo $branchRes['mst_branchname'];?>"  onclick="showProdDetails(this)"><?php echo $branchRes['mst_branchname'];?></a></td>
                      <?php
                        $month_start=$fmonth;
                        for($m=$month_start; $m<=12; $m++){
                          $month = date('F', mktime(0,0,0,$m,10));
                          $totalMD = getAmountBranch($mdname,$branchcode,$month,$fyear);
                          $totalArea = getAmountTotalLba($branchcode,$month,$fyear);
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
                            $totalArea = getAmountTotalLba($branchcode,$month_m,$year);
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
                        $totalArea = getAmountTotalLba($branchcode,$month_ends,$tyear);
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
            <?php }?>
            <?php
              // Between 2 Years
              if($fyear < $tyear && ($tyear-$fyear)==1){

            ?>
            <table id="example3" class="table table-bordered table-striped" style="margin-top:0 !important;" width="100%">
              <thead style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                  <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
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
                    $branchQuery = $mysqli -> query("SELECT DISTINCT data_branchcode,mst_branchname FROM mdc_senior.senior_data as a INNER JOIN mdc_senior.mst_database as b ON a.data_branchcode=b.mst_branchcode
                      WHERE data_doctor='$mdname' AND mst_lburebate='$lbucode'");
                      while($branchRes = $branchQuery -> fetch_assoc()){
                        $branchcode = $branchRes['data_branchcode'];
                        $sumMD = array();
                        $sumArea = array();
                  ?>
                  <tr>
                      <td style="white-space: nowrap;"><a href="#" data-toggle="modal" data-target="#prodModal" data-branchcode="<?php echo $branchcode;?>" data-branchname="<?php echo $branchRes['mst_branchname'];?>"  onclick="showProdDetails(this)"><?php echo $branchRes['mst_branchname'];?></a></td>
                      <?php
                        $month_start=$fmonth;
                        for($m=$month_start; $m<=12; $m++){
                          $month = date('F', mktime(0,0,0,$m,10));
                          $totalMD = getAmountBranch($mdname,$branchcode,$month,$fyear);
                          $totalArea = getAmountTotalLba($branchcode,$month,$fyear);
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
                        $totalArea = getAmountTotalLba($branchcode,$month_ends,$tyear);
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
            <?php }?>
            </div>

<script>
  $(function () {
    var table3 = $('#example3').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
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
              text: '<span class="fa fa-file-excel-o"></span> Export to Excel',
              filename: 'excel-export',
              extension: '.xlsx'
            }]
        }
    })
    table3.buttons().containers().prependTo( '#exportBranch' );
    $('#myInputTextFieldB').keyup(function(){
       table3.search( $(this).val() ).draw();
    });
    $('#example4').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>
