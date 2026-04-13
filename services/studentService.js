/* service gọi api cho các thao tác sinh viên */
function createStudent(formData) {
    return fetch(window.studentPageConfig.createApi, {
        method: "POST",
        body: formData
    }).then(res => res.text());
}

function deleteStudentById(studentId) {
    return fetch(window.studentPageConfig.deleteApi, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "id=" + studentId
    }).then(res => res.text());
}

function updateInlineStudent(payload) {
    return fetch(window.studentPageConfig.inlineUpdateApi, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
            "X-Requested-With": "XMLHttpRequest"
        },
        body: new URLSearchParams(payload).toString()
    }).then(res => res.json());
}

function getStudentCount() {
    return fetch(window.studentPageConfig.countApi).then(res => res.text());
}
