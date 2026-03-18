$(document).ready(function () {
    const rows = 10;
    const cols = 10;

    let currentPlayer = "X";
    let isPlaying = false;
    let moveHistory = [];
    let usedCells = {}; // lưu các ô đã từng đánh

    function createBoard() {
        $("#board").empty();

        for (let r = 0; r < rows; r++) {
        for (let c = 0; c < cols; c++) {
            $("#board").append(`<div class="cell" data-row="${r}" data-col="${c}"></div>`);
        }
        }
    }

    function resetBoard() {
        $(".cell").text("").removeClass("x o");
        moveHistory = [];
        usedCells = {};
        currentPlayer = "X";
    }

    function updateStatus(text) {
        $("#status").text(text);
    }

    createBoard();

    $("#showBoard").click(function () {
        $("#boardWrapper").show();
    });

    $("#hideBoard").click(function () {
        $("#boardWrapper").hide();
    });

    $("#startGame").click(function () {
        resetBoard();
        isPlaying = true;
        updateStatus("Game bắt đầu - Lượt của X");
    });

    $("#clearBoard").click(function () {
        resetBoard();
        isPlaying = false;
        updateStatus("Đã clear bàn cờ - Bấm Start để chơi lại");
    });

    $("#undoMove").click(function () {
        if (moveHistory.length === 0) {
        updateStatus("Không có nước đi nào để undo");
        return;
        }

        const lastMove = moveHistory.pop();
        const key = `${lastMove.row}-${lastMove.col}`;
        const cell = $(`.cell[data-row='${lastMove.row}'][data-col='${lastMove.col}']`);

        cell.text("").removeClass("x o");

        // vẫn giữ usedCells[key] = true
        // nên ô này dù đã undo vẫn không đánh lại được

        currentPlayer = lastMove.player;
        isPlaying = true;
        updateStatus("Đã undo - Lượt của " + currentPlayer);
    });

    $(document).on("click", ".cell", function () {
        if (!isPlaying) {
        updateStatus("Bạn phải bấm Start trước khi chơi");
        return;
        }

        const row = $(this).data("row");
        const col = $(this).data("col");
        const key = `${row}-${col}`;

        if (usedCells[key]) {
        updateStatus("Ô này đã từng được đánh, không thể đánh lại");
        return;
        }

        $(this).text(currentPlayer);
        $(this).addClass(currentPlayer.toLowerCase());

        usedCells[key] = true;

        moveHistory.push({
        row: row,
        col: col,
        player: currentPlayer
        });

        currentPlayer = currentPlayer === "X" ? "O" : "X";
        updateStatus("Lượt của " + currentPlayer);
    });
});