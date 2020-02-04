<!DOCTYPE html>
<?php
define('HOST', 'localhost');
define('USER_NAME', 'kinograd_admin');
define('PASSWORD', '1234');
define('DBNAME', 'kinograd');
@$db = new mysqli(HOST, USER_NAME, PASSWORD, DBNAME);
if (mysqli_connect_errno()) {
    // Если не удалось подсоединиться к базе данных веб-календаря
    exit;
}
// Запрос на список фильмов
$query = 'select * from movies';
$movies = $db->query($query);
if (!$movies) {
    // Если запрос не выполнен, произошла ошибка
    exit;
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,300,400,700&display=swap" rel="stylesheet">
    <title>КиноГрад | Афиша</title>
</head>

<body>
    <div class="container">
        <div class="header clearfix">
            <a href="index.php" class="logo">КиноГрад</a>
            <ul class="menu">
                <li class="menu_item"><a href="index.php" class="menu_link">Расписание</a></li>
                <li class="menu_item"><a href="poster.php" class="menu_link menu_link_active">Афиша</a></li>
                <li class="menu_item"><a href="viewers.html" class="menu_link">Зрителям</a></li>
                <li class="menu_item"><a href="contact.php" class="menu_link">О нас</a></li>
            </ul>
        </div>
        <div class="content">
            <h1>Афиша</h1>
            <?php
            $n = $movies->num_rows;
            if ($n != 0) {
                $movies->data_seek(0);
                for ($i = 0; $i < $n; $i++) {
                    $row = $movies->fetch_assoc();
                    echo "<h2>" . $row['name'] . "</h2>";
                    echo "<div class='poster'><img src='" . $row['img'] . "' alt='" . $row['name'] . "'></div>";
                    echo "<div class='movie'>";
                    echo "<p><span>В прокате с</span> " . $row['show_since'] . "</p>";
                    echo "<p><span>В прокате до</span> " . $row['show_until'] . "</p>";
                    echo "<p>" . $row['short_description'] . "</p>";
                    echo "</div>";
                    echo "<div class='clearfix'></div>";
                }
                echo '<br>';
            }
            ?>
        </div>
        <div class="clr"></div>
    </div>
    <div class="footer">
        <div class="footer_block">
            <a href="index.php">Расписание</a>
        </div>
        <div class="footer_block">
            <a href="poster.php">Афиша</a>
        </div>
        <div class="footer_block">
            <a href="viewers.html">Зрителям</a>
        </div>
        <div class="footer_block">
            <a href="contact.php">Контакты</a>
            <p>Администратор <span>+7 (999) 999-99-99</span></p>
        </div>
        <div class="footer_bottom">
            <div class="copyright">
                <p>&#169; Студент 2019 Все права защищены</p>
            </div>
            <div class="social_networks">
                <a href="https://www.facebook.com/" target="_blank" class="social_network_link"><img src="img/fb.png" alt="fb"></a>
                <a href="https://twitter.com/" target="_blank" class="social_network_link"><img src="img/twitter.png" alt="twitter"></a>
                <a href="https://vk.com/" target="_blank" class="social_network_link"><img src="img/vk.png" alt="vk"></a>
            </div>
        </div>
    </div>
</body>

</html>