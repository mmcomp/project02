<?php
require_once("basic.php");

class Comment extends Basic
{
    protected $data = array(
        "id" => 0,
        "full_name" => "",
        "email" => "",
        "website" => "",
        "comment" => "",
        "comment_time" => 0,
        "user_ip" => "",
        "post_id" => 0,
        "parent_id" => 0
    );

    public static function get_comment()
    {
        $connect = self::connect();
        $query = "SELECT * FROM comment ORDER BY comment_time DESC";
        $result = $connect->query($query);
        if ($result->num_rows) {
            $comment = array();
            foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
                $comment[] = new Comment($row);
            }
            $return = $comment;
        } else
            $return = false;
        self::disconnect($connect);
        return $return;
    }

    public static function get_comment_by_post_id($post_id, $parent_id = 0)
    {
        $connect = self::connect();
        $query = "SELECT * FROM comment WHERE post_id = $post_id AND parent_id = $parent_id ORDER BY comment_time ASC";
        $result = $connect->query($query);
        if ($result->num_rows) {
            $comments = array();
            foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
                $comments[] = new Comment($row);
                $id = $row["id"];
                $secondary_connect = self::connect();
                $secondary_query = "SELECT * FROM comment WHERE post_id = $post_id AND parent_id = $id ORDER BY comment_time ASC";
                $secondary_result = $secondary_connect->query($secondary_query);
                if ($secondary_result->num_rows) {
                    $comments = array_merge($comments, Comment::get_comment_by_post_id($post_id, $id));
                }
            }
            $return = $comments;
        } else
            $return = false;
        self::disconnect($connect);
        return $return;
    }

    public
    static function get_comment_by_id($id)
    {
        $connect = self::connect();
        $query = "SELECT * FROM comment WHERE id = $id";
        $result = $connect->query($query);
        if ($result->num_rows) {
            $row = $result->fetch_assoc();
            $return = new Comment($row);
        } else
            $return = false;
        self::disconnect($connect);
        return $return;
    }

    public static function insert_comment($comment_array)
    {
        $return = true;
        $connect = self::connect();
        $full_name = $comment_array["full_name"];
        $email = $comment_array["email"];
        $website = $comment_array["website"];
        $comment = $comment_array["comment"];
        $post_id = $comment_array["post_id"];
        $parent_id = $comment_array["parent_id"];
        $user_ip = $_SERVER["REMOTE_ADDR"];
        $comment_time = time();
        $query = "INSERT INTO comment
        (full_name , email, website , comment , comment_time , user_ip , post_id , parent_id) VALUES
        ('$full_name' , '$email' , '$website' , '$comment' , $comment_time , '$user_ip' ,$post_id , $parent_id)";
        if (!$connect->query($query))
            $return = false;
        self::disconnect($connect);
        return $return;
    }
}