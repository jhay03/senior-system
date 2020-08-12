<?php
  include('../../../connection.php');
  $fyear = $_GET['fyear'];
  $tyear = $_GET['tyear'];
  $prodname = $_GET['prodname'];


  // Function Get All in one query 08/22/2018
  function getAllRecords($prodcode,$year){
    $mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME) or die("Couldn't make connection.");
    $recordsArray = array();
    $amountQuery = $mysqli -> query("SELECT 'totalProduct' as prodname,SUM(data_amount) as total FROM mdc_senior.senior_data
                                     WHERE data_prodcode='$prodcode' AND data_year='$year'
                                     GROUP BY data_prodcode
                                     UNION ALL
                                     SELECT DISTINCT data_doctor as prodname,SUM(data_amount) as total FROM mdc_senior.senior_data
                                     WHERE data_prodcode='$prodcode' AND data_year='$year'
                                    GROUP BY REPLACE(data_doctor,' ','')");
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
      <input type="text" id="yearProduct" class="form-control input-round" style="display:inline-block;" placeholder="Search..."></input>
    </h4>
    <span style="display: inline-block;float:right;" id="exportProduct"></span>
  </div>
  <div class="box-body no-padding" style="font-size:12px;">
    <div class="table-responsive">
      <table id="yearProd_DT" class="table table-bordered table-striped" style="margin-top:0 !important;" width="100%">
        <thead style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
            <tr style="background:#d7ebf9 url(../../dist/img/bg.png) 50% 50% repeat-x;border: 1px solid #FFF;padding:4px;color:#2779aa">
                <th style="white-space: nowrap;text-align:center;" rowspan="2">PRODUCT NAME</th>
                <th style="white-space: nowrap;text-align:center;" rowspan="2">MD NAME</th>
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
                <?php
                  for($i = $fyear; $i <= $tyear; $i++){

                    ${'allRecords'.$i} = getAllRecords($prodcode,$i);
                    if(array_key_exists('totalproduct',${'allRecords'.$i})){
                      $totalMDLba = ${'allRecords'.$i}['totalproduct'];
                      ${'totalMDLba'.$i} = $totalMDLba;
                    }else{
                      $totalMDLba = 0;
                      ${'totalMDLba'.$i} = 0;
                    }
                    $sumMD[] = str_replace(',','',$totalMDLba);
                ?>
                <td style="white-space: nowrap;text-align:right;"><?php echo $totalMDLba;?></td>
                <td style="white-space: nowrap;text-align:right;"></td>
                <?php }?>
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
                <td style="white-space: nowrap;"><a href="#" data-toggle="modal" data-target="#_prodModal" data-prodname="<?php echo $prodRes['prod_name'];?>" data-prod="<?php echo $prodcode;?>" data-mdname="<?php echo $mdname;?>" onclick="showResultYearBranchP(this);"><?php echo $mdname;?></a></td>
                <?php
                  for($i = $fyear; $i <= $tyear; $i++){
                    // Test Implementation 08/22/2018
                    if(array_key_exists(str_replace(" ","",strtolower($mdname)),${'allRecords'.$i})){
                      $totalMD = ${'allRecords'.$i}[str_replace(" ","",strtolower($mdname))];
                    }else{
                      $totalMD = 0;
                    }
                    $totalArea = ${'totalMDLba'.$i};

                    // $totalMD = getAmountProd($mdname,$lbacode,$prodname,$i);
                    // $totalArea = getAmountTotalProd($lbacode,$prodname,$i);
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
    </div>
  </div>
  <!-- /.box-body -->
</div>
<!-- /.box -->
<script>
  $(function () {
    var table1 = $('#yearProd_DT').DataTable({
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
              filename: '(<?php echo $fyear."-".$tyear;?>)-<?php echo implode(',',$exTitle);?>',
              extension: '.xlsx',
              exportOptions: {
                  columns: ':visible'
              }
            }]
        }
    })

    table1.buttons().containers().prependTo( '#exportProduct' );
    $('#yearProduct').keyup(function(){
       table1.search( $(this).val() ).draw();
    });

    var t1count = table1.columns().header().length-2;
    //alert(t1count);
    table1.rows().nodes().each(function(a,b) {
      if($(a).children().eq(t1count).text() == '0.00'){
        table1.rows(a).remove();
      }
      return false;
    });
    table1.rows().invalidate();
    table1.draw();
  })
</script>
