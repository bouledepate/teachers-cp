<?php


namespace app\commands;


use Yii;
use yii\console\Controller;

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

        $viewStudentsCategories = $auth->createPermission("viewStudentsCategories");
        $viewStudentsCategories->description = "Просмотр категорий студентов";

        // Добавление прав в БД.
        $auth->add($viewAdminCategories);
        $auth->add($addUser);
        $auth->add($editUser);
        $auth->add($viewUser);
        $auth->add($viewTeacherCategories);
        $auth->add($viewStudentsCategories);

        // Иерархия "студент -> преподаватель -> админ".
        $auth->addChild($student, $viewStudentsCategories);
        $auth->addChild($student, $viewUser);
        $auth->addChild($teacher, $student);
        $auth->addChild($teacher, $viewTeacherCategories);
        $auth->addChild($admin, $teacher);
        $auth->addChild($admin, $addUser);
        $auth->addChild($admin, $editUser);

        // Назначаем роли юзерам. (убрать после тестов)
        $auth->assign($admin, 1);
        $auth->assign($teacher, 2);
        $auth->assign($student, 3);
    }
}