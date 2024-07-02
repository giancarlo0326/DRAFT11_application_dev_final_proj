<?php
session_start();
require 'database.php';
require 'functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$userId = $_SESSION['user_id'];
$videoId = $_GET['id'];

if (rentVideo($conn, $userId, $videoId)) {
    header("Location: viewrentals.php");
    exit();
} else {
    echo "Failed to rent video.";
}
?>
