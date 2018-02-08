<?php
  //database connection
  include ('dbconnect.php');

  //logic for adding new administrator
  if(isset($_GET['thesis_id']))
  {
    $thesisIDtoEdit = $_GET['thesis_id'];

    $queryThesisToEdit = "SELECT * FROM tblThesis WHERE thesis_id = '".$thesisIDtoEdit."'";
    $thesisToEditResult = mysqli_query($conn, $queryThesisToEdit);

    $rowThesisToEdit = mysqli_fetch_array($thesisToEditResult);

    $queryThesisAbstractToEdit = "SELECT * FROM tblThesis_Abstract WHERE thesis_id = '$thesisIDtoEdit'";
    $abstractToEditRes = mysqli_query($conn, $queryThesisAbstractToEdit);
  }

  if(!empty(isset($_GET['edit_evaluator_thesis_id'])) && !empty(isset($_GET['edit_thesis_id'])))
  {
    $evaluatorID = $_GET['edit_evaluator_thesis_id'];
    $thesisID = $_GET['edit_thesis_id'];
    $thesisIDtoEdit = $_GET['edit_thesis_id'];

    $queryThesisToEdit = "SELECT * FROM tblThesis WHERE thesis_id = '".$thesisIDtoEdit."'";
    $thesisToEditResult = mysqli_query($conn, $queryThesisToEdit);

    $rowThesisToEdit = mysqli_fetch_array($thesisToEditResult);

    $querySearchEditEvaluator = "SELECT * FROM tblEvaluators WHERE evaluator_id = '$evaluatorID'";
    $searchEditEvaluatorResult = mysqli_query($conn, $querySearchEditEvaluator);

    $rowEvaluatorToEdit = mysqli_fetch_array($searchEditEvaluatorResult);
  }
  elseif(isset($_GET['delete_evaluator_id']))
  {
    $evaluatorIDtoRemove = $_GET['delete_evaluator_id'];
    $thesisID = $_GET['thesis_id'];

    $queryDeleteEvaluator = "DELETE FROM tblThesis_evaluators WHERE thesis_id = '$thesisID' AND evaluator_id = '".$evaluatorIDtoRemove."')";
    $deleteEvaluatorResult = mysqli_query($conn, $queryDeleteEvaluator);

    header("location: admin_edit_thesis.php?thesis_id=".$thesisID."");
  }

   if(!empty(isset($_GET['delete_author_thesis_id'])) && !empty(isset($_GET['delete_thesis_id'])))
  {
    $authorID = $_GET['delete_author_thesis_id'];
    $thesisID = $_GET['delete_thesis_id'];

    $queryDeleteAuthor = "DELETE FROM tblProponents WHERE thesis_id = '$thesisID' AND id = '$authorID'";
    $deleteAuthorResult = mysqli_query($conn, $queryDeleteAuthor);

    header("location: admin_edit_thesis.php?thesis_id=".$thesisID."");
  }
  elseif(!empty(isset($_GET['edit_author_thesis_id'])) && !empty(isset($_GET['edit_thesis_id'])))
  {
    $authorID = $_GET['edit_author_thesis_id'];
    $thesisIDtoEdit = $_GET['edit_thesis_id'];
    $thesisID = $_GET['edit_thesis_id'];

    $queryThesisToEdit = "SELECT * FROM tblThesis WHERE thesis_id = '".$thesisIDtoEdit."'";
    $thesisToEditResult = mysqli_query($conn, $queryThesisToEdit);

    $rowThesisToEdit = mysqli_fetch_array($thesisToEditResult);

    $querySearchEditAuthor = "SELECT * FROM tblProponents WHERE id = '$authorID' AND thesis_id = '$thesisID'";
    $searchEditAuthorResult = mysqli_query($conn, $querySearchEditAuthor);

    $rowAuthorsToEdit = mysqli_fetch_array($searchEditAuthorResult);

    $queryThesisAbstractToEdit = "SELECT * FROM tblThesis_Abstract WHERE thesis_id = '$thesisIDtoEdit'";
    $abstractToEditRes = mysqli_query($conn, $queryThesisAbstractToEdit);
  }

  if(isset($_POST['add_author']))
  {
    $authorLastName = $_POST['add_txtLastName'];
    $authorFirstName = $_POST['add_txtFirstName'];
    $authorMiddleInitial = $_POST['add_txtMiddleInitial'];
    $thesisID = $_POST['thesis_id'];

    $queryAddAuthor = "INSERT INTO tblProponents(thesis_id, last_name, first_name, middle_initial)
                       VALUES('$thesisID', '$authorLastName', '$authorFirstName', '$authorMiddleInitial')";
    $addAuthorResult = mysqli_query($conn, $queryAddAuthor);

    header('location: admin_edit_thesis.php?thesis_id='.$thesisID.'');
  }
  elseif(isset($_POST['edit_author']))
  {
    $authorLastName = $_POST['edit_txtLastName'];
    $authorFirstName = $_POST['edit_txtFirstName'];
    $authorMiddleInitial = $_POST['edit_txtMiddleInitial'];
    $thesisID = $_POST['thesis_id'];
    $authorID = $_POST['author_id'];

    $queryEditAuthor = "UPDATE tblProponents SET last_name='$authorLastName', first_name='$authorFirstName', middle_initial='$authorMiddleInitial' WHERE                 thesis_id = '$thesisID' AND id ='$authorID'";
    $editAuthorResult = mysqli_query($conn, $queryEditAuthor);

    header('location: admin_edit_thesis.php?thesis_id='.$thesisID.'');
  }

  if(isset($_POST['add_evaluator']))
  {
    $evaluatorID = $_POST['add_txtEvaluatorID'];
    $evaluatorLastName = $_POST['add_txtLastName'];
    $evaluatorFirstName = $_POST['add_txtFirstName'];
    $evaluatorMiddleInitial = $_POST['add_txtMiddleInitial'];
    $thesisID = $_POST['thesis_id'];

    $queryAddEvaluator = "INSERT INTO tblEvaluators(evaluator_id, last_name, first_name, middle_initial)
                          VALUES('$evaluatorID', '$evaluatorLastName', '$evaluatorFirstName', '$evaluatorMiddleInitial')";
    $addEvaluatorResult = mysqli_query($conn, $queryAddEvaluator);

    $queryAddThesisEvaluator = "INSERT INTO tblThesis_Evaluators(evaluator_id, thesis_id)
                                VALUES('$evaluatorID', '$thesisID')";
    $addThesisEvaluatorResult = mysqli_query($conn, $queryAddThesisEvaluator);

    header('location: admin_edit_thesis.php?thesis_id='.$thesisID.'');
  }
  elseif(isset($_POST['edit_evaluator']))
  {
    $evaluatorLastName = $_POST['edit_txtLastName'];
    $evaluatorFirstName = $_POST['edit_txtFirstName'];
    $evaluatorMiddleInitial = $_POST['edit_txtMiddleInitial'];
    $thesisID = $_POST['thesis_id'];
    $evaluatorID = $_POST['evaluator_id'];

    $queryEditEvaluator = "UPDATE tblEvaluators SET last_name='$evaluatorLastName', first_name='$evaluatorFirstName', 
                           middle_initial='$evaluatorMiddleInitial' WHERE evaluator_id ='$evaluatorID'";
    $editEvaluatorResult = mysqli_query($conn, $queryEditEvaluator);

    header('location: admin_edit_thesis.php?thesis_id='.$thesisID.'');
  }
  elseif(isset($_POST['edit_thesis'])) {
    $thesisID = $_POST['edit_txtCode'];
      if(!empty($_FILES['edit_flThesisFile'])) {
      $thesisTitle = $_POST['edit_txtTitle'];
      $year = $_POST['edit_txtYear'];
      $abstract = $_POST['edit_txtAbstract'];

      $thesisFile = $_FILES['edit_flThesisFile'];

      $thesisFileName = $thesisFile['name'];
      $thesisFileTempName = $thesisFile['tmp_name'];
      $thesisFileSize = $thesisFile['size'];
      $thesisFileError =$thesisFile['error'];

      $allowedThesisFileExt = array('zip','docx','pdf');
      if((in_array($thesisFileActualExt, $allowedThesisFileExt))){
        if ($thesisFileError === 0) {
          if ($thesisFileSize < 100000000){
            $newThesisFileName = $thesisID."file.".$thesisFileActualExt;
            $thesisFileDestination = 'uploads/'.$newThesisFileName;
            $queryEditThesis = "UPDATE tblThesis SET thesis_title = '$thesisTitle', year_accomplished = $year, file = '$thesisFileDestination' WHERE thesis_id = '$thesisID'";
            $editThesisResult = mysqli_query($conn,$queryEditThesis);
            move_uploaded_file($thesisFileTempName, $thesisFileDestination);

            $queryUpdateAbstract = "UPDATE tblThesis_Abstract SET abstract = '$abstract' WHERE thesis_id = '$thesisID'";
            $updateAbstractRes = mysqli_query($conn, $queryUpdateAbstract);

            header('location: admin_edit_thesis.php?thesis_id='.$thesisID.'');
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
      }
      else {
          $thesisTitle = $_POST['edit_txtTitle'];
          $year = $_POST['edit_txtYear'];
          $abstract = $_POST['edit_txtAbstract'];

          $queryEditThesis = "UPDATE tblThesis SET thesis_title = '$thesisTitle', year_accomplished = $year WHERE thesis_id = '$thesisID'";
          $editThesisResult = mysqli_query($conn,$queryEditThesis);
          $queryUpdateAbstract = "UPDATE tblThesis_Abstract SET abstract = '$abstract' WHERE thesis_id = '$thesisID'";
          $updateAbstractRes = mysqli_query($conn, $queryUpdateAbstract);

          header('location: admin_edit_thesis.php?thesis_id='.$thesisID.'');
      }
  }
?>

<?php
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

  function AutoGenerateEvaluatorID() { 

            $s = strtoupper(md5(uniqid(rand(),true)));
 
            $guidText = str_pad('E',8,substr($s,0,9));
     
            return $guidText;
        }
        // End Generate Guid 
        $Guid = AutoGenerateEvaluatorID();
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
  <title>Thesys | Edit Thesis (Admin)</title>
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
        <li><a href="admin_dashboard.php"><i class="fa fa-th"></i> <span>Dashboard</span></a></li>
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
        Edit
        <small>Administrators</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="admin_thesis.php"><i class="fa fa-book"></i>Theses</a></li>
        <li class="active">Edit Thesis (Administrator)</li>
      </ol>

    <div class="row">
      <div class="col-lg-4">
        <div class="box">
          <div class="box-header"><h4>Edit Thesis</h4></div>
          <form action="admin_edit_thesis.php" method="post">
            <div class="box-body">
               <table class="table table">
                 <tr>
                   <td>Thesis Code:</td>
                   <td> <input type="text" name="edit_txtCode" class="form-control" required="" style="width: 85%" value="<?php echo $thesisIDtoEdit?>" readonly="readonly"></td>
                 </tr>

                 <tr>
                   <td>Thesis Title:</td>
                   <td><input type="text" name="edit_txtTitle" class="form-control" required="" style="width: 85%" value="<?php echo $rowThesisToEdit['thesis_title']?>"></td>
                 </tr>

                 <tr>
                   <td>Thesis File</td>
                   <td><input type="file" name="edit_flThesisFile"></td>
                   <td><input type="hidden" name="hdnFile" value="<?php ?>"></td>
                 </tr>

                 <tr>
                  <td>Year Accomplished</td>
                  <td><input type="text" name="edit_txtYear" class="form-control" required="" style="width: 85%" value="<?php echo $rowThesisToEdit['year_accomplished']?>"></td>
                 </tr>
                 <tr>
                  <td>Thesis Abstract</td>
                   <td>
                    <?php
                      $rowAbstract = mysqli_fetch_assoc($abstractToEditRes);
                    ?>
                     <textarea name="edit_txtAbstract" style="resize: none; height: 200px; width: 220px; font-size: 16px; vertical-align: left;"><?php echo $rowAbstract['abstract']?></textarea>
                   </td>
                 </td>
                 </tr>
                 <tr>
                  <td></td>
                   <td>
                    <input type="submit" name="edit_thesis" class="btn btn-warning pull-right" value="Save Changes">
                   </td>
                 </tr>
               </table>
            </div>
          </form>
        </div>
      </div>
      
      <div class="col-lg-4">
        <div class="box">
          <div class="box-header"><h4>Edit Author</h4></div>
            <div class="box-body">
              <?php
              if(isset($_GET['edit_author_thesis_id'])){
                $id = $_GET['edit_author_thesis_id'];
                $queryAuthors = "SELECT * FROM tblProponents WHERE thesis_id = '$thesisIDtoEdit' AND id !=' $id'";
              }
              else{
                $queryAuthors = "SELECT * FROM tblProponents WHERE thesis_id = '$thesisIDtoEdit'";
              }
                $authorsResult = mysqli_query($conn, $queryAuthors);
              ?>
                <table class="table">
              <?php
                while($rowAuthors = mysqli_fetch_array($authorsResult))
                {
              ?>
                 <tr>
                   <td><?php echo $rowAuthors['first_name']." ".$rowAuthors['middle_initial'].". ".$rowAuthors['last_name'];?></td>
                   <td>
                    <a href="admin_edit_thesis.php?edit_author_thesis_id=<?php echo $rowAuthors['id']?>&edit_thesis_id=<?php echo $rowThesisToEdit['thesis_id']?>" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit Author"><i class="fa fa-edit"></i></a>
                    <a href="admin_edit_thesis.php?delete_author_thesis_id=<?php echo $rowAuthors['id']?>&delete_thesis_id=<?php echo $rowThesisToEdit['thesis_id']?>" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete Author"><i class="fa fa-close"></i></a>
                  </td>
                 </tr>
              <?php
                }
              ?>
              <?php 
                if(!empty(isset($_GET['edit_author_thesis_id'])))
                {
              ?>
              <form action="admin_edit_thesis.php" method="post">
                 <tr>
                  <td><input type="text" name="edit_txtLastName" class="form-control" onkeypress="return alphaOnly(event);" required="" value="<?php echo $rowAuthorsToEdit['last_name']?>"></td>
                  <td rowspan="3"><input type="submit" name="edit_author" value="Save Changes" class="btn btn-sm btn-warning"></td>
                 </tr>
                 <tr>
                   <td><input type="text" name="edit_txtFirstName" class="form-control" onkeypress="return alphaOnly(event);" required="" value="<?php echo $rowAuthorsToEdit['first_name']?>"></td>
                </tr>
                <tr>
                   <td><input type="text" name="edit_txtMiddleInitial" class="form-control" onkeypress="return alphaOnly(event);" value="<?php echo $rowAuthorsToEdit['middle_initial']?>"></td>
                </tr>
                <tr>
                  <td>
                    <input type="hidden" name="thesis_id" value="<?php echo $thesisID?>">
                  </td>
                </tr>
                 <tr>
                  <td>
                    <input type="hidden" name="author_id" value="<?php echo $rowAuthorsToEdit['id']?>">
                  </td>
                </tr>
              </form>
              <?php
                }
                else
                {
              ?>
              <form action="admin_edit_thesis.php" method="post">
                 <tr>
                  <td><input type="text" name="add_txtLastName" class="form-control" onkeypress="return alphaOnly(event);" required="" placeholder="Enter Author's Last Name"></td>
                  <td rowspan="3"><input type="submit" name="add_author" value="Add Author" class="btn btn-sm btn-primary"></td>
                 </tr>
                 <tr>
                   <td><input type="text" name="add_txtFirstName" class="form-control" onkeypress="return alphaOnly(event);" required="" placeholder="Enter Author's First Name"></td>
                </tr>
                <tr>
                   <td><input type="text" name="add_txtMiddleInitial" class="form-control" onkeypress="return alphaOnly(event);" placeholder="Enter Author's Middle Initial"></td>
                </tr>
                <tr>
                  <td>
                    <input type="hidden" name="thesis_id" value="<?php echo $rowThesisToEdit['thesis_id']?>">
                  </td>
                </tr>
              </form>
              <?php
                }
              ?>
               </table>
            </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="box">
          <div class="box-header"><h4>Edit Evaluator</h4></div>
            <div class="box-body">
            <?php
              $queryThesisEvaluators = "SELECT * FROM tblThesis_evaluators WHERE thesis_id = '".$thesisIDtoEdit."'";
              $thesisEvaluatorsResult = mysqli_query($conn,$queryThesisEvaluators);
            ?>
              <table class="table">
            <?php
              while($rowThesisEvaluators = mysqli_fetch_array($thesisEvaluatorsResult))
              {
                $queryEvaluators = "SELECT * from tblEvaluators WHERE evaluator_id = '".$rowThesisEvaluators['evaluator_id']."'";
                $evaluatorsResult = mysqli_query($conn, $queryEvaluators);

                while($rowEvaluators = mysqli_fetch_array($evaluatorsResult))
                {
            ?>
                <tr>
                   <td><?php echo $rowEvaluators['first_name']." ".$rowEvaluators['middle_initial'].". ".$rowEvaluators['last_name'];?></td>
                   <td>
                    <a href="admin_edit_thesis.php?edit_evaluator_thesis_id=<?php echo $rowEvaluators['evaluator_id']?>&edit_thesis_id=<?php echo $rowThesisToEdit['thesis_id']?>" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                    <a href="admin_edit_thesis.php?delete_evaluator_id=<?php echo $rowEvaluators['evaluator_id']?>&thesis_id=<?php echo $rowThesisToEdit['thesis_id']?>" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Remove"><i class="fa fa-close"></i></a>
                  </td>
                 </tr>
            <?php
                }
              }
            ?>
            <?php
              if(!empty(isset($_GET['edit_evaluator_thesis_id'])))
              {
            ?>
                <form action="admin_edit_thesis.php" method="post">
                <tr>
                  <td><input type="text" name="edit_txtLastName" class="form-control" onkeypress="return alphaOnly(event);" required="" value="<?php echo $rowEvaluatorToEdit['last_name'] ?>"></td>
                  <td rowspan="3">
                    <input type="submit" name="edit_evaluator" value="Save Changes" class="btn btn-sm btn-warning">
                   </td>
                </tr>
                <tr>
                  <td><input type="text" name="edit_txtFirstName" class="form-control" onkeypress="return alphaOnly(event);" required="" value="<?php echo $rowEvaluatorToEdit['first_name'] ?>"></td>
                </tr>
                <tr>
                  <td><input type="text" name="edit_txtMiddleInitial" class="form-control" onkeypress="return alphaOnly(event);" value="<?php echo $rowEvaluatorToEdit['middle_initial'] ?>"></td>
                </tr>
                <tr>
                  <td><input type="hidden" name="thesis_id" value="<?php echo $thesisID?>"></td>
                </tr>
                <tr>
                  <td><input type="hidden" name="evaluator_id" value="<?php echo $rowEvaluatorToEdit['evaluator_id']?>"></td>
                </tr>
                </form>
              <?php
                }
                else
                {
              ?>
              <form action="admin_edit_thesis.php" method="post">
                <tr>
                  <td>
                    <input type="text" name="add_txtEvaluatorID" value="<?php echo $Guid;?>" class="form-control" readonly="readonly">
                  </td>
                  <td rowspan="4">
                    <input type="submit" name="add_evaluator" value="Add Evaluator" class="btn btn-sm btn-primary">
                   </td>
                </tr>
                <tr>
                  <td><input type="text" name="add_txtLastName" class="form-control" onkeypress="return alphaOnly(event);" required="" placeholder="Evaluator's Last Name"></td>
                </tr>
                <tr>
                  <td><input type="text" name="add_txtFirstName" class="form-control" onkeypress="return alphaOnly(event);" required="" placeholder="Evaluator's First Name"></td>
                </tr>
                <tr>
                  <td><input type="text" name="add_txtMiddleInitial" class="form-control" onkeypress="return alphaOnly(event);" required="" placeholder="Evaluator's Middle Initial"></td>
                </tr>
                <tr>
                  <td><input type="hidden" name="thesis_id" value="<?php echo $rowThesisToEdit['thesis_id']?>"></td>
                </tr>
              </form>
              <?php
                }
              ?>
               </table>
            </div>
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