function openModal(name, code, credits, desc, att, mid, finalScore) {
    const texts = (window.APP_TEXTS && window.APP_TEXTS.subjects) || {};
    document.getElementById('m_name').textContent = name;
    document.getElementById('m_code').textContent = code || texts.no_value || 'Chưa có';
    document.getElementById('m_credits').textContent = credits;
    document.getElementById('m_desc').textContent = desc || texts.no_description || 'Chưa có mô tả';

    document.getElementById('m_att').textContent = att + '%';
    document.getElementById('m_mid').textContent = mid + '%';
    document.getElementById('m_final').textContent = finalScore + '%';

    document.getElementById('bar_att').style.width = att + '%';
    document.getElementById('bar_mid').style.width = mid + '%';
    document.getElementById('bar_final').style.width = finalScore + '%';

    document.getElementById('subjectModal').classList.add('show');
}

function closeModal() {
    document.getElementById('subjectModal').classList.remove('show');
}

window.onclick = function (event) {
    const modal = document.getElementById('subjectModal');
    if (event.target === modal) {
        closeModal();
    }
};
