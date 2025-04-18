<?php
include "../../include/layout/header.php";

// اتصال به دیتابیس
$monthlyPosts = $db->query("
    SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total
    FROM posts
    GROUP BY month
    ORDER BY month
")->fetchAll(PDO::FETCH_ASSOC);

$authorPosts = $db->query("
    SELECT author, COUNT(*) as total
    FROM posts
    GROUP BY author
    ORDER BY total DESC
")->fetchAll(PDO::FETCH_ASSOC);

$categoryPosts = $db->query("
    SELECT c.title as category, COUNT(*) as total
    FROM posts p
    JOIN categories c ON p.category_id = c.id
    GROUP BY category
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <div class="row">
        <?php include "../../include/layout/sidebar.php" ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <h1 class="fs-3 fw-bold">نمودارهای آماری مقالات</h1>
            </div>

            <!-- نمودار مقالات در هر ماه -->
            <div class="card mb-4">
                <div class="card-header fw-bold">تعداد مقالات در هر ماه</div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 400px;">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- نمودار مقالات هر نویسنده -->
            <div class="card mb-4">
                <div class="card-header fw-bold">تعداد مقالات هر نویسنده</div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 400px;">
                        <canvas id="authorChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- نمودار مقالات در هر دسته‌بندی -->
            <div class="card mb-5">
                <div class="card-header fw-bold">تعداد مقالات در هر دسته‌بندی</div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 400px;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const monthlyPosts = <?= json_encode($monthlyPosts) ?>;
    const authorPosts = <?= json_encode($authorPosts) ?>;
    const categoryPosts = <?= json_encode($categoryPosts) ?>;

    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false
    };

    new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: monthlyPosts.map(p => p.month),
            datasets: [{
                label: 'تعداد مقالات',
                data: monthlyPosts.map(p => p.total),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'blue',
                borderWidth: 2,
                fill: true
            }]
        },
        options: commonOptions
    });

    new Chart(document.getElementById('authorChart'), {
        type: 'bar',
        data: {
            labels: authorPosts.map(p => p.author),
            datasets: [{
                label: 'تعداد مقالات',
                data: authorPosts.map(p => p.total),
                backgroundColor: 'orange'
            }]
        },
        options: commonOptions
    });

    new Chart(document.getElementById('categoryChart'), {
        type: 'pie',
        data: {
            labels: categoryPosts.map(p => p.category),
            datasets: [{
                label: 'تعداد مقالات',
                data: categoryPosts.map(p => p.total),
                backgroundColor: ['red', 'green', 'blue', 'purple', 'orange', 'brown']
            }]
        },
        options: commonOptions
    });
</script>

<?php include "../../include/layout/footer.php" ?>
