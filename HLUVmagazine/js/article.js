        const API='../api';
        const params=new URLSearchParams(location.search);
        const postId=parseInt(params.get('id')) || 1;
        const currentUser = JSON.parse(localStorage.getItem('hluv-current-user') || 'null');

        function getLocalBookmarks(){
            const all = JSON.parse(localStorage.getItem('hluv-user-bookmarks') || '{}');
            return all[currentUser?.id] || [];
        }

        function setLocalBookmarks(ids){
            const all = JSON.parse(localStorage.getItem('hluv-user-bookmarks') || '{}');
            all[currentUser?.id] = ids;
            localStorage.setItem('hluv-user-bookmarks', JSON.stringify(all));
        }

        function isLocallyBookmarked(){
            return getLocalBookmarks().includes(postId);
        }

        async function loadArticle(){
            const res=await fetch(`${API}/posts.php?action=get&id=${postId}`);
        let article;
        if (res.ok) {
            article = await res.json();
        } else {
            const localPosts = JSON.parse(localStorage.getItem('hluv-user-posts')||'[]');
            article = localPosts.find(p=>p.id==postId);
            if(!article){
                document.getElementById('article-title').innerText='Bài viết không tìm thấy';
                return;
            }
        }

        document.getElementById('article-title').innerText=article.title || 'Tiêu đề trống';
        document.getElementById('article-category').innerText=article.category || '';
        document.getElementById('article-author').innerText=article.author_name || (currentUser?.name||'');
        document.getElementById('article-date').innerText=article.created_at || article.created || '';
        document.getElementById('article-readtime').innerText=`${article.readtime || 5} phút đọc`;
        document.getElementById('article-image').src=article.image_url || article.image || '../images/placeholder.svg';
        document.getElementById('article-content').innerText=article.content || '';
        const tagList=document.getElementById('tag-list'); tagList.innerHTML='';
        (article.tags ? article.tags.split(',') : [article.category]).forEach(t=>{const span=document.createElement('span');span.className='tag-pill';span.innerText=t;tagList.appendChild(span);});

            const relatedRes=await fetch(`${API}/posts.php?action=list&category=${encodeURIComponent(article.category)}`);
            const relatedData=await relatedRes.json();
            const related=document.getElementById('related'); related.innerHTML='';
            relatedData.filter(r=>r.id!==article.id).slice(0,4).forEach(x=>{
                const card=document.createElement('article'); card.className='cardsmall';
                card.innerHTML=`<div style="padding:.7rem;"><p class="story-meta">${x.category}</p><h4 style='margin:.35rem 0 .45rem;'>${x.title}</h4><p>${x.excerpt || x.content.slice(0,60)}...</p></div>`;
                card.onclick=()=>location.href=`article.html?id=${x.id}`;
                related.appendChild(card);
            });
        }

        async function refreshBookmarkButton(){
            const btn=document.getElementById('bookmark-btn');
            if(!currentUser){btn.innerText='🔖 Đăng nhập để lưu'; return;}
            try {
                const res=await fetch(`${API}/bookmarks.php?action=check&user_id=${currentUser.id}&post_id=${postId}`);
                if(res.ok){
                    const data=await res.json();
                    btn.innerText=data.bookmarked ? '🔖 Đã lưu' : '🔖 Lưu bài';
                    return;
                }
            } catch(e){
                console.warn('Bookmark check API fail:', e);
            }
            btn.innerText = isLocallyBookmarked() ? '🔖 Đã lưu' : '🔖 Lưu bài';
        }

        async function toggleBookmark(){
            if(!currentUser){return alert('Vui lòng đăng nhập để lưu bookmark.');}
            let apiSuccess=false;
            try {
                const res=await fetch(`${API}/bookmarks.php?action=toggle`,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({user_id:currentUser.id, post_id:postId})});
                if(res.ok){
                    const data=await res.json();
                    apiSuccess = !!(data.added || data.removed);
                }
            } catch(e){
                console.warn('Bookmark toggle API fail:', e);
            }

            // Local fallback cho post chưa tồn tại bảng DB (user posts local) hoặc khi API lỗi
            const localIds = getLocalBookmarks();
            const idx = localIds.indexOf(postId);
            if(idx >= 0){
                localIds.splice(idx, 1);
            } else {
                localIds.push(postId);
            }
            setLocalBookmarks(localIds);

            alert(apiSuccess ? 'Đã cập nhật bookmark' : 'Bookmark đã được lưu cục bộ (không kết nối server)');
            refreshBookmarkButton();
        }

        document.getElementById('bookmark-btn').onclick=toggleBookmark;
        document.getElementById('share-btn').onclick=()=>{if(navigator.share){navigator.share({title:document.getElementById('article-title').innerText,text:'Hãy xem bài viết này',url:location.href});}else{prompt('Sao chép liên kết:',location.href);}};

        const backTop=document.getElementById('back-top');
        backTop.onclick=()=>window.scrollTo({top:0,behavior:'smooth'});
        window.addEventListener('scroll',()=>{backTop.style.display=window.scrollY>320?'flex':'none';});

        document.documentElement.setAttribute('data-theme',localStorage.getItem('hluv-theme')||'light');

        const menuToggle=document.getElementById('menu-toggle');
        const navLinks=document.getElementById('nav-links');
        menuToggle.addEventListener('click',()=>navLinks.classList.toggle('open'));

        loadArticle().then(refreshBookmarkButton);