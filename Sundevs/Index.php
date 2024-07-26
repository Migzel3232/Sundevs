<?php 
session_start();
include("Conexion/conexion.php");
include_once("Links/Links.php");

$sql = "SELECT t.Task_Name, t.Start_Date, t.End_Date, s.Status, t.Priority_Level, t.Description , ID_Task
        FROM tasks t
        JOIN status s ON t.FK_Status = s.ID_Status";
$result = $conn->query($sql);

$tasksByStatus = [
    'To_Do' => [],
    'Inprocess' => [],
    'Finished' => []
];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tasksByStatus[$row['Status']][] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task_id']) && isset($_POST['new_status'])) {
        $taskId = intval($_POST['task_id']);
        $newStatus = $conn->real_escape_string($_POST['new_status']);
    
        $statusQuery = "SELECT ID_Status FROM status WHERE Status = '$newStatus'";
        $statusResult = $conn->query($statusQuery);
        $statusRow = $statusResult->fetch_assoc();
        $statusId = $statusRow['ID_Status'];
    
        $updateQuery = "UPDATE tasks SET FK_Status = $statusId WHERE ID_Task = $taskId";
        if ($conn->query($updateQuery) === TRUE) {
            header("Refresh:0");
        }
    } elseif (isset($_POST['delete_task_id'])) {
        $taskId = intval($_POST['delete_task_id']);
        $deleteQuery = "DELETE FROM tasks WHERE ID_Task = $taskId";
        if ($conn->query($deleteQuery) === TRUE) {
            header("Refresh:0");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centro de Divs</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/Index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>
<body>
<div class="container-fluid full-height">
    <div class="row full-height">
        <div class="col-12 d-flex justify-content-center align-items-center">
            <div class="outer-div">
                <h1>Task Pane</h1>
                <input type="text" id="searchInput" class="form-control search-input" placeholder="Search tasks...">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div id="toDo" class="inner-div text-center" ondrop="drop(event)" ondragover="allowDrop(event)">
                            <h2>To Do</h2>
                            <?php foreach ($tasksByStatus['To_Do'] as $task): ?>
                                <div id="task-<?php echo htmlspecialchars($task['ID_Task']); ?>" class="task-item" draggable="true" onclick="showTaskDetails(<?php echo htmlspecialchars(json_encode($task)); ?>)">
                                    <span class="task-number"><?php echo htmlspecialchars($task['ID_Task']); ?>:</span>
                                    <span><?php echo htmlspecialchars($task['Task_Name']); ?></span>
                                    <button class="delete-btn" data-task-id="<?php echo htmlspecialchars($task['ID_Task']); ?>">&times;</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div id="inProcess" class="inner-div text-center" ondrop="drop(event)" ondragover="allowDrop(event)">
                            <h2>Inprocess</h2>
                            <?php foreach ($tasksByStatus['Inprocess'] as $task): ?>
                                <div id="task-<?php echo htmlspecialchars($task['ID_Task']); ?>" class="task-item" draggable="true" onclick="showTaskDetails(<?php echo htmlspecialchars(json_encode($task)); ?>)">
                                    <span class="task-number"><?php echo htmlspecialchars($task['ID_Task']); ?>:</span>
                                    <span><?php echo htmlspecialchars($task['Task_Name']); ?></span>
                                    <button class="delete-btn" data-task-id="<?php echo htmlspecialchars($task['ID_Task']); ?>">&times;</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div id="finished" class="inner-div text-center" ondrop="drop(event)" ondragover="allowDrop(event)">
                            <h2>Finished</h2>
                            <?php foreach ($tasksByStatus['Finished'] as $task): ?>
                                <div id="task-<?php echo htmlspecialchars($task['ID_Task']); ?>" class="task-item" draggable="true" onclick="showTaskDetails(<?php echo htmlspecialchars(json_encode($task)); ?>)">
                                    <span class="task-number"><?php echo htmlspecialchars($task['ID_Task']); ?>:</span>
                                    <span><?php echo htmlspecialchars($task['Task_Name']); ?></span>
                                    <button class="delete-btn" data-task-id="<?php echo htmlspecialchars($task['ID_Task']); ?>">&times;</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-start mt-4">
                    <button type="button" class="btn btn-primary" id="createTaskBtn">
                        Create
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="dragDropForm" action="" method="POST" style="display:none;">
    <input type="hidden" name="task_id" id="taskIdInput">
    <input type="hidden" name="new_status" id="newStatusInput">
</form>

<form id="deleteTaskForm" action="" method="POST" style="display:none;">
    <input type="hidden" name="delete_task_id" id="deleteTaskIdInput">
</form>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>

function allowDrop(event) {
    event.preventDefault();
}

function drag(event) {
    event.dataTransfer.setData("text", event.target.id); 
    event.target.classList.add('dragging'); 
}

function dragEnd(event) {
    event.target.classList.remove('dragging'); 
}

function drop(event) {
    event.preventDefault();
    let data = event.dataTransfer.getData("text");
    let draggedElement = document.getElementById(data);
    let targetDiv = event.target.closest('.inner-div');
    
    if (!targetDiv) return;

    let taskId = draggedElement.id.replace('task-', '');
    let newStatus = targetDiv.id;

    const statusMap = {
        'toDo': 'To_Do',
        'inProcess': 'Inprocess',
        'finished': 'Finished'
    };

    newStatus = statusMap[newStatus] || 'To_Do'; 
    document.getElementById('taskIdInput').value = taskId;
    document.getElementById('newStatusInput').value = newStatus;
    document.getElementById('dragDropForm').submit();
}

document.querySelectorAll('.task-item').forEach(item => {
    item.addEventListener('dragstart', drag);
    item.addEventListener('dragend', dragEnd); 
});

document.querySelectorAll('.inner-div').forEach(container => {
    container.addEventListener('dragover', allowDrop);
    container.addEventListener('drop', drop);
});

document.querySelector('#createTaskBtn').addEventListener('click', function() {
    Swal.fire({
        title: 'Create a new task',
        text: 'Are you sure you want to create a new task?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, create it!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'Create.php';
        } else {
            console.log('Task creation canceled.');
        }
    });
});

document.querySelector('#searchInput').addEventListener('input', function() {
    let searchValue = this.value.toLowerCase();
    let taskItems = document.querySelectorAll('.task-item');
    taskItems.forEach(function(item) {
        let taskName = item.querySelector('span:nth-child(2)').textContent.toLowerCase();
        if (taskName.includes(searchValue)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
});

document.querySelectorAll('.delete-btn').forEach(function(btn) {
    btn.addEventListener('click', function(event) {
        event.stopPropagation();
        let taskId = btn.getAttribute('data-task-id');
        document.getElementById('deleteTaskIdInput').value = taskId;
        document.getElementById('deleteTaskForm').submit();
    });
});

function showTaskDetails(task) {
    let taskDetails = `
        <p><strong>Task Name:</strong> ${task.Task_Name}</p>
        <p><strong>Start Date:</strong> ${task.Start_Date}</p>
        <p><strong>End Date:</strong> ${task.End_Date}</p>
        <p><strong>Status:</strong> ${task.Status}</p>
        <p><strong>Priority Level:</strong> ${task.Priority_Level}</p>
        <p><strong>Description:</strong> ${task.Description}</p>
    `;

    Swal.fire({
        title: 'Task Details',
        html: taskDetails,
        icon: 'info'
    });
}
</script>
</body>
</html>
