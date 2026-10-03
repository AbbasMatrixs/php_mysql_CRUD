<?php

require_once "config/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION["user_id"])) {

    header(
        "Location: " .
        BASE_URL .
        "/login.php"
    );

    exit();
}


if ($_SESSION["role"] === "admin") {

    header(
        "Location: " .
        BASE_URL .
        "/admin/dashboard.php"
    );

} else {

    header(
        "Location: " .
        BASE_URL .
        "/student/dashboard.php"
    );
}

exit();