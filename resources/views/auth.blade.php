<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="{{asset('css/reset.css')}}">
    <link rel="stylesheet" href="{{asset('css/auth/style.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập</title>
</head>

<body>

    <div class="container" id="container_vanta">
        <div class="login-box">
            <div class="login-box_container">
                <div class="logo">
                    <img src="{{asset('images/logo/logo.jpeg')}}" alt="Logo" class="">
                </div>
                <div class="login-title">
                    <div class="login-title-head">
                        <p>HE PTIT</p>
                    </div>
                    <div class="box-noti">
                    </div>
                </div>

                <div class="form-group">
                    <div class="form_input_field">
                        <label class="form_label" for="form_username">Tài khoản</label>
                        <input class="form-input" value="" type="text" name="username" id="username">
                    </div>
                    <div class="form_input_field">
                        <label class="form_label" for="form_password">Mật khẩu</label>
                        <input class="form-input" type="password" name="password" id="password">
                    </div>
                    <div class="remember_group">
                        <input class="form-checkbox" type="checkbox" value="1" class="form_input--checkbox" id="form_remember_me">
                        <label class="form_label input_label--remember" for="form_input--checkbox">Ghi nhớ</label>
                    </div>
                    <button id="submitButton" type="submit" class="form_button--submit">Đăng nhập</button>
                    <div class="form_forget_password">
                        <a href="#!">Quên mật khẩu?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.js" integrity="sha512-MnKz2SbnWiXJ/e0lSfSzjaz9JjJXQNb2iykcZkEY2WOzgJIWVqJBFIIPidlCjak0iTH2bt2u1fHQ4pvKvBYy6Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>
<script src="{{asset('js/auth/auth.js')}}"></script>

<script>
    const body = document.querySelector('body');

    VANTA.NET({
        el: body,
        mouseControls: true,
        touchControls: true,
        gyroControls: false,
        minHeight: 600.00,
        minWidth: 200.00,
        scale: 1.00,
        scaleMobile: 1.00,
        backgroundColor: 0x4a435c,
        maxDistance: 24.00,
        spacing: 18.00
    })
</script>

</html>