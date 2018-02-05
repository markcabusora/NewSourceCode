  <?php 

  function loadData() {
    $conn = mysqli_connect("localhost", "root", "", "dbthesys");

    $query = sprintf("SELECT thesis_id as y, COUNT(id) as item1 FROM tblThesis_citation GROUP BY thesis_id");
    $query2 = mysqli_query($conn, "SELECT thesis_id as y, COUNT(id) as item1 FROM tblThesis_citation GROUP BY thesis_id");

    $result = $conn->query($query);


    $data = array();
    $data1 = array();
    $data2 = array();

    foreach($result as $row){
      $data[] = $row;
    }

    $result->close();

    $conn->close();
    

    //$helloKo = json_encode($data);
    return json_encode($data);
  }
  loadData();

?>