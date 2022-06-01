<?php

/**
 * Файл login.php для не авторизованного пользователя выводит форму логина.
 * При отправке формы проверяет логин/пароль и создает сессию,
 * записывает в нее логин и id пользователя.
 * После авторизации пользователь перенаправляется на главную страницу
 * для изменения ранее введенных данных.
 **/

// Отправляем браузеру правильную кодировку,
// файл login.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// Начинаем сессию.
session_start();

// В суперглобальном массиве $_SESSION хранятся переменные сессии.
// Будем сохранять туда логин после успешной авторизации.


// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (!empty($_GET['nologin']))
    $nologin = "Неправильный логин";
  if (!empty($_GET['wrongpass']))
    $wrongpass = "Неправильный пароль";
?>
  <!DOCTYPE html>
  <html lang="ru">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Anastasia</title>
  </head>

  <body>
  <div>
    <nav class="navbar navbar-default navigation-clean-button">
      <div class="container">
        <div class="navbar-header"><a class="navbar-brand" href="#">Admin</a></div>
        <p class="navbar-text navbar-right actions"><a class="navbar-link login"  href="./">Exit</a>
                    <a class="btn btn-default action-button" role="button" href="admin.php">Admin</a></p>
      </div>
    </nav>
  </div>

    <div class="block_form" id="form">
      <div class="top_bot">
      <h1>Авторизация</h1>
        <form action="" method="post">
        <div pl-2 class="form-row">
        <label>Логин:</label>
          <input name="login" placeholder="логин" <?php if (!empty($nologin)) {
                                                    echo 'class= bg-danger text-light is-invalid';
                                                  } ?> />
                                                  <div pl-2 class="form-row">
          </br>
          <div pl-2 class="form-row">
          <label>Пароль:</label>
          <input name="pass" placeholder="пароль" <?php if (!empty($wrongpass)) {
                                                    echo 'class= bg-danger text-light is-invalid';
                                                  } ?> />
                                                  <div pl-2 class="form-row">
          </br>
          <input class="button3" class="btn btn-primary btn-sm" type="submit" value="Войти" />
          </br>
          <?php
          if (!empty($nologin)) {
            echo '<span class="text-danger">' . $nologin . '</span>';
          }
          ?>
          <?php
          if (!empty($wrongpass)) {
            echo '<span class="text-danger">' . $wrongpass . '</span>';
          }
          ?>
        </form>
      </div>
    </div>
  </body>

  </html>

<?php
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {
  $conn = new PDO("mysql:host=localhost;dbname=u41810", 'u41810', '3516685', array(PDO::ATTR_PERSISTENT => true));
  // TODO: Проверть есть ли такой логин и пароль в базе данных.
  // Выдать сообщение об ошибках.
  $q1 = $conn->prepare('SELECT id, pass, login FROM form WHERE login = ?');
  $q1->execute([$_POST['login']]);

  $row = $q1->fetch(PDO::FETCH_ASSOC);
  if (!$row && $row['login'] != $_POST['login'] && !isset($row)) {
    header('Location: ?nologin=1');
    exit();
  }
  if (!$row && $row['pass'] != $_POST['pass'] && !isset($row)) {
    header('Location: ?wrongpass=1');
    exit();
  }
  // Если все ок, то авторизуем пользователя.
  $_SESSION['login'] = $_POST['login'];
  // Записываем ID пользователя.
  $_SESSION['uid'] = $row['id'];;

  // Делаем перенаправление.
  header('Location: ./');
}
?>