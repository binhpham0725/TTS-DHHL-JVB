document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll(".score-input");

    inputs.forEach((input) => {
        input.addEventListener("input", function () {
            let value = parseFloat(this.value);
            if (isNaN(value)) return;
            if (value < 0) this.value = 0;
            if (value > 10) this.value = 10;
        });
    });
});