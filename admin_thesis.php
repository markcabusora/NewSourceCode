<?php
   include('dbconnect.php');

    $autoArchiveQuery = "UPDATE tblThesis SET status = 'ARCHIVED' WHERE (year(now()) - year_accomplished) > 10";
    $autoArchiveQueryResult = mysqli_query($conn, $autoArchiveQuery);

   if (isset($_POST['addThesis'])) {
    
    $thesisAbstract = $_POST['add_txtAbstract'];

    $thesisID = $_POST['add_txtThesisID'];
    $thesisTitle = $_POST['add_txtThesisTitle'];
    $thesisFileType = $_POST['add_thesisFileType'];
    $year = $_POST['add_txtYear'];
    $thesisCategory = $_POST['add_ddlThesisCategory'];

    $yearDiffQuery = "SELECT year(now()) - $year as Difference";
    $yearDiffQueryResult = mysqli_query($conn, $yearDiffQuery);

    $rowDiff = mysqli_fetch_assoc($yearDiffQueryResult);

    if($rowDiff['Difference'] > 10)
      $thesisStatus = 'ARCHIVED';
    else
      $thesisStatus = 'ACTIVE';


    if(!empty($_FILES['add_flthesisFile'])){
      $thesisFile = $_FILES['add_flThesisFile'];

      $thesisFileName = $thesisFile['name'];
      $thesisFileTempName = $thesisFile['tmp_name'];
      $thesisFileSize = $thesisFile['size'];
      $thesisFileError =$thesisFile['error'];

      $thesisFileExt = explode('.', $thesisFileName);
      $thesisFileActualExt = strtolower(end($thesisFileExt));

    $allowedThesisFileExt = array('zip','docx','pdf');
      if((in_array($thesisFileActualExt, $allowedThesisFileExt))){
        if ($thesisFileError === 0) {
          if ($thesisFileSize < 100000000){
            $newThesisFileName = $thesisID."file.".$thesisFileActualExt;
            $thesisFileDestination = 'uploads/'.$newThesisFileName;
            $queryUploadFile = "INSERT INTO tblThesis(thesis_id, thesis_title, year_accomplished, file, file_type,status ) VALUES (upper('$thesisID'),'$thesisTitle', $year, '$thesisFileDestination', '$thesisFileType', '$thesisStatus')";
            $uploadFileResult = mysqli_query($conn,$queryUploadFile);
            move_uploaded_file($thesisFileTempName, $thesisFileDestination);
          }else{
          ?>
          <script type="text/javascript">
            alert('Your File is Too Big!');
          </script>
          <?php
            echo"<script>location.assign('admin_thesis.php')</script>";
              }
            }else{
          ?>
          <script type="text/javascript">
            alert('You Have an Error!');
          </script>
          <?php
            echo"<script>location.assign('admin_thesis.php')</script>";
            }
          }else{
          ?>
          <script type="text/javascript">
            alert('Invalid File Extension!');
          </script>
          <?php
            echo"<script>location.assign('admin_thesis.php')</script>";
          }
    }else{
      $queryAddThesis = "INSERT INTO tblThesis(thesis_id, thesis_title, year_accomplished, status) VALUES(upper('$thesisID'),'$thesisTitle', $year, '$thesisStatus')";
      $addThesisRes = mysqli_query($conn, $queryAddThesis);

      $queryAddAbstract = "INSERT INTO tblThesis_Abstract(thesis_id, abstract) VALUES(upper('$thesisID'), '$thesisAbstract')";
      $addAbstractRes = mysqli_query($conn, $queryAddAbstract);

      $queryAddThesisCategory = "INSERT INTO tblThesis_category(category_id, thesis_id) VALUES($thesisCategory, $thesisID)";
      $addThesisCategoryRes = mysqli_query($conn, $queryAddThesisCategory);

      header('location: admin_thesis.php');
    }
  }elseif(isset($_POST['search'])){

    if(!empty($_GET['page']))
    {
      $page = $_GET['page'];
    
    }
    else
    {
      $page = 1;
    }

      $keyword = $_POST['keyword'];
      $category = $_POST['category'];
   
   if(!empty($_POST['chkActiveOrNot']) && $_POST['chkActiveOrNot'] == 'Active'){
      if ($category == 'Thesis Code') {
        $start = (($page-1) * 20);
        $queryTheses = "SELECT * FROM tblThesis WHERE thesis_id LIKE('%$keyword%') AND status = 'ACTIVE'";
        $queryThesesResult = mysqli_query($conn, $queryTheses);
        $totalRecords = mysqli_num_rows($queryThesesResult);
        $totalPages = ceil($totalRecords / 30);
        $queryTheses = "SELECT * FROM tblThesis WHERE thesis_id LIKE('%$keyword%') AND status = 'ACTIVE' LIMIT $start, 20";
        $queryThesesResult = mysqli_query($conn, $queryTheses);
      }
      elseif ($category == 'Thesis Title') {
        $start = (($page-1) * 20);
        $queryTheses = "SELECT * FROM tblThesis WHERE thesis_title LIKE('%$keyword%') AND status = 'ACTIVE'";
        $queryThesesResult = mysqli_query($conn, $queryTheses);
        $totalRecords = mysqli_num_rows($queryThesesResult);
        $totalPages = ceil($totalRecords / 30);
        $queryTheses = "SELECT * FROM tblThesis WHERE thesis_title LIKE('%$keyword%') AND status = 'ACTIVE' LIMIT $start, 20";
        $queryThesesResult = mysqli_query($conn, $queryTheses);
      }
      elseif ($category == 'Year Accomplished') {
        $start = (($page-1) * 20);
        $queryTheses = "SELECT * FROM tblThesis WHERE year_accomplished LIKE('%$keyword%') AND status = 'ACTIVE'";
        $queryThesesResult = mysqli_query($conn, $queryTheses);
        $totalRecords = mysqli_num_rows($queryThesesResult);
        $totalPages = ceil($totalRecords / 30);
        $queryTheses = "SELECT * FROM tblThesis WHERE year_accomplished LIKE('%$keyword%') AND status = 'ACTIVE' LIMIT $start, 20";
        $queryThesesResult = mysqli_query($conn, $queryTheses);
      }
  }
  elseif(empty($_POST['chkActiveOrNot'])) {
      if ($category == 'Thesis Code') {
        $start = (($page-1) * 20);
        $queryTheses = "SELECT * FROM tblThesis WHERE thesis_id LIKE('%$keyword%') AND status = 'ARCHIVED'";
        $queryThesesResult = mysqli_query($conn, $queryTheses);
        $totalRecords = mysqli_num_rows($queryThesesResult);
        $totalPages = ceil($totalRecords / 30);
        $queryTheses = "SELECT * FROM tblThesis WHERE thesis_id LIKE('%$keyword%') AND status = 'ARCHIVED' LIMIT $start, 20";
        $queryThesesResult = mysqli_query($conn, $queryTheses);
      }
      elseif ($category == 'Thesis Title') {
        $start = (($page-1) * 20);
        $queryTheses = "SELECT * FROM tblThesis WHERE thesis_title LIKE('%$keyword%') AND status = 'ARCHIVED'";
        $queryThesesResult = mysqli_query($conn, $queryTheses);
        $totalRecords = mysqli_num_rows($queryThesesResult);
        $totalPages = ceil($totalRecords / 30);
        $queryTheses = "SELECT * FROM tblThesis WHERE thesis_title LIKE('%$keyword%') AND status = 'ARCHIVED' LIMIT $start, 20";
        $queryThesesResult = mysqli_query($conn, $queryTheses);
      }
      elseif ($category == 'Year Accomplished') {
        $start = (($page-1) * 20);
        $queryTheses = "SELECT * FROM tblThesis WHERE year_accomplished LIKE('%$keyword%') AND status = 'ARCHIVED'";
        $queryThesesResult = mysqli_query($conn, $queryTheses);
        $totalRecords = mysqli_num_rows($queryThesesResult);
        $totalPages = ceil($totalRecords / 30);
        $queryTheses = "SELECT * FROM tblThesis WHERE year_accomplished LIKE('%$keyword%') AND status = 'ARCHIVED' LIMIT $start, 20";
        $queryThesesResult = mysqli_query($conn, $queryTheses);
      }
  }
  }elseif(isset($_GET['thesis_id'])){
  	 $thesisID = $_GET['thesis_id'];

	   $archiveThesisQuery = "UPDATE tblThesis SET status = 'ARCHIVED' WHERE thesis_id = '$thesisID'";
	   $archiveThesisQueryResult = mysqli_query($conn, $archiveThesisQuery);
	   header('location: admin_thesis.php');
  }else{
      
    if(!empty($_GET['page']))
    {
      $page = $_GET['page'];
    
    }
    else
    {
      $page = 1;
    }
      $start = (($page-1) * 20);
      $queryTheses = "SELECT * FROM tblThesis WHERE status = 'ACTIVE'";
      $queryThesesResult = mysqli_query($conn, $queryTheses);
      $totalRecords = mysqli_num_rows($queryThesesResult);
      $totalPages = ceil($totalRecords / 30);
      $queryTheses = "SELECT * FROM tblThesis WHERE status = 'ACTIVE' LIMIT $start, 20";
      $queryThesesResult = mysqli_query($conn, $queryTheses); 
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

  //preparation of session ID for next landing page

  //$_SESSION['user_id'] = $userID;
?>
<!DOCTYPE html>
<html>
<script type="text/javascript">

function alphaOnly(e) {
  var code;
  if (!e) var e = window.event;
  if (e.keyCode) code = e.keyCode;
  else if (e.which) code = e.which;
  if ((code >= 48) && (code <= 57)) { return false; }
  return true;
}

</script>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Thesys | Users (Admin)</title>
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
              <!-- The user image in the navbar-->
              <img src="images/user.png" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs"><?php echo $rowLoggedUser['user_type'];?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="images/user.png" class="img-circle" alt="User Image">

                <p>
                  <?php echo $rowLoggedUser['last_name']; echo ", "; echo $rowLoggedUser['first_name']; echo" "; echo $rowLoggedUser['middle_initial']; echo ".";?>
                </p>
              </li>

              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  
                </div>
                <div class="pull-right">
                  <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="images/user.png" class="img-circle" alt="User Image">
                <p>
                  
             </p>
              </li>
              <!-- Menu Body -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <!--<input type="submit" name="" class="btn btn-default btn-flat"></a>-->
                </div>
              </li>
            </ul>
          </li>

          <!-- Control Sidebar Toggle Button -->

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
          <br>
          <p>
              <?php echo $rowLoggedUser['first_name']; ?>
              <br>
              <?php echo " "; echo $rowLoggedUser['last_name'];?>
          </p>
        </div>
      </div>

      <!-- search form (Optional) -->
      
      <!-- /.search form -->

      <!-- Sidebar Menu -->
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

      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="font: sans-serif;">
    <!-- Content Header (Page header) -->
    <section class="input content-header">
      <h1>
        Thesis
        <small>Administrators</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="admin_users.php"><i class="fa fa-users"></i>Users</a></li>
        <li class="active">Theses (Administrator)</li>
      </ol>
    <!--ADD ADMIN MODAL-->
    <div class="modal fade" id="modal_addThesis">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Add Thesis
                </div>
                <div class="modal-body">
                <div class="box-body">
                  <form action="admin_thesis.php" method="post" enctype="multipart/form-data">
                    <table class="table table-bordered">
                      <tr>
                            <td>Thesis Code</td>
                            <td> 
                              <!-- onkeypress="return alphaOnly(event);" -->
                              <input type="text" name="add_txtThesisID" class="form-control"  required="" style="text-transform: uppercase;">
                            </td>
                        </tr>
                        <tr>
                        <tr>
                            <td>Thesis Title</td>
                            <td> 
                              <input type="text" name="add_txtThesisTitle" class="form-control" required="">
                            </td>
                        </tr>
                        <tr>
                          <td>Thesis File</td>
                          <td>
                            <input type="file" name="add_flThesisFile">
                          </td>
                        </tr>
                        <tr>
                          <td>Thesis File Type:</td>
                          <td>
                            <select name="add_thesisFileType" class="form-control select2">
                              <option>Original Copy</option>
                              <option>Scanned</option>
                            </select>
                          </td>
                        </tr>
                        <tr>
                            <td>Category</td>
                            <td>
                                <select name="add_ddlThesisCategory" class="form-control select2">
                                  <?php
                                    $queryCategory = "SELECT * FROM tblCategory";
                                    $categoryRes = mysqli_query($conn, $queryCategory);

                                    while($rowCategory = mysqli_fetch_assoc($categoryRes)){
                                  ?>
                                    <option value="<?php echo $rowCategory['id']?>"><?php echo $rowCategory['category_name'];?></option>
                                  <?php
                                    }
                                  ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Year Accomplished</td>
                            <td>
                              <input type="text" name="add_txtYear" class="form-control" required="">
                            </td>
                        </tr>
                        <tr>
                            <td>Abstract</td>
                            <td>
                              <textarea style="resize: none; height: 200px; width: 400px; font-size: 16px; vertical-align: left;" name="add_txtAbstract">
                              </textarea>
                            </td>
                        </tr>
                        <tr class="modal-footer">
                            <td>
                               <input type="submit" name="addThesis" class="btn btn-default btn-flat" value="Add Thesis">
                            </td>
                        </tr>
                    </table>
                  </form>
                </div>
                </div>
            </div>
        </div>
      </div>

      <div class="modal fade" id="modal_addCategory">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Add Category
                </div>

              <div class="modal-body">
                <div class="box-body">
                  <form action="admin_users_admin.php" method="post">
                    <table class="table table-bordered">
                      <tr>
                            <td>Category Name</td>
                            <td> 
                              <input type="text" name="" class="form-control" onkeypress="return alphaOnly(event);" required="">
                            </td>
                      </tr>
                        
                        <tr class="modal-footer">
                            <td>
                               <input type="submit" name="add_userAdmin" class="btn btn-default btn-flat" value="Add Category">
                            </td>
                        </tr>
                    </table>
                  </form>
                </div>
                </div>
            </div>
        </div>
      </div>
    <!--END ADD ADMIN MODAL-->
    <div class="row">
        <div class="col-lg-12">
          <div class="box">
            <div class="box-header">
              <form action="admin_thesis.php" method="post">
              <table class="table" style="width: 100%">
                  <th>
                  <div class="input-group-btn">
                   <select class="form-control select2" name="category">
                      <option selected="selected">Thesis Title</option>
                      <option>Thesis Code</option>
                      <option>Year Accomplished</option>
                    </select>
                  </div>
                  </th>
                  <th>
                    <input type="text" class="form-control" placeholder="Search" name="keyword" autofocus="">
                  </th>
                  <th>
                    <label><input type="checkbox" value="Active" name="chkActiveOrNot" checked="checked">Active</label>
                    <input type="submit" class="btn btn-default" btn-sm style="margin-left: 1%" name="search" value="Search">
                    <input type="reset" class="btn btn-default" style="margin-left: 1%" value="Clear">
                  </th>
                  <th>
                    
                  </th>
                  <th>
                   <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_addCategory"> Add Category</button>
                   <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_addThesis"> Add Thesis</button>
                  </th>
              </table>
              </form>

               <!-- <div class="btn-group btn-group-justified">
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">A</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">B</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">C</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">D</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">E</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">F</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">G</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">H</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">I</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">J</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">K</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">L</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">M</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">N</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">O</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">P</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">Q</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">R</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">S</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">T</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">U</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">V</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">W</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">X</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">Y</button></a></div>
                <div class="btn-group"><a href="#"><button type="button" class="btn btn-primary btn-sm">Z</button></a></div>
              </div>
 -->
            </div>
              <div class="box-body">
                
              </div>
          </div>
      </div>
    </div>
    </section>
    <div class="box-body">
            <table class="table table-bordered" style="width:100%;">
              <tr>
                <th>Thesis Code</th>
                <th>Thesis Title</th>
                <th>Year Accomplished</th>
                <th colspan="3">Actions</th>
              </tr>
              <?php
                //displaying of records retrieved from the database
                while( $rowTheses = mysqli_fetch_array($queryThesesResult))
                {
              ?>

              <tr>
                <td ><?php echo $rowTheses['thesis_id']; ?></td>
                <td><?php echo $rowTheses['thesis_title']; ?></td>
                <td><?php echo $rowTheses['year_accomplished']; ?></td>
                <td>
                  	<a href="admin_edit_thesis.php?thesis_id=<?php echo $rowTheses['thesis_id']?>" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                  	<a href="admin_thesis.php?thesis_id=<?php echo $rowTheses['thesis_id']?>" onClick="return confirm('Sure To Archive This Record?');"class="btn btn-sm btn-success" data-toggle="tooltip" title="Deactivate Thesis"><i class="fa fa-archive"></i></a>
                  	<a href="admin_view_thesis.php?thesis_id=<?php echo $rowTheses['thesis_id']?>" class="btn btn-sm btn-info" data-toggle="tooltip" title="Preview"><i class="fa fa-eye"></i></a>
                </td>
               <?php
                }
               ?>
              </tr>
          </table>

          <ul class="pagination pagination-sm no-margin pull-right">
                  <?php

                  if(isset($_POST['search'])){
                    for($i=1;$i<=$totalPages;$i++) {
                      ?>
                       <li><a href="admin_thesis.php?page=<?php echo $i?>"><?php echo $i; ?></a></li>
                  <?php   
                      }
                    }
                    else{
                      for($i=1;$i<=$totalPages;$i++) {
                      ?>
                       <li><a href="admin_thesis.php?page=<?php echo $i?>"><?php echo $i; ?></a></li>
                       <?php
                    }
                  }
                   ?>
          </ul>

    </div>
  
    <!-- /.content -->
    </div>
  <!-- /.content-wrapper -->


  <!-- Main Footer -->

  <!-- Control Sidebar -->
  
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