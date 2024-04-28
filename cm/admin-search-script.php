<?php
define('DB_SERVER', 'localhost');
define('DB_USER', 'i608426_bb2');
define('DB_PASSWORD', 'E&g0&F1qd]60)~1');
define('DB_NAME', 'i608426_bb2');

if (isset($_GET['term'])){
$return_arr = array();

try {
$conn = new PDO("mysql:host=".DB_SERVER.";port=8889;dbname=".DB_NAME, DB_USER, DB_PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare('SELECT user_id,pf_real_name FROM bb_profile_fields_data WHERE pf_real_name LIKE :term ORDER BY pf_real_name ASC');
$stmt->execute(array('term' => '%'.$_GET['term'].'%'));

while($row = $stmt->fetch()) {
$return_arr[] = $row['pf_real_name'];
}

} catch(PDOException $e) {
echo 'ERROR: ' . $e->getMessage();
}


    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);
}

?>