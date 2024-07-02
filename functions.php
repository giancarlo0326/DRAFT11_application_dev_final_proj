<?php
require_once 'database.php';

function addVideo($title, $genre, $director, $release_date, $available_copies, $video_type) {
    global $conn;
    
    $stmt = $conn->prepare("INSERT INTO videos (title, genre, director, release_date, available_copies, video_type) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssssds", $title, $genre, $director, $release_date, $available_copies, $video_type);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }

    $stmt->close();
}

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function getAllVideos($conn) {
    $stmt = $conn->prepare("SELECT * FROM videos");
    $stmt->execute();
    $result = $stmt->get_result();
    $videos = [];
    while ($row = $result->fetch_assoc()) {
        $videos[] = $row;
    }
    return $videos;
}

function getVideoById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM videos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function editVideo($conn, $id, $title, $genre, $director, $release_date, $available_copies, $video_type) {
    $stmt = $conn->prepare("UPDATE videos SET title = ?, genre = ?, director = ?, release_date = ?, available_copies = ?, video_type = ? WHERE id = ?");
    $stmt->bind_param("ssssisi", $title, $genre, $director, $release_date, $available_copies, $video_type, $id);
    return $stmt->execute();
}

function deleteVideo($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM videos WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function rentVideo($conn, $userId, $videoId) {
    $stmt = $conn->prepare("INSERT INTO rentals (user_id, video_id, rent_date) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $userId, $videoId);
    return $stmt->execute();
}
?>