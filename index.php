<?php
header("Content-Type: text/html; charset=ISO-8859-1");
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="ISO-8859-1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="shortcut icon" href="dist/img/BK LOGO.png">
  <title>MDC Senior System | Login</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="dependencies/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="dependencies/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="dependencies/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page" style="background: #314755; /* fallback for old browsers */
  background: -webkit-linear-gradient(to right, #314755, #26a0da); /* Chrome 10-25, Safari 5.1-6 */
  background: linear-gradient(to right, #314755, #26a0da); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */">
<div class="login-box" style="">
  <!-- /.login-logo -->
  <div class="login-box-body" align="center">
    <h1 style="margin-top: 2px;margin-bottom: -5px;"><b>MDC SENIOR SYSTEM V2.0</b></h1>
    <p class="login-box-msg"></p>

    <form action="verify.php" method="post">
      <div class="form-group has-feedback">
        <input type="text" name="username" class="form-control" placeholder="Username" required="required">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="Password" required="required">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <?php
        if(isset($_SESSION['error_login'])){
      ?>
      <div class="alert alert-danger alert-dismissible" align="left">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-ban"></i> Alert!</h4>
        Wrong Username or Password, Try again!
      </div>
      <?php }?>
      <div class="row" align="right">
        <div class="col-xs-12">
            <button type="submit" class="btn btn-primary btn-flat" name="btnLogin">Login Account</button>
        </div>
      </div>
    </form>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="dependencies/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="dependencies/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
