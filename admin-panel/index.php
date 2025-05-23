<?php
include "./include/layout/header.php";

if (isset($_GET['entity']) && isset($_GET['action']) && isset($_GET['id'])) {
    $entity = $_GET['entity'];
    $action = $_GET['action'];
    $id = $_GET['id'];

    if ($action == "delete") {
        switch ($entity) {
            case "post":
                $query = $db->prepare('DELETE FROM posts WHERE id = :id');
                break;
            case "comment":
                $query = $db->prepare('DELETE FROM comments WHERE id = :id');
                break;
            case "category":
                $query = $db->prepare('DELETE FROM categories WHERE id = :id');
                break;
        }
    } elseif ($action == "approve") {
        $query = $db->prepare("UPDATE comments SET status = '1' WHERE id = :id");
    }

    $query->execute(['id' => $id]);
}

$posts = $db->query("SELECT * FROM posts ORDER BY id DESC LIMIT 5");
$comments = $db->query("SELECT * FROM comments ORDER BY id DESC LIMIT 5");
$categories = $db->query("SELECT * FROM categories ORDER BY id DESC");
$postsCount = $db->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$commentsCount = $db->query("SELECT COUNT(*) FROM comments")->fetchColumn();
$categoriesCount = $db->query("SELECT COUNT(*) FROM categories")->fetchColumn();




?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Section -->
        <?php
        include "./include/layout/sidebar.php"
            ?>

        <!-- Main Section -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 bg-light-subtle">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="fs-3 fw-bold">داشبورد</h1>
            </div>
            <!-- Cards -->
            <div class="container py-3">
                <div class="row row-cols-3 g-3 text-center">
                    <?php
                    $items = [
                        ['count' => $postsCount, 'label' => 'تعداد مقالات', 'bg' => '#A8DADC', 'url' => '/php-course-blog/admin-panel/pages/posts/index.php'],
                        ['count' => $commentsCount, 'label' => 'تعداد کامنت', 'bg' => '#FFB6B9', 'url' => '/php-course-blog/admin-panel/pages/comments/index.php'],
                        ['count' => $categoriesCount, 'label' => 'تعداد دسته بندی', 'bg' => '#FFE156', 'url' => '/php-course-blog/admin-panel/pages/categories/index.php'],
                    ];
                    foreach ($items as $item):
                        ?>
                        <div class="col">
                            <a href="<?= $item['url'] ?>" class="text-decoration-none">
                                <div class="card h-100 shadow-sm"
                                    style="height: 150px; background-color: <?= $item['bg'] ?>">
                                    <div
                                        class="card-body d-flex flex-column justify-content-center align-items-center h-100">
                                        <div class="card-text fs-4 fw-bold"><?= $item['count'] ?></div>
                                        <div class="card-title"><?= $item['label'] ?></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Recently Posts -->
            <div class="mt-4 bg-light-subtle">
                <h4 class="text-secondary fw-bold">مقالات اخیر</h4>
                <?php if ($posts->rowCount() > 0): ?>
                    <div class="table-responsive small">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>عنوان</th>
                                    <th>نویسنده</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($posts as $post): ?>
                                    <tr>
                                        <th><?= $post['id'] ?></th>
                                        <td><?= $post['title'] ?></td>
                                        <td><?= $post['author'] ?></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-dark">ویرایش</a>
                                            <a href="index.php?entity=post&action=delete&id=<?= $post['id'] ?>"
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
                            مقاله ای یافت نشد ....
                        </div>
                    </div>
                <?php endif ?>
            </div>

            <!-- Recently Comments -->
            <div class="mt-4 bg-light-subtle">
                <h4 class="text-secondary fw-bold">کامنت های اخیر</h4>
                <?php if ($comments->rowCount() > 0): ?>
                    <div class="table-responsive small">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>نام</th>
                                    <th>متن کامنت</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($comments as $comment): ?>
                                    <tr>
                                        <th><?= $comment['id'] ?></th>
                                        <td><?= $comment['name'] ?></td>
                                        <td><?= $comment['comment'] ?></td>
                                        <td>
                                            <?php if ($comment['status']): ?>
                                                <button class="btn btn-sm btn-outline-dark disabled">تایید شده</button>
                                            <?php else: ?>
                                                <a href="index.php?entity=comment&action=approve&id=<?= $comment['id'] ?>"
                                                    class="btn btn-sm btn-outline-info">در انتظار تایید</a>
                                            <?php endif ?>


                                            <a href="index.php?entity=comment&action=delete&id=<?= $comment['id'] ?>"
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
                            کامنتی یافت نشد ....
                        </div>
                    </div>
                <?php endif ?>
            </div>

            <!-- Categories -->
            <?php if (in_array($user['position'], ['Main Manager', 'Blog Manager'])): ?>
                <div class="mt-4 bg-light-subtle">
                    <h4 class="text-secondary fw-bold">دسته بندی</h4>
                    <?php if ($categories->rowCount() > 0): ?>
                        <div class="table-responsive small">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>عنوان</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categories as $category): ?>
                                        <tr>
                                            <th><?= $category['id'] ?></th>
                                            <td><?= $category['title'] ?></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-outline-dark">ویرایش</a>
                                                <a href="index.php?entity=category&action=delete&id=<?= $category['id'] ?>"
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
                                دسته بندی یافت نشد ....
                            </div>
                        </div>
                    <?php endif ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php
include "./include/layout/footer.php"
    ?>