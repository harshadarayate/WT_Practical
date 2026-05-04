<?php
require 'vendor/autoload.php';

// 1. Database Connection
try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->StudentDB->students;
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

// 2. Handle Insert Logic
if (isset($_POST['add_student'])) {
    $collection->insertOne([
        'name'   => $_POST['name'],
        'roll'   => $_POST['roll'],
        'branch' => $_POST['branch']
    ]);
    header("Location: index.php"); // Refresh to show new data
    exit();
}

// 3. Handle Delete Logic
if (isset($_GET['delete'])) {
    $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($_GET['delete'])]);
    header("Location: index.php");
    exit();
}

// 4. Fetch All Students
$students = $collection->find([], ['sort' => ['roll' => 1]]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card { border-radius: 12px; }
        .btn-custom { border-radius: 8px; }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow border-0">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0">Add New Student</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter Name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Roll Number</label>
                            <input type="text" name="roll" class="form-control" placeholder="e.g. 3101" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Branch</label>
                            <select name="branch" class="form-select">
                                <option value="Computer">Computer</option>
                                <option value="IT">IT</option>
                                <option value="ENTC">ENTC</option>
                                <option value="Mechanical">Mechanical</option>
                            </select>
                        </div>
                        <button type="submit" name="add_student" class="btn btn-success w-100 btn-custom">Save Student</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">Registered Student Records</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Roll No</th>
                                    <th>Branch</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $s): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($s['name']) ?></strong></td>
                                    <td><?= htmlspecialchars($s['roll']) ?></td>
                                    <td><span class="badge bg-info text-dark"><?= $s['branch'] ?></span></td>
                                    <td class="text-center">
                                        <a href="edit.php?id=<?= $s['_id'] ?>" class="btn btn-sm btn-warning btn-custom">Edit</a>
                                        <a href="index.php?delete=<?= $s['_id'] ?>" 
                                           class="btn btn-sm btn-danger btn-custom" 
                                           onclick="return confirm('Delete this record permanently?')">Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>