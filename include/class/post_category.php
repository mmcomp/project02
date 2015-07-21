<?php
require_once("basic.php");

class Post_Category extends Basic
{
    protected $data = array(
        "id" => 0,
        "post_id" => 0,
        "category_id" => 0
    );

    public static function get_post_by_id($post_id)
    {
        $connect = self::connect();
        $query = "SELECT *FROM post_category WHERE post_id = $post_id";
        $result = $connect->query($query);
        if ($result->num_rows) {
            $category = array();
            foreach ($result->fetch_all(MYSQLI_ASSOC) AS $row) {
                $category[] = new Post_Category($row);
            }
            $return = $category;
        } else
            $return = false;
        self::disconnect($connect);
        return $return;
    }

    public static function get_category_by_id($category_id, $child = true)
    {
        $connect = self::connect();
        $query = "SELECT *FROM post_category WHERE category_id = $category_id";
        if ($child) {
            if ($child_category = Category::get_category_by_parent_id($category_id)) {
                $child_id = "(";
                foreach ($child_category as $child) {
                    $child_id .= $child->id . ",";
                }
                $child_id = substr($child_id, 0, strlen($child_id) - 1) . ")";
                "SELECT *FROM post_category WHERE category_id = $category_id OR category_id IN $child_id";
            }

        }
        $result = $connect->query($query);
        if ($result->num_rows) {
            $category = array();
            foreach ($result->fetch_all(MYSQLI_ASSOC) AS $row) {
                $category[] = new Post_Category($row);
            }
            $return = $category;
        } else
            $return = false;
        self::disconnect($connect);
        return $return;
    }
}
