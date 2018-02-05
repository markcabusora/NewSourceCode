<?php
    include 'dbconnect.php';
	$filePath=urldecode($_REQUEST['file']);
    $thesisID= $_GET['thesis_id'];
    $userID = $_GET['user_id'];

    if(file_exists($filePath)) {
        $fileName = basename($filePath);
        $fileSize = filesize($filePath);

        header("Cache-Control: private");
        header("Content-Type: application/stream");
        header("Content-Length: ".$fileSize);
        header("Content-Disposition: attachment; filename=".$fileName);

        readfile ($filePath);

        $queryInsertToDownloads = "INSERT INTO tblThesis_downloads(user_id, thesis_id, date_and_time) VALUES('$userID', '$thesisID', now())";
        $downloadResult = mysqli_query($conn, $queryInsertToDownloads);                   
        exit();
    }
    else {
        die('The provided file path is not valid.');
    }
?>
