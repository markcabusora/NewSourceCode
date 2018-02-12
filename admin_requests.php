
<?php
  require('dbconnect.php');
  session_start();

  $userID = $_SESSION['user_id'];

  if(isset($_GET['id'])){
  	$approvedID = $_GET['id'];

  	$uploadCode = AutoGenerateRequestID();

  	$querySendCode = "UPDATE tblRequest SET UPLOAD_CODE = '$uploadCode' WHERE REQUEST_ID = '$approvedID'";
  	$sendCodeRes = mysqli_query($conn, $querySendCode);
  	header('location: admin_requests.php');
  }
  elseif(isset($_GET['delete_request'])){
  	$deleteID = $_GET['delete_request'];

  	$queryDeleteRequest = "DELETE FROM tblRequest WHERE REQUEST_ID = '$deleteID'";
  	$deleteRequestRes = mysqli_query($conn, $queryDeleteRequest);

  	header('location: admin_requests.php');
  }
  else {
  	$queryRequest = "SELECT * FROM tblRequest WHERE UPLOAD_CODE = ''";
 	$requestRes = mysqli_query($conn, $queryRequest);
  }


  $queryUser = "SELECT * FROM tblUsers WHERE user_id = '$userID'";

  $queryResult = mysqli_query($conn,$queryUser);

  $rowLoggedUser = mysqli_fetch_array($queryResult);

  $_SESSION['user_id'] = $userID;

  

  function AutoGenerateRequestID() { 

            $s = strtoupper(md5(uniqid(rand(),true)));
 
            $guidText = str_pad('R',8,substr($s,0,9));
     
            return $guidText;
        }

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Thesys | Requests (Admin)</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="dist/css/skins/skin-green-light.min.css">

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-green-light sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
       <span class="logo-mini"><b>T</b>SYS</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>THE</b>SYS</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
  
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
             
              <img src="images/user.png" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $rowLoggedUser['user_type']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="images/user.png" class="img-circle" alt="User Image">
                <p>
                  <?php echo $rowLoggedUser['last_name'].", ";?>
                  <?php echo $rowLoggedUser['first_name']." ";?>
                  <?php echo $rowLoggedUser['middle_initial']."."?>
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
                  <a href="logout.php" class="btn btn-default btn-flat">Sign Out</a>
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
      <div class="user-panel">
        <div class="pull-left image">
          <img src="images/user.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>
            <br>
            <?php echo $rowLoggedUser['first_name']."<br>".$rowLoggedUser['last_name'];?>
          </p>
          <!-- Status -->
         
        </div>
      </div>

      <!-- search form (Optional) -->
      
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
      
        <!-- Optionally, you can add icons to the links -->
        <li><a href="admin_dashboard.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
        <li><a href="admin_users.php"><i class="fa fa-users"></i> <span>Users</span></a></li>
        <li><a href="admin_thesis.php"><i class="fa fa-book"></i> <span>Theses</span></a></li>
        <li class="active"><a href="admin_requests.php"><i class="fa fa-th"></i> <span>Requests</span></a></li>
        
      </ul>

      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Requests
        <small><!-- requests of? --></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="admin_thesis.php"><i class="fa fa-book"></i>Theses</a></li>
        <li class="active">Requests (Administrator)</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
    <div class="row">
      <div class="box-body">
        <div class="col-md-12">
          <table class="table table-bordered" style="width:100%;">
            <tr>
              <th>Request ID</th>
              <th>User ID</th>
              <th>Date Requested</th>
              <th>Actions</th>
            </tr>
                
            <tr>
              <?php
                if($requestRes->num_rows >0) {
                	while($rowRequest = mysqli_fetch_assoc($requestRes)){
                	?>
             
             <td><?php echo $rowRequest['REQUEST_ID']?></td>
             <td><?php echo $rowRequest['USER_ID']?></td>
             <td><?php echo $rowRequest['DATE_REQUESTED']?></td>
             <td><a href="admin_requests.php?id=<?php echo $rowRequest['REQUEST_ID']?>" class="btn btn-sm btn-success" data-toggle="tooltip" title="Approve Request"><i class="fa fa-check"></i></a>
                    <a href="javascript:delete_id(<?php echo $rowRequest['REQUEST_ID']; ?>)" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Deny Request"><i class="fa fa-trash"></i></a></td>
                 <?php
                 		} 
                  }else {
                 ?>
            <td colspan="4">No records found.</td>
                 <?php
                    }
                 ?>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

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