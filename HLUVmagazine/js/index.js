        const defaultData = [
            { id: 1, title: 'AI & Công nghệ trong giáo dục đại học 2026', category:'Công nghệ', content:'Ứng dụng AI hỗ trợ học tập cá nhân hóa cho sinh viên.', image:'../images/tech-ai.jpg', created:'2026-03-30' },
            { id: 2, title: 'Kỹ năng mềm cần có để thành công', category:'Đời sống', content:'Tư duy phản biện và quản lý thời gian.', image:'../images/soft-skills.jpg', created:'2026-03-28' },
            { id: 3, title: 'Lộ trình học tập môn AI năm cuối', category:'Học tập', content:'Nên tập trung vào học sâu qua dự án thực tế.', image:'../images/ai-study.jpg', created:'2026-03-29' },
            { id: 4, title: 'Giải trí cuối tuần: sự kiện âm nhạc học đường', category:'Giải trí', content:'Tham gia sự kiện để kết nối bạn bè mới.', image:'../images/music-event.jpg', created:'2026-03-24' },
            { id: 5, title: 'Tuyển thủ thể thao - câu chuyện cảm hứng', category:'Đời sống', content:'Nỗ lực tập luyện và chiến thắng bản thân.', image:'../images/sports.jpg', created:'2026-03-25' },
            { id: 6, title: 'Khám phá lab Công nghệ mới của trường', category:'Công nghệ', content:'Các phòng thí nghiệm 4.0 mở cửa cho sinh viên.', image:'../images/lab-tech.jpg', created:'2026-03-27' },
            { id: 7, title: 'Chiến lược thi cuối kỳ hiệu quả', category:'Học tập', content:'Lập kế hoạch, ôn tập và nghỉ ngơi khoa học.', image:'../images/exam-strategy.jpg', created:'2026-03-26' },
            { id: 8, title: 'Hoạt động cộng đồng: thiết kế poster bảo vệ môi trường', category:'Sự kiện', content:'Sự kiện sinh viên tham gia chiến dịch Xanh.', image:'../images/environment.jpg', created:'2026-03-31' }
        ];
        const userPosts = JSON.parse(localStorage.getItem('hluv-user-posts')||'[]');
        const data = [...userPosts, ...defaultData];

        const categories=['Tất cả','Công nghệ','Đời sống','Học tập','Giải trí','Sự kiện'];
        const articles = document.getElementById('articles');
        const filters = document.getElementById('category-filters');
        const loadMoreBtn = document.getElementById('load-more');
        const backTopBtn = document.getElementById('back-top');
        const themeToggle = document.getElementById('theme-toggle');
        const progressBar = document.getElementById('reading-progress');
        const menuToggle = document.getElementById('menu-toggle');
        const navLinks = document.querySelector('.nav-links');

        let shown = data; let page = 1; const pageSize = 4;

        function renderFilters(){
            filters.innerHTML='';
            categories.forEach(cat=>{
                const btn=document.createElement('button');
                btn.className='chip';
                btn.innerText=cat;
                btn.onclick=()=> applyCategory(cat);
                filters.appendChild(btn);
            });
        }

        function getPageData(){
            const start=(page-1)*pageSize;
            return shown.slice(start,start+pageSize);
        }

        function render(append=false){
            const chunk=getPageData();
            if(!append) articles.innerHTML='';
            if(!chunk.length){
                articles.innerHTML='<p>Không còn bài để hiển thị.</p>';
                loadMoreBtn.style.display='none';
                return;
            }
            chunk.forEach(item=>{
                const card=document.createElement('article');
                card.className='story-card';
                const imgSrc = item.image || item.image_url || '../images/placeholder.svg';
                const createdAt = item.created || item.created_at || '';
                card.innerHTML=`<img src="${imgSrc}" alt="${item.title}" onerror="this.src='../images/placeholder.svg'"><div class="story-body"><p class="story-meta">${item.category}</p><h3 class="story-title">${item.title}</h3><p class="story-desc">${item.content}</p><small style="color:var(--muted);">${createdAt}</small></div>`;
                card.onclick = ()=> location.href=`article.html?id=${item.id}`;
                articles.appendChild(card);
            });
            loadMoreBtn.style.display = shown.length > page*pageSize ? 'inline-flex' : 'none';
        }

        function applyCategory(cat){
            page=1;
            if(cat==='Tất cả') shown=data.slice(); else shown=data.filter(i=>i.category===cat);
            render(false);
        }

        function renderFeaturedCategories(){
            const featured = document.getElementById('featured-cats');
            featured.innerHTML = '';
            const cats = ['Công nghệ','Đời sống','Học tập','Giải trí','Sự kiện'];
            cats.forEach(cat=>{
                const btn = document.createElement('button');
                btn.innerText = cat;
                btn.onclick = () => applyCategory(cat);
                featured.appendChild(btn);
            });
        }

        menuToggle.addEventListener('click',()=>{
            navLinks.classList.toggle('open');
        });

        loadMoreBtn.addEventListener('click',()=>{page++;render(true);});
        backTopBtn.addEventListener('click',()=>window.scrollTo({top:0,behavior:'smooth'}));

        themeToggle.addEventListener('click',()=>{
            const current=document.documentElement.getAttribute('data-theme')||'light';
            const next=current==='dark'?'light':'dark';
            document.documentElement.setAttribute('data-theme',next);
            themeToggle.innerText=next==='dark'?'Light Mode':'Dark Mode';
            localStorage.setItem('hluv-theme',next);
        });

        window.addEventListener('scroll',()=>{
            const max= document.body.scrollHeight-window.innerHeight;
            const ratio=max>0? window.scrollY/max : 0;
            progressBar.style.transform=`scaleX(${Math.min(Math.max(ratio,0),1)})`;
            backTopBtn.style.display=window.scrollY>360?'block':'none';
        });

        document.addEventListener('DOMContentLoaded',()=>{
            const saved=localStorage.getItem('hluv-theme')||'light';
            document.documentElement.setAttribute('data-theme',saved);
            themeToggle.innerText=saved==='dark'?'Light Mode':'Dark Mode';
            renderFilters();
            renderFeaturedCategories();
            render(false);
        });