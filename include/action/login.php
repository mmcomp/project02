<?php
if (!isset($_POST["username"]) or !isset($_POST["password"])) {
    login_form();
} else {
    $user = User::authenticate_user($_POST["username"], $_POST["password"]);
    if ($user) {
        $_SESSION["id"] = $user->id;
        $_SESSION["username"] = $user->username;
        $_SESSION["user_type"] = $user->type;
        $_SESSION["first_name"] = $user->first_name;
        $_SESSION["last_name"] = $user->last_name;
        if (isset($_POST['remember']))
            setcookie("remember", $user->username, time() + REMEMBER_TIME);
        header("Location: ./cpanel/");
    } else {
        login_form('<p style="color: #e83155; font-family: Koodak-Bold; text-align: center; margin-top: 10px;">
     نام کاربری یا کلمه عبور نامعتبر است.
           </p>');
    }
}

function login_form($error = "")
{ ?>
    <div id="login-form">
        <?php echo $error ?>
        <form method="post">
            <table>
                <tr>
                    <td>نام کاربری :</td>
                    <td>
                        <input type="text" name="username"/>
                    </td>
                </tr>
                <tr>
                    <td>نام کاربری :</td>
                    <td>
                        <input type="password" name="password"/>
                    </td>
                </tr>
                <tr>
                    <td>مرا به خاطر بسپار :</td>
                    <td>
                        <input type="checkbox" name="remember" value="yes"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="ورود"/>
                    </td>
                </tr>
            </table>
            <a href="?action=sign_up">ثبت نام</a>
            <a href="?action=forget">فراموشی کلمه عبور</a>
        </form>
    </div>
<?php
}

?>