<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;

$this->title = 'Маршрут для клиента: ';
$this->params['breadcrumbs'][] = $this->title;



?>
<div class="container">
	<div class="site-contact">



	    
	    <div class="searates-planner">
<!--	   <h1>Container tracking</h1>
	        <iframe src="https://sirius.searates.com/tracking?container=&sealine=" width="100%" height="100%" scrolling="No" frameborder="0" webkitallowfullscreen="true" mozallowfullscreen="true" allowfullscreen="true"> </iframe> -->
	         


<?= 
Html::encode($this->title);
if (Yii::$app->user->getId() == 1) { 

echo '<iframe src="https://www.searates.com/route-planner/viewer-frame?sid=06c5e68cc684ee121c65303632b12250" width="100%" height="700" frameborder="0" align="middle" scrolling="No"> </iframe>';

} elseif (Yii::$app->user->getId() == 2) {

echo '<iframe src="https://www.searates.com/route-planner/viewer-frame?sid=0f5fd9b1006ea8f03a7734f3766087b1" width="100%" height="700" frameborder="0" align="middle" scrolling="No"> </iframe>	';

}
elseif (Yii::$app->user->getId() == 3) {

echo '<iframe src="https://www.searates.com/ru/route-planner/frame?url=http://sealines.company/web/site/route_page" width="100%" height="700" frameborder="0" align="middle" scrolling="No"> </iframe>';

}
else
{

}
?>


	    </div>
	</div>
</div>

