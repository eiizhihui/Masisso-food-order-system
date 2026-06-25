<?php
require_once("../config.php");

$q = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';

if (!$q) {
    echo json_encode([]);
    exit;
}

$results = [];

// Search users (customer + staff)
$user_query = "
    SELECT user_id, name, email, 'Customer' as role, 'user' as type FROM customer WHERE name LIKE '%$q%' OR email LIKE '%$q%'
    UNION ALL
    SELECT staff_id as user_id, name, email, position as role, 'user' as type FROM staff WHERE name LIKE '%$q%' OR email LIKE '%$q%'
    LIMIT 5
";
$res = mysqli_query($conn, $user_query);
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $row['type'] = 'user';
        $results[] = $row;
    }
}

// Search menu
$menu_query = "SELECT *, 'menu' as type FROM menu_items WHERE name LIKE '%$q%' OR category LIKE '%$q%' LIMIT 5";
$res = mysqli_query($conn, $menu_query);
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $row['type'] = 'menu';
        $results[] = $row;
    }
}

// Search offers
$offer_query = "SELECT *, 'offer' as type FROM offers WHERE code LIKE '%$q%' OR title LIKE '%$q%' LIMIT 5";
$res = mysqli_query($conn, $offer_query);
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $row['type'] = 'offer';
        $results[] = $row;
    }
}

// Search rewards
$reward_query = "SELECT *, 'reward' as type FROM rewards WHERE reward_name LIKE '%$q%' LIMIT 5";
$res = mysqli_query($conn, $reward_query);
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        // Map columns to match what front-end expects
        $row['type'] = 'reward';
        $row['title'] = $row['reward_name'];
        $row['bowls_required'] = $row['points_required'];
        $results[] = $row;
    }
}

echo json_encode($results);
?>
