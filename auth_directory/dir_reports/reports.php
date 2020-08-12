<?php
  session_start();
  include('../../connection.php');
  if(!isset($_SESSION['authUser'])){
    header('Location:../../logout.php');
  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="shortcut icon" href="../../dist/img/BK LOGO.png">
  <title>MDC Senior System | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../dependencies/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../dependencies/Ionicons/css/ionicons.min.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="../../plugins/iCheck/all.css">
  <link rel="stylesheet" href="../../dependencies/select2/dist/css/select2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../dependencies/datatables/media/css/dataTables.bootstrap.css">
  <link href="../../dependencies/datatables/extensions/Buttons/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../dist/css/skins/skin-blue.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="../../dependencies/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="../../dependencies/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="../../dependencies/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../../dependencies/bootstrap-daterangepicker/daterangepicker.css">
  <link href="../../plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="../../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

  <script type="text/javascript" src="../../dist/js/dateperiod.js"></script>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <script type="text/javascript">
      // ====== Per Product Tabs ===== //
      function showResultYearP(){
        $('#div_result_prod').html('<div align="center"><i class="fa fa-spinner fa-pulse text-green fa-fw"></i>Loading data. Please Wait...</div>');
        //var prodname = document.getElementById('prodname').value;
        var prodname = $('#prodname').val();
        var fyear = document.getElementById('Pyt_start').value;
        var tyear = document.getElementById('Pyt_end').value;
        //alert(prodname);
        var dataString = "fyear=" + fyear + "&tyear=" + tyear + "&prodname=" + prodname;
        if(fyear > tyear){
          $("#div_result").html('<div class="callout callout-warning"><h4>Range Warning!</h4>Cannot accept range! Year <b>FROM</b> must be lower than Year <b>TO</b>.</p></div>');
        }else{
          $.ajax({
            type: "GET",
            url: "_products/year_query_result.php?r=m",
            data: dataString,
            cache: false,
            success: function (data) {
              console.log(data);
              $("#div_result_prod").html(data);
            },
            error: function(err) {
              console.log(err);
            }
          });
        }
      }

      function showResultYearBranchP(obj){
        var prodcode = $(obj).attr('data-prod');
        var prodname = $(obj).attr('data-prodname');
        var mdname = $(obj).attr('data-mdname');
        $('#_exportProd').html('');
        $('#_prod-title').html(mdname + '( ' + prodname + ' )');
        $('#_prod-body').html('<div align="center"><i class="fa fa-spinner fa-pulse text-green fa-fw"></i>Loading data. Please Wait...</div>');
        var md = document.getElementById('mdname').value;
        var fyear = document.getElementById('Pyt_start').value;
        var tyear = document.getElementById('Pyt_end').value;
        var dataString = "fyear=" + fyear + "&tyear=" + tyear + "&md=" + mdname + "&prod=" + prodcode + "&prodname=" + prodname;
        if(fyear > tyear){
          $("#_prod-body").html('<div class="callout callout-warning"><h4>Range Warning!</h4>Cannot accept range! Year <b>FROM</b> must be lower than Year <b>TO</b>.</p></div>');
        }else{
          $.ajax({
            type: "GET",
            url: "_products/resultYearLbaBranch.php",
            data: dataString,
            cache: false,
            success: function (data) {
              console.log(data);
              $("#_prod-body").html(data);
            },
            error: function(err) {
              console.log(err);
            }
          });
        }
      }

      // Quarter Option
      function showResultQuarterP(){
        $('#div_result_prod').html('<div align="center"><i class="fa fa-spinner fa-pulse text-green fa-fw"></i>Loading data. Please Wait...</div>');
        var prodname = $('#prodname').val();
        var fmo = document.getElementById('Pqt_start').value;
        var fyear = document.getElementById('Pqty_start').value;
        var tmo = document.getElementById('Pqt_end').value;
        var tyear = document.getElementById('Pqty_end').value;
        var dataString = "fquarter=" + fmo + "&fyear=" + fyear + "&tquarter=" + tmo + "&tyear=" + tyear + "&prodname=" + prodname;

        var qfrom = fmo.replace("Q","");
        var qto = tmo.replace("Q","");

        // Validations
    		if(qfrom > qto && fyear > tyear){
    			$('#div_result_prod').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or quarter is higher than the ending year or quarter</p></div>');
    		}
    		else if(qfrom < qto && fyear > tyear){
    			$('#div_result_prod').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or quarter is higher than the ending year or quarter</p></div>');
    		}
    		else if(qfrom == qto && fyear > tyear){
    			$('#div_result_prod').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or quarter is higher than the ending year or quarter</p></div>');
    		}
    		else if(qfrom > qto && fyear == tyear){
    			$('#div_result_prod').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or quarter is higher than the ending year or quarter</p></div>');
    		}
        else{
          $.ajax({
            type: "GET",
            url: "_products/quarter_query_result.php?r=m",
            data: dataString,
            cache: false,
            success: function (data) {
              console.log(data);
              $("#div_result_prod").html(data);
            },
            error: function(err) {
              console.log(err);
            }
          });
        }
      }

      function showResultQuarterBranchP(obj){
        var prodcode = $(obj).attr('data-prod');
        var prodname = $(obj).attr('data-prodname');
        var mdname = $(obj).attr('data-mdname');
        $('#_exportProd').html('');
        $('#_prod-title').html(mdname + '( ' + prodname + ' )');
        $('#_prod-body').html('<div align="center"><i class="fa fa-spinner fa-pulse text-green fa-fw"></i>Loading data. Please Wait...</div>');
        var md = document.getElementById('mdname').value;
        var fmo = document.getElementById('Pqt_start').value;
        var fyear = document.getElementById('Pqty_start').value;
        var tmo = document.getElementById('Pqt_end').value;
        var tyear = document.getElementById('Pqty_end').value;
        var dataString = "fquarter=" + fmo + "&fyear=" + fyear + "&tquarter=" + tmo + "&tyear=" + tyear + "&md=" + mdname + "&prod=" + prodcode + "&prodname=" + prodname;
        if(qfrom > qto && fyear > tyear){
    			$('#div_result_prod').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or quarter is higher than the ending year or quarter</p></div>');
    		}
    		else if(qfrom < qto && fyear > tyear){
    			$('#div_result_prod').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or quarter is higher than the ending year or quarter</p></div>');
    		}
    		else if(qfrom == qto && fyear > tyear){
    			$('#div_result_prod').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or quarter is higher than the ending year or quarter</p></div>');
    		}
    		else if(qfrom > qto && fyear == tyear){
    			$('#div_result_prod').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or quarter is higher than the ending year or quarter</p></div>');
    		}
        else{
          $.ajax({
            type: "GET",
            url: "_products/resultQuarterLbaBranch.php",
            data: dataString,
            cache: false,
            success: function (data) {
              console.log(data);
              $("#_prod-body").html(data);
            },
            error: function(err) {
              console.log(err);
            }
          });
        }
      }

      // Month Option
      function showResultMonthP(){
        $('#div_result_prod').html('<div align="center"><i class="fa fa-spinner fa-pulse text-green fa-fw"></i>Loading data. Please Wait...</div>');
        var prodname = $('#prodname').val();
        var fmo = document.getElementById('Pmt_start').value;
        var fyear = document.getElementById('Pmty_start').value;
        var tmo = document.getElementById('Pmt_end').value;
        var tyear = document.getElementById('Pmty_end').value;
        var dataString = "fmo=" + fmo + "&fyear=" + fyear + "&tmo=" + tmo + "&tyear=" + tyear + "&prodname=" + prodname;

        // Month array
        var month = new Array();
        month['January'] = "1";
        month['February'] = "2";
        month['March'] = "3";
        month['April'] = "4";
        month['May'] = "5";
        month['June'] = "6";
        month['July'] = "7";
        month['August'] = "8";
        month['September'] = "9";
        month['October'] = "10";
        month['November'] = "11";
        month['December'] = "12";

        // Validations
    		if(month[fmo] > month[tmo] && fyear > tyear){
    			$('#div_result_prod').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or month is higher than the ending year or month</p></div>');
    		}
    		else if(month[fmo] < month[tmo] && fyear > tyear){
    			$('#div_result_prod').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or month is higher than the ending year or month</p></div>');
    		}
    		else if(month[fmo] == month[tmo] && fyear > tyear){
    			$('#div_result_prod').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or month is higher than the ending year or month</p></div>');
    		}
    		else if(month[fmo] > month[tmo] && fyear == tyear){
    			$('#div_result_prod').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or month is higher than the ending year or month</p></div>');
    		}
        else{
          $.ajax({
            type: "GET",
            url: "_products/month_query_result.php?r=m",
            data: dataString,
            cache: false,
            success: function (data) {
              console.log(data);
              $("#div_result_prod").html(data);
            },
            error: function(err) {
              console.log(err);
            }
          });
        }
      }

      function showResultMonthBranchP(obj){
        var prodcode = $(obj).attr('data-prod');
        var prodname = $(obj).attr('data-prodname');
        var mdname = $(obj).attr('data-mdname');
        $('#_exportProd').html('');
        $('#_prod-title').html(mdname + '( ' + prodname + ' )');
        $('#_prod-body').html('<div align="center"><i class="fa fa-spinner fa-pulse text-green fa-fw"></i>Loading data. Please Wait...</div>');
        var fmo = document.getElementById('Pmt_start').value;
        var fyear = document.getElementById('Pmty_start').value;
        var tmo = document.getElementById('Pmt_end').value;
        var tyear = document.getElementById('Pmty_end').value;
        var dataString = "fmo=" + fmo + "&fyear=" + fyear + "&tmo=" + tmo + "&tyear=" + tyear + "&md=" + mdname + "&prod=" + prodcode + "&prodname=" + prodname;

        // Month array
        var month = new Array();
        month['January'] = "1";
        month['February'] = "2";
        month['March'] = "3";
        month['April'] = "4";
        month['May'] = "5";
        month['June'] = "6";
        month['July'] = "7";
        month['August'] = "8";
        month['September'] = "9";
        month['October'] = "10";
        month['November'] = "11";
        month['December'] = "12";

        if(month[fmo] > month[tmo] && fyear > tyear){
    			$('#div_result_prod').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or month is higher than the ending year or month</p></div>');
    		}
    		else if(month[fmo] < month[tmo] && fyear > tyear){
    			$('#div_result_prod').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or month is higher than the ending year or month</p></div>');
    		}
    		else if(month[fmo] == month[tmo] && fyear > tyear){
    			$('#div_result_prod').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or month is higher than the ending year or month</p></div>');
    		}
    		else if(month[fmo] > month[tmo] && fyear == tyear){
    			$('#div_result_prod').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or month is higher than the ending year or month</p></div>');
    		}
        else{
          $.ajax({
            type: "GET",
            url: "_products/resultMonthLbaBranch.php",
            data: dataString,
            cache: false,
            success: function (data) {
              console.log(data);
              $("#_prod-body").html(data);
            },
            error: function(err) {
              console.log(err);
            }
          });
        }
      }

      // ====== END Per Product Tabs ===== //

      // By Branch
      function showResultMonthBranch(){
        $('#div_result').html('<div align="center"><i class="fa fa-spinner fa-pulse text-green fa-fw"></i>Loading data. Please Wait...</div>');
        var fmo = document.getElementById('mt_start').value;
        var md = document.getElementById('mdname').value;
        var fyear = document.getElementById('mty_start').value;
        var tmo = document.getElementById('mt_end').value;
        var tyear = document.getElementById('mty_end').value;
        var dataString = "fmo=" + fmo + "&fyear=" + fyear + "&tmo=" + tmo + "&tyear=" + tyear + "&md=" + md;

        // Month array
        var month = new Array();
        month['January'] = "1";
        month['February'] = "2";
        month['March'] = "3";
        month['April'] = "4";
        month['May'] = "5";
        month['June'] = "6";
        month['July'] = "7";
        month['August'] = "8";
        month['September'] = "9";
        month['October'] = "10";
        month['November'] = "11";
        month['December'] = "12";

        // Validations
    		if(month[fmo] > month[tmo] && fyear > tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or month is higher than the ending year or month</p></div>');
    		}
    		else if(month[fmo] < month[tmo] && fyear > tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or month is higher than the ending year or month</p></div>');
    		}
    		else if(month[fmo] == month[tmo] && fyear > tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or month is higher than the ending year or month</p></div>');
    		}
    		else if(month[fmo] > month[tmo] && fyear == tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or month is higher than the ending year or month</p></div>');
    		}
        else{
          $.ajax({
            type: "GET",
            url: "branch_month_query_result.php?r=m",
            data: dataString,
            cache: false,
            success: function (data) {
              console.log(data);
              $("#div_result").html(data);
            },
            error: function(err) {
              console.log(err);
            }
          });
        }
      }

      function showResultQuarterBranch(){
        $('#div_result').html('<div align="center"><i class="fa fa-spinner fa-pulse text-green fa-fw"></i>Loading data. Please Wait...</div>');
        var fmo = document.getElementById('qt_start').value;
        var md = document.getElementById('mdname').value;
        var fyear = document.getElementById('qty_start').value;
        var tmo = document.getElementById('qt_end').value;
        var tyear = document.getElementById('qty_end').value;
        var dataString = "fquarter=" + fmo + "&fyear=" + fyear + "&tquarter=" + tmo + "&tyear=" + tyear + "&md=" + md;

        var qfrom = fmo.replace("Q","");
        var qto = tmo.replace("Q","");

        // Validations
    		if(qfrom > qto && fyear > tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or quarter is higher than the ending year or quarter</p></div>');
    		}
    		else if(qfrom < qto && fyear > tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or quarter is higher than the ending year or quarter</p></div>');
    		}
    		else if(qfrom == qto && fyear > tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or quarter is higher than the ending year or quarter</p></div>');
    		}
    		else if(qfrom > qto && fyear == tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or quarter is higher than the ending year or quarter</p></div>');
    		}
        else{
          $.ajax({
            type: "GET",
            url: "branch_quarter_query_result.php?r=m",
            data: dataString,
            cache: false,
            success: function (data) {
              console.log(data);
              $("#div_result").html(data);
            },
            error: function(err) {
              console.log(err);
            }
          });
        }
      }

      function showResultYearBranch(){
        $('#div_result').html('<div align="center"><i class="fa fa-spinner fa-pulse text-green fa-fw"></i>Loading data. Please Wait...</div>');
        var md = document.getElementById('mdname').value;
        var fyear = document.getElementById('yt_start').value;
        var tyear = document.getElementById('yt_end').value;
        var dataString = "fyear=" + fyear + "&tyear=" + tyear + "&md=" + md;
        if(fyear > tyear){
          $("#div_result").html('<div class="callout callout-warning"><h4>Range Warning!</h4>Cannot accept range! Year <b>FROM</b> must be lower than Year <b>TO</b>.</p></div>');
        }else{
          $.ajax({
            type: "GET",
            url: "branch_year_query_result.php?r=m",
            data: dataString,
            cache: false,
            success: function (data) {
              console.log(data);
              $("#div_result").html(data);
            },
            error: function(err) {
              console.log(err);
            }
          });
        }
      }

      // By Product
      function showResultMonthProd(){
        $('#div_result').html('<div align="center"><i class="fa fa-spinner fa-pulse text-green fa-fw"></i>Loading data. Please Wait...</div>');
        var fmo = document.getElementById('mt_start').value;
        var md = document.getElementById('mdname').value;
        var fyear = document.getElementById('mty_start').value;
        var tmo = document.getElementById('mt_end').value;
        var tyear = document.getElementById('mty_end').value;
        var dataString = "fmo=" + fmo + "&fyear=" + fyear + "&tmo=" + tmo + "&tyear=" + tyear + "&md=" + md;

        // Month array
        var month = new Array();
        month['January'] = "1";
        month['February'] = "2";
        month['March'] = "3";
        month['April'] = "4";
        month['May'] = "5";
        month['June'] = "6";
        month['July'] = "7";
        month['August'] = "8";
        month['September'] = "9";
        month['October'] = "10";
        month['November'] = "11";
        month['December'] = "12";

        // Validations
    		if(month[fmo] > month[tmo] && fyear > tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or month is higher than the ending year or month</p></div>');
    		}
    		else if(month[fmo] < month[tmo] && fyear > tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or month is higher than the ending year or month</p></div>');
    		}
    		else if(month[fmo] == month[tmo] && fyear > tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or month is higher than the ending year or month</p></div>');
    		}
    		else if(month[fmo] > month[tmo] && fyear == tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or month is higher than the ending year or month</p></div>');
    		}
        else{
          $.ajax({
            type: "GET",
            url: "products_month_query_result.php?r=m",
            data: dataString,
            cache: false,
            success: function (data) {
              console.log(data);
              $("#div_result").html(data);
            },
            error: function(err) {
              console.log(err);
            }
          });
        }
      }


      function showResultQuarterProd(){
        $('#div_result').html('<div align="center"><i class="fa fa-spinner fa-pulse text-green fa-fw"></i>Loading data. Please Wait...</div>');
        var fmo = document.getElementById('qt_start').value;
        var md = document.getElementById('mdname').value;
        var fyear = document.getElementById('qty_start').value;
        var tmo = document.getElementById('qt_end').value;
        var tyear = document.getElementById('qty_end').value;
        var dataString = "fquarter=" + fmo + "&fyear=" + fyear + "&tquarter=" + tmo + "&tyear=" + tyear + "&md=" + md;

        var qfrom = fmo.replace("Q","");
        var qto = tmo.replace("Q","");

        // Validations
    		if(qfrom > qto && fyear > tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or quarter is higher than the ending year or quarter</p></div>');
    		}
    		else if(qfrom < qto && fyear > tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or quarter is higher than the ending year or quarter</p></div>');
    		}
    		else if(qfrom == qto && fyear > tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or quarter is higher than the ending year or quarter</p></div>');
    		}
    		else if(qfrom > qto && fyear == tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or quarter is higher than the ending year or quarter</p></div>');
    		}
        else{
          $.ajax({
            type: "GET",
            url: "products_quarter_query_result.php?r=m",
            data: dataString,
            cache: false,
            success: function (data) {
              console.log(data);
              $("#div_result").html(data);
            },
            error: function(err) {
              console.log(err);
            }
          });
        }
      }

      function showResultYearProd(){
        $('#div_result').html('<div align="center"><i class="fa fa-spinner fa-pulse text-green fa-fw"></i>Loading data. Please Wait...</div>');
        var md = document.getElementById('mdname').value;
        var fyear = document.getElementById('yt_start').value;
        var tyear = document.getElementById('yt_end').value;
        var dataString = "fyear=" + fyear + "&tyear=" + tyear + "&md=" + md;
        if(fyear > tyear){
          $("#div_result").html('<div class="callout callout-warning"><h4>Range Warning!</h4>Cannot accept range! Year <b>FROM</b> must be lower than Year <b>TO</b>.</p></div>');
        }else{
          $.ajax({
            type: "GET",
            url: "products_year_query_result.php?r=m",
            data: dataString,
            cache: false,
            success: function (data) {
              console.log(data);
              $("#div_result").html(data);
            },
            error: function(err) {
              console.log(err);
            }
          });
        }
      }

      // By LBA
      function showResultYear(){
        $('#div_result').html('<div align="center"><i class="fa fa-spinner fa-pulse text-green fa-fw"></i>Loading data. Please Wait...</div>');
        var md = document.getElementById('mdname').value;
        var fyear = document.getElementById('yt_start').value;
        var tyear = document.getElementById('yt_end').value;
        var dataString = "fyear=" + fyear + "&tyear=" + tyear + "&md=" + md;
        if(fyear > tyear){
          $("#div_result").html('<div class="callout callout-warning"><h4>Range Warning!</h4>Cannot accept range! Year <b>FROM</b> must be lower than Year <b>TO</b>.</p></div>');
        }else{
          $.ajax({
            type: "GET",
            url: "year_query_result.php?r=m",
            data: dataString,
            cache: false,
            success: function (data) {
              console.log(data);
              $("#div_result").html(data);
            },
            error: function(err) {
              console.log(err);
            }
          });
        }
      }
      function showYearDetails(obj){
        var lbucode = $(obj).attr('data-lbucode');
        var lbuarea = $(obj).attr('data-lbuarea');
        $('#details-body').html('<div align="center"><i class="fa fa-spinner fa-pulse text-green fa-fw"></i>Loading data. Please Wait...</div>');
        $('#exportBranch').html('');
        var fyear = document.getElementById('yt_start').value;
        var tyear = document.getElementById('yt_end').value;
        var md = document.getElementById('mdname').value;
        var dataString = "fyear=" + fyear + "&tyear=" + tyear + "&md=" + md + "&lbucode=" + lbucode;
        $('#modal-title').html(md + '( ' + lbuarea + ' )');
        $.ajax({
          type: "GET",
          url: "year_query_result_lba_branch.php?r=m",
          data: dataString,
          cache: false,
          success: function (data) {
            console.log(data);
            $("#details-body").html(data);
          },
          error: function(err) {
            console.log(err);
          }
        });
      }
      function showYearProdDetails(obj){
        var branchcode = $(obj).attr('data-branchcode');
        var branchname = $(obj).attr('data-branchname');
        $('#prod-body').html('<div align="center"><i class="fa fa-spinner fa-pulse text-green fa-fw"></i>Loading data. Please Wait...</div>');
        $('#exportProd').html('');
        var fyear = document.getElementById('yt_start').value;
        var tyear = document.getElementById('yt_end').value;
        var md = document.getElementById('mdname').value;
        $('#prod-title').html(md + '( ' + branchname + ' )');
        var dataString = "fyear=" + fyear + "&tyear=" + tyear + "&md=" + md + "&branchcode=" + branchcode;
        $.ajax({
          type: "GET",
          url: "year_result_lba_branch_product.php?r=m",
          data: dataString,
          cache: false,
          success: function (data) {
            console.log(data);
            $("#prod-body").html(data);
          },
          error: function(err) {
            console.log(err);
          }
        });
      }

      function showResultQuarter(){
        $('#div_result').html('<div align="center"><i class="fa fa-spinner fa-pulse text-green fa-fw"></i>Loading data. Please Wait...</div>');
        var fmo = document.getElementById('qt_start').value;
        var md = document.getElementById('mdname').value;
        var fyear = document.getElementById('qty_start').value;
        var tmo = document.getElementById('qt_end').value;
        var tyear = document.getElementById('qty_end').value;
        var dataString = "fquarter=" + fmo + "&fyear=" + fyear + "&tquarter=" + tmo + "&tyear=" + tyear + "&md=" + md;

        var qfrom = fmo.replace("Q","");
        var qto = tmo.replace("Q","");

        // Validations
    		if(qfrom > qto && fyear > tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or quarter is higher than the ending year or quarter</p></div>');
    		}
    		else if(qfrom < qto && fyear > tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or quarter is higher than the ending year or quarter</p></div>');
    		}
    		else if(qfrom == qto && fyear > tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or quarter is higher than the ending year or quarter</p></div>');
    		}
    		else if(qfrom > qto && fyear == tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or quarter is higher than the ending year or quarter</p></div>');
    		}
        else{
          $.ajax({
            type: "GET",
            url: "quarter_query_result.php?r=m",
            data: dataString,
            cache: false,
            success: function (data) {
              console.log(data);
              $("#div_result").html(data);
            },
            error: function(err) {
              console.log(err);
            }
          });
        }
      }
      function showQuarterDetails(obj){
        var lbucode = $(obj).attr('data-lbucode');
        var lbuarea = $(obj).attr('data-lbuarea');
        $('#details-body').html('<div align="center"><i class="fa fa-spinner fa-pulse text-green fa-fw"></i>Loading data. Please Wait...</div>');
        $('#exportBranch').html('');
        var fmo = document.getElementById('qt_start').value;
        var md = document.getElementById('mdname').value;
        var fyear = document.getElementById('qty_start').value;
        var tmo = document.getElementById('qt_end').value;
        var tyear = document.getElementById('qty_end').value;
        var dataString = "fquarter=" + fmo + "&fyear=" + fyear + "&tquarter=" + tmo + "&tyear=" + tyear + "&md=" + md + "&lbucode=" + lbucode;
        $('#modal-title').html(md + '( ' + lbuarea + ' )');
        $.ajax({
          type: "GET",
          url: "quarter_query_result_lba_branch.php?r=m",
          data: dataString,
          cache: false,
          success: function (data) {
            console.log(data);
            $("#details-body").html(data);
          },
          error: function(err) {
            console.log(err);
          }
        });
      }
      function showQuarterProdDetails(obj){
        var branchcode = $(obj).attr('data-branchcode');
        var branchname = $(obj).attr('data-branchname');
        $('#prod-body').html('<div align="center"><i class="fa fa-spinner fa-pulse text-green fa-fw"></i>Loading data. Please Wait...</div>');
        $('#exportProd').html('');
        var fmo = document.getElementById('qt_start').value;
        var md = document.getElementById('mdname').value;
        var fyear = document.getElementById('qty_start').value;
        var tmo = document.getElementById('qt_end').value;
        var tyear = document.getElementById('qty_end').value;
        $('#prod-title').html(md + '( ' + branchname + ' )');
        var dataString = "fquarter=" + fmo + "&fyear=" + fyear + "&tquarter=" + tmo + "&tyear=" + tyear + "&md=" + md + "&branchcode=" + branchcode;
        $.ajax({
          type: "GET",
          url: "quarter_result_lba_branch_product.php?r=m",
          data: dataString,
          cache: false,
          success: function (data) {
            console.log(data);
            $("#prod-body").html(data);
          },
          error: function(err) {
            console.log(err);
          }
        });
      }


      function showResultMonth(){
        $('#div_result').html('<div align="center"><i class="fa fa-spinner fa-pulse text-green fa-fw"></i>Loading data. Please Wait...</div>');
        var fmo = document.getElementById('mt_start').value;
        var md = document.getElementById('mdname').value;
        var fyear = document.getElementById('mty_start').value;
        var tmo = document.getElementById('mt_end').value;
        var tyear = document.getElementById('mty_end').value;
        var dataString = "fmo=" + fmo + "&fyear=" + fyear + "&tmo=" + tmo + "&tyear=" + tyear + "&md=" + md;

        // Month array
        var month = new Array();
        month['January'] = "1";
        month['February'] = "2";
        month['March'] = "3";
        month['April'] = "4";
        month['May'] = "5";
        month['June'] = "6";
        month['July'] = "7";
        month['August'] = "8";
        month['September'] = "9";
        month['October'] = "10";
        month['November'] = "11";
        month['December'] = "12";

        // Validations
    		if(month[fmo] > month[tmo] && fyear > tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or month is higher than the ending year or month</p></div>');
    		}
    		else if(month[fmo] < month[tmo] && fyear > tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or month is higher than the ending year or month</p></div>');
    		}
    		else if(month[fmo] == month[tmo] && fyear > tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or month is higher than the ending year or month</p></div>');
    		}
    		else if(month[fmo] > month[tmo] && fyear == tyear){
    			$('#div_result').html('<div class="callout callout-warning"><h4>Range Warning!</h4><p>Cannot accept range!The starting year or month is higher than the ending year or month</p></div>');
    		}
        else{
          $.ajax({
            type: "GET",
            url: "query_result.php?r=m",
            data: dataString,
            cache: false,
            success: function (data) {
              console.log(data);
              $("#div_result").html(data);
            },
            error: function(err) {
              console.log(err);
            }
          });
        }
      }

      function showDetails(obj){
        var lbucode = $(obj).attr('data-lbucode');
        var lbuarea = $(obj).attr('data-lbuarea');
        $('#details-body').html('<div align="center"><i class="fa fa-spinner fa-pulse text-green fa-fw"></i>Loading data. Please Wait...</div>');
        $('#exportBranch').html('');
        var fmo = document.getElementById('mt_start').value;
        var md = document.getElementById('mdname').value;
        var fyear = document.getElementById('mty_start').value;
        var tmo = document.getElementById('mt_end').value;
        var tyear = document.getElementById('mty_end').value;
        $('#modal-title').html(md + '( ' + lbuarea + ' )');
        var dataString = "fmo=" + fmo + "&fyear=" + fyear + "&tmo=" + tmo + "&tyear=" + tyear + "&md=" + md + "&lbucode=" + lbucode;
        $.ajax({
          type: "GET",
          url: "query_result_lba_branch.php?r=m",
          data: dataString,
          cache: false,
          success: function (data) {
            console.log(data);
            $("#details-body").html(data);
          },
          error: function(err) {
            console.log(err);
          }
        });
      }
      function showProdDetails(obj){
        var branchcode = $(obj).attr('data-branchcode');
        var branchname = $(obj).attr('data-branchname');
        $('#prod-body').html('<div align="center"><i class="fa fa-spinner fa-pulse text-green fa-fw"></i>Loading data. Please Wait...</div>');
        $('#exportProd').html('');
        var fmo = document.getElementById('mt_start').value;
        var md = document.getElementById('mdname').value;
        var fyear = document.getElementById('mty_start').value;
        var tmo = document.getElementById('mt_end').value;
        var tyear = document.getElementById('mty_end').value;
        $('#prod-title').html(md + '( ' + branchname + ' )');
        var dataString = "fmo=" + fmo + "&fyear=" + fyear + "&tmo=" + tmo + "&tyear=" + tyear + "&md=" + md + "&branchcode=" + branchcode;
        $.ajax({
          type: "GET",
          url: "result_lba_branch_product.php?r=m",
          data: dataString,
          cache: false,
          success: function (data) {
            console.log(data);
            $("#prod-body").html(data);
          },
          error: function(err) {
            console.log(err);
          }
        });
      }
  </script>
  <style type="text/css">
    .separator {
      display: inline-block;
      width: 8px;
      border-right: 1px solid #666;
      height: 25px;
      cursor: default;
      margin: 4px 4px -8px -2px;
      border-right: 1px solid #D6D6D6;
    }
    div.dt-buttons {
        position: relative;
        float: none !important;
    }
    .margin5 {
        margin-top:-5px !important;
    }
    .dropdown-toggle{
      width: 270px !important;
    }
  </style>

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="index.php" class="logo" style="background-color: #256188;">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>BK</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg" align="left" style="font-size:14px;font-weight:bold;">
		<img src="../../dist/img/BK LOGO.png" class="img-circle" alt="User Image" style="width:40px;">
		MDC SENIOR SYSTEM
	</span>

    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a class="pull-left" data-toggle="push-menu" role="button" style="margin-left: 10px;margin-top: 15px;color: #FFFFFF;cursor: pointer;"><i class="fa fa-bars"></i> </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../../dist/img/admin.png" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $_SESSION['authUser'];?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="../../dist/img/admin.png" class="img-circle" alt="User Image">

                <p>
                  <?php echo $_SESSION['authUser'];?>
                  <small><?php echo $_SESSION['authRole'];?></small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-right">
                  <a href="../../logout.php" class="btn btn-warning btn-flat"><i class="fa fa-sign-out-alt"></i> Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel (optional) -->

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li><a href="../index.php"><i class="fa fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
        <?php if($_SESSION['authRole'] == "TEAM LEADER"){?>
        <li class="header">SANITATION MANAGEMENT</li>
        <li><a href="../dir_sanitation_management/user_maintenance.php"><i class="fa fa-users-cog"></i> <span>User Maintenance</span></a></li>
        <li><a href="../dir_sanitation_management/district_assignment.php"><i class="fa fa-user-tag"></i> <span>District Assignment</span></a></li>
        <li><a href="../dir_export_report/export_report.php"><i class="fa fa-download"></i> <span>Export Data</span></a></li>
        <?php }?>
        <li class="header">RULES MANAGEMENT</li>
        <li><a href="../dir_rules_management/add_new_rule.php"><i class="fa fa-plus"></i> <span>Add New Rule</span></a></li>
        <?php if($_SESSION['authRole'] == "TEAM LEADER"){?>
        <li><a href="../dir_rules_management/rules_list.php"><i class="fa fa-tasks"></i> <span>Rules List</span></a></li>
        <?php }?>
        <li class="header">DATA SANITATION</li>
        <li>
          <a href="../dir_data_sanitation/data-sanitation.php"><i class="fa fa-clipboard-check"></i> <span>Masterlist Sanitation</span></a>
        </li>
        <li>
          <a href="../dir_data_sanitation/data-sanitation-not-masterlist.php"><i class="fa fa-clipboard-check"></i> <span>Not Masterlist Sanitation</span></a>
        </li>
        <li class="header">REPORTS</li>
        <li class="active"><a href="../dir_reports/reports.php" class="bg-blue"><i class="fa fa-folder"></i> <span>MDC SC Report</span></a></li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-folder text-green"></i> MDC SC Report
        <small>Generate reports</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-folder"></i> Home</a></li>
        <li class="active">MDC SC Report</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom" style="font-size:16px;">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab"><b>Report Per MD</b></a></li>
              <li><a href="#tab_2" data-toggle="tab">Report Per Class</a></li>
              <li><a href="#tab_3" data-toggle="tab">Report Per Product</a></li>
              <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="box box-widget">
                  <div class="box-header with-border bg-gray disabled" style="padding-bottom:6px;padding-top:6px;">
                    <h4 class="box-title text-blue"><i class="fa fa-filter"></i> <b>Filter Options</b></h4>
                  </div>
                  <div class="box-body">

                    <div class="row no-padding">
                      <div class="col-md-3" style="padding-right:1px;">
                        <div class="input-group">
                          <span class="input-group-addon bg-info"><i class="fa fa-search"></i> Search MD</span>
                          <select class="form-control select2" id="mdname">
                            <?php
                              $mdQuery = $mysqli -> query("SELECT DISTINCT sanit_mdname FROM mdc_senior.db_sanitation");
                                while($mdRes = $mdQuery -> fetch_assoc()){
                            ?>
                            <option value="<?php echo $mdRes['sanit_mdname'];?>"><?php echo $mdRes['sanit_mdname'];?></option>
                            <?php }?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-5" style="padding-left:1px;">
                        <span class="separator"></span>
                        <div style="margin-top:4px;display:inline-block;" id="range_option" class='form-group'>
                          <strong>Select Range Options :</strong>
                            <label onClick="ShowNoneTrend()" style="font-weight:normal"><input type="radio" name="r3" class="minimal-red" checked> None</label>
                            <label onClick="ShowYTrend()" style="font-weight:normal"><input type="radio" name="r3" class="minimal-red"> Year</label>
                            <label onClick="ShowQTrend()" style="font-weight:normal"><input type="radio" name="r3" class="minimal-red"> Quarter</label>
                            <label onClick="ShowMTrend()" style="font-weight:normal"><input type="radio" name="r3" class="minimal-red"> Month</label>
                        </div>
                      </div>

                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <?php include('dateperiod.php');?>
                        </div>
                      </div>
                    </div>

                  </div>
                  <div class="row">
                    <div class="col-md-12" id="div_result">
                      <!----- For Result --->
                    </div>
                  </div>

                </div>

                <!-- For Reports per Product  -->
                <div class="tab-pane" id="tab_3">
                  <div class="box box-widget">
                    <div class="box-header with-border bg-gray disabled" style="padding-bottom:6px;padding-top:6px;">
                      <h4 class="box-title text-blue"><i class="fa fa-filter"></i> <b>Filter Options</b></h4>
                    </div>
                    <div class="box-body">

                      <div class="row no-padding">
                        <div class="col-md-3" style="padding-right:1px;">
                          <div class="input-group">
                            <span class="input-group-addon bg-info"><i class="fa fa-search"></i> Select Product</span>
                            <select class="selectpicker show-tick" multiple="multiple" name="prodname[]" id="prodname">
                              <?php
                                $mdQuery = $mysqli -> query("SELECT DISTINCT prod_code,prod_name FROM mdc_senior.mst_productdb ORDER BY prod_name ASC");
                                  while($mdRes = $mdQuery -> fetch_assoc()){
                              ?>
                              <option value="<?php echo $mdRes['prod_code'];?>"><?php echo $mdRes['prod_name'];?></option>
                              <?php }?>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-5" style="padding-left:1px;">
                          <span class="separator"></span>
                          <div style="margin-top:4px;display:inline-block;" id="range_option" class='form-group'>
                            <strong>Select Range Options :</strong>
                              <label onClick="ShowNoneTrendP()" style="font-weight:normal"><input type="radio" name="r3" class="minimal-red" checked> None</label>
                              <label onClick="ShowYTrendP()" style="font-weight:normal"><input type="radio" name="r3" class="minimal-red"> Year</label>
                              <label onClick="ShowQTrendP()" style="font-weight:normal"><input type="radio" name="r3" class="minimal-red"> Quarter</label>
                              <label onClick="ShowMTrendP()" style="font-weight:normal"><input type="radio" name="r3" class="minimal-red"> Month</label>
                          </div>
                        </div>

                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <?php include('dateperiod_products.php');?>
                          </div>
                        </div>
                      </div>

                    </div>
                    <div class="row">
                      <div class="col-md-12" id="div_result_prod">
                        <!----- For Result --->
                      </div>
                    </div>

                  </div>
                <!-- End Reports Per Product -->

              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.0.0
    </div>
    <strong>Copyright &copy; 2018 <a href="#">MDC Senior System</a>.</strong> All rights
    reserved.
  </footer>


</div>
<!-- ./wrapper -->

<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" style="width: 80%">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modal-title" style="display: inline-block;"></h3>
                <span class="separator"></span>
                <input type="text" id="myInputTextFieldB" class="form-control" style="display:inline-block;width:15%" placeholder="Search..."></input>
                <span class="separator"></span>
                <span style="display: inline-block;" id="exportBranch"></span>
                <button class="btn-sm close" type="button" data-dismiss="modal">Close</button>
            </div>

            <div id="details-body" class="modal-body no-padding" style="padding-right:2px !important;padding-left:2px !important;">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="prodModal" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" style="width: 80%">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="prod-title" style="display: inline-block;"></h3>
                <span class="separator"></span>
                <input type="text" id="myInputTextField" class="form-control" style="display:inline-block;width:15%" placeholder="Search..."></input>
                <span class="separator"></span>
                <span style="display: inline-block;" id="exportProd"></span>
                <button class="btn-sm close" type="button" data-dismiss="modal">Close</button>
            </div>

            <div id="prod-body" class="modal-body no-padding">

            </div>
        </div>
    </div>
</div>
<!-- LBA-Branch Per Product -->
<div class="modal fade" id="_prodModal" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" style="width: 80%">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="_prod-title" style="display: inline-block;"></h3>
                <span class="separator"></span>
                <input type="text" id="_myInputTextField" class="form-control" style="display:inline-block;width:15%" placeholder="Search..."></input>
                <span class="separator"></span>
                <span style="display: inline-block;" id="_exportProd"></span>
                <button class="btn-sm close" type="button" data-dismiss="modal">Close</button>
            </div>

            <div id="_prod-body" class="modal-body no-padding">

            </div>
        </div>
    </div>
</div>


<!-- jQuery 3 -->
<script src="../../dependencies/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../../dependencies/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="../../dependencies/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../../plugins/bootstrap-select/js/bootstrap-select.min.js"></script>
<!-- DataTables -->
<script src="../../dependencies/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="../../dependencies/datatables/media/js/dataTables.bootstrap.min.js"></script>
<script src="../../dependencies/datatables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script src="../../dependencies/datatables/extensions/jsZip/jszip.min.js"></script>
<script src="../../dependencies/datatables/extensions/Buttons/js/buttons.html5.js"></script>
<!-- Select2 -->
<script src="../../dependencies/select2/dist/js/select2.full.min.js"></script>
<!-- Sparkline -->
<script src="../../dependencies/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="../../plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../../plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="../../dependencies/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../../dependencies/moment/min/moment.min.js"></script>
<script src="../../dependencies/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="../../dependencies/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="../../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="../../dependencies/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="../../plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->
<script src="../../dependencies/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<script>
  $(function () {
    $('.select2').select2()
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })

  })
</script>
</body>
</html>
