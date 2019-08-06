<?php

require_once BASE_DIRECTORY.'/src/singleton.php';

final class registry extends singleton
{
    private $data = [];

    public function get($key = null)
    {
        return $key === null
            ? $this->data
            : $this->data[$key] ?? null;
    }

    public function set($key, $data)
    {
        $this->data[$key] = $data;
    }
}