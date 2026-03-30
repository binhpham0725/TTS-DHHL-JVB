let deleteId = null;
let editId = null;
let actionLocked = false;

/* toast */
function showToast(toastId, barId, duration) {
    let toast = document.getElementById(toastId);
    let bar = document.getElementById(barId);

    toast.style.display = "block";

    bar.style.animation = "none";
    bar.offsetHeight; // force reflow
    bar.style.animation = "progress " + duration + "ms linear forwards";
}

/* form */
function openForm() {
    if (actionLocked) return;
    document.getElementById("formOverlay").style.display = "flex";
}

function closeForm() {
    if (actionLocked) return;
    document.getElementById("formOverlay").style.display = "none";
}

function switchTab(tab) {
    document.querySelectorAll(".tab-content").forEach(t => t.classList.remove("active"));
    document.querySelectorAll(".tab").forEach(t => t.classList.remove("active"));

    document.getElementById("tab" + tab).classList.add("active");
    document.querySelectorAll(".tab")[tab - 1].classList.add("active");
}

/* bulk delete button */
function toggleBulkDeleteButton() {
    const checked = document.querySelectorAll(".row-check:checked");
    const bulkBtn = document.getElementById("bulkDeleteBtn");

    if (!bulkBtn) return;

    bulkBtn.style.display = checked.length >= 2 ? "inline-block" : "none";
}

function confirmBulkDelete() {
    if (actionLocked) return;

    const checked = document.querySelectorAll(".row-check:checked");
    if (checked.length < 2) return;

    document.getElementById("bulkDeleteOverlay").style.display = "flex";
}

/* register */
document.addEventListener("DOMContentLoaded", function () {
    let form = document.getElementById("studentForm");
    let searchInput = document.getElementById("search");

    /* restore search state */
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has("search")) {
        searchInput.value = urlParams.get("search");
        liveSearch();
    }

    if (form) {
        form.onsubmit = function (e) {
            if (actionLocked) return;

            e.preventDefault();
            actionLocked = true;

            let formData = new FormData(this);

            fetch("../login/register.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.text())
            .then(data => {
                if (data === "success") {
                    showToast("registerToast", "registerBar", 2000);
                    setTimeout(() => location.reload(), 2000);
                } else {
                    actionLocked = false;
                    document.getElementById("loadingText").innerText = "❌ Lỗi";
                    showToast("loadingToast", "loadingBar", 2000);
                }
            });
        };
    }

    /* delete buttons */
    let confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
    let cancelDeleteBtn = document.getElementById("cancelDeleteBtn");

    if (confirmDeleteBtn) {
        confirmDeleteBtn.onclick = function () {
            if (actionLocked) return;
            actionLocked = true;

            document.getElementById("deleteOverlay").style.display = "none";

            let formData = new FormData();
            formData.append("id", deleteId);

            fetch("../database/delete.php", { method: "POST", body: formData })
            .then(() => {
                showToast("deleteToast", "deleteBar", 2000);
                setTimeout(() => location.reload(), 2000);
            });
        };
    }

    if (cancelDeleteBtn) {
        cancelDeleteBtn.onclick = function () {
            document.getElementById("deleteOverlay").style.display = "none";
        };
    }

    /* bulk delete buttons */
    let confirmBulkDeleteBtn = document.getElementById("confirmBulkDeleteBtn");
    let cancelBulkDeleteBtn = document.getElementById("cancelBulkDeleteBtn");

    if (confirmBulkDeleteBtn) {
        confirmBulkDeleteBtn.onclick = function () {
            if (actionLocked) return;
            actionLocked = true;

            document.getElementById("bulkDeleteOverlay").style.display = "none";

            const checked = document.querySelectorAll(".row-check:checked");
            const requests = [];

            checked.forEach(cb => {
                let formData = new FormData();
                formData.append("id", cb.value);

                requests.push(
                    fetch("../database/delete.php", {
                        method: "POST",
                        body: formData
                    })
                );
            });

            Promise.all(requests).then(() => {
                showToast("deleteToast", "deleteBar", 2000);
                setTimeout(() => location.reload(), 2000);
            });
        };
    }

    if (cancelBulkDeleteBtn) {
        cancelBulkDeleteBtn.onclick = function () {
            document.getElementById("bulkDeleteOverlay").style.display = "none";
        };
    }

    /* edit buttons */
    let confirmEditBtn = document.getElementById("confirmEditBtn");
    let cancelEditBtn = document.getElementById("cancelEditBtn");

    if (confirmEditBtn) {
        confirmEditBtn.onclick = function () {
            if (actionLocked) return;
            actionLocked = true;

            document.getElementById("editOverlay").style.display = "none";
            showToast("loadingToast", "loadingBar", 1000);

            setTimeout(() => {
                window.location = "../edit/edit.php?id=" + editId;
            }, 1000);
        };
    }

    if (cancelEditBtn) {
        cancelEditBtn.onclick = function () {
            document.getElementById("editOverlay").style.display = "none";
        };
    }

    /* search */
    searchInput.addEventListener("input", liveSearch);

    function liveSearch() {
        let input = searchInput.value.toLowerCase().trim();
        let rows = document.querySelectorAll("#tableBody tr");

        rows.forEach(row => {
            let text = row.textContent || "";
            text = text.toLowerCase().trim();
            row.style.display = text.includes(input) ? "" : "none";
        });

        /* update URL param without reload */
        const url = new URL(window.location.href);
        if (input) url.searchParams.set("search", input);
        else url.searchParams.delete("search");
        window.history.replaceState({}, "", url);
    }

    /* delete / edit confirmation */
    window.confirmDelete = function (id) {
        if (actionLocked) return;
        deleteId = id;
        document.getElementById("deleteOverlay").style.display = "flex";
    };

    window.confirmEdit = function (id) {
        if (actionLocked) return;
        editId = id;
        document.getElementById("editOverlay").style.display = "flex";
    };

    toggleBulkDeleteButton();
});

/* pagination */
function goPage(page) {
    if (actionLocked) return;
    actionLocked = true;

    document.getElementById("pageText").innerText = "⏳ Đang lấy dữ liệu trang " + page + "...";
    showToast("pageToast", "pageBar", 1200);

    setTimeout(() => {
        let url = new URL(window.location.href);
        url.searchParams.set("page", page);

        /* preserve search param */
        let searchVal = document.getElementById("search").value.trim();
        if (searchVal) url.searchParams.set("search", searchVal);
        else url.searchParams.delete("search");

        window.location = url;
    }, 1200);
}

/* logout */
function logout() {
    if (actionLocked) return;
    actionLocked = true;

    showToast("logoutToast", "logoutBar", 2000);

    setTimeout(() => {
        window.location = "../login/login.html";
    }, 2500);
}

/* sort */
function sortTable(col) {
    if (actionLocked) return;

    let url = new URL(window.location.href);
    let currentSort = url.searchParams.get("sort");
    let currentOrder = url.searchParams.get("order") || "asc";
    let order = (currentSort === col && currentOrder === "asc") ? "desc" : "asc";

    url.searchParams.set("sort", col);
    url.searchParams.set("order", order);

    /* preserve search param */
    let searchVal = document.getElementById("search").value.trim();
    if (searchVal) url.searchParams.set("search", searchVal);
    else url.searchParams.delete("search");

    window.location = url;
}

/* view */
function changeView() {
    if (actionLocked) return;

    let mode = document.getElementById("viewMode").value;
    let url = new URL(window.location.href);
    url.searchParams.set("view", mode);

    /* reset sort when switching view */
    url.searchParams.delete("sort");
    url.searchParams.delete("order");

    /* preserve search param */
    let searchVal = document.getElementById("search").value.trim();
    if (searchVal) url.searchParams.set("search", searchVal);
    else url.searchParams.delete("search");

    window.location = url;
}

// live student count
function updateStudentCount() {
    fetch("../database/count.php")
    .then(res => res.text())
    .then(count => {
        const span = document.getElementById("studentCount");
        if (span) span.textContent = count;
    });
}

// initial load
updateStudentCount();

// optional: refresh every 5 seconds
setInterval(updateStudentCount, 5000);