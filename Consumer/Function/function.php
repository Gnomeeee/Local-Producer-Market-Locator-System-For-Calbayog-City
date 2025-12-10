<?php

// FOR SEARCH 
include '../Database/dbconnect.php';  

$search = isset($_GET['search_farm']) ? trim($_GET['search_farm']) : '';

$sql = "SELECT farms.*, producers.username
        FROM farms
        JOIN producers ON farms.producer_id = producers.producer_id";

$params = [];
$types = "";

if(!empty($search)){
    $sql .= " WHERE farms.farm_name LIKE ?
              OR farms.address LIKE ?
              OR farms.city LIKE ?
              OR farms.description LIKE ?";
    $searchParam = "%{$search}%";
    $params = [$searchParam, $searchParam, $searchParam, $searchParam];
    $types = 'ssss';
}

$stmt = $conn->prepare($sql);
if(!empty($params)){
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();