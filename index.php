<?php
require_once("include/include.php");
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
    <link href="stylesheet.css" rel="stylesheet">
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->
    <script src="jquery-1.11.3.min.js"></script>
    <script src="jquery.color.js"></script>
    <script src="javascript.js"></script>
</head>
<body>
<!--header-->
<div id="header-bar">
    <div id="header">
        <div id="logo"><a href="#"><img src="image/logo.png"></a></div>
    </div>
</div>
<!--menu-->
<div id="menu-bar">
    <div id="menu">
        <ul>
            <li><a href="index.php">صفحه اصلی</a></li>
            <li><a href="./?action=sign_up">ثبت نام</a></li>
            <li><a href="#">درباره ما</a></li>
            <li><a href="#">تماس با ما</a></li>
        </ul>
    </div>
</div>
<!--central section-->
<div id="central-section">
    <!--right section-->
    <div id="right-section">
        <div id="login">
            <header class="right-section-header"><p>ورود</p></header>
            <?php
            include("include/action/login.php");
            ?>
        </div>
        <div id="search">
            <header class="right-section-header"><p>جستجو</p></header>
        </div>
        <div id="category">
            <header class="right-section-header"><p>گروه ها</p></header>
            <ul>
                <?php
                if ($categories = Category::get_category_by_parent_id(0)) {
                    foreach ($categories as $category) { ?>
                        <li>
                            <a href="./?category=<?php echo $category->id ?>"><?php echo $category->category_name ?></a>
                        </li>
                    <?php
                    }
                }
                ?>
            </ul>
        </div>
        <div id="last-post">
            <header class="right-section-header"><p>آخرین مطالب</p></header>
            <ul>
                <?php
                $last_posts = Post::get_post(1, true, MAX_LAST_POST);
                foreach ($last_posts as $last_post) { ?>
                    <li><a href="./?post=<?php echo $last_post->id ?>"><?php echo $last_post->title ?></a></li>
                <?php
                }
                ?>
            </ul>
        </div>
    </div>
    <!--left section-->
    <?php
    if (isset($_GET["post"]) and is_numeric($_GET["post"]))
        include("include/show_post.php");
    elseif (isset($_GET["action"])) {
        if ($_GET["action"] == "sign_up")
            include("include/action/sign_up.php");
        elseif ($_GET["action"] == "login")
            include("include/action/login.php");
        elseif ($_GET["action"] == "activate")
            include("include/action/activate.php");
    } else
        include("include/first_page.php");
    ?>
</div>
<!--footer-->
<div id="footer"></div>
</body>
</html>
