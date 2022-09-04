<?php

require_once("../config.php");

$sql = "SELECT DISTINCT name FROM category";
$result = mysqli_query($con, $sql);

$categories = array();
while ($row = $result->fetch_assoc()) {
    $categories[] = $row['name'];
}
echo json_encode($categories);

$con->close();
