<?php
if (isset($_GET["username"]) and isset($_GET["code"])) {
    $result = User::active_user($_GET["username"], $_GET["code"]);
    if ($result) { ?>
        <div id="activate">
            <p>حساب کاربری شما با موفقیت فعال شد.</p>

            <p>برای ورود به حساب خود <a href="./?action=login">اینجا</a> کلیک کنید</p>
        </div>
    <?php
    } else { ?>
        <div id="activate">
            <p style="color:red;font-size:22px;">لینک فعالسازی نامعتبر است</p>
        </div>
    <?php
    }
}