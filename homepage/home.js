/* global state quản lý lock hành động và toast timing tránh spam interaction */
let actionLocked = false;
let lastToastTime = 0;
const TOAST_COOLDOWN = 1500;
const TOAST_DURATION = 1500;

let deleteId = null;
let editId = null;
let searchTimer = null;

const INLINE_FIELDS = {
    personal: ["email", "dia_chi"],
    academic: ["chuyen_nganh", "tinh_trang", "xep_loai"]
};

const INLINE_SELECT_OPTIONS = {
    tinh_trang: ["Năm 1", "Năm 2", "Năm 3", "Năm 4", "Đã tốt nghiệp", "Khác"],
    xep_loai: ["Xuất sắc", "Giỏi", "Khá", "Trung bình", "Yếu"]
};

/* toast hiển thị message trạng thái với progress bar và anti spam */
function showToast(id, barId, duration = TOAST_DURATION, force = false) {
    let now = Date.now();
    if (!force && now - lastToastTime < TOAST_COOLDOWN) return;

    lastToastTime = now;

    let toast = document.getElementById(id);
    let bar = document.getElementById(barId);

    if (!toast || !bar) return;

    toast.style.display = "block";

    bar.style.animation = "none";
    bar.offsetHeight;
    bar.style.animation = "progress " + duration + "ms linear forwards";

    clearTimeout(toast._hideTimer);
    toast._hideTimer = setTimeout(() => {
        toast.style.display = "none";
    }, duration);
}

/* helper escape html an toàn khi render inline edit */
function escapeHtml(value) {
    return String(value ?? "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

/* helper set nội dung toast tải trang */
function setPageToastMessage(message) {
    const pageText = document.getElementById("pageText");
    if (pageText) pageText.textContent = message;
}

/* helper lấy search hiện tại */
function getSearchValue() {
    const input = document.getElementById("search");
    return input ? input.value.trim() : "";
}

/* helper update search param vào url */
function applySearchParam(url) {
    const searchVal = getSearchValue();
    if (searchVal) url.searchParams.set("search", searchVal);
    else url.searchParams.delete("search");
}

/* export csv giữ nguyên state hiện tại */
function exportCSV() {
    if (actionLocked) return;

    let url = new URL(window.location.href);
    applySearchParam(url);
    url.searchParams.set("export", "csv");
    window.location.href = url.toString();
}

/* helper build action cell mặc định */
function buildDefaultActionCell(id, hasInline) {
    let html = "";

    if (hasInline) {
        html += `<button type="button" onclick="startInlineEdit(this)">Sửa nhanh</button>`;
    }

    html += `<button onclick="confirmEdit(${id})">Sửa</button>`;
    html += `<button onclick="confirmDelete(${id})">Xóa</button>`;
    html += `<input type="checkbox" class="row-check" value="${id}" onchange="toggleBulkDeleteButton()">`;

    return html;
}

/* live search filter trực tiếp trên table hiện tại, không fetch không reload */
function liveSearch() {
    clearTimeout(searchTimer);

    searchTimer = setTimeout(() => {
        const searchInput = document.getElementById("search");
        if (!searchInput) return;

        let input = searchInput.value.toLowerCase().trim();
        let rows = document.querySelectorAll("#tableBody tr");

        rows.forEach(row => {
            let text = row.textContent || "";
            text = text.toLowerCase().trim();
            row.style.display = text.includes(input) ? "" : "none";
        });

        const url = new URL(window.location.href);
        if (input) url.searchParams.set("search", input);
        else url.searchParams.delete("search");

        window.history.replaceState({}, "", url.toString());
    }, 80);
}

/* logout action hiển thị toast rồi redirect về login page */
function logout() {
    if (actionLocked) return;
    actionLocked = true;

    showToast("logoutToast", "logoutBar", TOAST_DURATION, true);

    setTimeout(() => {
        window.location.href = "../login/login.html";
    }, TOAST_DURATION);
}

/* mở form overlay thêm sinh viên mới và reset dữ liệu form */
function openForm() {
    if (actionLocked) return;
    document.getElementById("formOverlay").style.display = "flex";
    document.getElementById("studentForm").reset();
}

/* đóng form overlay và clear trạng thái nhập liệu */
function closeForm() {
    if (actionLocked) return;
    document.getElementById("formOverlay").style.display = "none";
}

/* switch tab giữa thông tin cá nhân và học tập trong form */
function switchTab(tab) {
    document.querySelectorAll(".tab-content").forEach(t => t.classList.remove("active"));
    document.querySelectorAll(".tab").forEach(b => b.classList.remove("active"));

    document.getElementById("tab" + tab).classList.add("active");
    document.querySelectorAll(".tab")[tab - 1].classList.add("active");
}

/* pagination chuyển trang giữ nguyên state sort view search hiện tại */
function goPage(page) {
    if (actionLocked) return;

    actionLocked = true;
    setPageToastMessage("⏳ Đang chuyển trang...");
    showToast("pageToast", "pageBar", TOAST_DURATION, true);

    setTimeout(() => {
        let url = new URL(window.location.href);
        url.searchParams.set("page", page);
        applySearchParam(url);
        window.location.href = url.toString();
    }, TOAST_DURATION);
}

/* sort table theo column và toggle asc desc bằng URL params */
function sortTable(column) {
    if (actionLocked) return;

    actionLocked = true;
    setPageToastMessage("⏳ Đang sắp xếp dữ liệu...");
    showToast("pageToast", "pageBar", TOAST_DURATION, true);

    setTimeout(() => {
        let url = new URL(window.location.href);

        let currentSort = url.searchParams.get("sort");
        let currentOrder = url.searchParams.get("order");

        if (currentSort === column) {
            url.searchParams.set("order", currentOrder === "asc" ? "desc" : "asc");
        } else {
            url.searchParams.set("sort", column);
            url.searchParams.set("order", "asc");
        }

        applySearchParam(url);
        window.location.href = url.toString();
    }, TOAST_DURATION);
}

/* change view giữa personal và academic bằng query param */
function changeView() {
    if (actionLocked) return;

    actionLocked = true;

    let view = document.getElementById("viewMode").value;

    const text = view === "academic"
        ? "⏳ Đang chuyển sang thông tin học tập..."
        : "⏳ Đang chuyển sang thông tin cá nhân...";

    setPageToastMessage(text);
    showToast("pageToast", "pageBar", TOAST_DURATION, true);

    setTimeout(() => {
        let url = new URL(window.location.href);
        url.searchParams.set("view", view);
        applySearchParam(url);
        window.location.href = url.toString();
    }, TOAST_DURATION);
}

/* confirm delete một sinh viên với dialog overlay */
function confirmDelete(id) {
    if (actionLocked) return;

    deleteId = id;
    document.getElementById("deleteOverlay").style.display = "flex";
}

/* confirm edit chuyển sang trang edit với delay nhẹ UX */
function confirmEdit(id) {
    if (actionLocked) return;

    editId = id;
    document.getElementById("editOverlay").style.display = "flex";
}

/* bulk delete hiển thị button khi chọn nhiều checkbox */
function toggleBulkDeleteButton() {
    let checked = document.querySelectorAll(".row-check:checked");
    let btn = document.getElementById("bulkDeleteBtn");

    if (!btn) return;
    btn.style.display = checked.length >= 2 ? "inline-block" : "none";
}

/* mở dialog xác nhận bulk delete */
function confirmBulkDelete() {
    if (actionLocked) return;

    let checked = document.querySelectorAll(".row-check:checked");
    if (checked.length < 2) return;

    document.getElementById("bulkDeleteOverlay").style.display = "flex";
}

/* bắt đầu inline edit chỉ cho field nhẹ */
function startInlineEdit(button) {
    if (actionLocked) return;

    const row = button.closest("tr");
    if (!row || row.classList.contains("editing")) return;

    const view = row.dataset.view;
    const allowedFields = INLINE_FIELDS[view] || [];
    if (!allowedFields.length) return;

    row.classList.add("editing");

    allowedFields.forEach(field => {
        const cell = row.querySelector(`td[data-field="${field}"]`);
        if (!cell) return;

        const value = cell.dataset.value ?? cell.textContent.trim();
        cell.dataset.original = value;

        if (INLINE_SELECT_OPTIONS[field]) {
            let options = INLINE_SELECT_OPTIONS[field]
                .map(opt => `<option value="${escapeHtml(opt)}" ${opt === value ? "selected" : ""}>${escapeHtml(opt)}</option>`)
                .join("");

            cell.innerHTML = `<select class="inline-input" data-inline-field="${field}">${options}</select>`;
        } else {
            cell.innerHTML = `<input type="text" class="inline-input" data-inline-field="${field}" value="${escapeHtml(value)}">`;
        }
    });

    const actionCell = row.querySelector(".action-cell");
    if (actionCell) {
        actionCell.innerHTML = `
            <button type="button" class="save-inline-btn" onclick="saveInlineEdit(this)">Lưu</button>
            <button type="button" class="cancel-inline-btn" onclick="cancelInlineEdit(this)">Hủy</button>
            <input type="checkbox" class="row-check" value="${row.dataset.id}" onchange="toggleBulkDeleteButton()">
        `;
    }
}

/* hủy inline edit */
function cancelInlineEdit(button) {
    const row = button.closest("tr");
    if (!row) return;

    restoreInlineRow(row);
}

/* restore row sau khi hủy inline edit */
function restoreInlineRow(row) {
    const view = row.dataset.view;
    const allowedFields = INLINE_FIELDS[view] || [];

    allowedFields.forEach(field => {
        const cell = row.querySelector(`td[data-field="${field}"]`);
        if (!cell) return;

        const original = cell.dataset.original ?? cell.dataset.value ?? "";
        cell.dataset.value = original;
        cell.textContent = original;
    });

    row.classList.remove("editing");

    const actionCell = row.querySelector(".action-cell");
    if (actionCell) {
        actionCell.innerHTML = buildDefaultActionCell(row.dataset.id, true);
    }

    toggleBulkDeleteButton();
}

/* lưu inline edit qua ajax */
function saveInlineEdit(button) {
    const row = button.closest("tr");
    if (!row || actionLocked) return;

    const view = row.dataset.view;
    const id = row.dataset.id;
    const allowedFields = INLINE_FIELDS[view] || [];
    const payload = {};

    allowedFields.forEach(field => {
        const input = row.querySelector(`[data-inline-field="${field}"]`);
        if (input) payload[field] = input.value.trim();
    });

    actionLocked = true;

    fetch(window.location.href, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
            "X-Requested-With": "XMLHttpRequest"
        },
        body: new URLSearchParams({
            action: "inline_update",
            id: id,
            view: view,
            payload: JSON.stringify(payload)
        }).toString()
    })
        .then(res => res.json())
        .then(data => {
            actionLocked = false;

            if (!data || data.status !== "success") {
                showToast("inlineErrorToast", "inlineErrorBar", TOAST_DURATION, true);
                return;
            }

            allowedFields.forEach(field => {
                const cell = row.querySelector(`td[data-field="${field}"]`);
                if (!cell) return;

                const value = data.data[field] ?? "";
                cell.dataset.value = value;
                cell.textContent = value;
            });

            row.classList.remove("editing");

            const actionCell = row.querySelector(".action-cell");
            if (actionCell) {
                actionCell.innerHTML = buildDefaultActionCell(id, true);
            }

            showToast("inlineSuccessToast", "inlineSuccessBar", TOAST_DURATION, true);
            toggleBulkDeleteButton();
        })
        .catch(() => {
            actionLocked = false;
            showToast("inlineErrorToast", "inlineErrorBar", TOAST_DURATION, true);
        });
}

/* live student count fetch dữ liệu tổng số sinh viên định kỳ */
function updateStudentCount() {
    fetch("../database/count.php")
        .then(res => res.text())
        .then(count => {
            const span = document.getElementById("studentCount");
            if (span) span.textContent = count;
        });
}

document.addEventListener("DOMContentLoaded", function () {
    let form = document.getElementById("studentForm");
    let searchInput = document.getElementById("search");

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
                        closeForm();
                        showToast("registerToast", "registerBar", TOAST_DURATION, true);
                        setTimeout(() => location.reload(), TOAST_DURATION);
                    } else {
                        actionLocked = false;
                        document.getElementById("loadingText").textContent = "❌ Lỗi";
                        showToast("loadingToast", "loadingBar", TOAST_DURATION, true);
                    }
                })
                .catch(() => {
                    actionLocked = false;
                    document.getElementById("loadingText").textContent = "❌ Lỗi";
                    showToast("loadingToast", "loadingBar", TOAST_DURATION, true);
                });
        };
    }

    if (searchInput) {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has("search")) {
            searchInput.value = urlParams.get("search");
            liveSearch();
        }

        searchInput.addEventListener("input", liveSearch);
    }

    let cancelDeleteBtn = document.getElementById("cancelDeleteBtn");
    let confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
    let cancelBulkDeleteBtn = document.getElementById("cancelBulkDeleteBtn");
    let confirmBulkDeleteBtn = document.getElementById("confirmBulkDeleteBtn");
    let cancelEditBtn = document.getElementById("cancelEditBtn");
    let confirmEditBtn = document.getElementById("confirmEditBtn");

    if (cancelDeleteBtn) {
        cancelDeleteBtn.onclick = function () {
            document.getElementById("deleteOverlay").style.display = "none";
            deleteId = null;
        };
    }

    if (confirmDeleteBtn) {
        confirmDeleteBtn.onclick = function () {
            if (!deleteId || actionLocked) return;

            actionLocked = true;

            fetch("../database/delete.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "id=" + deleteId
            })
                .then(res => res.text())
                .then(() => {
                    document.getElementById("deleteOverlay").style.display = "none";
                    showToast("deleteToast", "deleteBar", TOAST_DURATION, true);
                    setTimeout(() => location.reload(), TOAST_DURATION);
                })
                .catch(() => {
                    actionLocked = false;
                });
        };
    }

    if (cancelBulkDeleteBtn) {
        cancelBulkDeleteBtn.onclick = function () {
            document.getElementById("bulkDeleteOverlay").style.display = "none";
        };
    }

    if (confirmBulkDeleteBtn) {
        confirmBulkDeleteBtn.onclick = function () {
            if (actionLocked) return;

            let checked = document.querySelectorAll(".row-check:checked");
            if (checked.length < 2) return;

            actionLocked = true;

            let requests = [];

            checked.forEach(cb => {
                requests.push(
                    fetch("../database/delete.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: "id=" + cb.value
                    })
                );
            });

            Promise.all(requests)
                .then(() => {
                    document.getElementById("bulkDeleteOverlay").style.display = "none";
                    showToast("deleteToast", "deleteBar", TOAST_DURATION, true);
                    setTimeout(() => location.reload(), TOAST_DURATION);
                })
                .catch(() => {
                    actionLocked = false;
                });
        };
    }

    if (cancelEditBtn) {
        cancelEditBtn.onclick = function () {
            document.getElementById("editOverlay").style.display = "none";
            editId = null;
        };
    }

    if (confirmEditBtn) {
        confirmEditBtn.onclick = function () {
            if (!editId || actionLocked) return;

            actionLocked = true;

            document.getElementById("editOverlay").style.display = "none";
            document.getElementById("loadingText").textContent = "⏳ Đang lấy thông tin sinh viên...";
            showToast("loadingToast", "loadingBar", TOAST_DURATION, true);

            setTimeout(() => {
                window.location.href = "../edit/edit.php?id=" + editId;
            }, TOAST_DURATION);
        };
    }

    toggleBulkDeleteButton();
});

/* initial load và auto refresh student count */
updateStudentCount();
setInterval(updateStudentCount, 5000);