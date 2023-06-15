<?php
namespace App\controllers;

use App\QueryBuilder;
use League\Plates\Engine;
use Delight\Auth\Auth;
use PDO;
use function Tamtamchik\SimpleFlash\flash;

class HomeController
{
    private $templates;
    private $auth;
    private $qb;

    public function __construct(QueryBuilder $qb, Engine $engine, Auth $auth)
    {
        $this->qb = $qb;
        $this->templates = $engine;
        $this->auth = $auth;
    }

    public function index()
    {
        // Login state
        if ($this->auth->isLoggedIn()) {
            header("location: /users");
        }
        else {
            //Render a template
            echo $this->templates->render('page_login');
        }
    }

    public function create()
    {
        // Login state
        if ($this->auth->isLoggedIn() & $this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {
            
            $username = $this->auth->getUsername();
            $id = $this->auth->getUserId();

            echo $this->templates->render('create_user', ['uname' => $username, 'uid' => $id]);
        }
        else {
            flash()->message('Вы не вошли в систему или нет прав на добавление нового пользователя', 'error');
            header("location: /");
        }
    }

    public function createForm()
    {
        try {
            $userId = $this->auth->admin()->createUser($_POST['email'], $_POST['password'], $_POST['username']);

            $this->qb->update([
                'job' => $_POST['job'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
                'user_status' => $_POST['status'],
                'vk' => $_POST['vk'],
                'tgm' => $_POST['tgm'],
                'inst' => $_POST['inst']
            ], $userId, 'users');

            flash()->message('Был зарегистрирован новый пользователь с ID ' . $userId, 'success');

            if ($_FILES & $_FILES['file']['error'] == UPLOAD_ERR_OK) {
                // file upload
                $uploadDir = 'img/demo/avatars';
                $tmpName = $_FILES['file']['tmp_name'];
                $name = basename($_FILES['file']['name']);
                $extension = pathinfo($name, PATHINFO_EXTENSION);
                $newName = uniqid().'.'.$extension;
                $uploadFile = $uploadDir .'/'. $newName;
                move_uploaded_file($tmpName, $uploadFile);

                $this->qb->update([
                    'img' => $uploadFile
                ], $userId, 'users');
            }
            else {
                flash()->message('Аватар не был загружен', 'warning');
            }

            header("location: /users");

        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->message('Неверный адрес электронной почты', 'error');
            header("location: /create");
            die();
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->message('Неверный пароль', 'error');
            header("location: /create");
            die();
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->message('Пользователь уже существует', 'info');
            header("location: /create");
            die();
        }
    }

    public function edit()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            flash()->message('Вы не выбрали пользователя', 'error');
            header("location: /users");
        }

        if ($this->auth->isLoggedIn() && $this->auth->getUserId() == $_GET['id'] || $this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {
            
            $username = $this->auth->getUsername();
            $id = $this->auth->getUserId();

            $user = $this->qb->getUser('users', $_GET['id']);

            echo $this->templates->render('edit', ['uname' => $username, 'uid' => $id, 'viewUser' => $user]);
        }
        else {
            flash()->message('Можно редактировать только свой профиль', 'error');
            header("location: /users");
        }
    }

    public function editForm()
    {
        $this->qb->update([
            'username' => $_POST['username'],
            'job' => $_POST['job'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address']
        ], $_POST['id'], 'users');

        flash()->message('Профиль успешно обновлен', 'success');

        header("location: /profile?id=". $_POST['id'] ."");
    }

    public function umedia()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            flash()->message('Вы не выбрали пользовател', 'error');
            header("location: /users");
        }

        if ($this->auth->isLoggedIn() && $this->auth->getUserId() == $_GET['id'] || $this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {
            $username = $this->auth->getUsername();
            $id = $this->auth->getUserId();

            $user = $this->qb->getUser('users', $_GET['id']);

            echo $this->templates->render('media', ['uname' => $username, 'uid' => $id, 'viewUser' => $user]);
        }
        else {
            flash()->message('Можно редактировать только свой профиль', 'error');
            header("location: /users");
        }
    }

    public function umediaForm()
    {
        if ($_FILES & $_FILES['file']['error'] == UPLOAD_ERR_OK) {
            // delete old img
            if (!empty($_POST['img'])) {
                unlink($_POST['img']);
            }
            
            // file upload
            $uploadDir = 'img/demo/avatars';
            $tmpName = $_FILES['file']['tmp_name'];
            $name = basename($_FILES['file']['name']);
            $extension = pathinfo($name, PATHINFO_EXTENSION);
            $newName = uniqid().'.'.$extension;
            $uploadFile = $uploadDir .'/'. $newName;
            move_uploaded_file($tmpName, $uploadFile);

            $this->qb->update([
                'img' => $uploadFile
            ], $_POST['id'], 'users');

            flash()->message('Профиль успешно обновлен', 'success');
        }
        else {
            flash()->message('Аватар не был загружен', 'warning');
        }

        header("location: /profile?id=". $_POST['id'] ."");
    }

    public function profile()
    {   
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            flash()->message('Вы не выбрали пользователя', 'error');
            header("location: /users");
        }

        if ($this->auth->isLoggedIn()) {

            $username = $this->auth->getUsername();
            $id = $this->auth->getUserId();

            $user = $this->qb->getUser('users', $_GET['id']);

            echo $this->templates->render('page_profile', ['uname' => $username, 'uid' => $id, 'viewUser' => $user]);
        }
        else {
            flash()->message('Вы не вошли в систему', 'error');
            header("location: /");
        }
    }

    // Registration (sign up)
    public function register()
    {
        if ($this->auth->isLoggedIn()) {
            flash()->message('Для регистрации нужно выйти из системы', 'info');
            header("location: /users");
        }
        else {
            echo $this->templates->render('page_register');
        }
    }

    public function regForm()
    {
        try {
            $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['name'], function ($selector, $token) {
                echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
                echo '  For emails, consider using the mail(...) function, Symfony Mailer, Swiftmailer, PHPMailer, etc.';
                echo '  For SMS, consider using a third-party service and a compatible SDK';
                // Email confirmation
                $this->auth->confirmEmail($selector, $token);
            });

            //echo 'We have signed up a new user with the ID ' . $userId;

            flash()->message('Был зарегистрирован новый пользователь с ID ' . $userId, 'success');

            header("location: /");
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->message('Неверный адрес электронной почты', 'error');
            header("location: /register");
            die();
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->message('Неверный пароль', 'error');
            header("location: /register");
            die();
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            // die('Пользователь уже существует');
            flash()->message('Пользователь уже существует', 'info');
            header("location: /register");
            die();
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->message('Слишком много запросов', 'warning');
            header("location: /register");
            die();
        }
    }

    public function loginForm()
    {
        try {
            $this->auth->login($_POST['email'], $_POST['password']);

            // echo 'Пользователь вошел в систему';
            
            header("location: /users");
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->message('Неверный адрес электронной почты', 'error');
            header("location: /");
            die();
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->message('Неверный пароль', 'error');
            header("location: /");
            die();
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            flash()->message('Электронная почта не подтверждена', 'info');
            header("location: /");
            die();
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->message('Слишком много запросов', 'warning');
            header("location: /");
            die();
        }
    }

    public function emailVerifie()
    {
        try {
            $this->auth->confirmEmail($_GET['selector'], $_GET['token']);
        
            echo 'Email address has been verified';
        }
        catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            die('Invalid token');
        }
        catch (\Delight\Auth\TokenExpiredException $e) {
            die('Token expired');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            die('Email address already exists');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }
    }

    public function logout()
    {
        $this->auth->logOut();

        header("location: /");
    }

    public function role()
    {
        $role = $this->auth->getRoles();
        d($role); exit;
    }

    public function security()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            flash()->message('Вы не выбрали пользователя', 'error');
            header("location: /users");
        }

        if ($this->auth->isLoggedIn() && $this->auth->getUserId() == $_GET['id'] || $this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {
            $username = $this->auth->getUsername();
            $id = $this->auth->getUserId();

            $user = $this->qb->getUser('users', $_GET['id']);

            echo $this->templates->render('security', ['uname' => $username, 'uid' => $id, 'viewUser' => $user]);
        }
        else {
            flash()->message('Можно редактировать только свой профиль', 'error');
            header("location: /users");
        }
    }

    public function secForm()
    {
        $user = $this->qb->getUserEmail('users', $_POST['email']);

        if (empty($user)) {
            // update email
            $this->qb->update(['email' => $_POST['email']], $_POST['id'], 'users');
            flash()->message('Email успешно обновлен', 'success');

            if (!empty($_POST['newPassword']) && $_POST['newPassword'] == $_POST['newPasswordConfirm']) {
                // update password
                $this->auth->admin()->changePasswordForUserById($_POST['id'], $_POST['newPassword']);
                flash()->message('Пароль успешно обновлен', 'success');
                header("location: /security?id=" . $_POST['id'] . "");
            }
            else {
                flash()->message('Пароль не обновлен. Не указан новый пароль или пароль не подтвержден', 'warning');
                header("location: /security?id=" . $_POST['id'] . "");
            }
        }
        else {
            flash()->message('Такой email уже существует', 'error');
            header("location: /security?id=" . $_POST['id'] . "");
        }
    }

    public function status()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            flash()->message('Вы не выбрали пользователя', 'error');
            header("location: /users");
        }

        if ($this->auth->isLoggedIn() && $this->auth->getUserId() == $_GET['id'] || $this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {

            $username = $this->auth->getUsername();
            $id = $this->auth->getUserId();

            $user = $this->qb->getUser('users', $_GET['id']);

            $status = $this->qb->getAll('users_status');

            echo $this->templates->render('status', ['uname' => $username, 'uid' => $id, 'viewUser' => $user, 'viewStatus' => $status]);
        }
        else {
            flash()->message('Можно редактировать только свой профиль', 'error');
            header("location: /users");
        }
    }

    public function statusForm()
    {
        $this->qb->update(['user_status' => $_POST['status']], $_POST['id'], 'users');

        flash()->message('Статус успешно обновлен', 'success');
        header("location: /profile?id=". $_POST['id'] ."");
    }

    public function users()
    {
        // Login state
        if ($this->auth->isLoggedIn()) {
            //echo 'User is signed in';

            $username = $this->auth->getUsername();
            $id = $this->auth->getUserId();
            $roles = $this->auth->getRoles();
            $role = current($roles);

            $users = $this->qb->getAll('users');

            echo $this->templates->render('users', ['uname' => $username, 'uid' => $id, 'urole' => $role, 'users' => $users]);
        }
        else {
            flash()->message('Вы не вошли в систему', 'error');
            //echo 'User is not signed in yet';
            echo $this->templates->render('page_login');
        }
    }

    public function delete()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            flash()->message('Вы не выбрали пользователя', 'error');
            header("location: /users");
        }

        // Deleting users
        if ($this->auth->isLoggedIn() && $this->auth->getUserId() == $_GET['id'] || $this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {
            try {
                $user = $this->qb->getUser('users', $_GET['id']);
                $itemUser = current($user);

                if(!empty($itemUser['img'])) {
                    unlink($itemUser['img']);
                }

                $this->auth->admin()->deleteUserById($_GET['id']);
                flash()->message('Пользователь удален', 'info');

                if($this->auth->getUserId() == $_GET['id']) {
                    header("location: /logout");
                }
                else {
                    header("location: /users");
                }
            }
            catch (\Delight\Auth\UnknownIdException $e) {
                flash()->message('Неизвестный пользователь', 'error');
                header("location: /users");
                die();
            }
        }
        else {
            flash()->message('Можно редактировать только свой профиль', 'error');
            header("location: /users");
        }
    }

    public function test()
    {

    }

}

?>
