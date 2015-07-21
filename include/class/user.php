<?php
require_once("basic.php");

class User extends Basic
{
    protected $data = array(
        "id" => 0,
        "username" => "",
        "password" => "",
        "email" => "",
        "sign_up_time" => 0,
        "first_name" => "",
        "last_name" => "",
        "activated" => 0,
        "activation_code" => 0,
        "type" => 0
    );

    public static function get_user()
    {
        $connect = self::connect();
        $query = "SELECT user_info.* , user_types.type FROM user_info , user_types WHERE
        user_info.user_type = user_types.id ORDER BY user_info.id";
        $result = $connect->query($query);
        if ($result->num_rows) {
            $users = array();
            foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
                $users[] = new User($row);
            }
            $return = $users;
        } else
            $return = false;
        self::disconnect($connect);
        return $return;
    }

    public static function get_user_by_id($user_id)
    {
        $connect = self::connect();
        $query = "SELECT user_info.* , user_types.type FROM user_info , user_types WHERE
        user_info.user_type=user_types.id AND user_info.id = $user_id";
        $result = $connect->query($query);
        if ($result->num_rows) {
            $row = $result->fetch_assoc();
            $return = new User($row);
        } else
            $return = false;
        self::disconnect($connect);
        return $return;
    }

    public static function insert_user($user_array)
    {
        $connect = self::connect();
        $username = $user_array["username"];
        $password = $user_array["password"];
        $email = $user_array["email"];
        $first_name = $user_array["first_name"];
        $last_name = $user_array["last_name"];
        $user_type = $user_array["user_type"];
        $sign_up_time = time();

        $query = "SELECT * FROM user_info WHERE username = '$username'";
        $result = $connect->query($query);
        if ($result->num_rows)
            return array(false, "این نام کاربری قبلا انتخاب شده است.");
        $query = "SELECT * FROM user_info WHERE email = '$email'";
        $result = $connect->query($query);
        if ($result->num_rows)
            return array(false, "این ایمیل قبلا ثبت شده است.");
        $query = "INSERT INTO user_info (username , password , email , sign_up_time , first_name , last_name ,  user_type)
        VALUES ('$username' , '$password' , '$email' , '$sign_up_time' , '$first_name' , '$last_name' , $user_type)";
        if (!$connect->query($query))
            $return = array(false, "خطا در اتصال به پایگاه داده");
        else
            $return = array(true, "لینک فعال سازی به ایمیل شما ارسال گردید.");
        if (!User::activation_email($username, $email))
            $return = array(false, "");
        self::disconnect($connect);
        return $return;
    }

    public static function activation_email($username, $email)
    {
        $connect = self::connect();
        $activation_code = rand(1000000, 9999999);
        $query = "UPDATE user_info SET activation_code = $activation_code WHERE username = '$username'";
        if (!$connect->query($query))
            return false;
        $mail = new PHPMailer();
        $mail->Subject = "لینک فعالسازی";
        $mail->Body = <<<EOS
<p style="direction: rtl; font-family: Tahoma;">
جهت فعال سازی حساب کاربری خود بر روی لینک زیر کلیک کنید.<br />
<a href="http://localhost/project02/?action=activate&username=$username&code=$activation_code" target="_blank">
http://localhost/project02/?action=activate&amp;username=$username&amp;code=$activation_code
</a>
</p>
EOS;
        $mail->addAddress($email);
        $mail->isHTML();
        $mail->isSMTP();
        $mail->CharSet = "utf-8";
        $mail->FromName = "Web Design";
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 465;
        $mail->Username = "dev.zirak@gmail.com";
        $mail->Password = "dev.zirakgm";
        return $mail->send();
    }

    public static function active_user($username, $activation_code)
    {
        $connect = self::connect();
        $query = "SELECT * FROM user_info WHERE username = '$username' AND activation_code = '$activation_code'";
        $result = $connect->query($query);
        if ($result->num_rows) {
            $query = "UPDATE user_info SET activated = 1 WHERE username = '$username'";
            $connect->query($query);
            return true;
        } else
            return false;
    }

    public static function authenticate_user($username, $password)
    {
        $connect = self::connect();
        $query = "SELECT user_info.* , user_types.type FROM user_info , user_types WHERE
        user_info.user_type = user_types.id AND user_info.username = '$username' AND password = '$password' AND activated = 1";
        $result = $connect->query($query);
        if ($result->num_rows)
            return new User($result->fetch_assoc());
        else
            return false;
    }

    public static function get_user_by_username($username)
    {
        $connect = self::connect();
        $query = "SELECT user_info.* , user_types.type From user_info , user_types WHERE
        user_info.user_type = user_types.id AND user_info.username = '$username'";
        $result = $connect->query($query);
        if ($result->num_rows) {
            $row = $result->fetch_assoc();
            $return = new User($row);
        } else
            $return = false;
        self::disconnect($connect);
        return $return;
    }

    public static function forget_password($email)
    {
        $connect = self::connect();
        $query = "SELECT * FROM user_info WHERE email = '$email'";
        $result = $connect->query($query);
        if ($result->num_rows) {
            $user = $result->fetch_assoc();
            $username = $user["username"];
            $password = $user["password"];
            $mail = new PHPMailer();
            $mail->Subject = "یادآوری کلمه عبور";
            $mail->Body = <<<EOS
<p style="direction: rtl; font-family: Tahoma;">
با سلام<br />
کاربر گرامی، نام کاربری و کلمه عبور برای شما ارسال شد.<br />
نام کاربری : $username <br />
کلمه عبور : $password <br />
</p>
EOS;
            $mail->addAddress($email);
            $mail->isHTML();
            $mail->isSMTP();
            $mail->CharSet = "utf-8";
            $mail->FromName = "Web Design";
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 465;
            $mail->Username = 'dev.zirak@gmail.com';
            $mail->Password = "dev.zirakgm";
            return $mail->send();
        } else
            return false;
    }

}