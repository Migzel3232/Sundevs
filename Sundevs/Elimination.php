<?php
include("Conexion/conexion.php");
include_once("Links/Links.php");

if (isset($_GET['ID'])) {
    $taskId = $_GET['ID'];
    
    
    $taskId = mysqli_real_escape_string($conexion, $taskId);

    $Elimination = "DELETE FROM tasks WHERE ID_Task = '$taskId'";

    if (mysqli_query($conexion, $Elimination)) {
       
        echo "<script>
            Swal.fire({
                title: '¡Éxito!',
                text: 'La tarea ha sido eliminada.',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'Index.php'; 
                }
            });
        </script>";
    } else {
        
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'No se pudo eliminar la tarea.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'Index.php'; 
                }
            });
        </script>";
    }
} else {
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'No se ha proporcionado ningún ID.',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'Index.php'; 
            }
        });
    </script>";
}
?>
