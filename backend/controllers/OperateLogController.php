<?php
/**
 * Created by PhpStorm.
 * User: 康华茹
 * Date: 2016/12/30
 * Time: 18:25
 */

namespace backend\controllers;

use backend\models\entity\AdminUserEntity;
use backend\models\ar\AdminOperate;
use Yii;
use backend\models\ar\AdminOperateLog;
use backend\models\ar\search\AdminOperateLogSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdminOperateLogController implements the CRUD actions for AdminOperateLog model.
 */
class OperateLogController extends BackendController
{
    /**
     * Lists all AdminOperateLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminOperateLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AdminOperateLog model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model=$this->findModel($id);
        $user=new AdminUserEntity();
        $username=$user->getInfoById($model->admin_id);
        $adminoperate=new AdminOperate();
        $desc=$adminoperate->getInfoById($model->operate_id);
        return $this->render('view', [
            'model' => $model,
            'truename'=>$username['trueName'],
            'desc'=>$desc['desc'],
        ]);
    }

    /**
     * Creates a new AdminOperateLog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminOperateLog();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AdminOperateLog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AdminOperateLog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AdminOperateLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AdminOperateLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminOperateLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
