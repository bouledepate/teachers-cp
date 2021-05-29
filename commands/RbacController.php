<?php


namespace app\commands;


use Yii;
use yii\console\Controller;
use app\models\User;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // Создание ролей.
        $admin = $auth->createRole("admin");
        $auth->add($admin);
        $teacher = $auth->createRole("teacher");
        $auth->add($teacher);
        $student = $auth->createRole("student");
        $auth->add($student);


        // Создание прав доступа.
        $viewAdminCategories = $auth->createPermission("viewAdminCategories");
        $viewAdminCategories->description = "Просмотр категорий администратора";

        $addUser = $auth->createPermission("addUser");
        $addUser->description = "Добавить нового пользователя";

        $editUser = $auth->createPermission("editUser");
        $editUser->description = "Изменить данные пользователя";

        $viewUser = $auth->createPermission("viewUser");
        $viewUser->description = "Просмотр данных пользователя";

        $viewTeacherCategories = $auth->createPermission("viewTeacherCategories");
        $viewTeacherCategories->description = "Просмотр категорий преподавателей";

        $viewStudentCategories = $auth->createPermission("viewStudentCategories");
        $viewStudentCategories->description = "Просмотр категорий студентов";

        // Добавление прав в БД.
        $auth->add($viewAdminCategories);
        $auth->add($addUser);
        $auth->add($editUser);
        $auth->add($viewUser);
        $auth->add($viewTeacherCategories);
        $auth->add($viewStudentCategories);

        // Иерархия "студент -> преподаватель -> админ".
        $auth->addChild($student, $viewStudentCategories);
        $auth->addChild($student, $viewUser);
        $auth->addChild($teacher, $student);
        $auth->addChild($teacher, $viewTeacherCategories);
        $auth->addChild($admin, $teacher);
        $auth->addChild($admin, $addUser);
        $auth->addChild($admin, $editUser);
        $auth->addChild($admin, $viewAdminCategories);

        // Создаём пользователя "admin-admin".
        $user = new User();
        $user->username = 'admin';
        $user->email = 'admin@example.com';
        $user->setPassword('admin');
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;
        $user->save();

        // Назначаем роли юзеру. (убрать после тестов)
        $auth->assign($admin, $user->getId());
    }
}