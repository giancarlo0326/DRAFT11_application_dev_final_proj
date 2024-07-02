<?php
if ($_FILES['thumbnailFile']['error'] === UPLOAD_ERR_OK) {
    $tempFile = $_FILES['thumbnailFile']['tmp_name'];
    $targetFile = 'uploads/' . $_FILES['thumbnailFile']['name'];

    if (move_uploaded_file($tempFile, $targetFile)) {
        echo json_encode(['success' => true, 'file' => $targetFile]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error moving file']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Error uploading file']);
}
?>
