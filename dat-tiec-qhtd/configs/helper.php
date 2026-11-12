<?php

if (!function_exists('debug')) {
    function debug($data)
    {
        echo '<pre>';
        print_r($data);
        die;
    }
}

if (!function_exists('upload_file')) {
    function upload_file($folder, $file)
    {
        $targetFile = $folder . '/' . time() . '-' . $file["name"];

        if (move_uploaded_file($file["tmp_name"], PATH_ASSETS_UPLOADS . $targetFile)) {
            return $targetFile;
        }

        throw new Exception('Upload file không thành công!');
    }
}

if (!function_exists('redirect')) {
    function redirect($url)
    {
        header("Location: $url");
        exit;
    }
}

if (!function_exists('set_flash')) {
    function set_flash($type, $message)
    {
        $_SESSION['flash'][$type] = $message;
    }
}

if (!function_exists('get_flash')) {
    function get_flash($type)
    {
        if (isset($_SESSION['flash'][$type])) {
            $msg = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $msg;
        }
        return null;
    }
}

if (!function_exists('has_flash')) {
    function has_flash($type)
    {
        return isset($_SESSION['flash'][$type]);
    }
}

if (!function_exists('auth_user')) {
    function auth_user()
    {
        return $_SESSION['user'] ?? null;
    }
}

if (!function_exists('is_logged_in')) {
    function is_logged_in()
    {
        return !empty($_SESSION['user']);
    }
}

if (!function_exists('is_admin')) {
    function is_admin()
    {
        return !empty($_SESSION['user']) && ($_SESSION['user']['vai_tro'] ?? '') === 'admin';
    }
}

if (!function_exists('old')) {
    function old($key, $default = '')
    {
        return htmlspecialchars($_SESSION['old'][$key] ?? $default);
    }
}

if (!function_exists('form_error')) {
    function form_error($key)
    {
        return $_SESSION['errors'][$key] ?? null;
    }
}

if (!function_exists('has_error')) {
    function has_error($key)
    {
        return !empty($_SESSION['errors'][$key]);
    }
}

if (!function_exists('clear_flash_data')) {
    function clear_flash_data()
    {
        unset($_SESSION['errors'], $_SESSION['old']);
    }
}
