<?php
  require 'dbconnect.php';
  if(isset($_GET['namestart'])){
    $nameStart = $_GET['namestart'];
    $queryThesis = "SELECT * FROM tblThesis WHERE status LIKE('active') AND thesis_title LIKE('$nameStart%')";
    $queryThesisResult = mysqli_query($conn, $queryThesis);
    
  }
  elseif(isset($_GET['cat'])){
    $cat = $_GET['cat'];
    if($cat == 'mostcited') {
      $queryThesis = "SELECT id, thesis_id, data_and_time, COUNT(*) as citation_count FROM tblThesis_citation WHERE citation_count > 50 GROUP by thesis_id";
      $queryThesisResult = mysqli_query($conn, $queryThesis);
    }
  }
  else{
    $queryThesis = "SELECT * FROM tblThesis WHERE status LIKE('active') and thesis_title LIKE('A')";
    $queryThesisResult = mysqli_query($conn, $queryThesis);
  }

  session_start();

  $userID = $_SESSION['user_id'];


  //checks whether a user account is logged in or not
  if(!$userID)
  {
      header('location: index.html');
  }

  $queryLoggedUser = "SELECT * FROM tblUsers WHERE user_id = '$userID'";
  $queryLoggedUserResult = mysqli_query($conn, $queryLoggedUser);

  //fetching of result of queryUserResult

  $rowLoggedUser = mysqli_fetch_assoc($queryLoggedUserResult);

  $userType = $rowLoggedUser['user_type'];

  //preparation of session ID for next landing page

  $_SESSION['user_id'] = $userID;

  

  $queryRecentVisits = "SELECT * FROM tblThesis_views WHERE user_id LIKE('$userID') ORDER BY date_and_time DESC LIMIT 5";
  $queryRecentVisitsResult = mysqli_query($conn, $queryRecentVisits);



?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Thesys | Dashboard</title>
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
    <a href="users_dashboard.php" class="logo">
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
                  <?php echo $rowLoggedUser['last_name']; echo ", "; echo $rowLoggedUser['first_name']; echo " "; echo $rowLoggedUser['middle_initial']; echo "."; ?>
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
          <Br>
           <p>
            <?php
              echo $rowLoggedUser['first_name']
            ?>
            <br>
            <?php 
              echo $rowLoggedUser['last_name'] 
            ?>
          </p>
        </div>
      </div>

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">

       <!--<li class="active"><a href="student_dashboard.php"><i class="fa fa-th"></i> <span>Dashboard</span></a></li>-->
       <!--<li><a href="users_requests.php"><i class="fa fa-th"></i> <span>Requests</span></a></li>-->

       <li class="active"><a href="student_dashboard.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>

        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Search
        <small>Theses</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-sm-8">
          <div class="box">
            <div class="box-header">
              <form action="student_dashboard.php" method="post">
              <div class="form-group">
                <div class="input-group margin">
                <div class="input-group-btn">
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Search for&nbsp;
                    <span class="fa fa-caret-down" style="width: 30%"></span></button>
                  <ul class="dropdown-menu">
                  </ul>
                </div>
                <!-- /btn-group -->
                <input type="text" class="form-control" placeholder="Search" style="width: 70%" autofocus="">
                <input type="submit" name="search" value="Search" class="btn btn-default" btn-sm style="margin-left: 1%">
                <input type="reset" name="clear" value="Clear" class="btn btn-default" style="margin-left: 1%">
                </form>
                </div>
              </div>

              <div class="btn-group btn-group-justified">
                <?php
                  $alphabetPage = range('A', 'Z');
                  foreach($alphabetPage as $key)
                    {
                ?>
                    <div class="btn-group"><a href="student_dashboard.php?namestart=<?php echo $key ?>"><button type="button" class="btn btn-primary btn-sm"><?php echo $key?></button></a></div>
                <?php      
                    }
                ?>
              </div>

              <div class="btn-group" style="margin-top: 1%">
                <div class="btn-group"><a href="users_dashboard.php?cat=mostcited"><button type="button" class="btn btn-primary">Most Cited</button></a></div>
                <div class="btn-group"><button type="button" class="btn btn-primary" style="margin-left: 4%">Most Viewed</button></div>
                <div class="btn-group"><button type="button" class="btn btn-primary" style="margin-left: 6%">Most Downloaded</button></div>
              </div>

              <div class="btn-group pull-right" style="margin-top: 3%; margin-right: 2%">
                <button type="button" class="btn bg-purple btn-circle btn-xs" 
                      style="width: 15px; height: 15px; padding: 6px 0px; line-height: 1.42; border-radius: 15px;"></button>
                      <button type="button" class="btn bg-olive btn-circle btn-xs"
                      style="width: 15px; height: 15px; padding: 6px 0px; line-height: 1.42; border-radius: 15px;"></button>
                      <button type="button" class="btn bg-orange btn-circle btn-xs"
                      style="width: 15px; height: 15px; padding: 6px 0px; line-height: 1.42; border-radius: 15px;"></button>
              </div>
              <h5>Search Query: <?php if(!empty(isset($_GET['namestart']))) {echo "Thesis title that starts with letter ".$nameStart;}?></h5>
            </div>
            <!-- /.box-header -->
            
            <?php

                while($rowThesis = mysqli_fetch_array($queryThesisResult))
                {
                  $queryAuthors = "SELECT * FROM tblProponents WHERE thesis_id='".$rowThesis['thesis_id']."'";
                  $queryAuthorsResult = mysqli_query($conn, $queryAuthors);
            ?>
            <div class="box-body">
                  <a href="users_thesis_view.php?thesis_id=<?php echo $rowThesis['thesis_id']; ?>"><h4><?php echo $rowThesis['thesis_title'] ?></h4></a>
                  <i class="fa fa-users">
            <?php  
                  while($rowAuthors = mysqli_fetch_array($queryAuthorsResult))
                  {
                    echo $rowAuthors['first_name']." ".$rowAuthors['middle_initial'].". ".$rowAuthors['last_name'].", ";
                  }
            ?>
                  </i><br>
                  <i class="fa fa-calendar"><?php echo $rowThesis['year_accomplished']; ?></i>
            </div>
            <?php
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
       
        <div class="col-sm-4">          
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Recent Thesis Visits</h3>
            </div>
            <!-- /.box-header -->
            <?php
              while($rowRecentVisits = mysqli_fetch_array($queryRecentVisitsResult))
              {
                $queryCheckThesis = "SELECT * FROM tblThesis WHERE thesis_id = '".$rowRecentVisits['thesis_id']."'";
                $queryCheckThesisResult = mysqli_query($conn, $queryCheckThesis);

                while($rowCheckThesis = mysqli_fetch_array($queryCheckThesisResult))
                {
            ?>
            <div class="box-body">
              <a href="#">
                <h4>
                <?php 
                  echo $rowCheckThesis['thesis_title'];
                }
                ?>  
                </h4>
              </a>
              <i class="fa fa-calendar">
                <?php
                  echo $rowRecentVisits['date_and_time'];
                ?>
              </i>
            </div>
            <?php
              }
            ?>
            <!-- /.box-body -->
          </div>

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Categories</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             <?php 
                $queryCategories = "SELECT * FROM tblCategory";
                $categoriesResult = mysqli_query($conn, $queryCategories);

                while($rowCategory = mysqli_fetch_array($categoriesResult))
                {
             ?>
                  <p><a href="student_view_category.php?selectedCategory=<?php echo $rowCategory['id']?>"><?php echo $rowCategory['category_name']?></a></p>
             <?php
                }
             ?>

              <a href="#" class="pull-right">See More</a>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>

 
</div>
<!-- ./wrapper -->

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
