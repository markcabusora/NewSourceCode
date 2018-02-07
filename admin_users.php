<?php
  //database connection
  include ('dbconnect.php');

  //logic for adding new administrator

   $deactivateUsers = "UPDATE tblUsers SET status = 'INACTIVE' WHERE DATEDIFF(date(now()), date(exp_date)) = 0";
   $deactivateResultUsers = mysqli_query($conn, $deactivateUsers);
  if(isset($_POST['add_user']))
  {
    $userType = $_POST['user_type'];
    $userID = $_POST['add_txtUserID'];
    $password = md5($_POST['add_txtPassword']);
    $lastName = $_POST['add_txtLastName'];
    $firstName = $_POST['add_txtFirstName'];
    $middleInitial = $_POST['add_txtMiddleInitial'];
    $status = 'active';

    //query for adding new record in tblUsers
    $queryAddUser = "INSERT INTO tblUsers(user_id, last_name, first_name, middle_initial, exp_date, user_type, status)
                     VALUES('$userID', upper('$lastName'), upper('$firstName'), upper('$middleInitial'), DATE_SUB(date(now()), INTERVAL -1 MONTH), upper('$userType'), upper('$status'))";

    //query for adding new record in tblPasswords
    $queryAddUserPassword = "INSERT INTO tblPasswords(user_id, password) VALUES('$userID', '$password')";

    $queryAddUserResult = mysqli_query($conn, $queryAddUser);
    $queryAddUserPasswordResult = mysqli_query($conn, $queryAddUserPassword);

    header('location: admin_users.php');
  }
  elseif (isset($_POST['edit_user'])) {
    $userID = $_POST['edit_txtUserID'];
    $editLastName = $_POST['edit_txtLastName'];
    $editFirstName = $_POST['edit_txtFirstName'];
    $editMiddleInitial = $_POST['edit_txtMiddleInitial'];

    if(!empty($_POST['edit_txtPassword'])) {
        $password = $_POST['password'];
        $queryEditUser = "UPDATE tblUsers SET last_name ='$editLastName', password = '$password' first_name = '$editFirstName', middle_initial = '$editMiddleInitial' WHERE user_id = '$userID'";
        $queryEditPassword = "UPDATE tblPasswords SET password = MD5($password) WHERE user_id = '$userID'";
        $editPasswordRes = mysqli_query($conn, $queryEditPassword);
    }
    else {
        $queryEditUser = "UPDATE tblUsers SET last_name ='$editLastName',  first_name = '$editFirstName', middle_initial = '$editMiddleInitial' WHERE user_id = '$userID'";
    }
    $queryEditUserResult = mysqli_query($conn, $queryEditUser);

    header('location: admin_users.php');
  }
  elseif (isset($_POST['search'])) {
    if(!empty($_GET['page']))
    {
      $page = $_GET['page'];
    
    }
    else
    {
      $page = 1;
    }

    $userType = strtoupper($_POST['user_type']);
    $category = $_POST['category'];
    $keyword = $_POST['keyword'];

   if(!empty($_POST['status']) && $_POST['status'] = 'Active')
   {
      if($category == 'User ID')
      {
        $start = (($page-1) * 20);
        $queryUsers = "SELECT * FROM tblUsers WHERE user_id LIKE('%$keyword%') AND status='ACTIVE' AND user_type='$userType' ORDER BY user_id";
        $queryUsersResult = mysqli_query($conn, $queryUsers);
        $totalRecords = mysqli_num_rows($queryUsersResult);
        $totalPages = ceil($totalRecords / 30);
        $queryUsers = "SELECT * FROM tblUsers WHERE status LIKE('active') AND user_id LIKE('%$keyword%') AND user_type='$userType' ORDER BY last_name LIMIT $start, 20";
        $queryUsersResult = mysqli_query($conn, $queryUsers);
      }
      elseif($category == 'Name')
      {
        $start = (($page-1) * 20);
        $queryUsers = "SELECT * FROM tblUsers WHERE CONCAT(last_name, first_name, middle_initial) LIKE('%$keyword%') AND status='ACTIVE' AND user_type='$userType'";
        $queryUsersResult = mysqli_query($conn, $queryUsers);
        $totalRecords = mysqli_num_rows($queryUsersResult);
        $totalPages = ceil($totalRecords / 30);
        $queryUsers = "SELECT * FROM tblUsers WHERE CONCAT(last_name, first_name, middle_initial) LIKE('%$keyword%') AND status='ACTIVE' AND user_type='$userType' LIMIT $start, 20";
        $queryUsersResult = mysqli_query($conn, $queryUsers);
      }
   }
   elseif (empty($_POST['status']))
   {
      if($category == 'User ID')
      {
        $start = (($page-1) * 20);
        $queryUsers = "SELECT * FROM tblUsers WHERE user_id LIKE('%$keyword%') AND status='INACTIVE' AND user_type='$userType' ORDER BY user_id";
        $queryUsersResult = mysqli_query($conn, $queryUsers);
        $totalRecords = mysqli_num_rows($queryUsersResult);
        $totalPages = ceil($totalRecords / 30);
        $queryUsers = "SELECT * FROM tblUsers WHERE user_id LIKE('%$keyword%') AND status='INACTIVE' AND user_type='$userType' ORDER BY user_id LIMIT $start, 20";
        $queryUsersResult = mysqli_query($conn, $queryUsers);
      }
      elseif($category == 'Name')
      {
        $start = (($page-1) * 20);
        $queryUsers = "SELECT * FROM tblUsers WHERE CONCAT(last_name, first_name, middle_initial) LIKE('%$keyword%') AND status='INACTIVE' AND user_type='$userType'";
        $queryUsersResult = mysqli_query($conn, $queryUsers);
        $totalRecords = mysqli_num_rows($queryUsersResult);
        $totalPages = ceil($totalRecords / 30);
        $queryUsers = "SELECT * FROM tblUsers WHERE CONCAT(last_name, first_name, middle_initial) LIKE('%$keyword%') AND status='INACTIVE' AND user_type='$userType' LIMIT $start, 20";
        $queryUsersResult = mysqli_query($conn, $queryUsers);

      }
   }
  }
  elseif(!empty($_GET['page']))
  {
    $page = $_GET['page'];

    $start = (($page-1) * 20);
    $queryUsers = "SELECT * FROM tblUsers WHERE status LIKE('active')";
    $queryUsersResult = mysqli_query($conn, $queryUsers);
    $totalRecords = mysqli_num_rows($queryUsersResult);
    $totalPages = ceil($totalRecords / 30);
    $queryUsers = "SELECT * FROM tblUsers WHERE status LIKE('active') AND ORDER BY last_name LIMIT $start, 20";
    $queryUsersResult = mysqli_query($conn, $queryUsers);
  }
  elseif(!empty($_GET['deactivate_user_id']))
  {
    $userIDtoDeactivate = $_GET['deactivate_user_id'];

    $queryDeactivateUser = "UPDATE tblUsers SET status = upper('inactive') WHERE user_id = '".$userIDtoDeactivate."'";
    $deactivateUserResult = mysqli_query($conn, $queryDeactivateUser);

    header('location: admin_users.php');
  }
  elseif(!empty($_GET['reactivate_user_id']))
  {
    $userIDtoDeactivate = $_GET['reactivate_user_id'];

    $queryDeactivateUser = "UPDATE tblUsers SET status = upper('active') WHERE user_id = '".$userIDtoDeactivate."'";
    $deactivateUserResult = mysqli_query($conn, $queryDeactivateUser);

    header('location: admin_users.php');
  }
  else
  {
    $page = 1;

    $start = (($page-1) * 20);
    $queryUsers = "SELECT * FROM tblUsers WHERE status LIKE('active') ORDER BY last_name";
    $queryUsersResult = mysqli_query($conn, $queryUsers);
    $totalRecords = mysqli_num_rows($queryUsersResult);
    $totalPages = ceil($totalRecords / 30);
    $queryUsers = "SELECT * FROM tblUsers WHERE status LIKE('active') ORDER BY last_name LIMIT $start, 20";
    $queryUsersResult = mysqli_query($conn, $queryUsers);
  }

  //start of session
  session_start();

  //variable for session
  $userID = $_SESSION['user_id'];

  //checks whether a user account is logged in or not
  if(!$userID)
  {
      header('location: index.html');
  }

  //query for retrieving records of the logged in user
  $queryLoggedUser = "SELECT * FROM tblUsers WHERE user_id = '$userID'";
  $queryLoggedUserResult = mysqli_query($conn, $queryLoggedUser);

  //fetching of result of queryUserResult
  $rowLoggedUser = mysqli_fetch_assoc($queryLoggedUserResult);

  function AutoGenerateAdminID() { 

            $s = strtoupper(md5(uniqid(rand(),true)));
 
            $guidText = str_pad('A',8,substr($s,0,9));
     
            return $guidText;
        }
        // End Generate Guid 

function AutoGenerateFacultyID() { 

            $s = strtoupper(md5(uniqid(rand(),true)));
 
            $guidText = str_pad('F',8,substr($s,0,9));
     
            return $guidText;
        }

function AutoGenerateVisitorID() { 

            $s = strtoupper(md5(uniqid(rand(),true)));
 
            $guidText = str_pad('V',8,substr($s,0,9));
     
            return $guidText;
        }
?>

<!DOCTYPE html>
<html>
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
  <script type="text/javascript">

function alphaOnly(e) {
  var code;
  if (!e) var e = window.event;
  if (e.keyCode) code = e.keyCode;
  else if (e.which) code = e.which;
  if ((code >= 48) && (code <= 57)) { return false; }
  return true;
}

function ChooseToAutoGenerateID()
{
  var userType = document.getElementById('ddlUserType').value;
  var userID = document.getElementById('txtUserID');

  if(userType == 'Administrator')
    userID.value = '<?php echo $Guid = AutoGenerateAdminID(); ?>';
  else if(userType == 'Student')
  {
    userID.value='';
  }
  else if(userType == 'Faculty')
    userID.value = '<?php echo $Guid = AutoGenerateFacultyID(); ?>';
  else if(userType == 'Visitor')
    userID.value = '<?php echo $Guid = AutoGenerateVisitorID(); ?>';
  else
    userID.value="";

}

function convertToString(ddlUserType){
  userType.value = String(userType.value);
}
</script>
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
              <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs"><?php echo $rowLoggedUser['user_type'];?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

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
                <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
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
          <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
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
        <li class="active">
          <a href="#"><i class="fa fa-users"></i> <span>Users</span>
          </a>
        </li>
        <li>
          <a href="admin_thesis.php"><i class="fa fa-book"></i> <span>Theses</span>
          </a>
        <li><a href="admin_requests.php"><i class="fa fa-th"></i> <span>Requests</span></a></li>
      </ul>

      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="input content-header">
      <h1>
        Users
        <small>Administrators</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="admin_dashboard.php"><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Users (Administrator)</li>
      </ol>
    <!--ADD ADMIN MODAL-->
    <div class="modal fade" id="modal_addThesis">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Add User
                </div>
                <div class="modal-body">
                <div class="box-body">
                  <form action="admin_users.php" method="post">
                    <table class="table table-bordered">
                      <tr>
                            <td>User Type</td>
                            <td> 
                              <select class="form-control select2" style="width: 100%;" name="user_type" id="ddlUserType" onclick="ChooseToAutoGenerateID()">
                                <option <?php if((!empty($_POST['user_type']) && $_POST['user_type']=="Administrator")){echo "selected='selected'";}?>>Administrator</option>
                                <option <?php if((!empty($_POST['user_type']) && $_POST['user_type']=="Student")){echo "selected='selected'";}?>>Student</option>
                                <option <?php if((!empty($_POST['user_type']) && $_POST['user_type']=="Faculty")){echo "selected='selected'";}?>>Faculty</option>
                                <option <?php if((!empty($_POST['user_type']) && $_POST['user_type']=="Visitor")){echo "selected='selected'";}?>>Visitor</option>
                              </select>
                            </td>
                        </tr>
                        <tr>
                        <tr>
                            <td>User ID</td>
                            <td> 
                              <input type="text" name="add_txtUserID" class="form-control" required="" style="text-transform: uppercase;" id="txtUserID" value="<?php 
                                if((!empty($_POST['user_type']) && $_POST['user_type']=="Administrator"))
                                  echo $newUserID = AutoGenerateAdminID();
                                elseif((!empty($_POST['user_type']) && $_POST['user_type']=="Faculty"))
                                  echo $newUserID = AutoGenerateFacultyID();
                                elseif((!empty($_POST['user_type']) && $_POST['user_type']=="Visitor"))
                                  echo $newUserID = AutoGenerateVisitorID()
                                ?>">
                            </td>
                        </tr>
                        <tr>
                          <td>Password</td>
                          <td>
                            <input type="text" name="add_txtPassword" class="form-control" required="">
                          </td>
                        </tr>
                        <tr>
                            <td>Last Name</td>
                            <td>
                                <input type="text" name="add_txtLastName" class="form-control" onkeypress="return alphaOnly(event);" required="" style="text-transform: uppercase;">
                            </td>
                        </tr>
                        <tr>
                            <td>First Name</td>
                            <td>
                              <input type="text" name="add_txtFirstName" class="form-control" onkeypress="return alphaOnly(event);" required="" style="text-transform: uppercase;">
                            </td>
                        </tr>
                        <tr>
                            <td>Middle Initial</td>
                            <td>
                                <input type="text" name="add_txtMiddleInitial" class="form-control" onkeypress="return alphaOnly(event);" style="text-transform: uppercase;">
                            </td>
                        </tr>
                        <tr class="modal-footer">
                            <td>
                               <input type="submit" name="add_user" class="btn btn-default btn-flat" value="Add User">
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
              <form action="admin_users.php" method="post">
              <table class="table" style="width: 100%">
                  <th>
                    <select class="form-control select2" name="user_type">
                        <option <?php if((!empty($_POST['user_type']) && $_POST['user_type']=="Administrator")){echo "selected='selected'";}?>>Administrator</option>
                        <option <?php if((!empty($_POST['user_type']) && $_POST['user_type']=="Student")){echo "selected='selected'";}?> Value="Student">Student</option>
                        <option <?php if((!empty($_POST['user_type']) && $_POST['user_type']=="Faculty")){echo "selected='selected'";}?> Value="Faculty">Faculty</option>
                        <option <?php if((!empty($_POST['user_type']) && $_POST['user_type']=="Visitor")){echo "selected='selected'";}?> Value="Visitor">Visitor</option>
                    </select>
                  </th>
                  <th>
                    <th>
                    <select class="form-control select2" name="category">
                      <option <?php if((!empty($_POST['category']) && $_POST['category']=="User ID")){echo "selected='selected'";}?> Value="User ID">User ID</option>
                      <option <?php if((!empty($_POST['category']) && $_POST['category']=="Name")){echo "selected='selected'";}?> Value="Name">Name</option>
                    </select>
                  </th>
                  </th>
                  <th>
                    <input type="text" class="form-control" placeholder="Search" name="keyword" autofocus="">
                  </th>
                  <th>
                    <label><input type="checkbox" value="Active" checked="checked" name="status" <?php if(!empty($_POST['status']) && $_POST['status'] == 'Active'){ echo "checked = checked"; } ?>>Active</label>
                    <input type="submit" name="search" value="Search" class="btn btn-default">
                    <input type="reset" name="clear" value="Clear" class="btn btn-default">
                  </th>
                  <th>
              
                  </th>
                  <th>
                   <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_addThesis"> Add User</button>
                  </th>
              </table>
              </form>

               <!-- <div class="btn-group btn-group-justified">
                <?php
                     $alphabetPage = range('A', 'Z');
                      
                      foreach($alphabetPage as $key)
                      {
                ?>
                <div class="btn-group"><a href="admin_users.php?namestart=<?php echo $key?>&page=<?php echo $page?>"><button type="button" class="btn btn-primary btn-sm"><?php echo $key?></button></a></div>
                <?php
                      }
                ?>
              </div> -->

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
                <th>User ID</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle Initial</th>
                <th>Actions</th>
              </tr>
              <?php
                //displaying of records retrieved from the database
                while( $rowUsers = mysqli_fetch_array($queryUsersResult))
                {
                  $queryPassword = "SELECT * FROM tblPasswords WHERE user_id = '".$rowUsers['user_id']."'";
                  $queryPasswordResult = mysqli_query($conn, $queryPassword);
                  $rowPassword = mysqli_fetch_array($queryPasswordResult);
              ?>

              <tr>
                <td ><?php echo $rowUsers['user_id']; ?></td>
                <td><?php echo $rowUsers['last_name']; ?></td>
                <td><?php echo $rowUsers['first_name']; ?></td>
                <td><?php echo $rowUsers['middle_initial']; ?></td>
                <td>
                  <a href="#modal_editUser<?php echo $rowUsers['user_id']?>" class="btn btn-sm btn-warning" data-toggle="modal" title="Edit User Record"><i class="fa fa-edit"></i></a>
                  <?php
                      if($rowUsers['status'] == 'INACTIVE') {
                  ?>
                      <a href="admin_users.php?reactivate_user_id=<?php echo $rowUsers['user_id']?>" onClick="return confirm('Sure To Reactivate This Record?');" class="btn btn-sm btn-success" data-toggle="tooltip" title="Reactivate"><i class="fa fa-refresh"></i></a>
                  <?php
                      }
                      else {
                  ?>
                      <a href="admin_users.php?deactivate_user_id=<?php echo $rowUsers['user_id']?>" onClick="return confirm('Sure To Deactivate This Record?');" class="btn btn-sm btn-danger" title="Deactivate Record"><i class="fa fa-trash"></i></a>
                  <?php
                      }
                  ?>
                 </td>
              </tr>
              <td>
          <div class="modal fade" id="modal_editUser<?php echo $rowUsers['user_id']?>" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Edit User
                </div>
                <div class="modal-body">
                <div class="box-body">
                  <form action="admin_users.php" method="post">
                    <table class="table table-bordered">
                      <tr>
                        </tr>
                        <tr>
                        <tr>
                            <td>User ID</td>
                            <td> 
                              <input type="text" name="edit_txtUserID" class="form-control" required="" style="text-transform: uppercase;" value="<?php echo $rowUsers['user_id']; ?>" readonly="readonly">
                            </td>
                        </tr>
                        <tr>
                          <td>Password</td>
                          <td>
                            <input type="text" name="edit_txtPassword" class="form-control" required="" >
                          </td>
                        </tr>
                        <tr>
                            <td>Last Name</td>
                            <td>
                                <input type="text" name="edit_txtLastName" class="form-control" onkeypress="return alphaOnly(event);" required="" style="text-transform: uppercase;" value="<?php echo $rowUsers['last_name']?>">
                            </td>
                        </tr>
                        <tr>
                            <td>First Name</td>
                            <td>
                              <input type="text" name="edit_txtFirstName" class="form-control" onkeypress="return alphaOnly(event);" required="" style="text-transform: uppercase;" value="<?php echo $rowUsers['first_name']?>">
                            </td>
                        </tr>
                        <tr>
                            <td>Middle Initial</td>
                            <td>
                                <input type="text" name="edit_txtMiddleInitial" class="form-control" onkeypress="return alphaOnly(event);" style="text-transform: uppercase;" value="<?php echo $rowUsers['middle_initial']?>">
                            </td>
                        </tr>
                        <tr class="modal-footer">
                            <td>
                               <input type="submit" name="edit_user" class="btn btn-default btn-flat" value="Save Changes">
                            </td>
                        </tr>
                    </table>
                  </form>
                </div>
                </div>
            </div>
        </div>
      </div>
              </td>
               <?php
                }
                ?>

          </table>
          <div class="pull-right">
            <ul class="pagination">
                  <?php

                  if(isset($_POST['search'])){
                    for($i=1;$i<=$totalPages;$i++) {
                      ?>
                       <li><a href="admin_users.php?page=<?php echo $i?>"><?php echo $i; ?></a></li>
                  <?php   
                      }
                    }
                    else{
                      for($i=1;$i<=$totalPages;$i++) {
                      ?>
                       <li><a href="admin_users.php?page=<?php echo $i?>"><?php echo $i; ?></a></li>
                       <?php
                    }
                  }
                   ?>
                  </ul>
          </div>
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