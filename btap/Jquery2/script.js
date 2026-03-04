const board = document.getElementById("board");

let size = 15;
let currentPlayer = "X";
let gameStarted = false;
let history = [];
let cells = [];

// Tạo bàn cờ
function createBoard() {
    board.innerHTML = "";
    cells = [];

    for (let row = 0; row < size; row++) {
        for (let col = 0; col < size; col++) {
            const cell = document.createElement("div");
            cell.classList.add("cell");

            cell.dataset.row = row;
            cell.dataset.col = col;

            cell.addEventListener("click", function () {
                if (!gameStarted) return;
                if (cell.innerText !== "") return;

                cell.innerText = currentPlayer;
                history.push(cell);

                if (checkWin(row, col)) {
                    alert(currentPlayer + " thắng!");
                    gameStarted = false;
                    return;
                }

                currentPlayer = currentPlayer === "X" ? "O" : "X";
            });

            board.appendChild(cell);
            cells.push(cell);
        }
    }
}

createBoard();

function showBoard() {
    board.style.display = "grid";
}

function hideBoard() {
    board.style.display = "none";
}

function startGame() {
    gameStarted = true;
    currentPlayer = "X";
    alert("Game bắt đầu!");
}

function clearBoard() {
    cells.forEach(cell => cell.innerText = "");
    history = [];
    currentPlayer = "X";
    gameStarted = false;
}

function undoMove() {
    if (history.length === 0) return;

    let lastCell = history.pop();
    lastCell.innerText = "";
    currentPlayer = currentPlayer === "X" ? "O" : "X";
}

// ====== KIỂM TRA THẮNG ======
function checkWin(row, col) {
    return (
        count(row, col, 0, 1) + count(row, col, 0, -1) > 3 ||   // ngang
        count(row, col, 1, 0) + count(row, col, -1, 0) > 3 ||   // dọc
        count(row, col, 1, 1) + count(row, col, -1, -1) > 3 ||  // chéo \
        count(row, col, 1, -1) + count(row, col, -1, 1) > 3     // chéo /
    );
}

function count(row, col, rowDir, colDir) {
    let r = row + rowDir;
    let c = col + colDir;
    let total = 0;

    while (
        r >= 0 &&
        r < size &&
        c >= 0 &&
        c < size &&
        getCell(r, c).innerText === currentPlayer
    ) {
        total++;
        r += rowDir;
        c += colDir;
    }

    return total;
}

function getCell(row, col) {
    return cells[row * size + col];
}