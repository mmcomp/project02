<?php
if (!isset($_POST["username"])) {
    $title = "";
    sign_up_form($title);
} else {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $retype_password = $_POST["retype_password"];
    if (strlen($first_name) == 0)
        sign_up_error("نام خود را صحیح وارد کنید.");
    elseif (strlen($last_name) == 0)
        sign_up_error("نام خانوادگی خود را صحیح وارد کنید");
    elseif (strlen($username) < 3)
        sign_up_error("نام کاربری باید حداقل 3 کاراکتر باشد");
    elseif (strlen($password) < 6)
        sign_up_error("کلمه عبور حداقل باید 6 کاراکتر باشد.");
    elseif ($password != $retype_password)
        sign_up_error("دو کلمه عبور یکسان نیستند.");
    else {
        $user_array = array(
            "username" => $username,
            "password" => $password,
            "email" => $email,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "user_type" => 3
        );
        $result = User::insert_user($user_array);
        if ($result[0] == false)
            sign_up_error($result[1]);
        else
            sign_up_success($result[1]);
    }
}

function sign_up_form($title)
{ ?>
    <div id="sign-up">
        <div id="sign-up-header">
            <p>برای عضویت در سایت فرم زیر را تکمیل کنید.</p>
        </div>
        <div style=" width: 656px; height: 45px;"><?php echo $title ?></div>
        <form method="post">
            <table>
                <tr>
                    <td>نام :</td>
                    <td>
                        <input type="text" name="first_name" id="first-name" required>
                    </td>
                </tr>
                <tr>
                    <td>نام خانوادگی :</td>
                    <td>
                        <input type="text" name="last_name" id="last-name" required>
                    </td>
                </tr>
                <tr>
                    <td>نام کاربری :</td>
                    <td>
                        <input type="text" name="username" id="username" required>
                    </td>
                </tr>
                <tr>
                    <td>ایمیل :</td>
                    <td>
                        <input type="email" name="email" id="email" title="یک ایمیل معتبر وارد کنید">
                    </td>
                </tr>
                <tr>
                    <td>کلمه‌ عبور :</td>
                    <td>
                        <input type="password" name="password" id=password">
                    </td>
                </tr>
                <tr>
                    <td>تکرار کلمه عبور :</td>
                    <td>
                        <input type="password" name="retype_password" id="retype-password">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="ثبت نام" onclick="return check_sign_up()">
                    </td>
                </tr>
            </table>
        </form>
    </div>
<?php
}

function sign_up_error($message)
{
    $title = "    <header>
        <p>برای عضویت در سایت فرم زیر را تکمیل و ارسال کنید</p>
        <p style='color: #e83155; font-family: Koodak-Bold; text-align: center;'>$message</p>
    </header>";
    sign_up_form($title);
}

function sign_up_success($message)
{ ?>
    <div id="sign_up">
        <p style="text-align: center;color: blue;font-size: 20px;padding: 15px;"><?php echo $message ?></p>
    </div>
<?php
}

?>