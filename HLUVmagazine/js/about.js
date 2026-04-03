        document.documentElement.setAttribute('data-theme',localStorage.getItem('hluv-theme')||'light');
        const menuToggle=document.getElementById('menu-toggle');
        const navLinks=document.getElementById('nav-links');
        menuToggle.addEventListener('click',()=>navLinks.classList.toggle('open'));