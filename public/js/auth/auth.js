
$('#loginForm').on('submit', function () {
    login()
});

$('#submitButton').on('click', function () {
    login()
});

const login = () => {

    username = $('#username').val();
    password = $('#password').val();
    if(username==undefined){
        Toastify({
            text: "Trống tên đăng nhập kìa th lồn!",
            duration: 3000,
            newWindow: true,
            close: true,
            gravity: "top",
            position: "right",
            stopOnFocus: true,
            style: {
                background: "linear-gradient(to right, #ee202f, #fc8d3c)",
            },
            onClick: function () { }
        }).showToast();
    }else{
    $.ajax({
        url: '/api/auth/login',
        type: 'POST',
        data: { username: username, password: password },
        dataType: 'json',
        success: function (response) {

            if (response.result == 'success') {
                localStorage.setItem('JWTtoken', response.token);
                
                Toastify({
                    text: "Đăng nhập thành công!",
                    duration: 3000,
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        background: "linear-gradient(to right, #00b09b, #96c93d)",
                    },
                    onClick: function () { }
                }).showToast();
                setTimeout(function () { window.location.href = "/" }, 1000);
            } else {
                alert("Sai pass rồi địt mẹ mày!");
                Toastify({
                    text: "Tên tài khoản hoặc mật khẩu như cặc !",
                    duration: 3000,
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        background: "linear-gradient(to right, #ee202f, #fc8d3c)",
                    },
                    onClick: function () { }
                }).showToast();
            }
        },
        error: function (xhr, status, error) {

        }
    });
    }
}