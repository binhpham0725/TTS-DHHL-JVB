const API='../api';
const currentKey='hluv-current-user';
const getCurrent=()=>JSON.parse(localStorage.getItem(currentKey)||'null');
const setCurrent=user=>localStorage.setItem(currentKey,JSON.stringify(user));

const user=getCurrent();
if(!user){alert('Vui lòng đăng nhập');location.href='login.html';}

document.getElementById('fullname').innerText=user.name;
document.getElementById('email').innerText=user.email;
document.getElementById('avatar').src=user.avatar;

async function renderBookmarks(){
    const list=document.getElementById('bookmark-list');
    list.innerHTML='';

    let apiBookmarks=[];
    try {
        const res=await fetch(`${API}/bookmarks.php?action=list&user_id=${user.id}`);
        if(res.ok){ apiBookmarks = await res.json(); }
    } catch(err){ console.warn('Cannot load API bookmarks:', err); }

    const localBookmarks = JSON.parse(localStorage.getItem('hluv-user-bookmarks')||'{}');
    const localIds = new Set(localBookmarks[user.id] || []);
    const localPosts = JSON.parse(localStorage.getItem('hluv-user-posts')||'[]').filter(p=>p.user_id===user.id);

    const renderedIds = new Set();

    apiBookmarks.forEach(item=>{
        const card=document.createElement('article'); card.className='story-card';
        card.innerHTML=`<img src="${item.image_url || '../images/placeholder.svg'}" alt="${item.title}"><div class="story-body"><p class="story-meta">${item.category}</p><h3 class="story-title">${item.title}</h3></div>`;
        card.onclick=()=>location.href=`article.html?id=${item.post_id}`;
        list.appendChild(card);
        renderedIds.add(Number(item.post_id));
    });

    localPosts.forEach(item=>{
        if(renderedIds.has(item.id)) return;
        if(!localIds.has(item.id)) return;
        const card=document.createElement('article'); card.className='story-card';
        card.innerHTML=`<img src="${item.image_url || item.image || '../images/placeholder.svg'}" alt="${item.title}"><div class="story-body"><p class="story-meta">${item.category}</p><h3 class="story-title">${item.title}</h3></div>`;
        card.onclick=()=>location.href=`article.html?id=${item.id}`;
        list.appendChild(card);
        renderedIds.add(item.id);
    });

    const count = list.children.length;
    if(count===0){ list.innerHTML='<p>Bạn chưa lưu bài nào.</p>'; }
    document.getElementById('bookmark-count').innerText = count;
}

async function renderMyPosts(){
    try {
        const res=await fetch(`${API}/posts.php?action=list`);
        const data=await res.json();
        const apiPosts=(Array.isArray(data)?data:[]).filter(p=>p.user_id===user.id);
        const localPosts=JSON.parse(localStorage.getItem('hluv-user-posts')||'[]').filter(p=>p.user_id===user.id);
        const postsById = {};
        apiPosts.forEach(p=>postsById[p.id]=p);
        localPosts.forEach(p=>postsById[p.id]=p);
        const posts=Object.values(postsById).sort((a,b)=>new Date(b.created_at||b.created||0)-new Date(a.created_at||a.created||0));
        const list=document.getElementById('my-posts');
        list.innerHTML='';

        if(!posts.length){
            list.innerHTML='<p>Chưa có bài đăng nào.</p>';
            document.getElementById('post-count').innerText='0';
            return;
        }

        posts.forEach(p=>{
            const card=document.createElement('article');
            card.className='story-card';
            card.innerHTML=`<img src="${p.image || p.image_url || '../images/placeholder.svg'}" alt="${p.title}" onerror="this.src='../images/placeholder.svg'"><div class="story-body"><p class="story-meta">${p.category}</p><h3 class="story-title">${p.title}</h3><div class="card-actions"><button class="btn btn-small btn-edit" onclick="editPost(${p.id})">✏️ Sửa</button><button class="btn btn-small btn-delete" onclick="deletePost(${p.id})">🗑️ Xóa</button></div></div>`;
            card.style.cursor='pointer';
            card.onclick=(e)=>{
                if(e.target.classList.contains('btn') || e.target.closest('.btn')) return;
                location.href=`article.html?id=${p.id}`;
            };
            list.appendChild(card);
        });

        document.getElementById('post-count').innerText=posts.length;
    } catch(error) {
        console.error('Error rendering posts:', error);
        document.getElementById('my-posts').innerHTML='<p>Có lỗi khi tải bài viết.</p>';
    }
}

function editPost(postId){
    const userPosts = JSON.parse(localStorage.getItem('hluv-user-posts')||'[]');
    const post = userPosts.find(p => p.id == postId);
    if(!post){return alert('Không tìm thấy bài viết');}
    
    document.getElementById('post-title').value = post.title;
    document.getElementById('post-category').value = post.category;
    document.getElementById('post-image-url').value = post.image_url;
    document.getElementById('post-content').value = post.content;
    document.getElementById('post-form').dataset.editId = postId;
    
    const previewImg = document.getElementById('image-preview');
    previewImg.src = post.image_url;
    previewImg.style.display = 'block';
    
    document.querySelector('section form h3').scrollIntoView({behavior:'smooth'});
}

async function deletePost(postId){
    if(!confirm('Xóa bài viết này?')){return;}

    // Xóa từ localStorage
    const userPosts = JSON.parse(localStorage.getItem('hluv-user-posts')||'[]');
    const filtered = userPosts.filter(p => p.id != postId);
    localStorage.setItem('hluv-user-posts', JSON.stringify(filtered));

    // Xóa từ API nếu tồn tại
    try {
        const res = await fetch(`${API}/posts.php?action=delete&id=${postId}`, {method:'DELETE'});
        const data = await res.json();
        if(!res.ok || !data.success){
            console.warn('API xóa bài viết thất bại', data);
        }
    } catch(err){
        console.warn('Không thể gọi API xóa:', err);
    }

    await renderMyPosts();
    alert('Xóa bài viết thành công');
}

// Image preview handlers
document.getElementById('post-image-file').addEventListener('change', (e) => {
    const file = e.target.files[0];
    if(file){
        const reader = new FileReader();
        reader.onload = (event) => {
            document.getElementById('post-image-url').value = event.target.result;
            const img = document.getElementById('image-preview');
            img.src = event.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('post-image-url').addEventListener('change', (e) => {
    const url = e.target.value.trim();
    if(url){
        const img = document.getElementById('image-preview');
        img.src = url;
        img.style.display = 'block';
        img.onerror = () => { img.style.display = 'none'; };
    } else {
        document.getElementById('image-preview').style.display = 'none';
    }
});

renderBookmarks(); renderMyPosts();
const progressMap = JSON.parse(localStorage.getItem('hluv-readprogress')||'{}');
document.getElementById('read-count').innerText = Object.keys(progressMap).length;

document.getElementById('post-form').onsubmit = async (e) => {
    e.preventDefault();
    const title=document.getElementById('post-title').value.trim();
    const category=document.getElementById('post-category').value.trim();
    const imageUrl=document.getElementById('post-image-url').value.trim()||'https://via.placeholder.com/720x480?text=No+Image';
    const content=document.getElementById('post-content').value.trim();
    if(!title||!category||!content){return alert('Điền đủ thông tin');}
    
    const editId = document.getElementById('post-form').dataset.editId;
    
    if(editId){
        // Edit mode - cập nhật bài viết hiện có
        const userPosts = JSON.parse(localStorage.getItem('hluv-user-posts')||'[]');
        const postIndex = userPosts.findIndex(p => p.id == editId);
        if(postIndex > -1){
            userPosts[postIndex].title = title;
            userPosts[postIndex].category = category;
            userPosts[postIndex].content = content;
            userPosts[postIndex].image_url = imageUrl;
            localStorage.setItem('hluv-user-posts', JSON.stringify(userPosts));
            alert('Cập nhật bài viết thành công');
        }
    } else {
        // Create mode - tạo bài viết mới
        const newPost = {
            id: Date.now(),
            user_id: user.id,
            title,
            category,
            content,
            image_url: imageUrl,
            created_at: new Date().toLocaleDateString('vi-VN'),
            author_name: user.name
        };

        // Gửi lên API
        const res = await fetch(`${API}/posts.php?action=create`, {
            method:'POST', headers:{'Content-Type':'application/json'},
            body: JSON.stringify(newPost)
        });
        const data = await res.json();
        if(!res.ok){
            // Lưu tạm local nếu server lỗi
            const userPosts = JSON.parse(localStorage.getItem('hluv-user-posts')||'[]');
            userPosts.push(newPost);
            localStorage.setItem('hluv-user-posts', JSON.stringify(userPosts));
            return alert(data.error || 'Đăng bài có thể chưa được lưu trên server, đã lưu cục bộ.');
        }

        // Thiết lập id thật từ API và đồng bộ localStorage
        newPost.id = data.id || newPost.id;
        newPost.created_at = newPost.created_at || new Date().toLocaleDateString('vi-VN');
        const userPosts = JSON.parse(localStorage.getItem('hluv-user-posts')||'[]').filter(p => p.id !== newPost.id);
        userPosts.push(newPost);
        localStorage.setItem('hluv-user-posts', JSON.stringify(userPosts));
        alert('Đăng bài thành công');
    }
    // Clear form và reset edit mode
    document.getElementById('post-form').reset();
    document.getElementById('post-form').dataset.editId = '';
    document.getElementById('image-preview').style.display = 'none';
    await renderMyPosts();
};

document.getElementById('logout-btn').addEventListener('click', () => {
    if(confirm('Bạn chắc chắn muốn đăng xuất không?')){
        localStorage.removeItem(currentKey);
        location.href='login.html';
    }
});

// Edit profile modal
document.getElementById('edit-profile-btn').addEventListener('click', () => {
    document.getElementById('edit-name').value = user.name;
    document.getElementById('edit-avatar').value = user.avatar;
    document.getElementById('edit-profile-modal').classList.add('active');
});

function setGravatarAvatar(){
    const email = user.email;
    const gravatarUrl = `https://i.pravatar.cc/120?u=${encodeURIComponent(email)}`;
    document.getElementById('edit-avatar').value = gravatarUrl;
}

document.getElementById('edit-profile-form').addEventListener('submit', (e) => {
    e.preventDefault();
    const newName = document.getElementById('edit-name').value.trim();
    const newAvatar = document.getElementById('edit-avatar').value.trim() || user.avatar;
    if(!newName){return alert('Tên không được để trống');}
    
    user.name = newName;
    user.avatar = newAvatar;
    setCurrent(user);
    
    document.getElementById('fullname').innerText = newName;
    document.getElementById('avatar').src = newAvatar;
    document.getElementById('edit-profile-modal').classList.remove('active');
    alert('Cập nhật hồ sơ thành công');
});

document.documentElement.setAttribute('data-theme', localStorage.getItem('hluv-theme') || 'light');

const menuToggle=document.getElementById('menu-toggle');
const navLinks=document.getElementById('nav-links');
menuToggle.addEventListener('click',()=>navLinks.classList.toggle('open'));