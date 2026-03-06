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

const signUpBtn = document.getElementById("signUp");
const signInBtn = document.getElementById("signIn");
const container = document.getElementById("container");

signUpBtn.addEventListener("click", () => {
    container.classList.add("right-panel-active");
});

signInBtn.addEventListener("click", () => {
    container.classList.remove("right-panel-active");
});