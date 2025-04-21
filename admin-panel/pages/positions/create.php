<?php
include "../../include/layout/header.php";

// بررسی اینکه کاربر مدیر اصلی باشه یا نه
if ($user['position'] !== 'Main Manager') {
    // اگر مدیر اصلی نیست، یک اسکریپت جاوا اسکریپت برای نمایش SweetAlert اضافه کن
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'دسترسی محدود',
            text: 'فقط مدیر اصلی میتواند به این صفحه دسترسی داشته باشد!',
            confirmButtonText: 'باشه'
        }).then(function() {
            window.location.href = '../../index.php';
        });
    </script>";
    exit();
}

// کوئری برای گرفتن کاربران
$users = $db->query("SELECT * FROM users");

$invalidInputemail = '';
$invalidInputpassword = '';
$invalidInputposition = '';

if (isset($_POST['addPost'])) {

    // بررسی ایمیل
    if (empty(trim($_POST['email']))) {
        $invalidInputemail = 'فیلد ایمیل الزامیست';
    }

    // بررسی رمز
    if (empty(trim($_POST['password']))) {
        $invalidInputpassword = 'فیلد رمز الزامیست';
    }

    // بررسی نقش
    if (empty(trim($_POST['userRole']))) {
        $invalidInputposition = 'فیلد نقش الزامیست';
    }

    // اگر همه فیلدها پر باشند
    if (!empty(trim($_POST['email'])) && !empty(trim($_POST['password'])) && !empty(trim($_POST['userRole']))) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $position = $_POST['userRole'];

        // اضافه کردن کاربر به دیتابیس
        $stmt = $db->prepare("INSERT INTO users (email, password, position) VALUES (:email, :password, :position)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':position', $position);

        // اجرای کوئری
        if ($stmt->execute()) {
            // ریدایرکت به صفحه دیگر بعد از موفقیت
            header("Location: index.php");
            exit();
        } else {
            // اگر مشکلی در درج داده‌ها وجود داشته باشد
            echo "خطا در افزودن کاربر به دیتابیس.";
        }
    }
}
?>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Section -->
        <?php
        include "../../include/layout/sidebar.php"
            ?>
        <!-- Main Section -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">ایجاد مقاله</h1>
            </div>

            <!-- Create Post -->
            <div class="mt-4">
                <form method="post" class="row g-4" enctype="multipart/form-data">
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">ایمیل کاربر</label>
                        <input type="text" name="email" class="form-control" />
                        <div class="form-text text-danger"><?= $invalidInputemail ?></div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">رمز کاربر</label>
                        <input type="text" name="password" class="form-control" />
                        <div class="form-text text-danger"><?= $invalidInputpassword ?></div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="userRole" class="form-label">نقش کاربر</label>
                        <select name="userRole" id="userRole" class="form-select">
                            <option value="Main Manager">مدیر اصلی</option>
                            <option value="Author">نویسنده</option>
                            <option value="Blog Manager">مدیر وبلاگ</option>
                        </select>
                        <div class="form-text text-danger"><?= $invalidInputposition ?? ''; ?></div>
                    </div>


                    <div class="col-12">
                        <button type="submit" name="addPost" class="btn btn-dark">
                            ایجاد
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<?php
include "../../include/layout/footer.php"
    ?>