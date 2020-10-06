<p>
    <b>Модератором:</b> 
    <br>
        ID:<?=$model->moder_id?>
    <br>
        Имя:<?=$model->moder->username?>
    <br>
        Email:<?=$model->moder->email?>    
</p>
<p>Выполнена операция <b><?=$model->operationType?></b> на аккаунт пользователя:
    <br>
        ID:<?=$model->_user->id?>
    <br>
        Имя:<?=$model->_user->username?>
    <br>
        Email:<?=$model->_user->email?>  
</p>
<p>
    <b>Информация о операции:</b> 
    <br>
        ID:<?=$model->id?>
    <br>
        Сумма:<?=$model->amount?>
    <br>
        Описание:<?=$model->description?>  
</p>