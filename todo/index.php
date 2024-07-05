<?php 
require("database.php");

$task_id = isset($_GET['id']) ? $_GET['id'] : '';
$task_name = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $task_name = @$_POST["task_name"];
    $task_id = @$_POST["task_id"];

    if ($task_name) {
        if ($task_id) {
           
            $update = $conn->prepare("UPDATE tasks SET task_name = ? WHERE id = ?");
            $update->execute([$task_name, $task_id]);
        } else {
           
            $in = $conn->prepare("INSERT INTO tasks(task_name) VALUES(?)");
            $in->execute([$task_name]);
        }
      
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $error = "Entering the task is required!";
    }
}

if ($task_id) {

    $task = $conn->prepare("SELECT task_name FROM tasks WHERE id = ?");
    $task->execute([$task_id]);
    $task_data = $task->fetch(PDO::FETCH_OBJ);
    if ($task_data) {
        $task_name = $task_data->task_name;
    }
}

$query = $conn->query("SELECT * FROM tasks order by id desc");
$tasks = $query->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WELCOM TO TO-DO-LIST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
   
</head>
<body>
    <nav class="navbar bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand text-center w-100" href="#">WELCOM TO TO-DO-LIST</a>
        </div>
    </nav>
    <section>
        <div class="form-container">
            <form action="" method="post" class="d-flex w-75">
                <input type="hidden" name="task_id" value="<?= $task_id ?>">
                <input type="text" name="task_name" class="form-control" placeholder="Enter task" value="<?= $task_name ?>">
                <input type="submit" value="<?= $task_id ? 'Update &#x2710;' : 'Add &#10010;' ?>" class="btn btn-primary">
            </form>
        </div>

        <?php if (isset($error)): ?>
            <div class="error-message"><?= $error ?></div>
        <?php endif; ?>

        <div class="table-container">
            <table class="table">
                
                <tbody>
                    <?php foreach ($tasks as $task) { ?>
                    <tr>
                        <td class="text-center">
                            <label class="custom-checkbox">
                                <input type="checkbox" class="check" onclick="check(this)">
                                <span class="checkmark"></span>
                            </label>
                        </td>
                        <td><?php echo $task->task_name ?></td>
                        <td class="text-end">
                            <a href="?id=<?= $task->id ?>" class="btn btn-sm btn-warning">&#x2710;</a>
                            <a href="delet_task.php?id=<?= $task->id ?>" class="btn btn-sm btn-danger" onclick="return confirm('Do you want to delete this task?')">&#10006;</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
    <script>
        function check(checkbox) {
                var row = checkbox.parentNode.parentNode.parentNode;
            row.classList.toggle('checked');
        }
    </script>
</body>
</html>
