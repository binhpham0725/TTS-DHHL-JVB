        const defaultData=[
            {id:1,title:'AI & Công nghệ trong giáo dục đại học 2026',category:'Công nghệ',content:'Ứng dụng AI hỗ trợ học tập cá nhân hóa cho sinh viên.',image:'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80',created:'2026-03-30'},
            {id:2,title:'Kỹ năng mềm cần có để thành công',category:'Đời sống',content:'Tư duy phản biện và quản lý thời gian.',image:'https://images.unsplash.com/photo-1543269865-cbf427effbad?auto=format&fit=crop&w=800&q=80',created:'2026-03-28'},
            {id:3,title:'Lộ trình học tập môn AI năm cuối',category:'Học tập',content:'Nên tập trung vào học sâu qua dự án thực tế.',image:'https://images.unsplash.com/photo-1537432376769-00a5a5f6898d?auto=format&fit=crop&w=800&q=80',created:'2026-03-29'},
            {id:4,title:'Giải trí cuối tuần: sự kiện âm nhạc học đường',category:'Giải trí',content:'Tham gia sự kiện để kết nối bạn bè mới.',image:'https://images.unsplash.com/photo-1529911098878-0d82c7fb1c68?auto=format&fit=crop&w=800&q=80',created:'2026-03-24'},
            {id:5,title:'Tuyển thủ thể thao - câu chuyện cảm hứng',category:'Đời sống',content:'Nỗ lực tập luyện và chiến thắng chính mình.',image:'https://images.unsplash.com/photo-1508609349937-5ec4ae374ebf?auto=format&fit=crop&w=800&q=80',created:'2026-03-25'},
            {id:6,title:'Khám phá lab Công nghệ mới của trường',category:'Công nghệ',content:'Các phòng thí nghiệm 4.0 mở cửa cho sinh viên.',image:'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=800&q=80',created:'2026-03-27'},
            {id:7,title:'Chiến lược thi cuối kỳ hiệu quả',category:'Học tập',content:'Lập kế hoạch ôn thi và nghỉ ngơi khoa học.',image:'https://images.unsplash.com/photo-1526470498-9a1e49fda1d4?auto=format&fit=crop&w=800&q=80',created:'2026-03-26'},
            {id:8,title:'Hoạt động cộng đồng: thiết kế poster bảo vệ môi trường',category:'Sự kiện',content:'Sinh viên tham gia chiến dịch Xanh trường học.',image:'https://images.unsplash.com/photo-1505404919724-80a98f7b38c9?auto=format&fit=crop&w=800&q=80',created:'2026-03-31'}
        ];
        const userPosts = JSON.parse(localStorage.getItem('hluv-user-posts')||'[]');
        const data = [...userPosts, ...defaultData];
        const categories=[{id:0,name:'Tất cả'},{id:1,name:'Công nghệ'},{id:2,name:'Đời sống'},{id:3,name:'Học tập'},{id:4,name:'Giải trí'},{id:5,name:'Sự kiện'}];
        let currentCategory='Tất cả';
        const articlesEl=document.getElementById('articles');
        const filtersEl=document.getElementById('category-filters');
        const loadMoreBtn=document.getElementById('load-more');
        const searchBox=document.getElementById('search-box');
        const backTop=document.getElementById('back-top');
        const progress=document.getElementById('reading-progress');
        const themeToggle=document.getElementById('theme-toggle');
        let page=1; const pageSize=4;

        function getFiltered(){
            let filtered=data;
            if(currentCategory!=='Tất cả') filtered=data.filter(a=>a.category===currentCategory);
            const q=searchBox?.value.trim().toLowerCase();
            if(q) filtered=filtered.filter(a=>(`${a.title} ${a.content} ${a.category}`).toLowerCase().includes(q));
            return filtered;
        }

        function renderFilters(){
            filtersEl.innerHTML='';
            categories.forEach(c=>{
                const btn=document.createElement('button');
                btn.className='chip';
                btn.textContent=c.name;
                if(c.name===currentCategory){btn.style.background='rgba(34,197,94,.18)';btn.style.color='#16a34a';}
                btn.onclick=()=>{currentCategory=c.name; page=1; render();};
                filtersEl.appendChild(btn);
            });
        }

        function render(){
            const filtered=getFiltered();
            const start=(page-1)*pageSize; const item=filtered.slice(start,start+pageSize);
            if(page===1) articlesEl.innerHTML='';
            if(!item.length){if(page===1) articlesEl.innerHTML='<p>Không tìm thấy bài viết</p>'; loadMoreBtn.style.display='none'; return;}
            item.forEach(article=>{
                const card=document.createElement('article'); card.className='story-card';
                const imgSrc = article.image || article.image_url || '../images/placeholder.svg';
                const createdAt = article.created || article.created_at || '';
                card.innerHTML=`<img src="${imgSrc}" alt="${article.title}" onerror="this.src='../images/placeholder.svg'"><div class="story-body"><p class="story-meta">${article.category}</p><h3 class="story-title">${article.title}</h3><p class="story-desc">${article.content}</p><small style="color:var(--muted);">${createdAt}</small></div>`;
                card.onclick=()=>location.href=`article.html?id=${article.id}`;
                articlesEl.appendChild(card);
            });
            loadMoreBtn.style.display = (page*pageSize < filtered.length) ? 'inline-flex' : 'none';
        }

        loadMoreBtn.addEventListener('click',()=>{page++;render();});

        searchBox && searchBox.addEventListener('input',()=>{page=1;render();});

        themeToggle.addEventListener('click',()=>{
            const current=document.documentElement.getAttribute('data-theme')||'light';
            const next=current==='dark'?'light':'dark';
            document.documentElement.setAttribute('data-theme',next);
            localStorage.setItem('hluv-theme',next);
            themeToggle.innerText=next==='dark'?'Light Mode':'Dark Mode';
        });

        backTop.addEventListener('click',()=>window.scrollTo({top:0,behavior:'smooth'}));
        window.addEventListener('scroll',()=>{
            const percent=window.scrollY/(document.body.scrollHeight-window.innerHeight)||0;
            progress.style.transform=`scaleX(${Math.min(Math.max(percent,0),1)})`;
            backTop.style.display=window.scrollY>250?'flex':'none';
        });

        document.addEventListener('DOMContentLoaded',()=>{
            const saved=localStorage.getItem('hluv-theme')||'light';
            document.documentElement.setAttribute('data-theme',saved);
            themeToggle.innerText=saved==='dark'?'Light Mode':'Dark Mode';
            renderFilters();
            render();
            
            const menuToggle=document.getElementById('menu-toggle');
            const navLinks=document.getElementById('nav-links');
            menuToggle.addEventListener('click',()=>navLinks.classList.toggle('open'));
        });