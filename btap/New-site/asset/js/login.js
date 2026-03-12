function showtab(tab){

    $(".tab").removeClass("active")
    $(".tab-content").removeClass("active")

    if(tab === "login"){
        $(".tab").eq(0).addClass("active")
        $("#login").addClass("active")
    }

    if(tab === "register"){
        $(".tab").eq(1).addClass("active")
        $("#register").addClass("active")
    }

}