<?php
  include('../../../connection.php');
  $fmonth = date('n',strtotime($_GET['fmo']));
  $fyear = $_GET['fyear'];
  $tmonth = date('n',strtotime($_GET['tmo']));
  $tyear = $_GET['tyear'];
  $prodname = $_GET['prodname'];


  // Function Get All in one query 08/22/2018
  function getAllRecords($prodcode,$year,$month){
    $mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME) or die("Couldn't make connection.");
    $recordsArray = array();
    $amountQuery = $mysqli -> query("SELECT 'totalProduct' as prodname,SUM(data_amount) as total FROM mdc_senior.senior_data
                                     WHERE data_prodcode='$prodcode' AND data_year='$year' AND data_month='$month'
                                     GROUP BY data_prodcode
                                     UNION ALL
                                     SELECT DISTINCT data_doctor as prodname,SUM(data_amount) as total FROM mdc_senior.senior_data
                                     WHERE data_prodcode='$prodcode' AND data_year='$year' AND data_month='$month'
                                     GROUP BY REPLACE(data_doctor,' ',''),data_prodcode");
      while($amountRes = $amountQuery -> fetch_assoc()){
        $prodname = str_replace(" ","",$amountRes['prodname']);
        $recordsArray[strtolower($prodname)] = number_format((float)$amountRes['total'],2);
      }
    return $recordsArray;
  }

  function computeShare($totalArea,$totalMD){
    $totalMD = str_replace(',','',$totalMD);
    $totalArea = str_replace(',','',$totalArea);
    $share = 0.00;
    if($totalMD > 0){
      $share = number_format((float)(($totalMD/$totalArea)*100),2)."%";
    }
    return $share;
  }

?>
<div class="box box-solid">
  <!-- /.box-header -->
  <div class="box-header with-border bg-light-blue disabled" style="">
    <h4 class="box-title" style="display: inline-block;">
      <input type="text" id="monthProduct" class="form-control input-round" style="display:inline-block;" placeholder="Search..."></input>
    </h4>
    <span style="display: inline-block;float:right;" id="exportProduct"></span>
  </div>
  <div class="box-body no-padding" style="font-size:12px;">
    <div class="table-responsive">
      <table id="monthProd_DT" class="table table-bordered table-striped" style="margin-top:0 !important;" width="100%">
        <thead style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
            <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                <th style="white-space: nowrap;text-align:center;" rowspan="2">PRODUCT NAME</th>
                <th style="white-space: nowrap;text-align:center;" rowspan="2">MD NAME</th>
                <!-- For Same Year -->
                <?php
                  if($tyear == $fyear){
                    for($i = $fmonth; $i <= $tmonth; $i++){
                ?>
                <th style="white-space: nowrap;text-align:center;" colspan="2"><?php echo date('M', mktime(0,0,0,$i,10))."-".$tyear;?></th>
                <?php }}?>
                <!-- End for Same Year -->

                <!-- More than 2 years -->
                <?php
                  if($fyear < $tyear && ($tyear-$fyear)>=2){
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
                <?php }}?>
                <!-- End More than 2 years -->

                <!-- Between 2 years -->
                <?php
                  if($fyear < $tyear && ($tyear-$fyear)==1){
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
                <?php }}?>
                <!-- End Between 2 years -->
                <th style="white-space: nowrap;text-align:center;" colspan="2">TOTAL</th>
            </tr>
            <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                <!-- For Same Year -->
                <?php
                  if($tyear == $fyear){
                    for($i = $fmonth; $i <= $tmonth; $i++){
                ?>
                <th style="white-space: nowrap;text-align:center;">AMOUNT</th>
                <th style="white-space: nowrap;text-align:center;">% SHARE</th>
                <?php }}?>
                <!-- End for Same Year -->

                <!-- More than 2 years -->
                <?php
                  if($fyear < $tyear && ($tyear-$fyear)>=2){
                    $month_start=$fmonth;
                    for($m=$month_start; $m<=12; $m++){
                      $month = date('F', mktime(0,0,0,$m,10));
                ?>
                <th style="white-space: nowrap;text-align:center;">AMOUNT</th>
                <th style="white-space: nowrap;text-align:center;">% SHARE</th>
                <?php }?>
                <?php
                  $mid_s = $fyear+1;
                  $mid_e = $tyear-1;
                  for($year=$mid_s;$year<=$mid_e;$year++){
                    for($m=1; $m<=12; $m++){
                      $month_m = date('F', mktime(0,0,0,$m,10));;
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
                <?php }}?>
                <!-- End More than 2 years -->

                <!-- Between 2 years -->
                <?php
                  if($fyear < $tyear && ($tyear-$fyear)==1){
                  $month_start=$fmonth;
                  for($m=$month_start; $m<=12; $m++){
                    $month = date('F', mktime(0,0,0,$m,10));
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
                <?php }}?>
                <!-- End Between 2 years -->
                <th style="white-space: nowrap;text-align:center;">AMOUNT</th>
                <th style="white-space: nowrap;text-align:center;">% SHARE</th>
            </tr>
        </thead>
        <tbody>
            <?php
              $prodArray = str_replace(",","','",$prodname);
              $exTitle = array();
              $prodQuery = $mysqli -> query("SELECT DISTINCT prod_code,prod_name FROM mdc_senior.mst_productdb WHERE prod_code IN('$prodArray')");
                while($prodRes = $prodQuery -> fetch_assoc()){
                  $sumMD = array();
                  $sumArea = array();
                  $exTitle[] = $prodRes['prod_name'];
                  $prodcode = $prodRes['prod_code'];
            ?>
            <tr style="font-weight:bold;background-color:#AED6F1 !important;">
                <td style="white-space: nowrap;"><?php echo strtoupper($prodRes['prod_name']);?></td>
                <td style="white-space: nowrap;"><?php echo strtoupper($prodRes['prod_name']);?></td>
                <!-- For Same Year -->
                <?php
                  if($tyear == $fyear){
                    for($i = $fmonth; $i <= $tmonth; $i++){

                      ${'allRecords'.$i.$tyear} = getAllRecords($prodcode,$tyear,date('F', mktime(0,0,0,$i,10)));
                      if(array_key_exists('totalproduct',${'allRecords'.$i.$tyear})){
                        $totalMDLba = ${'allRecords'.$i.$tyear}['totalproduct'];
                        ${'totalMDLba'.$i.$tyear} = $totalMDLba;
                      }else{
                        $totalMDLba = 0;
                        ${'totalMDLba'.$i.$tyear} = 0;
                      }
                      $sumMD[] = str_replace(',','',$totalMDLba);
                ?>
                <td style="white-space: nowrap;text-align:right;"><?php echo $totalMDLba;?></td>
                <td style="white-space: nowrap;text-align:right;"></td>
                <?php }}?>
                <!-- End for Same Year -->

                <!-- For More than 2 years -->
                <?php
                  if($fyear < $tyear && ($tyear-$fyear)>=2){
                    for($m=$month_start; $m<=12; $m++){

                      // Test Implementation 08/28/2018
                      ${'allRecords'.$m.$fyear} = getAllRecords($prodcode,$fyear,date('F', mktime(0,0,0,$m,10)));

                      if(array_key_exists('totalproduct',${'allRecords'.$m.$fyear})){
                        $totalMDLba = ${'allRecords'.$m.$fyear}['totalproduct'];
                        ${'totalMDLba'.$m.$fyear} = $totalMDLba;
                      }else{
                        $totalMDLba = 0;
                        ${'totalMDLba'.$m.$fyear} = 0;
                      }

                      $month = date('F', mktime(0,0,0,$m,10));
                      // $totalMDLba = getAmountLba($prodcode,$month,$fyear);
                      // $totalAreaLba = getAmountTotalLba($lbacode,$month,$fyear);
                      $sumMD[] = str_replace(',','',$totalMDLba);
                  ?>
                  <td style="white-space: nowrap;text-align:right;"><?php echo $totalMDLba;?></td>
                  <td style="white-space: nowrap;text-align:right;"></td>
                  <?php }?>
                  <?php
                    $mid_s = $fyear+1;
                    $mid_e = $tyear-1;
                    for($year=$mid_s;$year<=$mid_e;$year++){
                      for($m=1; $m<=12; $m++){
                        $month_m = date('F', mktime(0,0,0,$m,10));
                        // Test Implementation 08/22/2018
                        ${'allRecords'.$m.$year} = getAllRecords($prodcode,$year,date('F', mktime(0,0,0,$m,10)));
                        if(array_key_exists('totalproduct',${'allRecords'.$m.$year})){
                          $totalMDLba = ${'allRecords'.$m.$year}['totalproduct'];
                          ${'totalMDLba'.$m.$year} = $totalMDLba;
                        }else{
                          $totalMDLba = 0;
                          ${'totalMDLba'.$m.$year} = 0;
                        }

                        // $totalMDLba = getAmountLba($prodcode,$month_m,$year);
                        // $totalAreaLba = getAmountTotalLba($lbacode,$month_m,$year);
                        $sumMD[] = str_replace(',','',$totalMDLba);
                  ?>
                  <td style="white-space: nowrap;text-align:right;"><?php echo $totalMDLba;?></td>
                  <td style="white-space: nowrap;text-align:right;"></td>
                  <?php }}?>
                  <?php
                    $month_end=$tmonth;
                    for($m=1; $m<=$month_end; $m++){
                    $month_ends = date('F', mktime(0,0,0,$m,10));

                    // Test Implementation 08/22/2018
                    ${'allRecords'.$m.$tyear} = getAllRecords($prodcode,$tyear,date('F', mktime(0,0,0,$m,10)));
                    if(array_key_exists('totalproduct',${'allRecords'.$m.$tyear})){
                      $totalMDLba = ${'allRecords'.$m.$tyear}['totalproduct'];
                      ${'totalMDLba'.$m.$tyear} = $totalMDLba;
                    }else{
                      $totalMDLba = 0;
                      ${'totalMDLba'.$m.$tyear} = 0;
                    }
                    $sumMD[] = str_replace(',','',$totalMDLba);
                  ?>
                  <td style="white-space: nowrap;text-align:right;"><?php echo $totalMDLba;?></td>
                  <td style="white-space: nowrap;text-align:right;"></td>
                <?php }}?>
                <!-- End More than 2 years -->

                <!-- End Between 2 years -->
                <?php
                  if($fyear < $tyear && ($tyear-$fyear)==1){
                  $month_start=$fmonth;
                  for($m=$month_start; $m<=12; $m++){
                    $month = date('F', mktime(0,0,0,$m,10));
                    // Test Implementation 08/28/2018
                    ${'allRecords'.$m.$fyear} = getAllRecords($prodcode,$fyear,$month);
                    if(array_key_exists('totalproduct',${'allRecords'.$m.$fyear})){
                      $totalMDLba = ${'allRecords'.$m.$fyear}['totalproduct'];
                      ${'totalMDLba'.$m.$fyear} = $totalMDLba;
                    }else{
                      $totalMDLba = 0;
                      ${'totalMDLba'.$m.$fyear} = 0;
                    }

                    $sumMD[] = str_replace(',','',$totalMDLba);
                ?>
                <td style="white-space: nowrap;text-align:right;"><?php echo $totalMDLba;?></td>
                <td style="white-space: nowrap;text-align:right;"></td>
                <?php }?>
                <?php
                  $month_end=$tmonth;
                  for($m=1; $m<=$month_end; $m++){
                  $month_ends = date('F', mktime(0,0,0,$m,10));
                  // Test Implementation 08/28/2018
                  ${'allRecords'.$m.$tyear} = getAllRecords($prodcode,$tyear,$month_ends);
                  if(array_key_exists('totalproduct',${'allRecords'.$m.$tyear})){
                    $totalMDLba = ${'allRecords'.$m.$tyear}['totalproduct'];
                    ${'totalMDLba'.$m.$tyear} = $totalMDLba;
                  }else{
                    $totalMDLba = 0;
                    ${'totalMDLba'.$m.$tyear} = 0;
                  }

                  $sumMD[] = str_replace(',','',$totalMDLba);
                ?>
                <td style="white-space: nowrap;text-align:right;"><?php echo $totalMDLba;?></td>
                <td style="white-space: nowrap;text-align:right;"></td>
                <?php }}?>
                <!-- End Between 2 years -->

                <td style="white-space: nowrap;text-align:right;"><?php echo number_format((float)array_sum($sumMD),2);?></td>
                <td style="white-space: nowrap;text-align:right;"></td>
            </tr>
            <?php
                  // Query Branch
                  $mdQuery = $mysqli -> query("SELECT DISTINCT data_doctor FROM mdc_senior.senior_data WHERE data_prodcode='$prodcode' GROUP BY REPLACE(data_doctor,' ','') ORDER BY data_doctor ASC");
                    while($mdRes = $mdQuery -> fetch_assoc()){
                      $mdname = $mdRes['data_doctor'];
                      $sumMD = array();
                      $sumArea = array();
            ?>
            <tr>
                <td style="white-space: nowrap;"><?php echo strtoupper($prodRes['prod_name']);?></td>
                <td style="white-space: nowrap;"><a href="#" data-toggle="modal" data-target="#_prodModal" data-prodname="<?php echo $prodRes['prod_name'];?>" data-prod="<?php echo $prodcode;?>" data-mdname="<?php echo $mdname;?>" onclick="showResultMonthBranchP(this);"><?php echo $mdname;?></a></td>
                <!-- For Same Year -->
                <?php
                  if($tyear == $fyear){
                    for($i = $fmonth; $i <= $tmonth; $i++){
                      // Test Implementation 08/22/2018
                      if(array_key_exists(str_replace(" ","",strtolower($mdname)),${'allRecords'.$i.$tyear})){
                        $totalMD = ${'allRecords'.$i.$tyear}[str_replace(" ","",strtolower($mdname))];
                      }else{
                        $totalMD = 0;
                      }
                      $totalArea = ${'totalMDLba'.$i.$tyear};

                      $sumMD[] = str_replace(',','',$totalMD);
                      $sumArea[] = str_replace(',','',$totalArea);
                ?>
                <td style="white-space: nowrap;text-align:right;"><?php echo $totalMD;?></td>
                <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalArea,$totalMD);?></td>
                <?php }}?>
                <!-- End for Same Year -->
                <!-- For More than 2 Years -->
                <?php
                  if($fyear < $tyear && ($tyear-$fyear)>=2){
                    for($m=$month_start; $m<=12; $m++){
                      // Test Implementation 08/22/2018
                      if(array_key_exists(str_replace(" ","",strtolower($mdname)),${'allRecords'.$m.$fyear})){
                        $totalMD = ${'allRecords'.$m.$fyear}[str_replace(" ","",strtolower($mdname))];
                      }else{
                        $totalMD = 0;
                      }
                      $totalArea = ${'totalMDLba'.$m.$fyear};

                      $sumMD[] = str_replace(',','',$totalMD);
                      $sumArea[] = str_replace(',','',$totalArea);
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
                      if(array_key_exists(str_replace(" ","",strtolower($mdname)),${'allRecords'.$m.$year})){
                        $totalMD = ${'allRecords'.$m.$year}[str_replace(" ","",strtolower($mdname))];
                      }else{
                        $totalMD = 0;
                      }
                      $totalArea = ${'totalMDLba'.$m.$year};

                      $sumMD[] = str_replace(',','',$totalMD);
                      $sumArea[] = str_replace(',','',$totalArea);
                ?>
                <td style="white-space: nowrap;text-align:right;"><?php echo $totalMD;?></td>
                <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalArea,$totalMD);?></td>
                <?php }}?>
                <?php
                  $month_end=$tmonth;
                  for($m=1; $m<=$month_end; $m++){
                  $month_ends = date('F', mktime(0,0,0,$m,10));
                    if(array_key_exists(str_replace(" ","",strtolower($mdname)),${'allRecords'.$m.$tyear})){
                      $totalMD = ${'allRecords'.$m.$tyear}[str_replace(" ","",strtolower($mdname))];
                    }else{
                      $totalMD = 0;
                    }
                    $totalArea = ${'totalMDLba'.$m.$tyear};

                    $sumMD[] = str_replace(',','',$totalMD);
                    $sumArea[] = str_replace(',','',$totalArea);
                ?>
                <td style="white-space: nowrap;text-align:right;"><?php echo $totalMD;?></td>
                <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalArea,$totalMD);?></td>
                <?php }}?>
                <!-- End for More than 2 Years -->

                <!-- Between 2 Years -->
                <?php
                  if($fyear < $tyear && ($tyear-$fyear)==1){
                  $month_start=$fmonth;
                  for($m=$month_start; $m<=12; $m++){
                    $month = date('F', mktime(0,0,0,$m,10));
                      if(array_key_exists(str_replace(" ","",strtolower($mdname)),${'allRecords'.$m.$fyear})){
                        $totalMD = ${'allRecords'.$m.$fyear}[str_replace(" ","",strtolower($mdname))];
                      }else{
                        $totalMD = 0;
                      }
                      $totalArea = ${'totalMDLba'.$m.$fyear};

                      $sumMD[] = str_replace(',','',$totalMD);
                      $sumArea[] = str_replace(',','',$totalArea);
                ?>
                <td style="white-space: nowrap;text-align:right;"><?php echo $totalMD;?></td>
                <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalArea,$totalMD);?></td>
                <?php }?>
                <?php
                  $month_end=$tmonth;
                  for($m=1; $m<=$month_end; $m++){
                    if(array_key_exists(str_replace(" ","",strtolower($mdname)),${'allRecords'.$m.$tyear})){
                      $totalMD = ${'allRecords'.$m.$tyear}[str_replace(" ","",strtolower($mdname))];
                    }else{
                      $totalMD = 0;
                    }
                    $totalArea = ${'totalMDLba'.$m.$tyear};

                    $sumMD[] = str_replace(',','',$totalMD);
                    $sumArea[] = str_replace(',','',$totalArea);
                ?>
                <td style="white-space: nowrap;text-align:right;"><?php echo $totalMD;?></td>
                <td style="white-space: nowrap;text-align:right;"><?php echo computeShare($totalArea,$totalMD);?></td>
                <?php }}?>
                <!-- End Between 2 Years -->

                <td style="white-space: nowrap;text-align:right;"><?php echo number_format((float)array_sum($sumMD),2);?></td>
                <td style="white-space: nowrap;text-align:right;"><?php echo computeShare(array_sum($sumArea),array_sum($sumMD));?></td>
            </tr>
            <?php }}?>
        </tbody>
      </table>
    </div>
  </div>
  <!-- /.box-body -->
</div>
<!-- /.box -->
<script>
  $(function () {
    var table1 = $('#monthProd_DT').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : false,
      'info'        : false,
      'autoWidth'   : false,
        dom: 'Brtip',
        columnDefs: [
          {
          targets: 0,
          searchable: true,
          visible: false
          }
        ],
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
              filename: '(<?php echo date('M', mktime(0,0,0,$fmonth,10)).','.$fyear."-".date('M', mktime(0,0,0,$tmonth,10)).','.$tyear;?>)-<?php echo implode(',',$exTitle);?>',
              extension: '.xlsx',
              exportOptions: {
                  columns: ':visible'
              }
            }]
        }
    })

    table1.buttons().containers().prependTo( '#exportProduct' );
    $('#monthProduct').keyup(function(){
       table1.search( $(this).val() ).draw();
    });

    var totheadercount = table1.columns().header().length;
    var t1count = table1.columns().header().length-2;
    var tobedeleted = "$(a).children().eq(2).text() == '0.00'";
    for(i=3; i<=totheadercount; i++){
      if((i % 2) == 0){
        tobedeleted += " && $(a).children().eq("+ i +").text() == '0.00'";
      }
    }
    //alert(totheadercount);

    table1.rows().nodes().each(function(a,b) {
      if($(a).children().eq(2).text() == '0.00' && $(a).children().eq(4).text() == '0.00' && $(a).children().eq(6).text() == '0.00' && $(a).children().eq(8).text() == '0.00'){
        table1.rows(a).remove();
      }
      return false;
    });
    table1.rows().invalidate();
    table1.draw();
  })
</script>
