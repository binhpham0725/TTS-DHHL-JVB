function showtab(tab){

    let tabs = document.querySelectorAll(".tab")
    let contents = document.querySelectorAll(".tab-content")

    tabs.forEach(t => t.classList.remove("active"))
    contents.forEach(c => c.classList.remove("active"))

    if(tab === "login"){
        tabs[0].classList.add("active")
        document.getElementById("login").classList.add("active")
    }

    if(tab === "register"){
        tabs[1].classList.add("active")
        document.getElementById("register").classList.add("active")
    }

}