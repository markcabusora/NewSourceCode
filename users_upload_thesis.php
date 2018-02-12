
<?php
  require('dbconnect.php');
  session_start();

  $userID = $_SESSION['user_id'];
  $uploadCode = $_SESSION['upload_code'];


  $queryUser = "SELECT * FROM tblUsers WHERE user_id = '$userID'";

  $queryResult = mysqli_query($conn,$queryUser);

  $rowLoggedUser = mysqli_fetch_array($queryResult);

  $_SESSION['user_id'] = $userID;
  $_SESSION['upload_code'] = $uploadCode;


if(isset($_POST['btnUpload'])){
      
      $thesisCode = $_POST['upload_txtCode'];
      $thesisTitle = $_POST['upload_txtTitle'];
      $thesisFileType = $_POST['upload_ddlType'];
      $thesisCategoryID = $_POST['upload_ddlCategory'];
      $year_accomplished = $_POST['upload_txtYear'];

      $queryYearDiff = "SELECT year(now()) - $year_accomplished as Difference";
      $yearDiffRes = $conn->query($queryYearDiff);

      $rowDiff = $yearDiffRes-> fetch_assoc();
      if($rowDiff['Difference'] > 10) 
        $thesisStatus = 'ARCHIVED';
      else
        $thesisStatus = 'ACTIVE';

      $thesisAbstract = $_POST['upload_txtAbstract'];

      $thesisFile = $_FILES['upload_flThesisFile'];
      $thesisFileName = $thesisFile['name'];
      $thesisFileTempName = $thesisFile['tmp_name'];
      $thesisFileSize = $thesisFile['size'];
      $thesisFileError = $thesisFile['error'];


      $thesisFileExt = explode('.', $thesisFileName);
      $thesisFileActualExt = strtolower(end($thesisFileExt));

      $allowedThesisFileExt = array('zip','docx','pdf');
      if((in_array($thesisFileActualExt, $allowedThesisFileExt))){
        if ($thesisFileError === 0) {
          if ($thesisFileSize < 100000000){
            $newThesisFileName = $thesisCode."file.".$thesisFileActualExt;
            $thesisFileDestination = 'uploads/'.$newThesisFileName;
            move_uploaded_file($thesisFileTempName, $thesisFileDestination);

            $queryUploadFile = "INSERT INTO tblthesis (thesis_id, thesis_title, year_accomplished, file, file_type, status) VALUES (upper('$thesisCode'),'$thesisTitle', $year_accomplished, '$thesisFileDestination', upper('$thesisFileType'), '$thesisStatus')";
            $uploadFileResult = mysqli_query($conn,$queryUploadFile);
           

          $queryAddAbstract = "INSERT INTO tblthesis_Abstract (thesis_id, abstract) VALUES (upper('$thesisCode'), '$thesisAbstract')";
          $addAbstractRes = mysqli_query($conn, $queryAddAbstract);

          $queryAddThesisCategory = "INSERT INTO tblthesis_Category(category_id, thesis_id) VALUES ('$thesisCategoryID', '$thesisCode')";
          $addThesisCategoryRes = mysqli_query($conn, $queryAddThesisCategory);

           header('location: users_addAuthorEvaluator.php?thesis_id=$thesisCode');
          }else{
          ?>
          <script type="text/javascript">
            alert('Your File is Too Big!');
          </script>
          <?php
            echo"<script>location.assign('users_upload_thesis.php')</script>";
              }
            }else{
          ?>
          <script type="text/javascript">
            alert('You Have an Error!');
          </script>
          <?php
            echo"<script>location.assign('users_upload_thesis.php')</script>";
            }
          }else{
          ?>
          <script type="text/javascript">
            alert('Invalid File Extension!');
          </script>
          <?php
            echo"<script>location.assign('users_upload_thesis.php')</script>";
          }

      
  }

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Thesys | Requests (Users)</title>
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

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="images/user.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>
            <br>
           <?php echo $rowLoggedUser['last_name']; echo ", "; echo $rowLoggedUser['first_name']; echo" "; echo $rowLoggedUser['middle_initial']; echo ".";?>
          </p>
          <!-- Status -->
         
        </div>
      </div>

      <!-- search form (Optional) -->
      
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
      
        <!-- Optionally, you can add icons to the links -->
        <li><a href="users_dashboard.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
        <li><a href="users_requests.php"><i class="fa fa-th"></i> <span>Requests</span></a></li>
        
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
        <li><a href="users_dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Requests</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
      <iv class="row">

        <div class ="col-md-3">
        </div>

        <div class="codl-md-6" align="center">
          <form action="users_upload_thesis.php" method="post" enctype="multipart/form-data">
            <table class="table table">
                 <tr>
                   <td></td>
                   <td>Thesis Code:</td>
                   <td> <input type="text" name="upload_txtCode" class="form-control" required="" style="width: 50%" value="<?php echo $uploadCode?>" readonly="readonly"></td>
                 </tr>
                 <tr>
                  <td></td>
                   <td>Thesis Title:</td>
                   <td><input type="text" name="upload_txtTitle" class="form-control" required="" style="width: 50%"></td>
                 </tr>

                 <tr>
                  <td></td>
                   <td>Thesis File</td>
                   <td><input type="file" name="upload_flThesisFile" required=""></td>
                 </tr>
                 <tr>
                  <td></td>
                  <td>File Type:</td>
                  <td>
                    <select name="upload_ddlType" required="" class="form-control select2" style="width: 50%;">
                      <option>Select File Type</option>
                      <option>Original Copy</option>
                      <option>Scanned</option>
                    </select>
                  </td>
                 </tr>
                 <tr>
                  <td></td>
                  <td>Category:</td>
                  <td>
                    <select name="upload_ddlCategory" class="form-control select2" style="width: 50%;" required="">
                      <option>Select a Category</option>
                    <?php
                      $queryCategory = "SELECT * FROM tblCategory ORDER BY category_name";
                      $categoryRes = $conn->query($queryCategory);

                      while($rowCategory = $categoryRes->fetch_assoc()) {
                    ?>
                      
                      <option value='<?php echo $rowCategory['id']?>'><?php echo $rowCategory['category_name']?></option>
                    <?php
                      }
                    ?>
                    </select>
                  </td>
                 </tr>
                 <tr>
                  <td></td>
                  <td>Year Accomplished</td>
                  <td><input type="text" name="upload_txtYear" class="form-control" required="" style="width: 50%"></td>
                 </tr>
                 <tr>
                  <td></td>
                  <td>Thesis Abstract</td>
                   <td>
                     <textarea name="upload_txtAbstract" style="resize: none; height: 200px; width: 50%; font-size: 16px; vertical-align: left;"></textarea>
                   </td>
                 </td>
                 </tr>
                 <tr>
                   <td></td>
                   <td></td>
                   <td>
                    <center>
                      <input type="submit" name="btnUpload" class="btn btn-success" value="Upload Thesis" style="font-size: 15px;">
                    </center>
                   </td>
                 </tr>
               </table>
          </form>
        </div>
        <div class="col-md-3"></div>
      </div>

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