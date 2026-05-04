<?php
require 'vendor/autoload.php';

// 1. Connection
$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->FlightSystem->seats;

// 2. Handle Booking (This MUST come before fetching seats)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_seat'])) {
    $seatToBook = $_POST['book_seat'];
    
    // Update the specific seat status
    $collection->updateOne(
        ['seat_no' => $seatToBook, 'status' => 'Available'],
        ['$set' => ['status' => 'Booked']]
    );
    
    // Optional: Add a small delay or redirect to refresh state
    header("Location: airplane.php"); 
    exit();
}

// 3. Fetch fresh data from MongoDB
$seats = $collection->find([], ['sort' => ['seat_no' => 1]]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Airplane Seat Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .airplane-cabin {
            background: #f0f0f0;
            padding: 30px;
            border-radius: 50px 50px 10px 10px;
            max-width: 400px;
            margin: auto;
            border: 2px solid #ccc;
        }
        .seat {
            width: 50px;
            height: 50px;
            margin: 5px;
            border-radius: 5px;
            display: inline-block;
            text-align: center;
            line-height: 50px;
            font-weight: bold;
            cursor: pointer;
        }
        .available { background-color: #2ecc71; color: white; border: none; }
        .booked { background-color: #e74c3c; color: white; cursor: not-allowed; }
        .aisle { margin-right: 30px; } /* Creates a gap for the aisle */
    </style>
</head>
<body class="bg-light">

<div class="container py-5 text-center">
    <h2 class="mb-4">Flight 101: Seating Arrangement</h2>
    
    <div class="airplane-cabin bg-white shadow">
        <div class="mb-4 text-muted small">FRONT OF PLANE</div>
        
        <form method="POST">
            <?php 
            $count = 0;
            foreach ($seats as $seat): 
                $count++;
                $isBooked = ($seat['status'] === 'Booked');
                $aisleClass = ($count % 5 == 2) ? 'aisle' : ''; // Aisle after 2nd column
            ?>
                
                <button type="submit" name="book_seat" value="<?php echo $seat['seat_no']; ?>" 
                        class="seat <?php echo $isBooked ? 'booked' : 'available'; ?> <?php echo $aisleClass; ?>"
                        <?php echo $isBooked ? 'disabled' : ''; ?>>
                    <?php echo $seat['seat_no']; ?>
                </button>

                <?php if ($count % 5 == 0) echo "<br>"; // Next row after 5 seats ?>
            
            <?php endforeach; ?>
        </form>
        
        <div class="mt-4 pt-3 border-top">
            <span class="badge bg-success">Available</span>
            <span class="badge bg-danger">Booked</span>
        </div>
    </div>

    <div class="mt-4">
        <a href="airplane.php" class="btn btn-secondary btn-sm">Refresh Cabin Status</a>
    </div>
</div>

</body>
</html>