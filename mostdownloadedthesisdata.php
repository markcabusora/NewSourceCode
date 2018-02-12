<?php
	//header('Content-Type: application/json');
	
	$conn = new MySQLI("localhost", "root", "", "dbthesys");

	$query = sprintf("SELECT thesis_id, COUNT(id) as downloads FROM tblThesis_downloads GROUP BY thesis_id ORDER BY downloads DESC LIMIT 5");

	$result = $conn->query($query);


	$data = array();

	foreach($result as $row){
		$data[] = $row;
	}

	$result->close();

	$conn->close();
	
	print json_encode($data);

?>