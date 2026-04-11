<?php
$isAcademicView = $view === 'academic';
$rowNumber = (($pageData['page'] - 1) * $pageData['limit']) + 1;
?>
<div class="table-wrapper">
    <table>
        <thead>
            <?php if ($isAcademicView) { ?>
                <tr>
                    <th>STT</th>
                    <th onclick="sortTable('ho_ten')">Họ tên</th>
                    <th onclick="sortTable('chuyen_nganh')">Chuyên ngành</th>
                    <th onclick="sortTable('khoa_hoc')">Khóa học</th>
                    <th onclick="sortTable('gpa')">GPA</th>
                    <th onclick="sortTable('tinh_trang')">Tình trạng</th>
                    <th onclick="sortTable('xep_loai')">Xếp loại</th>
                    <th class="action-col">Thao tác</th>
                </tr>
            <?php } else { ?>
                <tr>
                    <th>STT</th>
                    <th onclick="sortTable('ho_ten')">Họ tên</th>
                    <th onclick="sortTable('gioi_tinh')">Giới tính</th>
                    <th onclick="sortTable('ngay_sinh')">Ngày sinh</th>
                    <th onclick="sortTable('email')">Email</th>
                    <th onclick="sortTable('dia_chi')">Địa chỉ</th>
                    <th class="action-col">Thao tác</th>
                </tr>
            <?php } ?>
        </thead>
        <tbody id="tableBody">
            <?php foreach ($pageData['students'] as $student) { ?>
                <?php if ($isAcademicView) { ?>
                    <tr data-id="<?= escapeValue($student['id']) ?>" data-view="academic">
                        <td><?= $rowNumber ?></td>
                        <td><?= escapeValue($student['ho_ten']) ?></td>
                        <td data-field="chuyen_nganh" data-value="<?= escapeValue($student['chuyen_nganh']) ?>"><?= escapeValue($student['chuyen_nganh']) ?></td>
                        <td><?= escapeValue($student['khoa_hoc']) ?></td>
                        <td><?= escapeValue($student['gpa']) ?></td>
                        <td data-field="tinh_trang" data-value="<?= escapeValue($student['tinh_trang']) ?>"><?= escapeValue($student['tinh_trang']) ?></td>
                        <td data-field="xep_loai" data-value="<?= escapeValue($student['xep_loai']) ?>"><?= escapeValue($student['xep_loai']) ?></td>
                        <td class="action-cell">
                            <button type="button" onclick="startInlineEdit(this)">Sửa nhanh</button>
                            <button type="button" onclick="confirmEdit(<?= (int) $student['id'] ?>)">Sửa</button>
                            <button type="button" onclick="confirmDelete(<?= (int) $student['id'] ?>)">Xóa</button>
                            <input type="checkbox" class="row-check" value="<?= escapeValue($student['id']) ?>" onchange="toggleBulkDeleteButton()">
                        </td>
                    </tr>
                <?php } else { ?>
                    <?php $dateOfBirth = $student['ngay_sinh'] ? date('d/m/Y', strtotime($student['ngay_sinh'])) : ''; ?>
                    <tr data-id="<?= escapeValue($student['id']) ?>" data-view="personal">
                        <td><?= $rowNumber ?></td>
                        <td><?= escapeValue($student['ho_ten']) ?></td>
                        <td><?= escapeValue($student['gioi_tinh']) ?></td>
                        <td><?= escapeValue($dateOfBirth) ?></td>
                        <td data-field="email" data-value="<?= escapeValue($student['email']) ?>"><?= escapeValue($student['email']) ?></td>
                        <td data-field="dia_chi" data-value="<?= escapeValue($student['dia_chi']) ?>"><?= escapeValue($student['dia_chi']) ?></td>
                        <td class="action-cell">
                            <button type="button" onclick="startInlineEdit(this)">Sửa nhanh</button>
                            <button type="button" onclick="confirmEdit(<?= (int) $student['id'] ?>)">Sửa</button>
                            <button type="button" onclick="confirmDelete(<?= (int) $student['id'] ?>)">Xóa</button>
                            <input type="checkbox" class="row-check" value="<?= escapeValue($student['id']) ?>" onchange="toggleBulkDeleteButton()">
                        </td>
                    </tr>
                <?php } ?>
                <?php $rowNumber++; ?>
            <?php } ?>
        </tbody>
    </table>
</div>
