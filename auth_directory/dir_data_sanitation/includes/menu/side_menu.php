<?php $page = basename($_SERVER['PHP_SELF']); ?>
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel" style="min-height:50px;">

      <div class="pull-left image" style="min-height:50px;color:#ffffff;font-weight:bold;">
        <p>MDC SENIOR SYSTEM</p>
        <!-- Status -->
        <a href="#">Version 2.0</a>
      </div>
    </div>

    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN NAVIGATION</li>
      <li><a href="index.php"><i class="fa fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
      <li><a href="index.php"><i class="fa fa-tachometer-alt"></i> <span>Team Roles</span></a></li>
      <li><a href="index.php"><i class="fa fa-tachometer-alt"></i> <span>District Assignment</span></a></li>
      <li><a href="index.php"><i class="fa fa-tachometer-alt"></i> <span>Rules Management</span></a></li>
      <li <?php if($page == 'data-sanitation.php'){ echo 'class="active"';} ?>><a href="data-sanitation.php"><i class="fa fa-tachometer-alt"></i> <span>Data Sanitation</span></a></li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
