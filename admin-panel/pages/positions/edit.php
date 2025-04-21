<?php
include "../../include/layout/header.php";

// بررسی اینکه کاربر مدیر اصلی باشه یا نه
if ($user['position'] !== 'Main Manager') {
    // اگر مدیر اصلی نیست، یک اسکریپت جاوا اسکریپت برای نمایش SweetAlert اضافه کن
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'error',
            email: 'دسترسی محدود',
            text: 'فقط مدیر اصلی میتواند به این صفحه دسترسی داشته باشد!',
            confirmButtonText: 'باشه'
        }).then(function() {
            window.location.href = '../../index.php';
        });
    </script>";
    exit();
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $user = $db->prepare('SELECT * FROM users WHERE id = :id');
    $user->execute(['id' => $userId]);
    $user = $user->fetch();

    if (!$user) {
        echo "<script>alert('کاربر مورد نظر پیدا نشد');</script>";
        exit();
    }
} else {
    echo "<script>alert('شناسه کاربر ارسال نشده است');</script>";
    exit();
}

$invalidInputemail = '';
$invalidInputpassword = '';
$invalidInputposition = '';

if (isset($_POST['editPost'])) {

    // بررسی داده‌ها
    if (empty(trim($_POST['email']))) {
        $invalidInputemail = 'فیلد ایمیل الزامیست';
    }

    if (empty(trim($_POST['password']))) {
        $invalidInputpassword = 'فیلد رمز الزامیست';
    }

    if (empty(trim($_POST['position'])) || !in_array($_POST['position'], ['Main Manager', 'Author', 'Blog Manager'])) {
        $invalidInputposition = 'فیلد نقش الزامیست و باید یکی از گزینه‌های معتبر باشد';
    }

    if (!empty(trim($_POST['email'])) && !empty(trim($_POST['password'])) && !empty(trim($_POST['position']))) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $position = $_POST['position'];  // مطمئن شوید که مقدار position یکی از مقادیر معتبر باشد
        $userUpdate = $db->prepare("UPDATE users SET email =:email, password=:password, position=:position WHERE id=:id");
        $userUpdate->execute(['email' => $email, 'password' => $password, 'position' => $position, 'id' => $userId]);

        header("Location:index.php");
        exit();
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
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">ویرایش کاربر</h1>
            </div>

            <!-- users -->
            <div class="mt-4">
                <form method="post" class="row g-4" enctype="multipart/form-data">
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">ایمیل کاربر</label>
                        <input type="text" name="email" class="form-control" value="<?= $user['email'] ?>" />
                        <div class="form-text text-danger"><?= $invalidInputemail ?></div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="form-label">رمز کاربر</label>
                        <input type="text" name="password" class="form-control" value="<?= $user['password'] ?>" />
                        <div class="form-text text-danger"><?= $invalidInputpassword ?></div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <label for="userRole" class="form-label">نقش کاربر</label>
                        <select name="position" id="userRole" class="form-select">
                            <?php
                            // تعریف مقادیر در یک آرایه
                            $roles = [
                                'Main Manager' => 'مدیر اصلی',
                                'Author' => 'نویسنده',
                                'Blog Manager' => 'مدیر وبلاگ'
                            ];

                            // بررسی نقش جاری (مقدار آن را از دیتابیس بگیرید)
                            $currentRoleValue = isset($user['position']) ? $user['position'] : '';
                            $currentRoleLabel = $roles[$currentRoleValue] ?? 'نامشخص'; // نمایش "نامشخص" در صورت عدم وجود نقش
                            
                            ?>
                            <!-- نمایش گزینه انتخابی فعلی -->
                            <option selected value="<?= $currentRoleValue ?>"><?= $currentRoleLabel ?></option>

                            <!-- نمایش سایر گزینه‌ها -->
                            <?php
                            foreach ($roles as $value => $label) {
                                if ($value != $currentRoleValue) {
                                    echo "<option value=\"$value\">$label</option>";
                                }
                            }
                            ?>
                        </select>

                    </div>


                    <div class="col-12">
                        <button name="editPost" type="submit" class="btn btn-dark">
                            ویرایش
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