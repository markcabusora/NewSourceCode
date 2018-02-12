<?php
  require 'dbconnect.php';
  session_start();

  $userID = $_SESSION['user_id'];

  if(isset($_GET['selectedCategory']))
  {
    $categoryID = $_GET['selectedCategory'];
    $queryThesis = "SELECT * FROM tblThesis_Category where category_id = '$categoryID'";
    $thesisResult = mysqli_query($conn, $queryThesis);
  }


  //checks whether a user account is logged in or not
  if(!$userID)
  {
      header('location: index.html');
  }

  $queryLoggedUser = "SELECT * FROM tblUsers WHERE user_id = '$userID'";
  $queryLoggedUserResult = mysqli_query($conn, $queryLoggedUser);

  //fetching of result of queryUserResult

  $rowLoggedUser = mysqli_fetch_assoc($queryLoggedUserResult);

  //preparation of session ID for next landing page

  $_SESSION['user_id'] = $userID;


?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Thesys | Dashboard (Student)</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-green-light sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="student_dashboard.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>T</b>SYS</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>THE</b>SYS</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
             
              <img src="images/user.png" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $rowLoggedUser['user_type']?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="images/user.png" class="img-circle" alt="User Image">
                <p>
                 <?php echo $rowLoggedUser['last_name']; echo ", "; echo $rowLoggedUser['first_name']; echo" "; echo $rowLoggedUser['middle_initial']; echo ".";?>
             </p>
              </li>
              <!-- Menu Body -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <!--<input type="submit" name="" class="btn btn-default btn-flat"></a>-->
                </div>
                <div class="pull-right">
                  <a href="logout.php" class="btn btn-default btn-flat" >Sign Out</a>
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
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="images/user.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
           <p><br>
            <?php echo $rowLoggedUser['first_name']?> <br>
            <?php echo $rowLoggedUser['last_name']?>
          </p>
        </div>
      </div>

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
       <li class="active"><a href="student_dashboard.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
        <li class="treeview">
          
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
      <h1>
        Categories
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Main row -->
      <div class="row">
        <div class="col-sm-4">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Categories</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <?php
            if(isset($_GET['selectedCategory']))
            {
              $queryCategory = "SELECT * FROM tblCategory ORDER BY category_name";
              $categoryResult = mysqli_query($conn, $queryCategory);

              while($rowCategory = mysqli_fetch_array($categoryResult))
              {
            ?>
             <p><a href="student_view_category.php?selectedCategory=<?php echo $rowCategory['id']?>"><?php echo $rowCategory['category_name']?></a></p>
            <?php
              }
            }
            ?>
            </div>
            <!-- /.box-body -->
          </div>
        </div>



        <div class="col-sm-8">
          <div class="box">
            <div class="box-header">
              <div class="form-group">
                <form action="student_view_category.php" method="post">
                <div class="input-group margin">
                <div class="input-group-btn" style="width: 20%">
                  <select class="form-control select2" name="category">
                      <option selected="selected">Thesis Title</option>
                      <option>Thesis Code</option>
                      <option>Year Accomplished</option>
                    </select>
                </div>
                <!-- /btn-group -->
                <input type="text" class="form-control" placeholder="Search" style="width: 70%" autofocus="">
                <input type="submit" name="search" value="Search" class="btn btn-default" btn-sm style="margin-left: 1%">
                <input type="reset" name="clear" value="Clear" class="btn btn-default" style="margin-left: 1%">
                </form>
                </div>
              </div>
              <h4>Search Result/s:</h4>
            </div>
            <!-- /.box-header -->
            <?php

            if(isset($_GET['selectedCategory']))
            {
              while($rowThesesCategory = mysqli_fetch_array($thesisResult))
              {
                $querySelectedTheses = "SELECT * FROM tblThesis WHERE thesis_id='".$rowThesesCategory['thesis_id']."'";
                $selectedThesesResult = mysqli_query($conn, $querySelectedTheses);

                while($rowSelectedTheses = mysqli_fetch_array($selectedThesesResult))
                  {
                    $queryAuthors = "SELECT * FROM tblProponents WHERE thesis_id ='".$rowSelectedTheses['thesis_id']."'";
                    $authorsResult = mysqli_query($conn, $queryAuthors);
            ?>
            <div class="box-body">
              <a href="student_thesis_view.php?thesis_id=<?php echo $rowSelectedTheses['thesis_id']?>"><h4><?php echo $rowSelectedTheses['thesis_title']?></h4></a>
              <?php
                  while($rowAuthors = mysqli_fetch_array($authorsResult))
                  {
              ?>
              <i class="fa fa-users"></i><?php echo $rowAuthors['first_name']." ".$rowAuthors['middle_initial'].". ".$rowAuthors['last_name'].", ";?><BR>
              <?php
                  }
              ?>
              <i class="fa fa-calendar"><?php echo $rowSelectedTheses['year_accomplished']?></i>
            </div>
            <?php
                  }
              }
            }
            ?>

            <!-- /.box-body -->
          </div>

          <ul class="pagination pagination-sm no-margin pull-right">
                  <li><a href="#">&laquo;</a></li>
                  <li><a href="#">1</a></li>
                  <li><a href="#">2</a></li>
                  <li><a href="#">3</a></li>
                  <li><a href="#">&raquo;</a></li>
          </ul>

        </div>
        </div>

      </div>
    </section>
  </div>
  <!-- /.content-wrapper -->
<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="bower_components/raphael/raphael.min.js"></script>
<script src="bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="bower_components/moment/min/moment.min.js"></script>
<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>

