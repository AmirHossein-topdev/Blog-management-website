<?php
include "../../include/layout/header.php";

$users = $db->query("SELECT * FROM users ORDER BY id DESC");

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = $db->prepare('DELETE FROM users WHERE id = :id');
    $query->execute(['id' => $id]);
    header("Location:index.php");
    exit();
}

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
?>



<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Section -->
        <?php include "../../include/layout/sidebar.php" ?>

        <!-- Main Section -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">کاربران</h1>

                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="./create.php" class="btn btn-sm btn-dark">ایجاد کاربر</a>
                </div>
            </div>

            <div class="mt-4">
                <?php if ($users->rowCount() > 0): ?>
                    <div class="table-responsive small">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ایمیل</th>
                                    <th>رمز</th>
                                    <th>نقش</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td>
                                            <span class="password" id="password-<?= $user['id'] ?>">**********</span>
                                            <button type="button"
                                                onclick="togglePassword(<?= $user['id'] ?>, '<?= htmlspecialchars($user['password']) ?>')"
                                                class="btn btn-sm bg-transparent border-0">
                                                <i class="bi bi-eye-slash" id="eye-icon-<?= $user['id'] ?>"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <?php
                                            switch ($user['position']) {
                                                case 'Main Manager':
                                                    echo 'مدیر اصلی';
                                                    break;
                                                case 'Author':
                                                    echo 'نویسنده';
                                                    break;
                                                case 'Blog Manager':
                                                    echo 'مدیر وبلاگ';
                                                    break;
                                                default:
                                                    echo 'نامشخص';
                                                    break;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="./edit.php?id=<?= $user['id'] ?>"
                                                class="btn btn-sm btn-outline-dark">ویرایش</a>
                                            <a href="index.php?action=delete&id=<?= $user['id'] ?>"
                                                class="btn btn-sm btn-outline-danger">حذف</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="col">
                        <div class="alert alert-danger">
                            کاربری یافت نشد ....
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </main>
    </div>
</div>

<?php include "../../include/layout/footer.php"; ?>


<?php
include "../../include/layout/footer.php"
    ?>