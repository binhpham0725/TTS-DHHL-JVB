        const API = '../api';
        const currentKey='hluv-current-user';
        const setCurrent = user => localStorage.setItem(currentKey, JSON.stringify(user));
        const getCurrent = () => JSON.parse(localStorage.getItem(currentKey) || 'null');

        const tabLogin = document.getElementById('tab-login');
        const tabRegister = document.getElementById('tab-register');
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');

        tabLogin.onclick = () => { tabLogin.classList.add('active'); tabRegister.classList.remove('active'); loginForm.style.display='block'; registerForm.style.display='none'; };
        tabRegister.onclick = () => { tabRegister.classList.add('active'); tabLogin.classList.remove('active'); registerForm.style.display='block'; loginForm.style.display='none'; };

        loginForm.onsubmit = async (e) => {
            e.preventDefault();
            const email = document.getElementById('email-login').value.trim();
            const password = document.getElementById('password-login').value.trim();
            if (!email || !password) return alert('Email và mật khẩu bắt buộc');

            const res = await fetch(`${API}/users.php?action=login`, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({email, password})
            });
            const data = await res.json();
            if (!res.ok) return alert(data.error || 'Đăng nhập thất bại');
            setCurrent(data.user);
            location.href = 'profile.html';
        };

        registerForm.onsubmit = async (e) => {
            e.preventDefault();
            const name = document.getElementById('name-register').value.trim();
            const email = document.getElementById('email-register').value.trim();
            const password = document.getElementById('password-register').value.trim();
            const confirm = document.getElementById('confirm-password-register').value.trim();
            if (!name || !email || !password) return alert('Điền đầy đủ thông tin');
            if (password !== confirm) return alert('Mật khẩu không khớp');

            const res = await fetch(`${API}/users.php?action=register`, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({name,email,password,avatar: `https://i.pravatar.cc/120?u=${encodeURIComponent(email)}`})
            });
            const data = await res.json();
            if (!res.ok) return alert(data.error || 'Đăng ký thất bại');
            // Tự động đăng nhập sau khi đăng ký
            const loginRes = await fetch(`${API}/users.php?action=login`, {
                method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({email,password})
            });
            const loginData = await loginRes.json();
            if (loginRes.ok) {
                setCurrent(loginData.user);
                location.href = 'profile.html';
            } else {
                alert('Đăng ký thành công, nhưng đăng nhập tự động không thành công');
            }
        };

        const current = getCurrent();
        if (current) { location.href = 'profile.html'; }
        document.documentElement.setAttribute('data-theme', localStorage.getItem('hluv-theme') || 'light');