Ï<?php
class PhpwebArray
{
    function __construct($arr=NULL)
    {
        $this->arr = array();
        if (isset($arr)) {
            $this->arr = $arr;
        }
    }

    function add($arr)
    {
        foreach ($arr as $key => $value) {
            $this->arr[$key] = $value;
        }
    }

    function set($key, $value)
    {
        $this->arr[$key] = $value;
    }

    function get($key, $default=NULL)
    {
        if (is_array($key)) {
            if (isset($key[2])) {
                if (isset($this->arr[$key[0]][$key[1]][$key[2]])) {
                    return $this->arr[$key[0]][$key[1]][$key[2]];
                }
            } else if (isset($key[1])) {
                if (isset($this->arr[$key[0]][$key[1]])) {
                    return $this->arr[$key[0]][$key[1]];
                }
            } else if (isset($key[0])) {
                if (isset($this->arr[$key[0]])) {
                    return $this->arr[$key[0]];
                }
            }
        } else {
            if (isset($this->arr[$key])) {
                return $this->arr[$key];
            }
        }
        return $default;
    }

    function getArray()
    {
        return $this->arr;
    }

    function setArray($arr)
    {
        $this->arr = $arr;
    }

    function hasKey($key)
    {
        return isset($this->arr[$key]);
    }
}
