<?php
if (isset($_POST["answer_id"])) {
    $comment_array["full_name"] = $_POST["full_name"];
    $comment_array["email"] = $_POST["email"];
    $comment_array["website"] = $_POST["website"];
    $comment_array["comment"] = $_POST["comment"];
    $comment_array["post_id"] = $_GET["post"];
    $comment_array["parent_id"] = $_POST["answer_id"];
    Comment::insert_comment($comment_array);
    show_post();
} else {
    show_post();
}

function show_post()
{
    $post_id = $_GET["post"];
    $post = Post::get_post_by_id($post_id);
    $content = $post->content;
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
    </div>
    <?php
    if ($comments = Comment::get_comment_by_post_id($post_id)) {
        foreach ($comments as $comment) {
            if ($comment->parent_id == 0) {
                $title = $comment->full_name . " " . "گفته :";
                $class = "class='parent-comment'";
            } else {
                $other = Comment::get_comment_by_id($comment->parent_id);
                $title = $comment->full_name . " " . "در پاسخ به " . $other->full_name . " " . "گفته :";
                $class = "class='child-comment'";
            }
            $creation_time = convert_date($comment->comment_time);
            $time = "در" . " " . $creation_time["day"] . " " . $creation_time["month_name"] . " " . $creation_time["year"];
            $time .= " ساعت " . $creation_time["hour"] . ":" . $creation_time["minute"];
            ?>
            <div class="comment">
                <div <?php echo $class ?>>
                    <div class="comment-header-style">
                        <p><?php echo $title ?></p>
                        <time><?php echo $time ?></time>
                    </div>
                    <div class="comment-content">
                        <?php echo nl2br($comment->comment); ?>
                    </div>
                    <div class="comment-footer">
                        <span onclick="answer_to(<?php echo $comment->id ?>,'<?php echo $comment->full_name ?>');">پاسخ دهید</span>
                    </div>
                </div>
            </div>
        <?php
        }
    }
    ?>

    <?php if ($post->allow_comment) { ?>
    <div id="comment-form">
        <form method="post" id="comment-location">
            <p id="comment-form-header">
                دیدگاه خود را در مورد این مطلب بنویسید.
            </p>
            <table>
                <tr>
                    <td>نام :</td>
                    <td><input type="text" name="full_name"></td>
                </tr>
                <tr>
                    <td>ایمیل :</td>
                    <td>
                        <input type="email" name="email">
                    </td>
                </tr>
                <tr>
                    <td>وب سایت :</td>
                    <td><input type="text" name="website"></td>
                </tr>
                <tr>
                    <td>دیدگاه :</td>
                    <td><textarea name="comment"></textarea></td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="ارسال">
                    </td>
                </tr>
            </table>
            <input type="hidden" name="answer_id" value="0" id="answer_id">
        </form>
    </div>
<?php
}
} ?>
