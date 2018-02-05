<?php
	//header('Content-Type: application/json');
	
	$conn = new MySQLI("localhost", "root", "", "dbthesys");

	$query = sprintf("SELECT thesis_id, COUNT(id) as Citations FROM tblThesis_citation GROUP BY thesis_id");

	$result = $conn->query($query);


	$data = array();

	foreach($result as $row){
		$data[] = $row;
	}

	$result->close();

	$conn->close();
	
	print json_encode($data);

?>