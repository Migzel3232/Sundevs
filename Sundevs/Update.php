<?php

session_start();
include("Conexion/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = isset($_POST['taskId']) ? intval($_POST['taskId']) : 0;
    $newStatus = isset($_POST['newStatus']) ? $_POST['newStatus'] : '';

    $statusMap = [
        'toDo' => 1,
        'ondOing' => 2,
        'finished' => 3
    ];

    if (isset($statusMap[$newStatus])) {
        $statusId = $statusMap[$newStatus];

        $sql = "UPDATE tasks SET FK_Status = ? WHERE ID_Task = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $statusId, $taskId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Task status updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating task status: ' . $stmt->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid status.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
