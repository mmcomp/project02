<?php
require_once("basic.php");

class Category extends Basic
{
    protected $data = array(
        "id" => 0,
        "category_name" => "",
        "parent_id" => 0
    );

    public static function get_category()
    {
        $connect = self::connect();
        $query = "SELECT *FROM category ORDER BY id";
        $result = $connect->query($query);
        if ($result->num_rows) {
            $category = array();
            foreach ($result->fetch_all(MYSQLI_ASSOC) AS $row) {
                $category[] = new Category($row);
            }
            $return = $category;
        } else
            $return = false;
        self::disconnect($connect);
        return $return;
    }

    public static function get_category_by_id($id)
    {
        $connect = self::connect();
        $query = "SELECT * FROM category WHERE id = $id";
        $result = $connect->query($query);
        if ($result->num_rows) {
            $return = new Category($result->fetch_assoc());
        } else
            $return = false;
        self::disconnect($connect);
        return $return;
    }

    public static function get_category_by_parent_id($parent_id)
    {
        $connect = self::connect();
        $query = "SELECT *FROM category WHERE parent_id = $parent_id ORDER BY id";
        $result = $connect->query($query);
        if ($result->num_rows) {
            $category = array();
            foreach ($result->fetch_all(MYSQLI_ASSOC) AS $row) {
                $category[] = new Category($row);
            }
            $return = $category;
        } else
            $return = false;
        self::disconnect($connect);
        return $return;
    }
}