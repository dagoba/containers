<?php
namespace app\modules\finance\controllers;


use yii\filters\AccessControl;
use yii\web\Controller;
use app\modules\finance\models\FinanceApplicationWithdrawal;
use app\modules\finance\models\FinanceApplicationWithdrawalSearch;
use app\modules\user\models\UsertransactionSearch;
use app\modules\user\models\Usertransaction;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use Yii;

class BackendController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['withdrawal-application',
                            'approve-withdrawal-application',
                            'direct-approve-withdrawal-application',
                            'reject-withdrawal-application',
                            'transactions',
                            'edit-transaction',
                            'edit-hidden'],
                        'allow' => true,
                        'roles' => ['moder'],
                    ],
                ],
            ]
        ];
    }

    public function actionEditHidden($id){
       if(($model = Usertransaction::findOne($id))==null||!array_key_exists($model->is_hidden, Usertransaction::hiddenArray())){
            throw new NotFoundHttpException('The requested page does not exist.');
        }else{
            $model->scenario = Usertransaction::SCENARIO_CHANGE_HIDDEN;
            if($model->is_hidden==0){
                $model->is_hidden=1;
                if($model->save()){
                    Yii::$app->getSession()->setFlash('success',Yii::t('app','Транзакция была скрыта'));
                }else{
                    Yii::$app->getSession()->setFlash('error',Yii::t('app','Ошибка скрытия/открытия'));
                }
            }elseif ($model->is_hidden==1) {
                $model->is_hidden=0;
                if($model->save()){
                    Yii::$app->getSession()->setFlash('success',Yii::t('app','Транзакция была открыта'));
                }else{
                    Yii::$app->getSession()->setFlash('error',Yii::t('app','Ошибка скрытия/открытия'));
                }
            }
            return $this->redirect(['transactions']);
        }
    }

    public function actionEditTransaction($id){
       if(($model = Usertransaction::findOne($id))==null){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $model->scenario = Usertransaction::SCENARIO_MODER_UPDATE;
        if($model->load(Yii::$app->request->post()) &&$model->save() ){
                Yii::$app->getSession()->setFlash('success','Транзакция успешно отредактирована');
                return $this->redirect(['transactions']);
            } 
        return $this->render('transactions/edit', ['model' => $model]);
    }

    public function actionDirectApproveWithdrawalApplication($id){
        if (($model = FinanceApplicationWithdrawal::findOne($id)) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if($model->actApproveWithdrawal())
            Yii::$app->getSession()->setFlash('success','Заяка была успешно одобрена');           
        else
            Yii::$app->getSession()->setFlash('error','Произошла ошибка');
        return $this->redirect(['withdrawal-application']);
    }
    
    public function actionWithdrawalApplication(){
        $searchModel = new FinanceApplicationWithdrawalSearch();
        $dataProvider = $searchModel->moderSearch(Yii::$app->request->queryParams);
        return $this->render('withdrawal_application/_view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionApproveWithdrawalApplication($id){
        if (($model = FinanceApplicationWithdrawal::findOne($id)) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if($model->canApproveWithdrawal()&&$model->actApproveWithdrawal())
            Yii::$app->getSession()->setFlash('success','Заяка была успешно одобрена');           
        else
            Yii::$app->getSession()->setFlash('error','Произошла ошибка');
        return $this->redirect(['withdrawal-application']);
    }
    
    public function actionRejectWithdrawalApplication(){
        if(!Yii::$app->request->isAjax ){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $model = new FinanceApplicationWithdrawal();
        $model->scenario = FinanceApplicationWithdrawal::SCENARIO_SEND_COMMENT;
        if ($model->load(Yii::$app->request->post())&&$model->validate()){
            if (($application = FinanceApplicationWithdrawal::findOne($model->moderid)) === null){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
            $application->modercomment = $model->modercomment;
            if($application->canRejectWithdrawal()&&$application->actRejectWithdrawal())
                Yii::$app->getSession()->setFlash('success','Заяка была успешно отклонена');   
            else
                Yii::$app->getSession()->setFlash('error','Произошла ошибка');
            return $this->redirect(['withdrawal-application']);
        } 
        else 
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ActiveForm::validate($model);
    }
    
    public function actionTransactions(){
        $searchModel = new UsertransactionSearch();
        $dataProvider = $searchModel->moderSearch(Yii::$app->request->queryParams);
        return $this->render('transactions/_view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    } 
}
