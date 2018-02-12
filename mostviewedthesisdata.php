<?php
	//header('Content-Type: application/json');
	
	$conn = new MySQLI("localhost", "root", "", "dbthesys");

	$query = sprintf("SELECT thesis_id, COUNT(id) as Views FROM tblThesis_Views GROUP BY thesis_id ORDER BY Views DESC LIMIT 5");

	$result = $conn->query($query);


	$data = array();

	foreach($result as $row){
		$data[] = $row;
	}

	$result->close();

	$conn->close();
	
	print json_encode($data);

?>