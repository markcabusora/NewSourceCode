<?php
    require 'dbconnect.php';

    session_start();

    $userID = $_SESSION['user_id'];

    if(isset($_GET['thesis_id']))
    {
      $viewThesisID = $_GET['thesis_id'];

      $thesisViewQuery = "SELECT * FROM tblThesis WHERE thesis_id='".$viewThesisID."'";
      $thesisViewQueryResult = mysqli_query($conn, $thesisViewQuery);

      $viewThesisAbstract = "SELECT * FROM tblThesis_abstract WHERE thesis_id = '".$viewThesisID."'";
      $viewThesisAbstractResult = mysqli_query($conn, $viewThesisAbstract);

      $queryThesisAuthor = "SELECT * FROM tblProponents WHERE thesis_id = '".$viewThesisID."'";
      $queryThesisAuthorResult = mysqli_query($conn, $queryThesisAuthor);

      $queryThesisEvaluator = "SELECT * FROM tblThesis_evaluators WHERE thesis_id = '".$viewThesisID."'";
      $queryThesisEvaluatorResult = mysqli_query($conn, $queryThesisEvaluator);

      $rowThesis = mysqli_fetch_array($thesisViewQueryResult);
      $rowThesisAbstract = mysqli_fetch_array($viewThesisAbstractResult);

      $thesisViewRecord = "INSERT INTO tblThesis_views(user_id, thesis_id, date_and_time)
                          VALUES('$userID', '$viewThesisID', now())";
      $thesisViewRecordResult = mysqli_query($conn, $thesisViewRecord);

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

  $userType = $rowLoggedUser['user_type'];

  //preparation of session ID for next landing page
   $_SESSION['user_id'] = $userID;
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Thesys | Thesis Details Page (Faculty)</title>
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
    <a href="admin_dashboard.php" class="logo">
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
              <span class="hidden-xs"><?php echo $rowLoggedUser['user_type'] ?></span>
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
       <li><a href="admin_dashboard.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
       <li>
          <a href="admin_users.php"><i class="fa fa-users"></i> <span>Users</span>
          </a>
        </li>
        <li class="active">
          <a href="admin_thesis.php"><i class="fa fa-book"></i> <span>Theses</span>
          </a>
        </li>
       <li><a href="admin_requests.php"><i class="fa fa-th"></i> <span>Requests</span></a></li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Thesis Details
        <small>Administrator</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-sm-4">
          <div class="box">
            <div class="box-header">

              <center><font size="+2"><b>Abstract</b></font></center>
              <br>
              <?php 
                echo nl2br($rowThesisAbstract['abstract']);
              ?>
            </div>
          </div>
        </div>

        <div class="col-sm-8">
          <div class="box-body">
             <ul class="list-inline">
                <li><i class="fa fa-book"></i></li>
                <li><p><?php echo $rowThesis['thesis_title'] ?></p></li>
              </ul>

            <ul class="list-inline">
                <li><i class="fa fa-bookmark"></i></li>
                <li><p>*Insert Category*</p></li>
            </ul>

            <ul class="list-inline">
                <li><i class="fa fa-users"></i></li>
                <li>
                  <p>
                    <?php  
                      while($rowAuthors = mysqli_fetch_array($queryThesisAuthorResult))
                      {
                        echo $rowAuthors['last_name'].", ".$rowAuthors['first_name']." ".$rowAuthors['middle_initial']."., ";
                      }
                    ?>
                  </p>
                </li>
            </ul>

            <ul class="list-inline">
                <li><i class="fa fa-calendar-check-o"></i></li>
                <li><p><?php echo $rowThesis['year_accomplished']?></p></li>
            </ul>

            <ul class="list-inline">
                <li><i class="fa fa-check-square"></i></li>
                <li>
                  <p>
                    <?php
                        while($rowThesisEvaluators = mysqli_fetch_array($queryThesisEvaluatorResult))
                        {
                          $queryEvaluators = "SELECT * FROM tblEvaluators WHERE evaluator_id ='".$rowThesisEvaluators['evaluator_id']."'";
                          $queryEvaluatorsResult = mysqli_query($conn, $queryEvaluators);

                          while($rowEvaluators = mysqli_fetch_array($queryEvaluatorsResult))
                          {
                            echo $rowEvaluators['last_name'].", ".$rowEvaluators['first_name']." ".$rowEvaluators['middle_initial'].".,";
                          }
                        }  
                    ?>
                  </p>
                </li>
            </ul>

          <div class="row">
            <div class="box-body">
              <div class="col-sm-8">
                <div class="row">
                </div>               
              </div>
              
              <div class="col-sm-4">
                
              </div>
            </div>
          </div>
        </div>

        <!-- div class="row">
          <div class="box">
            <div class="box-header"><h4>Suggested Theses</h4></div>
              <div class="box-body">
                <?php
                  if(isset($_GET['thesis_id'])){
                    $currThesisID = $_GET['thesis_id'];
                    $querySuggestedTheses = "SELECT DISTINCT(thesis_id) FROM tblThesis_views WHERE user_id != '$userID' AND thesis_id !='$currThesisID' ORDER BY date_and_time DESC LIMIT 5";
                  }
                  $suggestedThesesRes = mysqli_query($conn, $querySuggestedTheses);

                  while($rowSuggRes = mysqli_fetch_assoc($suggestedThesesRes)){
                    $thesisID = $rowSuggRes['thesis_id'];
                    $queryThesis = "SELECT * FROM tblThesis WHERE thesis_id = '$thesisID'";
                    $thesisRes = mysqli_query($conn, $queryThesis);

                    while($rowThesis = mysqli_fetch_assoc($thesisRes)){

                ?>
                  <div class="col-sm-3"><br><br><br><br><br><br><br><a href="admin_view_thesis.php?thesis_id=<?php echo $rowThesis['thesis_id']?>"><?php echo $rowThesis['thesis_title'];?></a></div>
                <?php
                    }
                }
                ?>
              </div>
             
            </div>            
        </div>

        </div>
      </div>
    </div>  -->
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
