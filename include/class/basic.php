<?php

class Basic
{
    protected $data = array();

    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->data))
                if (is_numeric($value))
                    $this->data[$key] = (int)$value;
                else
                    $this->data[$key] = $value;
        }
    }

    public function __get($property)
    {
        if (array_key_exists($property, $this->data))
            return $this->data[$property];
        else
            die("Invalid Property");
    }

    protected static function connect()
    {
        $connect = new mysqli(HOST_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        $connect->set_charset("utf8");
        return $connect;
    }

    protected static function disconnect($connect)
    {
        unset($connect);
    }
}
