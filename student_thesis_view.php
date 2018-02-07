<?php
    require 'dbconnect.php';

    session_start();

    $userID = $_SESSION['user_id'];

  if(isset($_GET['cite_thesis_id']))
  {
      $viewThesisID =$_GET['cite_thesis_id'];

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

      $querySortAuthors = "SELECT * FROM tblProponents WHERE thesis_id = '".$viewThesisID."'";
      $sortAuthorsResult = mysqli_query($conn, $querySortAuthors);

      $rowAuthors = mysqli_fetch_array($sortAuthorsResult);

      $queryCiteThesis = "INSERT INTO tblThesis_Citation(thesis_id, date_and_time) 
                          VALUES('$viewThesisID', now())";

      $citeThesisResult = mysqli_query($conn, $queryCiteThesis);

  }

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
<script type="text/javascript">
  function ChooseCitationFormat()
  {
      var format = document.getElementById('ddlFormat').value;
      var citation = document.getElementById('txtCitation');

      if(format == 'APA Format')
        citation.value='<?php 
              if(mysqli_num_rows($thesisViewQueryResult) > 0)
                  {
                  $thesisViewQuery = "SELECT * FROM tblThesis WHERE thesis_id='".$viewThesisID."'";
                  $thesisViewQueryResult = mysqli_query($conn, $thesisViewQuery);
                  $rowThesis = mysqli_fetch_array($thesisViewQueryResult);
                  $querySortAuthors = "SELECT * FROM tblProponents WHERE thesis_id = '".$viewThesisID."'";
                  $sortAuthorsResult = mysqli_query($conn, $querySortAuthors);


                  while($rowAuthors = mysqli_fetch_array($sortAuthorsResult))
                  {
                    echo $rowAuthors['last_name'].", ".substr($rowAuthors['first_name'], 0,1).".".$rowAuthors['middle_initial'].", ";
                  }
                  echo ". "."(".$rowThesis['year_accomplished'].")".". ".$rowThesis['thesis_title'].". "."Retrieved from University of Makati Database";
                }
         ?>';
        else if (format == 'MLA Format')
        {
          citation.value = '<?php 
              if(mysqli_num_rows($thesisViewQueryResult) > 0)
                  {
                  $thesisViewQuery = "SELECT * FROM tblThesis WHERE thesis_id='".$viewThesisID."'";
                  $thesisViewQueryResult = mysqli_query($conn, $thesisViewQuery);
                  $rowThesis = mysqli_fetch_array($thesisViewQueryResult);
                  $querySortAuthors = "SELECT * FROM tblProponents WHERE thesis_id = '".$viewThesisID."'";
                  $sortAuthorsResult = mysqli_query($conn, $querySortAuthors);

                  while($rowAuthors = mysqli_fetch_array($sortAuthorsResult))
                  {
                    echo $rowAuthors['last_name'].", ".$rowAuthors['first_name']." ".$rowAuthors['middle_initial']."., ";
                  }
                  echo ". ".$rowThesis['thesis_title'].".".date("Y")."."."University of Makati";
                }
          ?>'
        }
      /* else if (format == 'Chicago/Turabian Format')
        {
          citation.value = '<?php 
              if(mysqli_num_rows($thesisViewQueryResult) > 0)
                  {
                  $thesisViewQuery = "SELECT * FROM tblThesis WHERE thesis_id='".$viewThesisID."'";
                  $thesisViewQueryResult = mysqli_query($conn, $thesisViewQuery);
                  $rowThesis = mysqli_fetch_array($thesisViewQueryResult);
                  $querySortAuthors = "SELECT * FROM tblProponents WHERE thesis_id = '".$viewThesisID."'";
                  $sortAuthorsResult = mysqli_query($conn, $querySortAuthors);

                  while($rowAuthors = mysqli_fetch_array($sortAuthorsResult))
                  {
                    echo $rowAuthors['first_name']." ".$rowAuthors['last_name'].", ";
                  }
                  echo ", "."''".$rowThesis['thesis_title']."''".". Web. ".date("d M Y");
                }
          ?>'
        }*/
  }

</script>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Thesys | Thesis Details Page</title>
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
  <style>
#thesisImg {
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

#thesisImg:hover {opacity: 0.7;}

/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 200; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 50px;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (image) */
.modal-content {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
}

/* Caption of Modal Image */
#caption {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
    text-align: center;
    color: #ccc;
    padding: 10px 0;
    height: 150px;
}

/* Add Animation */
.modal-content, #caption {    
    -webkit-animation-name: zoom;
    -webkit-animation-duration: 0.6s;
    animation-name: zoom;
    animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
    from {-webkit-transform:scale(0)} 
    to {-webkit-transform:scale(1)}
}

@keyframes zoom {
    from {transform:scale(0)} 
    to {transform:scale(1)}
}

/* The Close Button */
.close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
}

.close:hover,
.close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
    .modal-content {
        width: 100%;
    }
}
</style>
</head>
<body class="hold-transition skin-green-light sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="faculty_dashboard.php" class="logo">
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
             
              <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $rowLoggedUser['user_type'] ?></span>
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
          <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
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
       <li class="active"><a href="student_dashboard.php"><i class="fa fa-th"></i> <span>Dashboard</span></a></li>
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
        <small>Faculty</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-sm-4">
          <div class="box">
            <div class="box-header">
              <CENTER><font size="+2">ABSTRACT</font></CENTER>
              <?php 
                echo string nl2br($rowThesisAbstract['abstract']);
              ?>
              <!-- The Modal -->
              <div id="myModal" class="modal">
                <span class="close">&times;</span>
                <img class="modal-content" id="img01">
              <div id="caption"></div>
              </div>
            </div>
          </div>
        </div>


        <div class="col-sm-8">
          <div class="box-body">
             <ul class="list-inline">
                <li><h2><?php if(mysqli_num_rows($thesisViewQueryResult) > 0)
                echo $rowThesis['thesis_title']?></h2></li>
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
                        echo $rowAuthors['first_name']." ".$rowAuthors['middle_initial'].". ".$rowAuthors['last_name'].", ";
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
                            echo $rowEvaluators['first_name']." ".$rowEvaluators['middle_initial'].". ".$rowEvaluators['last_name'].", ";
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
                  <div class="col-sm-6">
                    <a href="users_thesis_view.php?cite_thesis_id=<?php echo $viewThesisID; ?>" class="btn btn-block btn-primary" data-toggle="tooltip" title="Cite Thesis" <?php if(!empty(isset($_GET['cite_thesis_id']))) echo "disabled='disabled'"; ?>>
                      <i class="fa fa-quote-left">
                      </i>
                    </a></div>
                  <?php
                    if(!empty(isset($_GET['cite_thesis_id'])))
                    {
                      $viewThesisID =$_GET['cite_thesis_id'];

                      $thesisViewQuery = "SELECT * FROM tblThesis WHERE thesis_id='".$viewThesisID."'";
                      $thesisViewQueryResult = mysqli_query($conn, $thesisViewQuery);
                      $rowThesis = mysqli_fetch_array($thesisViewQueryResult);

                  ?>
                    
                  <div class="col-sm-6"><a href="student_thesis_download.php?file=<?php echo urlencode($rowThesis['file']); ?>&user_id=<?php echo $userID ?>&thesis_id= <?php echo $viewThesisID?>" class="btn btn-block btn-success"><i class="fa fa-download"></i></a></div>
                  <?php
                    }
                  ?>
                </div>               
              </div>
              
              <div class="col-sm-4">
                
              </div>
            </div>
            <?php
            if(isset($_GET['cite_thesis_id']))
            { 
            ?>
            <div class="col-sm-8">
              &nbsp;&nbsp;&nbsp;
              <select class="form-control select2" id="ddlFormat" onclick="ChooseCitationFormat()">
                <option>MLA Format</option>
                <option>APA Format</option>
                <!--<option>Chicago/Turabian Format</option>-->
              </select>
            </div>
            <div class="col-sm-8">
              <br>

            </div>
            <div class="col-sm-8">
              <textarea style="resize: none; height: 200px; width: 550px; font-size: 16px; vertical-align: left;" id="txtCitation">
                <?php
                  $viewThesisID =$_GET['cite_thesis_id'];

                  $thesisViewQuery = "SELECT * FROM tblThesis WHERE thesis_id='".$viewThesisID."'";
                  $thesisViewQueryResult = mysqli_query($conn, $thesisViewQuery);

                  if(mysqli_num_rows($thesisViewQueryResult) > 0)
                  {
                  $thesisViewQuery = "SELECT * FROM tblThesis WHERE thesis_id='".$viewThesisID."'";
                  $thesisViewQueryResult = mysqli_query($conn, $thesisViewQuery);
                  $rowThesis = mysqli_fetch_array($thesisViewQueryResult);
                  $querySortAuthors = "SELECT * FROM tblProponents WHERE thesis_id = '".$viewThesisID."'";
                  $sortAuthorsResult = mysqli_query($conn, $querySortAuthors);

                  while($rowAuthors = mysqli_fetch_array($sortAuthorsResult))
                  {
                    echo $rowAuthors['last_name'].", ".$rowAuthors['first_name']." ".$rowAuthors['middle_initial']."., ";
                  }
                  echo ". ".$rowThesis['thesis_title'].".".date("Y")."."."University of Makati";
          ?>
              </textarea>
            </div>
            <?php
                }
            }
            ?>
          </div>
        </div>

        <div class="col-sm-8">
          <div class="box">
            <div class="box-header"><h4>Suggested Theses</h4></div>
              <div class="box-body">
                <?php 
                  $querySuggest = "SELECT COUNT(thesis_id) from tblThesis_views WHERE user_id != '$userID' GROUP BY thesis_id";
                  $suggestRes = mysqli_query($conn, $querySuggest);

                  while($rowSuggest = mysqli_fetch_assoc($suggestRes)){
                    $thesisID = $rowSuggest['thesis_id'];
                    $queryRetThesis = "SELECT * FROM tblThesis WHERE thesis_id = '$thesisID'";
                    $retThesisRes = mysqli_query($conn, $queryRetThesis);

                    while($rowThesis = mysqli_fetch_assoc($retThesisRes)){
                ?>
                     <div class="col-sm-3"><br><br><br><br><br><br><br><a href="student_thesis_view.php?id=<?php echo $thesisID?>"><?php echo $rowThesis['thesis_title'] ?></a></div>
                  <?php
                    }
                  }
                ?>
               
              </div>
             
            </div>            
        </div>

        </div>
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
