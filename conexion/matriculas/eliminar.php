<?php
include_once '../db.php';

$eliminarId = isset($_GET['eliminarId']) ? $_GET['eliminarId'] : '';

if ($eliminarId) {

    $eliminarId = $conn->real_escape_string($eliminarId);
    $deleteSql = "DELETE FROM matriculas WHERE id = $eliminarId";
    
    if ($conn->query($deleteSql) === TRUE) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID no proporcionado']);
}
?>
