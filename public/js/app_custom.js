let CURRENT_URL = window.location;
let BASE_API_URL = `${window.location.origin}/api${window.location.pathname}`;
let table;
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) token = token.content;

var ajaxpost_cur_req = null;
const ajaxPost = (e, form, cb) => {
    e.preventDefault();
    if (ajaxpost_cur_req == null) {
        var submitBtn = form.querySelectorAll('button[type="submit"]');
        if (submitBtn.length > 0) {
            submitBtn.forEach(element => {
                element.disabled = true;
            });
        }
        var fd = new FormData(form);
        ajaxpost_cur_req = $.ajax({
            type: "POST",
            url: form.action,
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': token
            },
            success: function (res) {
                ajaxpost_cur_req = null;
                $(form).find('.is-invalid').removeClass('is-invalid');
                $('.modal').modal('hide');
                $('.modal').find('form').trigger('reset');
                table.ajax.reload();
                showNotif('info', res.message);
            }
        }).fail(function (res) {
            $(form).find('.is-invalid').removeClass('is-invalid');
            var message = res.message || "Internal Server Error";
            ajaxpost_cur_req = null;
            if (message) {
                showNotif('error', message);
            }
        });
    }
    return false;
}


const showNotif = (status, message) => {
    new Noty({
        layout: 'topRight',
        timeout:2000,
        progressBar: true,
        type: `${status}`,
        text: `${message}`,
    }).show();
}