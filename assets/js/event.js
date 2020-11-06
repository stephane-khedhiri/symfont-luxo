$(document).ready(function(){
    let button = $('#button')
    let form = $('#form_contact')
    let send =$('#form_send')
    button.click(function(){
        form.toggleClass('d-none')
        button.toggleClass('d-none')
    })
    send.click(function () {
        form.toggleClass('d-none')
    })


});