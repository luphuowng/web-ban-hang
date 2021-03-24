<?php


namespace Core;


class Controller
{
    public function view($name, $data = [])
    {
        extract($data);

        return require_once "app/views/$name.php";
    }

    public function redirect($path)
    {
        header("Location: {$path}");
    }
}