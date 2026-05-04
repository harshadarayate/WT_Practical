<?php
require 'vendor/autoload.php';

// 1. Database Connection
try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $db = $client->ComplaintSystem;
    $collection = $db->user_complaints;
} catch (Exception $e) {
    die("Database Connection Error: " . $e->getMessage());
}

// 2. Handle Submission
$alertMessage = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_complaint'])) {
    $insertResult = $collection->insertOne([
        'organization' => $_POST['org_name'],
        'service'      => $_POST['service_type'],
        'description'  => $_POST['complaint_text'],
        'status'       => 'Registered',
        'created_at'   => new MongoDB\BSON\UTCDateTime()
    ]);
    
    if ($insertResult->getInsertedCount() === 1) {
        $alertMessage = "Complaint filed successfully with ID: " . $insertResult->getInsertedId();
    }
}

// 3. Retrieve Complaints
$complaints = $collection->find([], ['sort' => ['created_at' => -1]]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Institutional Complaint Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .main-card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .sidebar-header { background: #e67e22; color: white; border-radius: 15px 15px 0 0; padding: 20px; }
        .badge-status { background-color: #3498db; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card main-card">
                <div class="sidebar-header">
                    <h4 class="mb-0">File a Complaint</h4>
                </div>
                <div class="card-body">
                    <?php if ($alertMessage): ?>
                        <div class="alert alert-success py-2 small"><?php echo $alertMessage; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Organization</label>
                            <select name="org_name" class="form-select" required>
                                <option value="PMC">PMC (Pune Municipal Corp)</option>
                                <option value="PMT">PMT (PMPML Transport)</option>
                                <option value="MSEB">MSEB (Electricity Board)</option>
                                <option value="Other">Other Institution</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Service Category</label>
                            <input type="text" name="service_type" class="form-control" placeholder="e.g. Water, Roads, Bus Delay" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Detailed Description</label>
                            <textarea name="complaint_text" class="form-control" rows="4" required></textarea>
                        </div>
                        <button type="submit" name="submit_complaint" class="btn btn-warning w-100 fw-bold">Submit Complaint</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card main-card">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0 text-dark">Recent Complaints</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Organization</th>
                                    <th>Service</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($complaints as $c): ?>
                                <tr>
                                    <td><strong class="text-primary"><?php echo $c['organization']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($c['service']); ?></td>
                                    <td><small class="text-muted"><?php echo htmlspecialchars($c['description']); ?></small></td>
                                    <td><span class="badge badge-status"><?php echo $c['status']; ?></span></td>
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