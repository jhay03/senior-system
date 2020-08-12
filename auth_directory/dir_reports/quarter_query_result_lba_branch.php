<?php
  include('../../connection.php');
  $fquarter = str_replace('Q','',$_GET['fquarter']);
  $fyear = $_GET['fyear'];
  $tquarter = str_replace('Q','',$_GET['tquarter']);
  $tyear = $_GET['tyear'];
  $mdname = $_GET['md'];
  $lbucode = $_GET['lbucode'];

  // function
  function getAmountBranch($mdname,$branchcode,$quarter,$year){
		$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME) or die("Couldn't make connection.");
    $amount = 0.00;
    $amountQuery = $mysqli -> query("SELECT SUM(data_amount) as total FROM mdc_senior.senior_data
                                     WHERE data_doctor='$mdname' AND data_branchcode='$branchcode'
                                     AND data_year='$year' AND data_quarter='$quarter'
                                     GROUP BY data_branchcode");
      while($amountRes = $amountQuery -> fetch_assoc()){
        $amount = number_format((float)$amountRes['total'],2);
      }
    return $amount;
  }
  function getAmountTotalLba($branchcode,$quarter,$year){
		$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME) or die("Couldn't make connection.");
    $amount = 0.00;
    $amountQuery = $mysqli -> query("SELECT SUM(data_amount) as total FROM mdc_senior.senior_data
                                     WHERE data_branchcode='$branchcode'
                                     AND data_year='$year' AND data_quarter='$quarter'
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
                        for($i = $fquarter; $i <= $tquarter; $i++){
                      ?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2"><?php echo strtoupper('Q'.$i)."-".$tyear;?></th>
                      <?php }?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2">TOTAL</th>
                  </tr>
                  <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                      <?php
                        for($i = $fquarter; $i <= $tquarter; $i++){
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
                      <td style="white-space: nowrap;"><a href="#" data-toggle="modal" data-target="#prodModal" data-branchcode="<?php echo $branchcode;?>" data-branchname="<?php echo $branchRes['mst_branchname'];?>"  onclick="showQuarterProdDetails(this)"><?php echo $branchRes['mst_branchname'];?></a></td>
                      <?php
                        for($i = $fquarter; $i <= $tquarter; $i++){
                           $totalMD = getAmountBranch($mdname,$branchcode,'Q'.$i,$tyear);
                           $totalArea = getAmountTotalLba($branchcode,'Q'.$i,$tyear);
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
                         $quarter_start=$fquarter;
                         for($m=$quarter_start; $m<=4; $m++){
                           $quarter = 'Q'.$m;
                      ?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2"><?php echo strtoupper($quarter)."-".$fyear;?></th>
                      <?php }?>
                      <?php
                         $mid_s = $fyear+1;
                         $mid_e = $tyear-1;
                         for($year=$mid_s;$year<=$mid_e;$year++){
                           for($m=1; $m<=4; $m++){
                             $quarter_m='Q'.$m;
                      ?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2"><?php echo strtoupper($quarter_m)."-".$year;?></th>
                      <?php }}?>
                      <?php
                         $quarter_end=$tquarter;
                         for($m=1; $m<=$quarter_end; $m++){
                         $quarter_ends='Q'.$m;
                      ?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2"><?php echo strtoupper($quarter_ends)."-".$tyear;?></th>
                      <?php }?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2">TOTAL</th>
                  </tr>
                  <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                      <?php
                        $quarter_start=$fquarter;
                        for($m=$quarter_start; $m<=4; $m++){
                          $quarter = 'Q'.$m;
                      ?>
                      <th style="white-space: nowrap;text-align:center;">AMOUNT</th>
                      <th style="white-space: nowrap;text-align:center;">% SHARE</th>
                      <?php }?>
                      <?php
                        $mid_s = $fyear+1;
                        $mid_e = $tyear-1;
                        for($year=$mid_s;$year<=$mid_e;$year++){
                          for($m=1; $m<=4; $m++){
                            $quarter_m='Q'.$m;
                      ?>
                      <th style="white-space: nowrap;text-align:center;">AMOUNT</th>
                      <th style="white-space: nowrap;text-align:center;">% SHARE</th>
                      <?php }}?>
                      <?php
                        $quarter_end=$tquarter;
                        for($m=1; $m<=$quarter_end; $m++){
                        $quarter_ends='Q'.$m;
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
                      <td style="white-space: nowrap;"><a href="#" data-toggle="modal" data-target="#prodModal" data-branchcode="<?php echo $branchcode;?>" data-branchname="<?php echo $branchRes['mst_branchname'];?>"  onclick="showQuarterProdDetails(this)"><?php echo $branchRes['mst_branchname'];?></a></td>
                      <?php
                        $quarter_start=$fquarter;
                        for($m=$quarter_start; $m<=4; $m++){
                          $quarter = 'Q'.$m;
                          $totalMD = getAmountBranch($mdname,$branchcode,$quarter,$fyear);
                          $totalArea = getAmountTotalLba($branchcode,$quarter,$fyear);
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
                          for($m=1; $m<=4; $m++){
                            $quarter_m='Q'.$m;
                            $totalMD = getAmountBranch($mdname,$branchcode,$quarter_m,$year);
                            $totalArea = getAmountTotalLba($branchcode,$quarter_m,$year);
                            $sumMD[] = str_replace(',','',$totalMD);
                            $sumArea[] = $totalArea;
                      ?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo $totalMD;?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalArea,$totalMD);?></td>
                      <?php }}?>
                      <?php
                        $quarter_end=$tquarter;
                        for($m=1; $m<=$quarter_end; $m++){
                        $quarter_ends='Q'.$m;
                        $totalMD = getAmountBranch($mdname,$branchcode,$quarter_ends,$tyear);
                        $totalArea = getAmountTotalLba($branchcode,$quarter_ends,$tyear);
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
                         $quarter_start=$fquarter;
                         for($m=$quarter_start; $m<=4; $m++){
                           $quarter = 'Q'.$m;
                      ?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2"><?php echo strtoupper($quarter)."-".$fyear;?></th>
                      <?php }?>
                      <?php
                         $quarter_end=$tquarter;
                         for($m=1; $m<=$quarter_end; $m++){
                         $quarter_ends='Q'.$m;
                      ?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2"><?php echo strtoupper($quarter_ends)."-".$tyear;?></th>
                      <?php }?>
                      <th style="white-space: nowrap;text-align:center;" colspan="2">TOTAL</th>
                  </tr>
                  <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                      <?php
                        $quarter_start=$fquarter;
                        for($m=$quarter_start; $m<=4; $m++){
                          $quarter = 'Q'.$m;
                      ?>
                      <th style="white-space: nowrap;text-align:center;">AMOUNT</th>
                      <th style="white-space: nowrap;text-align:center;">% SHARE</th>
                      <?php }?>
                      <?php
                        $quarter_end=$tquarter;
                        for($m=1; $m<=$quarter_end; $m++){
                        $quarter_ends='Q'.$m;
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
                      <td style="white-space: nowrap;"><a href="#" data-toggle="modal" data-target="#prodModal" data-branchcode="<?php echo $branchcode;?>" data-branchname="<?php echo $branchRes['mst_branchname'];?>"  onclick="showQuarterProdDetails(this)"><?php echo $branchRes['mst_branchname'];?></a></td>
                      <?php
                        $quarter_start=$fquarter;
                        for($m=$quarter_start; $m<=4; $m++){
                          $quarter = 'Q'.$m;
                          $totalMD = getAmountBranch($mdname,$branchcode,$quarter,$fyear);
                          $totalArea = getAmountTotalLba($branchcode,$quarter,$fyear);
                          $sumMD[] = str_replace(',','',$totalMD);
                          $sumArea[] = $totalArea;
                      ?>
                      <td style="white-space: nowrap;text-align:right;"><?php echo $totalMD;?></td>
                      <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalArea,$totalMD);?></td>
                      <?php }?>
                      <?php
                        $quarter_end=$tquarter;
                        for($m=1; $m<=$quarter_end; $m++){
                        $quarter_ends='Q'.$m;
                        $totalMD = getAmountBranch($mdname,$branchcode,$quarter_ends,$tyear);
                        $totalArea = getAmountTotalLba($branchcode,$quarter_ends,$tyear);
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
