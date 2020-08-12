<?php
  include('../../../connection.php');

  $fyear = $_GET['fyear'];
  $tyear = $_GET['tyear'];
  $mdname = $_GET['md'];
  $prodcode = $_GET['prod'];
  $prodname = $_GET['prodname'];
  // Function Get All in one query 08/24/2018
  function getAllRecords($mdname,$prodcode,$lbacode,$year){
    $mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME) or die("Couldn't make connection.");
    $recordsArray = array();
    $amountQuery = $mysqli -> query("SELECT 'mdProdname' as branchname,SUM(data_amount) as total FROM mdc_senior.senior_data as a INNER JOIN mdc_senior.mst_database as b ON a.data_branchcode=b.mst_branchcode
                                     WHERE data_doctor='$mdname' AND mst_lburebate='$lbacode' AND data_year='$year' AND data_prodcode='$prodcode' GROUP BY mst_lburebate
                                     UNION ALL
                                     SELECT 'lbaProdname' as branchname,SUM(data_amount) as total FROM mdc_senior.senior_data as a INNER JOIN mdc_senior.mst_database as b ON a.data_branchcode=b.mst_branchcode
                                     WHERE mst_lburebate='$lbacode' AND data_year='$year' AND data_prodcode='$prodcode' GROUP BY mst_lburebate
                                     UNION ALL
                                     SELECT CONCAT('md-',data_branchcode) as branchname,SUM(data_amount) as total FROM mdc_senior.senior_data as a INNER JOIN mdc_senior.mst_database as b ON a.data_branchcode=b.mst_branchcode
                                     WHERE data_doctor='$mdname' AND mst_lburebate='$lbacode' AND data_year='$year' AND data_prodcode='$prodcode' GROUP BY data_branchcode
                                     UNION ALL
                                     SELECT CONCAT('lba-',data_branchcode) as branchname,SUM(data_amount) as total FROM mdc_senior.senior_data as a INNER JOIN mdc_senior.mst_database as b ON a.data_branchcode=b.mst_branchcode
                                     WHERE mst_lburebate='$lbacode' AND data_year='$year' AND data_prodcode='$prodcode'
                                     GROUP BY data_branchcode");
      while($amountRes = $amountQuery -> fetch_assoc()){
        $prodname = str_replace(" ","",$amountRes['branchname']);
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
<table id="_example1" class="table table-bordered table-striped" style="margin-top:0 !important;" width="100%">
  <thead style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
      <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
          <th style="white-space: nowrap;text-align:center;" rowspan="2">LBA CODE / LBA NAME</th>
          <th style="white-space: nowrap;text-align:center;" rowspan="2">BRANCH NAME</th>
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
        $lbaQuery = $mysqli -> query("SELECT DISTINCT lbu_code,lbu_area FROM mdc_senior.senior_data as a
                                                                        INNER JOIN mdc_senior.lbu_rebatedb as b ON a.data_lburebate=b.lbu_code
                                                                        WHERE data_doctor='$mdname' AND data_prodcode='$prodcode'
                                                                        AND lbu_year='2018' AND lbu_quarter='Q1'");
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

                // Test Implementation 08/24/2018
                ${'allRecords'.$i} = getAllRecords($mdname,$prodcode,$lbacode,$i);
                if(array_key_exists('mdprodname',${'allRecords'.$i})){
                  $totalMDLba = ${'allRecords'.$i}['mdprodname'];
                  $totalAreaLba = ${'allRecords'.$i}['lbaprodname'];
                }else{
                  $totalMDLba = 0;
                  $totalAreaLba = 0;
                }
              // $totalMDLba = getAmountLba($mdname,$lbacode,$i);
              // $totalAreaLba = getAmountTotalLba($lbacode,$i);
              $sumMDLba[] = str_replace(',','',$totalMDLba);
              $sumAreaLba[] = str_replace(',','',$totalAreaLba);
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
              WHERE data_doctor='$mdname' AND mst_lburebate='$lbacode'");
              while($branchRes = $branchQuery -> fetch_assoc()){
                $branchcode = $branchRes['data_branchcode'];
                $sumMD = array();
                $sumArea = array();
      ?>
      <tr>
          <td style="white-space: nowrap;"></td>
          <td style="white-space: nowrap;"><?php echo $branchRes['mst_branchname'];?></td>
          <?php
            for($i = $fyear; $i <= $tyear; $i++){
              // Test Implementation 08/24/2018
              if(array_key_exists('md-'.str_replace(" ","",strtolower($branchcode)),${'allRecords'.$i})){
                $totalMD = ${'allRecords'.$i}['md-'.str_replace(" ","",strtolower($branchcode))];
              }else{
                $totalMD = 0;
              }
              if(array_key_exists('lba-'.str_replace(" ","",strtolower($branchcode)),${'allRecords'.$i})){
                $totalArea = ${'allRecords'.$i}['lba-'.str_replace(" ","",strtolower($branchcode))];
              }else{
                $totalArea = 0;
              }
              // $totalMD = getAmountBranch($mdname,$branchcode,$i);
              // $totalArea = getAmountTotalBranch($branchcode,$i);
              $sumMD[] = str_replace(',','',$totalMD);
              $sumArea[] = str_replace(',','',$totalArea);
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

<script>
  $(function () {
    var table1 = $('#_example1').DataTable({
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
              //title: 'ON-SITE(<?php echo $mdname;?>)',
              text: '<span class="fa fa-file-excel"></span> Export to Excel',
              filename: '<?php echo $prodname;?>(<?php echo $mdname;?>)',
              extension: '.xlsx'
            }]
        }
    })
    table1.buttons().containers().prependTo( '#_exportProd' );
    $('#_myInputTextField').keyup(function(){
       table1.search( $(this).val() ).draw();
    });
    var t1count = table1.columns().header().length - 1;
    table1.rows().nodes().each(function(a,b) {
      if($(a).children().eq(t1count).text() == '0'){
        table1.rows(a).remove();
      }
      return false;
    });
    table1.rows().invalidate();
    table1.draw();
  })
</script>
