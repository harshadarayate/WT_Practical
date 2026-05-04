<?php
$storage = 'tictactoe.json';

// Initialize or Load Game State
if (!file_exists($storage)) {
    $state = ['board' => array_fill(0, 9, ""), 'turn' => 'X', 'winner' => null];
    file_put_contents($storage, json_encode($state));
} else {
    $state = json_decode(file_get_contents($storage), true);
}

// Handle Move
if (isset($_POST['cell']) && $state['winner'] == null) {
    $index = $_POST['cell'];
    if ($state['board'][$index] == "") {
        $state['board'][$index] = $state['turn'];
        
        // Check for Winner
        $winPatterns = [
            [0,1,2], [3,4,5], [6,7,8], // Rows
            [0,3,6], [1,4,7], [2,5,8], // Cols
            [0,4,8], [2,4,6]           // Diagonals
        ];

        foreach ($winPatterns as $p) {
            if ($state['board'][$p[0]] != "" && 
                $state['board'][$p[0]] == $state['board'][$p[1]] && 
                $state['board'][$p[0]] == $state['board'][$p[2]]) {
                $state['winner'] = $state['turn'];
            }
        }

        // Check for Draw
        if ($state['winner'] == null && !in_array("", $state['board'])) {
            $state['winner'] = "Draw";
        }

        // Switch Turn
        $state['turn'] = ($state['turn'] == 'X') ? 'O' : 'X';
        file_put_contents($storage, json_encode($state));
    }
}

// Handle Reset
if (isset($_POST['reset'])) {
    unlink($storage);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP Tic-Tac-Toe</title>
    <style>
        body { font-family: sans-serif; text-align: center; background: #f0f2f5; }
        .grid { display: grid; grid-template-columns: repeat(3, 100px); gap: 5px; justify-content: center; margin-top: 20px; }
        .cell { width: 100px; height: 100px; background: white; border: 2px solid #333; font-size: 40px; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .cell:disabled { cursor: default; color: #333; }
        .X { color: #e74c3c; }
        .O { color: #3498db; }
        .status { margin: 20px; font-size: 24px; font-weight: bold; }
        button.reset { margin-top: 20px; padding: 10px 20px; background: #333; color: white; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>

    <h1>Tic-Tac-Toe</h1>

    <div class="status">
        <?php 
            if ($state['winner']) {
                echo ($state['winner'] == "Draw") ? "It's a Draw!" : "Winner: " . $state['winner'] . " 🎉";
            } else {
                echo "Player Turn: " . $state['turn'];
            }
        ?>
    </div>

    <div class="grid">
        <?php foreach ($state['board'] as $index => $value): ?>
            <form method="POST" style="margin:0;">
                <input type="hidden" name="cell" value="<?php echo $index; ?>">
                <button type="submit" class="cell <?php echo $value; ?>" <?php echo ($value != "" || $state['winner']) ? 'disabled' : ''; ?>>
                    <?php echo $value; ?>
                </button>
            </form>
        <?php endforeach; ?>
    </div>

    <form method="POST">
        <button type="submit" name="reset" class="reset">Restart Game</button>
    </form>

</body>
</html>

<!-- 
Logic & Important Functions
State Array
We use an associative array to store the three things that matter:

board: The current 9 squares.

turn: Who is moving next.

winner: Holds "X", "O", "Draw", or null.

Win Pattern Check
We define $winPatterns as a 2D array. For every move, the PHP loop checks if the icons in those three specific indices match. For example, [0,4,8] checks the top-left to bottom-right diagonal.

Persistence with unlink()
When you click Restart Game, we use unlink($storage). This deletes the tictactoe.json file. On the next page load, the script sees the file is missing and creates a fresh, empty board.

-->