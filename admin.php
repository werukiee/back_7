<?php

/**
 * Задача 6. Реализовать вход администратора с использованием
 * HTTP-авторизации для просмотра и удаления результатов.
 **/

// Пример HTTP-аутентификации.
// PHP хранит логин и пароль в суперглобальном массиве $_SERVER.
// Подробнее см. стр. 26 и 99 в учебном пособии Веб-программирование и веб-сервисы.
if (
  empty($_SERVER['PHP_AUTH_USER']) ||
  empty($_SERVER['PHP_AUTH_PW']) ||
  $_SERVER['PHP_AUTH_USER'] != 'admin' ||
  md5($_SERVER['PHP_AUTH_PW']) != md5('123')
) {
  header('HTTP/1.1 401 Unanthorized');
  header('WWW-Authenticate: Basic realm="My site"');
  print('<h1>401 Требуется авторизация</h1>');
  exit();
}

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
        <p class="navbar-text navbar-right actions"><a class="navbar-link login" href="./">Exit</a>
          <a class="btn btn-default action-button" role="button" href="login.php">Log In</a>
        </p>
      </div>
    </nav>
  </div>

  <div class="block_form" id="form">
    <div class="top_bot">
      <h1>Данные</h1>
      <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="form">
        <div class="top_bot">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">name</th>
                <th scope="col">email</th>
                <th scope="col">data</th>
                <th scope="col">gender</th>
                <th scope="col">konech</th>
                <th scope="col">bio</th>
              </tr>
            </thead>
            <tbody>
              <?php
              try {
                $db = new PDO("mysql:host=localhost;dbname=u41810", 'u41810', '3516685', array(PDO::ATTR_PERSISTENT => true));
                $stmt = $db->query('SELECT * FROM form');
                while ($row = $stmt->fetch()) {
                  $id = $row['id'];
                  $name = $row['name'];
                  $email = $row['email'];
                  $data = $row['data'];
                  $gender = $row['gender'];
                  $konech = $row['konech'];
                  $bio = $row['bio'];

                  echo "<tr> <th> $id </th> <th> $name </th> <th> $email</th> <th> $data </th> <th> $gender </th> <th> $konech </th> <th> $bio </th> </tr>";
                }
              } catch (PDOException $e) {
                echo $e->getMessage();
              }
              ?>
            </tbody>
          </table>
        </div>

        <div pl-2 class="form-row">
          <label>Введите id пользователя, чтобы удалить:</label>
          <input class="form-control" type="text" name="delete-id" class="form-control" required>
          <br>
          <input type="submit" value="Удалить" class="btn btn-primary text-dark" size="5" />
        </div>
      </form>
    </div>
  </div>
</body>

</html>
<?php
if (!($_SERVER['REQUEST_METHOD'] == 'GET')) {
  if (!empty($_POST['delete-id'])) {
    echo 'hello';
    $conn = new PDO("mysql:host=localhost;dbname=u41810", 'u41810', '3516685', array(PDO::ATTR_PERSISTENT => true));
    $user = $conn->query("DELETE FROM form WHERE id ='" . $_POST['delete-id'] . "'");
    header('Location: ./admin.php');
  }
}
?>