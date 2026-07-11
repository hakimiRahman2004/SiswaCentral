<?php
echo json_encode([
    'save_path' => session_save_path() ?: sys_get_temp_dir(),
    'session_id' => session_id(),
    'has_user' => isset($_SESSION['user']),
    'session_data' => $_SESSION,
]);
