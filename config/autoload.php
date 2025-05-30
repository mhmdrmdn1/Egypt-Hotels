<?php
spl_autoload_register(function ($class) {
    // Convert class name to file path
    $file = __DIR__ . '/../classes/' . $class . '.php';
    
    // Check if file exists and require it
    if (file_exists($file)) {
        require_once $file;
    }
}); 
spl_autoload_register(function ($class) {
    // Convert class name to file path
    $file = __DIR__ . '/../classes/' . $class . '.php';
    
    // Check if file exists and require it
    if (file_exists($file)) {
        require_once $file;
    }
}); 