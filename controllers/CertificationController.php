<?php

namespace app\controllers;

use app\helpers\PHPWordHelper;
use app\models\Certification;
use app\models\Discipline;
use app\models\Group;
use app\models\UserCertification;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

class CertificationController extends Controller
{
    protected PHPWordHelper $wordHelper;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->wordHelper = new PHPWordHelper();
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [],
                        'roles' => ['viewTeacherCategories']
                    ],
                ]
            ]
        ];
    }

    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Discipline::find()
                ->joinWith('users')
                ->where(['user.id' => \Yii::$app->user->getId()])

        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCheckCertification(int $group, int $discipline): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Certification::find()->where(['group_id' => $group, 'discipline_id' => $discipline]),
            'sort' => [
                'defaultOrder' => ['date' => SORT_DESC]
            ]
        ]);

        $additionalData = $this->getCertificationAdditionalData($group, $discipline);

        return $this->render('check-certification', array_merge(['dataProvider' => $dataProvider], $additionalData));
    }

    public function actionFillCertification(int $group, int $discipline)
    {
        $model = new Certification();

        if (\Yii::$app->request->isPost) {
            if ($model->load(\Yii::$app->request->post()) && $model->save()) {
                UserCertification::saveMany(\Yii::$app->request->post('UserCertification'), $model->id);
                \Yii::$app->session->setFlash('success', 'Аттестация успешно сохранена.');
                return $this->redirect(['check-certification', 'group' => $group, 'discipline' => $discipline]);
            } else {
                \Yii::$app->session->setFlash('error', 'Произошла ошибка при сохранении данных.');
                return $this->redirect(\Yii::$app->request->referrer);
            }
        }

        $additionalData = $this->getCertificationAdditionalData($group, $discipline);

        return $this->render('fill-certification', array_merge(['model' => $model], $additionalData));
    }

    public function actionReport(int $id)
    {
        $certification = Certification::findOne(['id' => $id]);

        try {
            $data = $certification->group->getStudentsCertification($certification);
            $this->wordHelper->generateNewReport($data, $certification);
        } catch (\Throwable $exception) {
            \Yii::$app->session->setFlash('error', 'При формировании отчёта произошла ошибка.');
        }


        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionDelete(int $id)
    {
        $certification = Certification::findOne(['id' => $id]);

        if ($certification && $certification->delete()) {
            \Yii::$app->session->setFlash('success', 'Отчёт был удалён из системы.');
        } else {
            \Yii::$app->session->setFlash('error', 'Ошибка при удалени отчёта. Обратитесь к администратору.');
        }

        return $this->redirect(\Yii::$app->request->referrer);
    }

    protected function getCertificationAdditionalData(int $group, int $discipline): array
    {
        $group = Group::findOne(['id' => $group]);
        $discipline = Discipline::findOne(['id' => $discipline]);

        return [
            'group' => $group ?? null,
            'discipline' => $discipline ?? null
        ];
    }
}