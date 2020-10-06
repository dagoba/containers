<?php

namespace app\modules\user\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\data\ActiveDataProvider;
use app\modules\user\models\User;
use yii\web\NotFoundHttpException;
use app\modules\user\models\UserSearch;
use app\modules\user\models\PasswordChangeForm;
use app\modules\user\models\ModerFinancialOperations;
use app\modules\user\models\ApplicationwithdrawalSearch;
use app\modules\platforms\models\ApplicationpartnerwithdrawalSearch;
use app\modules\user\models\ApplicationDepositSearch;
use app\modules\user\models\ApplicationHandDepositSearch;
use yii\web\Response;
use app\modules\user\models\UsertransactionSearch;
use app\modules\user\models\UserChangeEmail;
use app\modules\geo\models\GeoCountry;
use app\modules\user\models\UserContact;
use app\modules\user\models\UserClocks;
use app\modules\user\models\UserCarriageContract;
use app\modules\user\models\UserCarriageContractSearch;
use app\modules\user\models\UserContactType;
use app\modules\user\models\UserCarriageTicket;

class ProfileController extends Controller
{
    public function behaviors()
    {
        return 
        [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'change-password',
                            'change-personal-data',
                            'change-email',
                            'get-regions-list',
                            'get-cities-list',
                            'create-contact',
                            'edit-contact',
                            'delete-contact',
                            'add-clock',
                            'delete-clock',
                            'my-routes',
                            'view-route',
                            'dont-show-hint',
                            'get-contact-hint',
                            'upload-avatar',
                            'delete-avatar',
                            'create-ticket'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => [
                            'management',
                            'login-by-user',
                            'user-edit',
                            'user-financial-operations',
                            'applications-withdrawal-main',
                            'applications-deposit-auto',
                            'transaction-main',
                            'logout-all-devices',
                            'upload-avatar',
                        ],
                        'allow' => true,
                        'roles' => ['moder'],
                    ],
                ],
            ]
        ];
    }

    public function actionIndex() {  
        $user = $this->findModel();
        $queryContact = UserContact::find()
                 ->where(['user_id'=>$user->id]);
        $contactDataProvider = new ActiveDataProvider([
            'query' => $queryContact,
            'sort' =>['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $queryClock = UserClocks::find()
                 ->where(['user_id'=>$user->id]);
        $clockDataProvider = new ActiveDataProvider([
            'query' => $queryClock,
            'sort' =>['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        if ($user->id!==Yii::$app->user->identity->getId())
            throw new ForbiddenHttpException('Access denied');
        return $this->render('index', [
            'model' => $user,
            'contactDataProvider'=>$contactDataProvider,
            'clockDataProvider'=>$clockDataProvider
            ]);
    }

     public function actionCreateTicket(){
        $model = new UserCarriageTicket();
        $model->scenario = UserCarriageTicket::SCENARIO_CREATE_APPLICATION;
        if($model->save()&&$model->sendModerInfoMail()){
            Yii::$app->getSession()->setFlash('success',Yii::t('main', 'Заявка на перевозку успешно добавлена'));
            return $this->redirect(['/cabinet']);
        } else{
            Yii::$app->getSession()->setFlash('warning',Yii::t('main', 'Произошла ошибка. Обратитесь в поддержку'));
        }
        return $this->render('ticket/create', ['model' => $model]);     
    }

    public function actionUploadAvatar(){
        $model =  $this->findModel();
        $imageFile = \yii\web\UploadedFile::getInstance($model, 'avatarFile');
        $directory = Yii::getAlias('@app/web/uploads/avatars/');
        if ($imageFile) {
            $oldAvatar = $model->avatar;
            $uid = uniqid();
            $fileName = $uid . '.' . $imageFile->extension;
            $filePath = $directory . $fileName;
            $model->scenario = User::SCENARIO_UPDATE_AVATAR;
            $model->avatarFile = $imageFile;
            $model->avatar = $fileName;
            if ($model->validate()&&$model->save()&&$imageFile->saveAs($filePath)) {
                $path = '/img/temp/' . Yii::$app->session->id . DIRECTORY_SEPARATOR . $fileName;
                    if(!empty($oldAvatar)&&file_exists (Yii::getAlias('@app/web/uploads/avatars/').$oldAvatar)){
                        unlink(Yii::getAlias('@app/web/uploads/avatars/').$oldAvatar);
                    }
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'name' => $fileName,
                        'size' => $imageFile->size,
                        'url' => $path,
                        'thumbnailUrl' => Yii::getAlias('@web/web/uploads/avatars/').$fileName,
                        'deleteUrl' =>$fileName,
                        'deleteType' => 'POST'
                    ];
            }
        }
        return false;
    }
    
    public function actionDeleteAvatar($name){
        
        $model = $this->findModel();
        if ($model->id!==Yii::$app->user->identity->getId()||$model->avatar!=$name) { 
            throw new ForbiddenHttpException('Access denied');
        }
        if ($model->removeAvatar()){
            return true;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

     public function actionGetContactHint($id){
        if(!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if(($model = UserContactType::findOne($id))==null){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $model->example;
    }

    public function actionGetRegionsList($country_id,$selected=-1){
        if(!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if(($country = GeoCountry::findOne(intval($country_id)))==NULL){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if(!($regions=$country->regions)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if(count($regions)>0){
            $selectedFlag='';
            echo '<option value="">Укажите регион</option>';
            foreach($regions as $region){
                if($selected!=-1&&$selected==$region->id){
                    $selectedFlag='selected=""';
                }else {
                    $selectedFlag='';
                }
                echo "<option ".$selectedFlag." value='".$region->id."'>".
                        $region->name_ru.
                    "</option>";
            }
        } else{
            echo "<option>-</option>";
        }
    }
    
    public function actionGetCitiesList($region_id,$selected=-1){
        if(!Yii::$app->request->isAjax){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if(($country=  \app\modules\geo\models\GeoRegions::findOne(intval($region_id)))==NULL){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if(!($cities=$country->cities)){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if(count($cities)>0){
            $selectedFlag='';
            echo '<option value="">Укажите город</option>';
            foreach($cities as $city){
                if($selected!=-1&&$selected==$city->id){
                    $selectedFlag='selected=""';
                } else {
                    $selectedFlag='';
                }
                echo "<option ".$selectedFlag." value='".$city->id."'>".
                        $city->name_ru.
                    "</option>";
            }
        } else {
            echo "<option>-</option>";
        }
    }

    public function actionChangePassword(){
        if(!Yii::$app->request->isAjax ){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $user = $this->findModel();
        if ($user->id!==Yii::$app->user->identity->getId()) { 
            throw new ForbiddenHttpException('Access denied');
        }
        $model = new PasswordChangeForm($user);
        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            Yii::$app->getSession()->setFlash('success','Пароль успешно изменен');
            return $this->redirect(['index']);
        }  
        Yii::$app->response->format = Response::FORMAT_JSON;
        return \yii\bootstrap\ActiveForm::validate($model); 
    }
    
    public function actionChangeEmail(){
        if(!Yii::$app->request->isAjax ){
            throw new ForbiddenHttpException('Access denied');
        }
        $user = $this->findModel();
        if ($user->id!==Yii::$app->user->identity->getId()) {
            throw new ForbiddenHttpException('Access denied');
        }
        if(!UserChangeEmail::checkTryLimit()){
            throw new NotFoundHttpException('The requested page does not exist.'); 
        }
        $model=new UserChangeEmail();
        $model->scenario = UserChangeEmail::SCENARIO_CREATE;
        if($model->load(Yii::$app->request->post())&&$model->save()) {
            Yii::$app->getSession()->setFlash('success',
                'На вашу старую почту было выслано подтверждение для смены Email. Обращаем Ваше внимание на то, что ссылка действует в течении '.
                    UserChangeEmail::LINK_PERIOD_HOUR.' '.
                    \app\components\Fonetika::declOfNum(UserChangeEmail::LINK_PERIOD_HOUR,['часа','часов']));
        }  
        Yii::$app->response->format = Response::FORMAT_JSON;
        return \yii\bootstrap\ActiveForm::validate($model);
    }
    
    public function actionChangePersonalData(){
        if(!Yii::$app->request->isAjax ){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $model = $this->findModel();
        if ($model->id!==Yii::$app->user->identity->getId()) {
            throw new ForbiddenHttpException('Access denied');
        }
        $model->scenario = User::SCENARIO_UPDATE_PROFILE;
        $model->setLogString(); 
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success','Данные успешно изменены');
            return $this->redirect(['index']);
        }  
        Yii::$app->response->format = Response::FORMAT_JSON;
        return \yii\bootstrap\ActiveForm::validate($model);      
    }
    
    public function actionCreateContact(){
        $model = new UserContact();
        if($model->load(Yii::$app->request->post())&&$model->save()){
            Yii::$app->getSession()->setFlash('success',' Контакт успешно добавлен');
            return $this->redirect(['/user/profile/index']);
        }
        return $this->render('contact/create', ['model' => $model]);     
    }
    
    public function actionEditContact($id){
        if(($model = UserContact::findOne($id)) == null){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if (Yii::$app->user->id!= $model->user_id){
            throw new ForbiddenHttpException('Access denied');
        }
        if($model->load(Yii::$app->request->post())&&$model->save()){
            Yii::$app->getSession()->setFlash('success',' Контакт успешно изменен');
            return $this->redirect(['/user/profile/index']);
        }
        return $this->render('contact/edit', ['model' => $model]); 
    }
    
    public function actionDeleteContact($id){
        if(($model = UserContact::findOne($id)) == null){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if (Yii::$app->user->id!= $model->user_id){
            throw new ForbiddenHttpException('Access denied');
        }
        if($model->canDelete()&&$model->delete()){
            Yii::$app->getSession()->setFlash('success','Контакт успешно удален');
        }
        else{
            Yii::$app->getSession()->setFlash('error','Произошла ошибка');
        }
        return $this->redirect(['index']);
    }
    
    public function actionAddClock(){
        $model = new UserClocks();
        if($model->load(Yii::$app->request->post())&&$model->save()){
            Yii::$app->getSession()->setFlash('success',' Часы успешно добавлены');
            return $this->redirect(['/user/profile/index']);
        }
        return $this->render('clock/_form', ['model' => $model]);     
    }
    
    public function actionDeleteClock($id){
        if(($model = UserClocks::findOne($id)) == null){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if (Yii::$app->user->id!= $model->user_id){
            throw new ForbiddenHttpException('Access denied');
        }
        if($model->delete()){
            Yii::$app->getSession()->setFlash('success','Часы успешно удалены');
        }
        else{
            Yii::$app->getSession()->setFlash('error','Произошла ошибка');
        }
        return $this->redirect(['index']);    
    }
    
    public function actionMyRoutes(){
        $searchModel = new UserCarriageContractSearch();
        $dataProvider = $searchModel->userSearch(Yii::$app->request->queryParams);
        return $this->render('route/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionViewRoute($id){
        if(($model = UserCarriageContract::findOne($id))==null){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if (Yii::$app->user->id!= $model->user_id){
            throw new ForbiddenHttpException('Access denied');
        }
        return $this->render('route/view', ['model' => $model]);
    }

    public function actionDontShowHint($type){
        if(!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if($type!='empty-profile'){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => 'hint-'.$type,
            'value' => '1',
            'expire'=> time()+60*60*24
        ]));
    }
    /*---------------------------------MODER----------------------------------*/
    public function actionManagement() {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('moder/management', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider]);
    }
    
    public function actionLoginByUser($id){
        $user = $this->findModel($id);
        Yii::$app->user->logout();
        if(Yii::$app->user->login($user, 60*5)){
            return $this->goBack();
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionUserEdit($id){
        $user=$this->findModel($id);
        $user->scenario = User::SCENARIO_MODER_UPDATE;
        if ($user->load(Yii::$app->request->post()) && $user->save()) {
            Yii::$app->getSession()->setFlash('success',
                'Информация о пользователе успешно отредактирована');
            return $this->redirect(['user-details','id'=>$user->id]);
        } else {
            return $this->render('moder/userEdit', ['model' => $user]);
        }
    }
    
    public function actionUserFinancialOperations($id){
        $user=$this->findModel($id);
        $model=new ModerFinancialOperations();
        $model->initUser($user->id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()&&$model->execution()) {
            Yii::$app->getSession()->setFlash('success','Операция выполнена успешно');
            return $this->redirect(['management']);
        } else {
            return $this->render('moder/userFinancialOperations', [
                'model' => $model,
                'user'=>$user
            ]);
        }
    }
    
    public function actionApplicationsWithdrawalMain($id){
        $user=$this->findModel($id);
        $searchModel = new ApplicationwithdrawalSearch();
        $dataProvider = $searchModel->searchByUser(Yii::$app->request->queryParams,$user->account->user_id);
        return $this->render('moder/applicationWithdrawal/_view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user'=>$user
        ]);
    }
    
    public function actionApplicationsWithdrawalPartner($id){
        $user=$this->findModel($id);
        $searchModel = new ApplicationpartnerwithdrawalSearch();
        $dataProvider = $searchModel->searchByUser(Yii::$app->request->queryParams,$user->account->user_id);
        return $this->render('moder/applicationWithdrawalPartner/_view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user'=>$user
        ]);
    }
    
    public function actionApplicationsDepositAuto($id){
        $user=$this->findModel($id);
        $searchModel = new ApplicationDepositSearch();
        $dataProvider = $searchModel->searchByUser(Yii::$app->request->queryParams,$user->account->user_id);
        return $this->render('moder/applicationDepositAuto/_view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user'=>$user
        ]);
    }
    
    public function actionApplicationsDepositHand($id){
        $user=$this->findModel($id);
        $searchModel = new ApplicationHandDepositSearch();
        $dataProvider = $searchModel->searchByUser(Yii::$app->request->queryParams,$user->account->user_id);
        return $this->render('moder/applicationDepositHand/_view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user'=>$user
        ]);
    }
    
    public function actionTransactionMain($id){
        $user=$this->findModel($id);
        $searchModel = new UsertransactionSearch();
        $dataProvider = $searchModel->searchByUser(Yii::$app->request->queryParams,$user->account->user_id);
        return $this->render('moder/transactionMain/_view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user'=>$user
        ]);
    }
    /*---------------------------------MODER----------------------------------*/

    private function findModel($id = false){
        if(!$id){
            $id = Yii::$app->user->identity->getId();
        }
        if(($model=User::findOne(Yii::$app->user->identity->getId()))==NULL)
            throw new NotFoundHttpException('The requested page does not exist.');
        return $model;
    }
}
