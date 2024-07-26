<?php  
session_start();
include("Conexion/conexion.php");
include_once("Links/Links.php");

$sql_status = "SELECT ID_Status, Status FROM status";
$result_status = mysqli_query($conexion, $sql_status);

$sql_priority = "SELECT ID_Level, Level FROM priority_level";
$result_priority = mysqli_query($conexion, $sql_priority);

if (isset($_POST["register"])) {
    $task_name = mysqli_real_escape_string($conexion, $_POST['task_name']);
    $start_date = mysqli_real_escape_string($conexion, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conexion, $_POST['end_date']);
    $status = mysqli_real_escape_string($conexion, $_POST['status']);
    $priority = mysqli_real_escape_string($conexion, $_POST['priority']);
    $description = mysqli_real_escape_string($conexion, $_POST['description']);

    $errors = array();

    if (empty($task_name) || empty($start_date) || empty($end_date) || empty($status) || empty($priority) || empty($description)) {
        $errors[] = "All fields are required.";
    }

    $start_date_time = new DateTime($start_date);
    $end_date_time = new DateTime($end_date);

    if ($start_date_time >= $end_date_time) {
        $errors[] = "The start date and time must be before the end date and time.";
    }

    if ($priority < 1 || $priority > 10) {
        $errors[] = "The priority level must be between 1 and 10.";
    }

    $sql_check_task = "SELECT * FROM tasks WHERE Task_Name = '$task_name'";
    $result_check_task = mysqli_query($conexion, $sql_check_task);
    if (mysqli_num_rows($result_check_task) > 0) {
        $errors[] = "A task with the same name already exists.";
    }

    if (empty($errors)) {
        $Create = "INSERT INTO tasks (Task_Name, Start_Date, End_Date, FK_Status, Priority_Level, Description)
                VALUES ('$task_name', '$start_date', '$end_date', '$status', '$priority', '$description')";

        if (mysqli_query($conexion, $Create)) {
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Task created successfully',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = 'Index.php';
                    });
                </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error creating task',
                        text: '" . mysqli_error($conexion) . "',
                        showConfirmButton: true
                    });
                </script>";
        }
    } else {
        foreach ($errors as $error) {
            echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Error',
                        text: '$error',
                        showConfirmButton: true
                    });
                </script>";
        }
    }
}


$default_start_date = (new DateTime())->format('Y-m-d\TH:i');
$default_end_date = (new DateTime('+1 hour'))->format('Y-m-d\TH:i');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centered Divs</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/Create.css">
</head>
<body>
<div class="container-fluid full-height">
    <div class="row full-height">
        <div class="col-12 d-flex justify-content-center align-items-center">
            <div class="inner-div text-center">
                <h1 class="mb-4">Sundevs To-do list</h1>
                <div class="Form">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="task_name">Task Name</label>
                            <input type="text" class="form-control" name="task_name" id="task_name" required>
                        </div>
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="datetime-local" class="form-control" name="start_date" id="start_date" value="<?php echo $default_start_date; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="datetime-local" class="form-control" name="end_date" id="end_date" value="<?php echo $default_end_date; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Task Status</label>
                            <select class="form-control" name="status" id="status" required>
                                <?php while ($row_status = mysqli_fetch_assoc($result_status)) { ?>
                                    <option value="<?php echo $row_status['ID_Status']; ?>">
                                        <?php echo htmlspecialchars($row_status['Status']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="priority">Priority Level</label>
                            <select class="form-control" name="priority" id="priority" required>
                                <?php while ($row_priority = mysqli_fetch_assoc($result_priority)) { ?>
                                    <option value="<?php echo $row_priority['ID_Level']; ?>">
                                        <?php echo htmlspecialchars($row_priority['Level']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description" id="description" required></textarea>
                        </div>
                        <div class="d-flex justify-content-start">
                            <button type="submit" name="register" class="btn btn-primary">Add Task</button>
                            <button type="button" class="btn btn-secondary ml-2" onclick="window.location.href='Index.php'">Back</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
