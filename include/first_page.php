<?php
if (isset ($_GET["section"]) and is_numeric($_GET["section"]))
    $page = $_GET["section"];
else
    $page = 1;
$start = ($page - 1) * MAX_POST;
if ($posts = Post::get_post(1, true, MAX_POST, $start)) {
    foreach ($posts as $post) {
        if ($position = strpos($post->content, "--more--"))
            $content = substr($post->content, 0, $position);
        else
            $content = $post->content;
        if ($comment = Comment::get_comment_by_post_id($post->id))
            $comment_count = count($comment);
        else
            $comment_count = 0;
        ?>
        <div class="left-section">
            <header class="left-section-header">
                <a href="./?post=<?php echo $post->id ?>"><?php echo $post->title ?></a>
            </header>
            <div class="left-section-post-info">
                <p>
                    <?php
                    $creation_time = convert_date($post->creation_time);
                    echo "نوشته شده در" . " " . $creation_time["day"] . " " . $creation_time["month_name"] . " " . $creation_time["year"] . " ";
                    echo "ساعت" . " " . $creation_time["hour"] . ":" . $creation_time["minute"] . " " . "توسط" . " " . $post->first_name;
                    echo " " . $post->last_name . " " . "در گروه" . " " . ":";
                    foreach ($post->category as $category_id) {
                        $category_name = Category::get_category_by_id($category_id)->category_name; ?>
                        <a href="./?category=<?php echo $category_id ?>"><?php echo $category_name ?></a>
                    <?php
                    }
                    ?>
                </p>
            </div>
            <div class="left-section-post-content">
                <p><?php echo $content ?></p>
            </div>
            <div class="left-section-footer">
                <div class="comment-btn">
                    <a href="./?post=<?php echo $post->id ?>#comment-location"><?php echo "$comment_count دیدگاه" ?></a>
                </div>
                <div class="more-btn"><a href="./?post=<?php echo $post->id ?>">ادامه مطلب ...</a>
                </div>
            </div>
        </div>
    <?php
    }
    $total_posts = count(Post::get_post());
    $total_page = ceil($total_posts / MAX_POST);
    ?>
    <div id="paging">
        <p> صفحه ی <?php echo $page ?> از <?php echo $total_page ?></p>
        <ul>
            <?php
            for ($i = 1; $i <= $total_page; $i++) {
                if ($i == $page)
                    $class = "class=active";
                else
                    $class = "";
                echo "<li><a href='./?section=$i' $class>$i</a></li>";
            }
            ?>
        </ul>
    </div>
<?php
} ?>


