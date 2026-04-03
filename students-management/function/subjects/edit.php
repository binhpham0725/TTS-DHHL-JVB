<?php
session_start();
require_once "../../config/db.php";

$errors = [];

$id = (int)($_GET["id"] ?? 0);
if ($id <= 0) {
    header("Location: ../../interface/subjects.php");
    exit;
}

$sql = "SELECT * FROM subject WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$subject = mysqli_fetch_assoc($result);

if (!$subject) {
    header("Location: ../../interface/subjects.php");
    exit;
}

$subject_code = $subject["subject_code"] ?? "";
$subject_name = $subject["subject_name"] ?? "";
$credits = (int)($subject["credits"] ?? 3);
$description = $subject["description"] ?? "";
$attendance_weight = (int)($subject["attendance_weight"] ?? 10);
$midterm_weight = (int)($subject["midterm_weight"] ?? 30);
$final_weight = (int)($subject["final_weight"] ?? 60);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $subject_code = trim($_POST["subject_code"] ?? "");
    $subject_name = trim($_POST["subject_name"] ?? "");
    $credits = (int)($_POST["credits"] ?? 3);
    $description = trim($_POST["description"] ?? "");
    $attendance_weight = (int)($_POST["attendance_weight"] ?? 10);
    $midterm_weight = (int)($_POST["midterm_weight"] ?? 30);
    $final_weight = (int)($_POST["final_weight"] ?? 60);

    if ($subject_code === "") {
        $errors[] = "Vui lòng nhập mã môn.";
    }

    if ($subject_name === "") {
        $errors[] = "Vui lòng nhập tên môn học.";
    }

    if ($credits <= 0) {
        $errors[] = "Số tín chỉ phải lớn hơn 0.";
    }

    if (($attendance_weight + $midterm_weight + $final_weight) !== 100) {
        $errors[] = "Tổng tỷ trọng điểm phải bằng 100%.";
    }

    $checkSql = "SELECT id FROM subject WHERE subject_code = ? AND id != ?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "si", $subject_code, $id);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);

    if (mysqli_num_rows($checkResult) > 0) {
        $errors[] = "Mã môn đã tồn tại.";
    }

    if (empty($errors)) {
        $updateSql = "UPDATE subject
                      SET subject_code = ?, subject_name = ?, credits = ?, description = ?,
                          attendance_weight = ?, midterm_weight = ?, final_weight = ?
                      WHERE id = ?";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        mysqli_stmt_bind_param(
            $updateStmt,
            "ssisiiii",
            $subject_code,
            $subject_name,
            $credits,
            $description,
            $attendance_weight,
            $midterm_weight,
            $final_weight,
            $id
        );

        if (mysqli_stmt_execute($updateStmt)) {
            header("Location: ../../interface/subjects.php?msg=edit_success");
            exit;
        } else {
            $errors[] = "Cập nhật thất bại: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa môn học</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: #f5f7fb;
            color: #1f2937;
        }
        .wrapper {
            max-width: 760px;
            margin: 40px auto;
            background: #fff;
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            border: 1px solid #e5e7eb;
        }
        h2 {
            margin: 0 0 8px;
            font-size: 28px;
            font-weight: 800;
        }
        .sub {
            color: #6b7280;
            margin-bottom: 24px;
        }
        .alert {
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 18px;
            font-size: 14px;
        }
        .alert.error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-group.full {
            grid-column: 1 / -1;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 14px;
        }
        input, textarea {
            width: 100%;
            border: 1px solid #dbe2ea;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 14px;
            outline: none;
        }
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        input:focus, textarea:focus {
            border-color: #6d84f7;
            box-shadow: 0 0 0 3px rgba(109, 132, 247, 0.12);
        }
        .actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 10px;
        }
        .btn {
            text-decoration: none;
            border: none;
            border-radius: 12px;
            padding: 12px 18px;
            font-weight: 700;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-back {
            background: #eef2f7;
            color: #374151;
        }
        .btn-save {
            background: linear-gradient(135deg, #7c9cfb, #6d84f7);
            color: #fff;
        }
        @media (max-width: 768px) {
            .wrapper { margin: 20px; padding: 20px; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sửa môn học</h2>
        <div class="sub">Cập nhật thông tin môn học</div>

        <?php if (!empty($errors)): ?>
            <div class="alert error">
                <?php foreach ($errors as $error): ?>
                    <div>- <?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label>Mã môn</label>
                    <input type="text" name="subject_code" value="<?= htmlspecialchars($subject_code) ?>" required>
                </div>

                <div class="form-group">
                    <label>Tên môn học</label>
                    <input type="text" name="subject_name" value="<?= htmlspecialchars($subject_name) ?>" required>
                </div>

                <div class="form-group">
                    <label>Số tín chỉ</label>
                    <input type="number" name="credits" min="1" value="<?= htmlspecialchars((string)$credits) ?>" required>
                </div>

                <div class="form-group full">
                    <label>Mô tả</label>
                    <textarea name="description"><?= htmlspecialchars($description) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Tỷ trọng chuyên cần (%)</label>
                    <input type="number" name="attendance_weight" min="0" max="100" value="<?= htmlspecialchars((string)$attendance_weight) ?>" required>
                </div>

                <div class="form-group">
                    <label>Tỷ trọng giữa kỳ (%)</label>
                    <input type="number" name="midterm_weight" min="0" max="100" value="<?= htmlspecialchars((string)$midterm_weight) ?>" required>
                </div>

                <div class="form-group">
                    <label>Tỷ trọng cuối kỳ (%)</label>
                    <input type="number" name="final_weight" min="0" max="100" value="<?= htmlspecialchars((string)$final_weight) ?>" required>
                </div>
            </div>

            <div class="actions">
                <a href="../../interface/subjects.php" class="btn btn-back">Quay lại</a>
                <button type="submit" class="btn btn-save">Cập nhật</button>
            </div>
        </form>
    </div>
</body>
</html>