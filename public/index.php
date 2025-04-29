<?php
session_save_path(sys_get_temp_dir());
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/routes.php';