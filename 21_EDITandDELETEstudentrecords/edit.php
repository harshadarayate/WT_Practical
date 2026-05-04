<?php
require 'vendor/autoload.php';
$collection = (new MongoDB\Client("mongodb://localhost:27017"))->StudentDB->students;
$id = new MongoDB\BSON\ObjectId($_GET['id']);
$student = $collection->findOne(['_id' => $id]);

if (isset($_POST['update'])) {
    $collection->updateOne(
        ['_id' => $id],
        ['$set' => [
            'name' => $_POST['name'],
            'roll' => $_POST['roll'],
            'branch' => $_POST['branch']
        ]]
    );
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="col-md-6 mx-auto">
        <div class="card shadow">
            <div class="card-header bg-warning">Update Student Details</div>
            <form method="POST" class="card-body">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" value="<?= $student['name'] ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Roll No</label>
                    <input type="text" name="roll" value="<?= $student['roll'] ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Branch</label>
                    <input type="text" name="branch" value="<?= $student['branch'] ?>" class="form-control" required>
                </div>
                <button type="submit" name="update" class="btn btn-success">Save Changes</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>