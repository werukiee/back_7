<?php
header('Content-Type: text/html; charset=UTF-8');

$nameErr = $emailErr = $bioErr = $chickErr = "";
$result;


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $errors = FALSE;
    $messages = array();
    if (isset($_GET['logout'])) {
        // сбросить все переменные сессии
        $_SESSION = array();
        // сбросить куки, к которой привязана сессия
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
            setcookie('blackjack', '', 100000);
            setcookie('konech', '', 100000);
            setcookie('data', '', 100000);
            setcookie('bio', '', 100000);
            setcookie('gender', '', 100000);
            setcookie('name', '', 100000);
            setcookie('email', '', 100000);
        }
        // уничтожить сессию

        /*unset($_COOKIE[session_name()]);
        session_unset();
        session_destroy();
        $_SESSION = array();
        header('Location: index.php');*/
    }
    if (!empty($_COOKIE['save']) && !$errors) {
        setcookie('save', '', 100000);
        setcookie('login', '', 100000);
        setcookie('pass', '', 100000);

        $messages[] = 'Спасибо, результаты сохранены.';

        if (!empty($_COOKIE['blackjack']) && !$errors) {

            $messages[] = 'Вы можете <a href="?logout=1">выйти</a> из аккаунта';
        }
        if (!empty($_COOKIE['pass'])) {
            $messages[] = sprintf(
                'Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
                strip_tags($_COOKIE['login']),
                strip_tags($_COOKIE['pass'])
            );
        }
    }
    if (isset($_GET['field-name'])) {
        $name = $_GET['field-name'];
        $email = $_GET['field-email'];
        $data = $_GET['field-date'];
        $gender = $_GET['radio-gender'];
        $konech = $_GET['radio-konech'];
        $bio = $_GET['biography'];
        $sup = implode(",", $_GET['superpower']);
        if (empty($name)) {
            $nameErr = "Введите имя";
            $errors = TRUE;
        } else if (!preg_match("/^[a-яA-Я ]*$/", $name)) {
            $nameErr = "Некорректно введено имя";
            $errors = TRUE;
        } else {
            //setcookie('name',$name,time()+365*24*60*60);
        }
        if (empty($email)) {
            $emailErr = "Введите Email";
            $errors = TRUE;
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Некорректно введён email";
            $errors = TRUE;
        } else {
            //setcookie('email',$email,time()+365*24*60*60);
        }

        if (empty($bio)) {
            $bioErr = "Введите биографию";
            $errors = TRUE;
        } else {
            //setcookie('bio',$bio,time()+365*24*60*60);
        }
        if (empty($_GET['chick'])) {
            $chickErr = "Чтобы продолжить, нужно согласиться с условиями";
            $errors = TRUE;
        }
        setcookie('email', $email, time() + 365 * 24 * 60 * 60);
        setcookie('name', $name, time() + 365 * 24 * 60 * 60);
        setcookie('data', $data, time() + 365 * 24 * 60 * 60);
        setcookie('gender', $gender, time() + 365 * 24 * 60 * 60);
        setcookie('bio', $bio, time() + 365 * 24 * 60 * 60);
        setcookie('konech', $konech, time() + 365 * 24 * 60 * 60);

        $values = array();
        $values['email'] = empty($_COOKIE['email']) ? '' : strip_tags($_COOKIE['email']);
        $values['name'] = empty($_COOKIE['name']) ? '' : strip_tags($_COOKIE['name']);
        $values['data'] = empty($_COOKIE['data']) ? '' : strip_tags($_COOKIE['data']);
        $values['gender'] = empty($_COOKIE['gender']) ? '' : strip_tags($_COOKIE['gender']);
        $values['bio'] = empty($_COOKIE['bio']) ? '' : strip_tags($_COOKIE['bio']);
        $values['konech'] = empty($_COOKIE['konech']) ? '' : strip_tags($_COOKIE['konech']);

        $values['superpowers'] = array();
        $values['superpowers'][0] = empty($_COOKIE['superpowers_value_0']) ? '' : $_COOKIE['superpowers_value_0'];
        $values['superpowers'][1] = empty($_COOKIE['superpowers_value_1']) ? '' : $_COOKIE['superpowers_value_1'];
        $values['superpowers'][2] = empty($_COOKIE['superpowers_value_2']) ? '' : $_COOKIE['superpowers_value_2'];
        $values['superpowers'][3] = empty($_COOKIE['superpowers_value_3']) ? '' : $_COOKIE['superpowers_value_3'];

        if (empty($errors) && !empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {
            $db = new PDO("mysql:host=localhost;dbname=u41810", 'u41810', '3516685', array(PDO::ATTR_PERSISTENT => true));
            $q1 = $db->prepare("SELECT * FROM form WHERE id= ?");
            $q1->execute([$_SESSION['uid']]);
            $row = $q1->fetch(PDO::FETCH_ASSOC);
            $values['email'] = strip_tags($row['email']);
            $values['name'] = strip_tags($row['name']);
            $values['data'] = strip_tags($row['data']);
            $values['gender'] = strip_tags($row['gender']);
            $values['bio'] = strip_tags($row['bio']);
            $values['konech'] = strip_tags($row['konech']);
        }
    } else {
        include('form.php');
    }
} else {
    $errors = FALSE;
    if (isset($_POST['field-name'])) {
        $name = $_POST['field-name'];
        $email = $_POST['field-email'];
        $data = $_POST['field-date'];
        $gender = $_POST['radio-gender'];
        $konech = $_POST['radio-konech'];
        $bio = $_POST['biography'];
        $sup = implode(",", $_POST['superpower']);
        if (empty($name)) {
            $nameErr = "Введите имя";
            $errors = TRUE;
        } else 
        if (!preg_match("/^[a-яA-Я ]*$/", $name)) {
            $nameErr = "Некорректно введено имя";
            $errors = TRUE;
        }
        if (empty($email)) {
            $emailErr = "Введите Email";
            $errors = TRUE;
        } else 
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Некорректно введён email";
            $errors = TRUE;
        }
        if (empty($bio)) {
            $bioErr = "Введите биографию";
            $errors = TRUE;
        }
        if (empty($_POST['chick'])) {
            $chickErr = "Чтобы продолжить, нужно согласиться с условиями";
            $errors = TRUE;
        }
        setcookie('email', $email, time() + 365 * 24 * 60 * 60);
        setcookie('name', $name, time() + 365 * 24 * 60 * 60);
        setcookie('data', $data, time() + 365 * 24 * 60 * 60);
        setcookie('gender', $gender, time() + 365 * 24 * 60 * 60);
        setcookie('bio', $bio, time() + 365 * 24 * 60 * 60);
        setcookie('konech', $konech, time() + 365 * 24 * 60 * 60);
    }
    if (!empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {
        setcookie('blackjack', '1');
        $messages[] = sprintf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
        $conn = new PDO("mysql:host=localhost;dbname=u41810", 'u41810', '3516685', array(PDO::ATTR_PERSISTENT => true));
        $user = $conn->prepare("UPDATE form SET name = ?, email = ?, data = ?, gender = ?, konech = ?, bio = ? WHERE id ='" . $_SESSION['uid'] . "'");
        $user->execute([$_POST['field-name'], $_POST['field-email'], date('Y-m-d', strtotime($_POST['field-date'])), $_POST['radio-gender'], $_POST['radio-konech'], $_POST['biography']]);
    } else {
        $sup = implode(",", $_POST['superpower']);
        $id = uniqid();
        $hash = md5($id);
        $login = substr($hash, 0, 10);
        $pass = substr($hash, 10, 15);
        $hpass = substr(hash("sha256", $pass), 0, 20);
        setcookie('login', $login);
        setcookie('pass', $hpass);

        $conn = new PDO("mysql:host=localhost;dbname=u41810", 'u41810', '3516685', array(PDO::ATTR_PERSISTENT => true));

        $user = $conn->prepare("INSERT INTO `form` (id, name, email, data, gender, konech, bio, login, pass) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $id_user = $conn->lastInsertId();
        $user->execute([$id_user, $_POST['field-name'], $_POST['field-email'], date('Y-m-d', strtotime($_POST['field-date'])), $_POST['radio-gender'], $_POST['radio-konech'], $_POST['biography'], $login, $hpass]);

        //$user1 = $conn->prepare("INSERT INTO super SET id = ?, super_name = ?");
        //$user1->execute([$id_user, $sup]);
        $result = true;
    }
    if(!$errors){
    setcookie('save', '1');
    header('Location: ./');
    }
    else{
        include('form.php');
    }
    /*header('Location: ./');*/
}