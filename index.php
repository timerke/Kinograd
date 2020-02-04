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
// Запрос на список кинотеатров
$query = 'select * from theaters';
$theaters = $db->query($query);
if (!$theaters) {
    // Если запрос не выполнен, произошла ошибка
    exit;
}
if (array_key_exists('theater', $_POST)) {
    // Если выбран кинотеатр для показа расписания
    $theater = $_POST['theater'];
    // Запрос для получения расписания
    $date = date('Y-m-d');
    $query = "select movie_name, time(start_time) as start_time from timetable_view where theater_name='$theater' and date(start_time)='$date'";
    $timetable = $db->query($query);
    if (!$timetable) {
        // Если запрос не выполнен, произошла ошибка
        exit;
    }
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,300,400,700&display=swap" rel="stylesheet">
    <title>КиноГрад</title>
</head>

<body>
    <div class="container">
        <div class="header clearfix">
            <a href="index.php" class="logo">КиноГрад</a>
            <ul class="menu">
                <li class="menu_item"><a href="index.php" class="menu_link menu_link_active">Кинотеатр</a></li>
                <li class="menu_item"><a href="poster.php" class="menu_link">Афиша</a></li>
                <li class="menu_item"><a href="viewers.html" class="menu_link">Зрителям</a></li>
                <li class="menu_item"><a href="contact.php" class="menu_link">О нас</a></li>
            </ul>
        </div>
        <div class="content">
            <form action="index.php" method="POST">
                <?php
                // Список кинотеатров
                $n = $theaters->num_rows;
                if ($n == 0) {
                    echo '<h1>Нет кинотеатров для выбора</h1>';
                } else {
                    echo '<h1>Выберите кинотеатр</h1>';
                    $theaters->data_seek(0);
                    for ($i = 0; $i < $n; $i++) {
                        $row = $theaters->fetch_assoc();
                        echo "<input type='submit' name='theater' value='" . $row['name'] . "' ><br>";
                    }
                }
                // Расписание для выбранного кинотеатра
                if (isset($timetable)) {
                    echo "<h1>Расписание для $theater</h1>";
                    $n = $timetable->num_rows;
                    if ($n != 0) {
                        $timetable->data_seek(0);
                        for ($i = 0; $i < $n; $i++) {
                            $row = $timetable->fetch_assoc();
                            echo "<p>" . $row['start_time'] . " - " . $row['movie_name'] . "</p>";
                        }
                    } else {
                        echo '<h2>Сегодня нет показов</h2>';
                    }
                }
                ?>
            </form>
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