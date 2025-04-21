<?php
session_start();

include(__DIR__ . "/../config.php");
include(__DIR__ . "/../db.php");

$path = $_SERVER['REQUEST_URI'];

// بررسی لاگین بودن
if (!isset($_SESSION['email'])) {
    if (str_contains($path, 'pages')) {
        header("Location:../auth/login.php?err_msg=در ابتدا باید وارد سیستم شوید");
    } else {
        header("Location:./pages/auth/login.php?err_msg=در ابتدا باید وارد سیستم شوید");
    }
    exit();
}

// گرفتن position از دیتابیس
$position = '';
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    $stmt = $db->prepare("SELECT position FROM users WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $raw_position = $user['position'];

        // ترجمه موقعیت
        switch ($raw_position) {
            case 'Main Manager':
                $position = 'مدیر اصلی';
                break;
            case 'Author':
                $position = 'نویسنده';
                break;
            case 'Blog Manager':
                $position = 'مدیر وبلاگ';
                break;
            default:
                $position = 'نامشخص';
                break;
        }
    }
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ADMIN-PANEL</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous" />

    <?php if (str_contains($path, 'pages')): ?>
        <link rel="stylesheet" href="../../assets/css/style.css" />
    <?php else: ?>
        <link rel="stylesheet" href="./assets/css/style.css" />
    <?php endif ?>
</head>

<body>
    <header
        class="navbar sticky-top bg-secondary p-0 shadow-sm align-items-center d-flex justify-content-betweem justify-content-md-start">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-5 text-white" href="index.html">پنل ادمین</a>
        <span class="text-white me-3 fw-bold">سمت: <?php echo htmlspecialchars($position); ?></span>
        <button class="ms-2 nav-link px-3 text-white d-md-none " type="button" data-bs-toggle="offcanvas"
            data-bs-target="#sidebarMenu">
            <i class="bi bi-justify-left fs-2"></i>
        </button>
    </header>