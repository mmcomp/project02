<?php
require_once("basic.php");

class Post extends Basic
{
    protected $data = array(
        "id" => 0,
        "title" => "",
        "content" => "",
        "post_type" => 0,
        "user_id" => 0,
        "published" => 0,
        "allow_comment" => 0,
        "link" => "",
        "creation_time" => 0,
        "last_modify" => 0,
        "username" => "",
        "first_name" => "",
        "last_name" => "",
        "category" => array()
    );

    public static function get_post($post_type = 1, $published = true, $limit = 0, $start = 0)
    {
        $connect = self::connect();
        if ($published)
            $condition = "AND published = 1";
        else
            $condition = "";
        if ($limit > 0)
            $limiter = "LIMIT $start , $limit";
        else
            $limiter = "";
        $query = "SELECT post.* , username , first_name , last_name FROM post , user_info WHERE
        post.user_id = user_info.id AND post_type = $post_type $condition ORDER BY creation_time DESC $limiter";
        $result = $connect->query($query);
        if ($result->num_rows) {
            $post = array();
            foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
                if ($categories = Post_Category::get_post_by_id($row["id"])) {
                    foreach ($categories as $category) {
                        $row["category"][] = $category->category_id;
                    }
                }
                $post[] = new Post($row);
            }
            $return = $post;
        } else
            $return = false;
        self::disconnect($connect);
        return $return;
    }

    public static function get_post_by_id($id)
    {
        $connect = self::connect();
        $query = "SELECT post.* , username , first_name , last_name FROM post , user_info WHERE
        post.user_id = user_info.id AND post.id=$id";
        $result = $connect->query($query);
        if ($result->num_rows) {
            $row = $result->fetch_assoc();
            if ($categories = Post_Category::get_post_by_id($row["id"])) {
                foreach ($categories as $category) {
                    $row["category"][] = $category->category_id;
                }
            }
            $return = new Post($row);
        } else
            $return = false;
        self::disconnect($connect);
        return $return;
    }

    public static function search_post($q, $title_search = true, $content_search = true, $published = true, $limit = 0, $start = 0)
    {
        $connect = self::connect();
        if ($limit > 0)
            $limiter = "LIMIT $start , $limit";
        else
            $limiter = "";
        if ($title_search and $content_search)
            $condition = "(title LIKE '%$q%' OR content LIKE '%$q%')";
        elseif ($title_search)
            $condition = "title LIKE '%$q%'";
        elseif ($content_search)
            $condition = "content LIKE '%$q%'";
        else
            return false;
        if ($published == true)
            $condition .= " AND published = 1";
        $query = "SELECT post.* , username , first_name , last_name FROM post , user_info WHERE
        post.user_id = user_info.id AND $condition ORDER BY creation_time DESC $limiter";
        $result = $connect->query($query);
        if ($result->num_rows) {
            $posts = array();
            foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
                if ($cats = Post_Category::get_category_by_id($row["id"])) {
                    foreach ($cats as $cat) {
                        $row["category"][] = $cat->category_id;
                    }
                }
                $posts[] = new Post($row);
            }
            self::disconnect($connect);
            return $posts;
        } else
            return false;
    }

    public static function get_post_by_category($category_id, $published = true, $childes = true, $limit = 0, $start = 0)
    {
        $connect = self::connect();
        if ($limit > 0)
            $limiter = "LIMIT $start , $limit";
        else
            $limiter = "";
        if ($published == false)
            $condition = "";
        else
            $condition = "AND published = 1";
        if ($posts = Post_Category::get_category_by_id($category_id, $childes)) {
            $ids = "AND post.id IN (";
            foreach ($posts as $post) {
                $ids .= $post->post_id . ",";
            }
            $ids = substr($ids, 0, strlen($ids) - 1) . ")";
        } else {
            self::disconnect($connect);
            return false;
        }
        $query = "SELECT post.* , username , first_name , last_name FROM post , user_info WHERE
        post.user_id = user_info.id $condition $ids  ORDER BY creation_time DESC $limiter";
        $result = $connect->query($query);
        if ($result->num_rows) {
            $posts = array();
            foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
                if ($categories = Post_Category::get_category_by_id($row["id"])) {
                    foreach ($categories as $category) {
                        $row["category"][] = $category->category_id;
                    }
                }
                $posts[] = new Post($row);
            }
            $return = $posts;
        } else
            $return = false;
        self::disconnect($connect);
        return $return;
    }
}