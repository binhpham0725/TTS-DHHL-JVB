function toggleMenu(id, btn){

    let menu = document.getElementById(id);

    if(menu.style.display === "none"){
        menu.style.display = "block";
        btn.innerText = "Thu gọn";
    }else{
        menu.style.display = "none";
        btn.innerText = "Xem thêm";
    }

}