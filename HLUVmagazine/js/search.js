        const defaultData=[
            {id:1,title:'AI & Công nghệ trong giáo dục đại học 2026',category:'Công nghệ',content:'Ứng dụng AI trong học tập.',image:'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80',created:'2026-03-30'},
            {id:2,title:'Kỹ năng mềm cần có để thành công',category:'Đời sống',content:'Tự tin, giao tiếp, tố chất lãnh đạo.',image:'https://images.unsplash.com/photo-1543269865-cbf427effbad?auto=format&fit=crop&w=800&q=80',created:'2026-03-28'},
            {id:3,title:'Lộ trình học tập môn AI năm cuối',category:'Học tập',content:'Điểm danh tài nguyên, khóa học, thực hành.',image:'https://images.unsplash.com/photo-1537432376769-00a5a5f6898d?auto=format&fit=crop&w=800&q=80',created:'2026-03-29'},
            {id:4,title:'Giải trí cuối tuần: sự kiện âm nhạc học đường',category:'Giải trí',content:'Tham gia để giải tỏa stress và kết nối bạn bè.',image:'https://images.unsplash.com/photo-1529911098878-0d82c7fb1c68?auto=format&fit=crop&w=800&q=80',created:'2026-03-24'}
        ];
        const userPosts = JSON.parse(localStorage.getItem('hluv-user-posts')||'[]');
        const allData = [...userPosts, ...defaultData];
        const result=document.getElementById('search-result');
        const inputSearch=document.getElementById('input-search');
        const btnSearch=document.getElementById('btn-search');
        function render(items){
            result.innerHTML='';
            if(!items.length){result.innerHTML='<p>Không tìm thấy kết quả.</p>';return;}
            items.forEach(item=>{
                const card=document.createElement('article');card.className='story-card';
                const imgSrc = item.image || item.image_url || '../images/placeholder.svg';
                const createdAt = item.created || item.created_at || '';
                card.innerHTML=`<img src="${imgSrc}" alt="${item.title}" onerror="this.src='../images/placeholder.svg'"><div class="story-body"><p class="story-meta">${item.category}</p><h3 class="story-title">${item.title}</h3><p class="story-desc">${item.content}</p><small style="color:var(--muted);">${createdAt}</small></div>`;
                card.onclick=()=>location.href=`article.html?id=${item.id}`;
                result.appendChild(card);
            });
        }
        function search(){
            const q=inputSearch.value.trim().toLowerCase();
            if(!q){render([]);return;}
            const filtered=allData.filter(i=> (i.title+i.content+i.category).toLowerCase().includes(q));
            render(filtered);
        }
        btnSearch.addEventListener('click',search);
        inputSearch.addEventListener('keyup',e=>e.key==='Enter'&&search());
        const q=(new URLSearchParams(location.search)).get('q');
        if(q){inputSearch.value=q;search();}
        document.documentElement.setAttribute('data-theme',localStorage.getItem('hluv-theme')||'light');
        
        const menuToggle=document.getElementById('menu-toggle');
        const navLinks=document.getElementById('nav-links');
        menuToggle.addEventListener('click',()=>navLinks.classList.toggle('open'));