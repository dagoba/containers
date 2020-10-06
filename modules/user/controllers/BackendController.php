<?php
namespace app\modules\user\controllers;

use app\modules\user\models\User;
use app\modules\user\models\UserSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\modules\user\models\UserContact;
use yii\data\ActiveDataProvider;
use app\modules\user\models\UserBackendFinanceOperations;
use app\modules\user\models\UserEntryStatisticsSearch;
use app\modules\user\models\UserCarriageContract;
use app\modules\user\models\UserCarriageContractSearch;
use app\modules\user\models\UserCarriageTicket;
use app\modules\user\models\UserCarriageTicketSearch;
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
                        'actions' => [
                            'users',
                            'login-by-user',
                            'user-detailed-information',
                            'new-financial-transaction',
                            'get-last-user-page',
                            'logout-all-devices',
                            'user-edit',
                            'create-carriage-contract',
                            'carriage-contract',
                            'user-carriage-contract',
                            'view-carriage-contract',
                            'edit-carriage-contract',
                            'delete-carriage-contract',
                            'active-carriage-contract',
                            'complete-carriage-contract',
                            'canceled-carriage-contract',
                            'carriage-tickets',
                            'approve-ticket',
                            'cancel-ticket',
                            'delete-ticket'
                            ],
                        'allow' => true,
                        'roles' => ['moder'],
                    ],
                ],
            ]
        ];
    }

    public function actionCarriageTickets(){
    $queryNewTickets = UserCarriageTicket::find()
             ->where(['status'=>UserCarriageTicket::STATUS_CREATED]);
    $dataProviderNewTickets = new ActiveDataProvider([
        'query' => $queryNewTickets,
        'sort' =>['defaultOrder' => ['id' => SORT_DESC]]
    ]);
    $searchModel = new UserCarriageTicketSearch();
    $dataProvider = $searchModel->moderSearch(Yii::$app->request->queryParams);
    return $this->render('carriage/ticket/index', [
        'searchModel' => $searchModel,
        'dataProvider'=>$dataProvider,
        'dataProviderNewTickets'=>$dataProviderNewTickets,
       
    ]);
}

public function actionApproveTicket($id){
    if(($ticket = UserCarriageTicket::findOne($id))==null){
       throw new NotFoundHttpException('The requested page does not exist.'); 
    }
    if($ticket->canApprove()&&$ticket->actApprove()){
        Yii::$app->getSession()->setFlash('success',Yii::t('main','Заявка одобрена. Создайте контракт.'));
        return $this->redirect(['create-carriage-contract','user_id'=>$ticket->user_id]);
    }else{
        Yii::$app->getSession()->setFlash('warning',Yii::t('main', 'Не удалось одобрить заявку.'));
        return $this->redirect(['carriage-tickets']);
    }
}

public function actionCancelTicket($id){
    if(($ticket = UserCarriageTicket::findOne($id))==null){
       throw new NotFoundHttpException('The requested page does not exist.'); 
    }
    if($ticket->canCancel()&&$ticket->actCancel()){
        Yii::$app->getSession()->setFlash('success',Yii::t('main','Заявка отменена.'));
    }else{
        Yii::$app->getSession()->setFlash('warning',Yii::t('main', 'Не удалось отменить заявку.'));    
    }
    return $this->redirect(['carriage-tickets']);
}

public function actionDeleteTicket($id){
    if(($model = UserCarriageTicket::findOne($id))==null){
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    if($model->delete()){
        Yii::$app->getSession()->setFlash('success',Yii::t('app','Заявка успешно удалена'));
    }else{
        Yii::$app->getSession()->setFlash('warning',Yii::t('app','Невозможно удалить заявку'));
    }
    return $this->redirect(['carriage-tickets']);
}

    
    public function actionUsers(){
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->moderSearch(Yii::$app->request->queryParams);
        return $this->render('users/_view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionLoginByUser($id){
        $model =$this->findModel($id);
        Yii::$app->user->logout();
        if(Yii::$app->user->login($model, 60*5)){
             return $this->goBack();
        }
    }

    public function actionUserDetailedInformation($id){
        $model =$this->findModel($id);
        $searchEntry = new UserEntryStatisticsSearch();
        $queryContact = UserContact::find()
                 ->where(['user_id'=>$model->id]);
        $dataProviderContact = new ActiveDataProvider([
            'query' => $queryContact,
            'sort' =>['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        return $this->render('users/_detail', [
            'model' => $model,
            'dataProviderContact'=>$dataProviderContact,
            'dataProviderEntry'=>$searchEntry->searchByUser(Yii::$app->request->queryParams,$model->id)
        ]);
    }
    
    public function actionNewFinancialTransaction($id){
        $user =$this->findModel($id);
        $model=new UserBackendFinanceOperations();
        $model->initUser($user->id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()&&$model->execute()) {
            Yii::$app->getSession()->setFlash('success','Операция выполнена успешно');
            return $this->redirect(['users']);
        } else {
            return $this->render('users/formFinancialTransaction', [
                'model' => $model,
                'user'=>$user
            ]);
        }
    }
    
    public function actionGetLastUserPage($id){
        if(!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if(!User::isSuperAdmin()){
            throw new ForbiddenHttpException('Access denied');
        }
        $user =$this->findModel($id);
        if($user->userpass){
            echo $user->userpass->value;
        } else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionLogoutAllDevices($id){
        $user = $this->findModel($id);
        if($user->deleteAllSesions()&&$user->reGenerateAuthKey()){
            Yii::$app->getSession()->setFlash('success', 'Пользователь успешно разлогинен');
        }
        else {
            Yii::$app->getSession()->setFlash('error', 'Не удалось выполнить выход');
        }
        $this->redirect(['users']);        
    }
    
    public function actionUserEdit($id){
        $model = $this->findModel($id);
        $model->scenario = User::SCENARIO_MODER_UPDATE;
        if ($model->load(Yii::$app->request->post()) && $model->save()){
            Yii::$app->getSession()->setFlash('success','Информация о пользователе успешно отредактирована');
            return $this->redirect(['user-detailed-information','id'=>$model->id]);
        } else {
            return $this->render('users/_form', ['model' => $model]);
        }
    }
    
    
    
    public function actionCreateCarriageContract($user_id){
        $user = $this->findModel($user_id);
        $model = new UserCarriageContract();
        $model->scenario = UserCarriageContract::SCENARIO_CREATE_BY_MODER;
        if($model->load(Yii::$app->request->post()) ){
            $model->user_id = $user->id;
            if ($model->save()){
                Yii::$app->getSession()->setFlash('success','Новый контракт успешно закреплен за пользователем '.$user->email);
                return $this->redirect(['view-carriage-contract','id'=>$model->id]);
            } 
        }else {
            return $this->render('carriage/create', [
                'model' => $model,
                'user' => $user
                ]);
        }
    }
    
    public function actionCarriageContract(){
        $searchModel = new UserCarriageContractSearch();
        $dataProvider = $searchModel->moderSearch(Yii::$app->request->queryParams);
        return $this->render('carriage/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionUserCarriageContract($user_id){
        $user = $this->findModel($user_id);
        $searchModel = new UserCarriageContractSearch();
        $searchModel->user_id = $user->id;
        $dataProvider = $searchModel->moderSearch(Yii::$app->request->queryParams);
        return $this->render('carriage/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionViewCarriageContract($id){
        if(($model = UserCarriageContract::findOne($id))==null){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $this->render('carriage/view', ['model' => $model]);
    }
    public function actionEditCarriageContract($id){
        if(($model = UserCarriageContract::findOne($id))==null){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $model->scenario = UserCarriageContract::SCENARIO_SHANGE_INFO;
        if($model->load(Yii::$app->request->post()) ){
            if ($model->save()){
                Yii::$app->getSession()->setFlash('success','Контракт успешно отредактирован');
                return $this->redirect(['carriage-contract']);
            } 
            else
            {
                Yii::$app->getSession()->setFlash('warning',Yii::t('app','Ошибка редактирования контракта'));
                return $this->redirect(['edit-carriage-contract','id'=>$model->id]);
            }
        }

        return $this->render('carriage/edit', ['model' => $model]);
    }
    
    
    public function actionDeleteCarriageContract($id){
        if(($model = UserCarriageContract::findOne($id))==null){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if($model->canDelete() && $model->delete()){
            Yii::$app->getSession()->setFlash('success',Yii::t('app','Заявка успешно удалена'));
        }else{
            Yii::$app->getSession()->setFlash('warning',Yii::t('app','Невозможно удалить заявку'));
        }
        return $this->redirect(['carriage-contract']);
    }
    
    public function actionActiveCarriageContract($id){
        if(($model = UserCarriageContract::findOne($id))==null){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if(!$model->canActive()){
            Yii::$app->getSession()->setFlash('warning',Yii::t('app','Невозможно активировать заявку'));
            return $this->redirect(['view-carriage-contract','id'=>$model->id]);
        }
        if(!$model->checkUserMoneyForPay()){
            Yii::$app->getSession()->setFlash('warning',Yii::t('app','На счету пользователя недостаточно денег для оплаты заявки'));
            return $this->redirect(['view-carriage-contract','id'=>$model->id]);
        }
        $model->scenario = UserCarriageContract::SCENARIO_MAKE_ACTIVE;
        if($model->load(Yii::$app->request->post())&&$model->actActive()){
                Yii::$app->getSession()->setFlash('success',Yii::t('app','Заявка успешно активирована'));
                return $this->redirect(['view-carriage-contract','id'=>$model->id]);
        }else {
            return $this->render('carriage/activate', ['model' => $model]);
        }

    }
    
    public function actionCompleteCarriageContract($id){
        if(($model = UserCarriageContract::findOne($id))==null){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if($model->canComplete() && $model->actComplete()){
            Yii::$app->getSession()->setFlash('success',Yii::t('app','Статус заяки успешно изменен на выполнен'));
        }else{
            Yii::$app->getSession()->setFlash('warning',Yii::t('app','Невозможно завершить выполнение заявки'));
        }
        return $this->redirect(['view-carriage-contract','id'=>$model->id]);
    }
    
    public function actionCanceledCarriageContract($id){
        if(($model = UserCarriageContract::findOne($id))==null){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if($model->canCanceled() && $model->actCanceled()){
            Yii::$app->getSession()->setFlash('success',Yii::t('app','Заявка успешно отменена'));
        }else{
            Yii::$app->getSession()->setFlash('warning',Yii::t('app','Невозможно отменить заяку'));
        }
        return $this->redirect(['view-carriage-contract','id'=>$model->id]);
    }
   
    private function findModel($id){
        if(($model=User::findOne($id))==NULL){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $model;
    }

}
