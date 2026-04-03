        const defaultData=[
            {id:1,title:'AI & Công nghệ trong giáo dục đại học 2026',category:'Công nghệ',content:'Ứng dụng AI hỗ trợ học tập cá nhân hòa hóa cho sinh viên.',image:'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80',created:'2026-03-30'},
            {id:2,title:'Kỹ năng mềm cần có để thành công',category:'Đời sống',content:'Tư duy phản biện và quản lý thời gian.',image:'https://images.unsplash.com/photo-1543269865-cbf427effbad?auto=format&fit=crop&w=800&q=80',created:'2026-03-28'},
            {id:3,title:'Lộ trình học tập môn AI năm cuối',category:'Học tập',content:'Nên tập trung vào học sâu qua dự án thực tế.',image:'https://images.unsplash.com/photo-1537432376769-00a5a5f6898d?auto=format&fit=crop&w=800&q=80',created:'2026-03-29'},
            {id:4,title:'Giải trí cuối tuần: sự kiện âm nhạc học đường',category:'Giải trí',content:'Tham gia sự kiện để kết nối bạn bè mới.',image:'https://images.unsplash.com/photo-1529911098878-0d82c7fb1c68?auto=format&fit=crop&w=800&q=80',created:'2026-03-24'},
            {id:5,title:'Tuyển thủ thể thao - câu chuyện cảm hứng',category:'Đời sống',content:'Nỗ lực tập luyện và chiến thắng chính mình.',image:'https://images.unsplash.com/photo-1508609349937-5ec4ae374ebf?auto=format&fit=crop&w=800&q=80',created:'2026-03-25'},
            {id:6,title:'Khám phá lab Công nghệ mới của trường',category:'Công nghệ',content:'Các phòng thí nghiệm 4.0 mở cửa cho sinh viên.',image:'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=800&q=80',created:'2026-03-27'},
            {id:7,title:'Chiến lược thi cuối kỳ hiệu quả',category:'Học tập',content:'Lập kế hoạch ôn thi và nghỉ ngơi khoa học.',image:'https://images.unsplash.com/photo-1526470498-9a1e49fda1d4?auto=format&fit=crop&w=800&q=80',created:'2026-03-26'},
            {id:8,title:'Hoạt động cộng đồng: thiết kế poster bảo vệ môi trường',category:'Sự kiện',content:'Sinh viên tham gia chiến dịch Xanh trường học.',image:'https://images.unsplash.com/photo-1505404919724-80a98f7b38c9?auto=format&fit=crop&w=800&q=80',created:'2026-03-31'}
        ];
        const userPosts = JSON.parse(localStorage.getItem('hluv-user-posts')||'[]');
        const allData = [...userPosts, ...defaultData];
        const params=new URLSearchParams(location.search);
        const id=params.get('id')||'0';
        const catMap={'0':'Tất cả','1':'Công nghệ','2':'Đời sống','3':'Học tập','4':'Giải trí','5':'Sự kiện'};
        const selected=catMap[id]||'Tất cả';
        document.getElementById('category-title').innerText=selected;
        const section=document.getElementById('articles');
        const filtered=selected==='Tất cả'?allData:allData.filter(a=>a.category===selected);
        document.getElementById('category-count').innerText=`${filtered.length} bài viết`;
        if(!filtered.length){section.innerHTML='<p>Chưa có bài viết trong chuyên mục này.</p>';}
        filtered.forEach(item=>{
            const card=document.createElement('article'); card.className='story-card';
            const imgSrc = item.image || item.image_url || '../images/placeholder.svg';
            const createdAt = item.created || item.created_at || '';
            card.innerHTML=`<img src="${imgSrc}" alt="${item.title}" onerror="this.src='../images/placeholder.svg'"><div class="story-body"><p class="story-meta">${item.category}</p><h3 class="story-title">${item.title}</h3><p class="story-desc">${item.content}</p><small style="color:var(--muted);">${createdAt}</small></div>`;
            card.onclick=()=>location.href=`article.html?id=${item.id}`;
            section.appendChild(card);
        });
        document.documentElement.setAttribute('data-theme',localStorage.getItem('hluv-theme')||'light');
        
        const menuToggle=document.getElementById('menu-toggle');
        const navLinks=document.getElementById('nav-links');
        menuToggle.addEventListener('click',()=>navLinks.classList.toggle('open'));