<?php


namespace app\commands;


use app\models\Profile;
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

        // Добавление прав в БД.
        $auth->add($viewAdminCategories);
        $auth->add($addUser);
        $auth->add($editUser);
        $auth->add($viewUser);
        $auth->add($viewTeacherCategories);

        // Иерархия "студент -> преподаватель -> админ".
        $auth->addChild($teacher, $viewTeacherCategories);
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

        $profile = new Profile();
        $profile->first_name = 'Имя';
        $profile->last_name = 'Фамилия';
        $profile->second_name = 'Отчество';
        $profile->user_id = $user->id;
        $profile->save();

        // Назначаем роли юзеру. (убрать после тестов)
        $auth->assign($admin, $user->getId());
    }

    public function actionAdmin(string $username, string $password)
    {
        $userId = $this->createUser($username, $password);

        if (!empty($userId)) {
            $this->createProfile($userId);
            $this->assignUserRole($userId, 'admin');
        }
    }

    private function createUser(string $username, string $password)
    {
        $model = new User();

        $model->username = $username;
        $model->email = $username . '@example.com';
        $model->setPassword($password);
        $model->generateAuthKey();
        $model->status = User::STATUS_ACTIVE;

        if ($model->save())
            return $model->id;

        return null;
    }

    private function createProfile(int $userId)
    {
        $profile = new Profile();
        $profile->user_id = $userId;
        $profile->save();
    }

    private function assignUserRole(int $userId, string $role)
    {
        $role = Yii::$app->authManager->getRole($role);
        Yii::$app->authManager->assign($role, $userId);
    }
}