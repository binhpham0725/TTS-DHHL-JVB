(function(){
    const storageKey = 'hluv-current-user';
    const logoSrc = '../images/hluv-logo.png'; // hãy đặt ảnh logo ở đường dẫn này

    function getCurrentUser(){
        try{ return JSON.parse(localStorage.getItem(storageKey)||'null'); } catch(e){ return null; }
    }
    function setCurrentUser(user){ localStorage.setItem(storageKey, JSON.stringify(user)); }
    function logout(){ localStorage.removeItem(storageKey); updateAuthUI(); window.location.href='index.html'; }

    function applyBrand(){
        const brand = document.querySelector('.site-nav .brand');
        if(!brand) return;
        brand.href = 'index.html';
        brand.innerHTML = `<img class="site-logo" src="${logoSrc}" alt="Logo ĐH Hoa Lư" onerror="this.style.display='none'" /><span> Tạp chí ĐH Hoa Lư</span>`;
        const style = document.createElement('style');
        style.innerHTML = `
            .site-logo{height:80px;width:80px;object-fit:contain;border-radius:50%;margin-right:.5rem;vertical-align:middle;cursor:pointer;}
            .site-nav .brand{display:flex;align-items:center;gap:.35rem;font-weight:800;text-decoration:none;cursor:pointer;}
            .auth-btn, .logout-btn{font-size:.85rem;padding:.4rem .75rem;border:1px solid transparent;border-radius:999px;background:rgba(29,78,216,.12);color:#0b5ed7;cursor:pointer;text-decoration:none;white-space:nowrap;}
            .auth-btn:hover, .logout-btn:hover{background:rgba(29,78,216,.2);}
            .user-area{display:flex;align-items:center;gap:.6rem;cursor:pointer;padding:.35rem .5rem;border-radius:8px;transition:.2s;}
            .user-area:hover{background:rgba(29,78,216,.08);}
            .user-area img{width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid #0b5ed7;}
            .user-name{font-size:.9rem;color:#1f2937;font-weight:600;}
            .actions{display:flex;align-items:center;gap:.4rem;}
            [data-theme="dark"] .user-name{color:#e2e8f0;}
        `;
        document.head.appendChild(style);
    }

    function updateAuthUI(){
        const siteNav = document.querySelector('.site-nav');
        if(!siteNav) return;
        let actions = siteNav.querySelector('.actions');
        if(!actions){ actions = document.createElement('div'); actions.className = 'actions'; siteNav.appendChild(actions); }
        let authContainer = actions.querySelector('.auth-area');
        if(!authContainer){ authContainer = document.createElement('div'); authContainer.className = 'auth-area'; actions.appendChild(authContainer); }
        authContainer.innerHTML = '';

        const user = getCurrentUser();
        if(user){
            const avatar = document.createElement('div');
            avatar.className = 'user-area';
            avatar.onclick = () => { window.location.href = 'profile.html'; };
            const img = document.createElement('img');
            img.src = user.avatar || `https://i.pravatar.cc/128?u=${encodeURIComponent(user.email||user.name||'guest')}`;
            img.onerror = () => { img.src = `https://i.pravatar.cc/128?u=${encodeURIComponent(user.email||user.name||'guest')}`; };
            const name = document.createElement('span');
            name.className = 'user-name';
            name.textContent = user.name || user.email || 'Người dùng';
            avatar.appendChild(img);
            avatar.appendChild(name);
            authContainer.appendChild(avatar);

            const loginNavLink = document.querySelector('.nav-links a[href="login.html"]');
            if(loginNavLink) loginNavLink.style.display = 'none';
        } else {
            const loginBtn = document.createElement('a');
            loginBtn.href = 'login.html';
            loginBtn.className = 'auth-btn';
            loginBtn.textContent = 'Đăng nhập';
            authContainer.appendChild(loginBtn);
            const loginNavLink = document.querySelector('.nav-links a[href="login.html"]');
            if(loginNavLink) loginNavLink.style.display = '';
        }
    }

    function init(){
        applyBrand();
        updateAuthUI();
    }

    if(document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
