$(document).ready(function() {
    const roleInput  = $('#role')
    const btnAmdin   = $('#btn-admin')
    const btnStudent = $('#btn-student')
    const formLogin  = $('#login-form')
    btnAmdin.on('click',function (){
            var dataSet = btnAmdin.attr('data-tag')
            clearActiveTabs()
            btnAmdin.addClass('active')
            roleInput.val(dataSet)
    })
    btnStudent.on('click',function (){
        var dataSet = btnStudent.attr('data-tag')
        clearActiveTabs()
        btnStudent.addClass('active')
        roleInput.val(dataSet)
    })
    function clearActiveTabs() {
        $('.login-tabs button').removeClass('active');
    }
    Validator(formLogin,{
        onSubmit: function (data){
            url = 'loginApi'
            fetch(url,{
                method:"POST",
                headers:{
                    "Content-Type":"application/json"
                },
                body: JSON.stringify(data)
            })
                .then(function (response){
                    return response.json()
                })
                .then(function (data){
                    if(data.status=='success'){
                       toast({
                           title:"Thành công",
                           message:data.message,
                           type:"success",
                           duration:2000
                       })
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 2000); // 2000ms = 2 giây
                    }
                    else {
                        toast({
                            title:"Thất bại",
                            message:data.message,
                            type:"error",
                        })
                    }
                })

        }
    })
    function toast({
                       title="",
                       message = '',
                       type='info',
                       duration = 3000
                   }){
        const  main = $("#toast")
        if(main){
            const icons = {
                success:"fa-circle-check",
                info:"fa-circle-info",
                warning:"fa-triangle-exclamation",
                error:"fa-circle-xmark",

            }
            const htmls = `
            <div class="toast__icon">
                <i class="fa-solid ${icons[type]}"></i>
            </div>
            <div class="toast__body">
                <h3 class="toast__title">${title}</h3>
                <p class="toast__msg">${message}</p>
            </div>
            <div class="toast__close">
                <i class="fa-solid fa-xmark"></i>
            </div>
        `
            const  toast = $('<div></div>')
            toast
                .delay(duration) // Đợi 3s (giống tham số 3s trong CSS của bạn)
                .animate(
                    {
                        opacity: 0,

                    },
                    1000,
                    function() {
                        $(this).remove();
                    }
                );
            toast.html(htmls)
            toast.addClass('toast')
            toast.addClass(`toast--${type}`)
            main.append(toast)
        }
    }
});