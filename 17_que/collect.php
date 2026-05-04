<?php
require 'vendor/autoload.php';

// 1. Database Connection
try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->WasteManagement->requests;
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}

// 2. Handle Form Submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['location'])) {
    $collection->insertOne([
        'waste_type' => $_POST['waste_type'],
        'location'   => $_POST['location'],
        'status'     => 'Pending',
        'timestamp'  => new MongoDB\BSON\UTCDateTime()
    ]);
    $message = "Request submitted successfully!";
}

// 3. Fetch all requests to display
$allRequests = $collection->find([], ['sort' => ['timestamp' => -1]]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Waste Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { margin-top: 50px; max-width: 900px; }
        .card { border: none; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .header-bg { background: #2c3e50; color: white; padding: 20px; border-radius: 8px 8px 0 0; }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="header-bg">
            <h2 class="mb-0">Waste Collection Portal</h2>
        </div>
        
        <div class="card-body p-4">
            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>

            <form method="POST" class="row g-3 mb-5">
                <div class="col-md-5">
                    <label class="form-label fw-bold">Waste Type</label>
                    <select name="waste_type" class="form-select">
                        <option value="Plastic">Plastic</option>
                        <option value="Paper">Paper</option>
                        <option value="Metal">Metal</option>
                        <option value="Organic">Organic</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-bold">Location</label>
                    <input type="text" name="location" class="form-control" placeholder="Enter address" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </div>
            </form>

            <hr>

            <h4 class="mt-4 mb-3">Recent Collection Requests</h4>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allRequests as $req): ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?php echo $req['waste_type']; ?></span></td>
                                <td><?php echo htmlspecialchars($req['location']); ?></td>
                                <td><span class="text-warning fw-bold"><?php echo $req['status']; ?></span></td>
                                <td>
                                    <?php 
                                        // Convert MongoDB UTCDateTime to readable string
                                        echo $req['timestamp']->toDateTime()->format('d M, H:i'); 
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>