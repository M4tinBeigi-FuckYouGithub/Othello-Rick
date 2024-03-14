<?php

class Othello {

    private $board;
    private $turn;
    private $players;

    public function __construct() {
        $this->board = array_fill(0, 8, array_fill(0, 8, null));
        $this->turn = rand(0, 1) === 0 ? 'black' : 'white';
        $this->players = array(
            'black' => array(
                'color' => 'black',
                'pieces' => array(),
            ),
            'white' => array(
                'color' => 'white',
                'pieces' => array(),
            ),
        );
    }

    public function init() {
        $this->board[3][3] = 'black';
        $this->board[3][4] = 'white';
        $this->board[4][3] = 'white';
        $this->board[4][4] = 'black';

        $this->players['black']['pieces'][] = array(3, 3);
        $this->players['black']['pieces'][] = array(4, 4);
        $this->players['white']['pieces'][] = array(3, 4);
        $this->players['white']['pieces'][] = array(4, 3);
    }

    public function placePiece($x, $y) {
        if (!$this->isMoveValid($x, $y)) {
            return;
        }

        $this->board[$x][$y] = $this->turn;
        $this->players[$this->turn]['pieces'][] = array($x, $y);
        $this->flipPieces($x, $y);
        $this->turn = $this->turn === 'black' ? 'white' : 'black';
    }

    public function isMoveValid($x, $y) {
        if ($this->board[$x][$y] !== null) {
            return false;
        }

        return $this->isSurrounded($x, $y);
    }

    private function isSurrounded($x, $y) {
        // Placeholder for implementation
        return true;
    }

    private function flipPieces($x, $y) {
        for ($i = -1; $i <= 1; $i++) {
            for ($j = -1; $j <= 1; $j++) {
                if ($i === 0 && $j === 0) {
                    continue;
                }

                $nx = $x + $i;
                $ny = $y + $j;

                $flips = array();

                while ($nx >= 0 && $nx < 8 && $ny >= 0 && $ny < 8 && $this->board[$nx][$ny] === $this->turn) {
                    $flips[] = array($nx, $ny);

                    $nx += $i;
                    $ny += $j;
                }

                if ($nx >= 0 && $nx < 8 && $ny >= 0 && $ny < 8 && $this->board[$nx][$ny] !== null && $this->board[$nx][$ny] !== $this->turn) {
                    foreach ($flips as $flip) {
                        $this->board[$flip[0]][$flip[1]] = $this->turn;
                    }
                }
            }
        }
    }

    public function getWinner() {
        $blackCount = 0;
        $whiteCount = 0;

        for ($i = 0; $i < 8; $i++) {
            for ($j = 0; $j < 8; $j++) {
                if ($this->board[$i][$j] === 'black') {
                    $blackCount++;
                } elseif ($this->board[$i][$j] === 'white') {
                    $whiteCount++;
                }
            }
        }

        if ($blackCount > $whiteCount) {
            return 'black';
        } elseif ($whiteCount > $blackCount) {
            return 'white';
        } else {
            return 'draw';
        }
    }

    // Function to print the board
    public function printBoard() {
        for ($i = 0; $i < 8; $i++) {
            echo "|";
            for ($j = 0; $j < 8; $j++) {
                $piece = $this->board[$i][$j] === null ? '.' : $this->board[$i][$j];
                echo $piece . "|";
            }
            echo PHP_EOL;
        }
    }

    // Function to check for valid moves for a player
    public function hasValidMoves($player) {
        for ($i = 0; $i < 8; $i++) {
            for ($j = 0; $j < 8; $j++) {
                if ($this->isMoveValid($i, $j) && $this->board[$i][$j] === null) {
                    return true;
                }
            }
        }
        return false;
    }

    // Game loop
    public function playGame() {
        while (true) {
            // Display the board
            $this->printBoard();

            // Get user input for the next move
            $x = readline('Enter x: ');
            $y = readline('Enter y: ');

            if (!$this->isMoveValid($x, $y)) {
                echo 'Invalid move.' . PHP_EOL;
                continue;
            }

            // Place the piece
            $this->placePiece($x, $y);

            // Switch turns
            $this->turn = $this->turn === 'black' ? 'white' : 'black';

            // Check for stalemate
            if (!$this->hasValidMoves($this->turn)) {
                $winner = $this->getWinner();
                if ($winner === 'draw') {
                    echo "Stalemate! The game is drawn." . PHP_EOL;
                } else {
                    echo "Stalemate! The winner is $winner." . PHP_EOL;
                }
                break;
            }
        }
    }
}

$othello = new Othello();
$othello->init();
$othello->playGame();
