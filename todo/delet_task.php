<?php
require("database.php");
$id=$_GET["id"];
$query=$conn->prepare("DELETE FROM tasks where id=?");
$query->execute([$id]);
header("location:index.php");