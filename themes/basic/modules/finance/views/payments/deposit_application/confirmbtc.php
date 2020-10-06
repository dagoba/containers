<div style="height: 50px;"></div>
<div class="row">
<div class="col-sm-12">
    <div class="col-sm-2"></div>
        <div class="col-sm-8 user_panel">
            <h1 class="contact_h1">Подтверждение платежа</h1>
            <div class="second_line" style="margin-top: 20px;">
                <div class="alert alert-info" role="alert">
                  <p class="">Проверьте информацию в заявке на пополнение</p>
                </div> 
            </div>
            <div class="panel panel-default">
              <div class="panel-heading">Подтверждение платежа:</div>
                <table class="table">
                    <tr><td>1</td><td>Номер транзакции:</td><td><?=$model->id;?></td></tr>
                    <tr><td>2</td><td>Вы пополняете счет на: </td><td><?=$model->amount;?>$</td></tr>
                    <tr><td>3</td><td>Платежная система:</td><td><?=$model->PaymentSystem;?></td></tr>
                    <tr><td>4</td><td>Курс BTC:</td><td><?=$model->btc_curs;?></td></tr>
                    <tr><td>5</td><td>Сумма в BTC:</td><td><?=number_format($model->btc_amount, 8, ".","");?></td></tr>
                </table>
              <p class="text-center">Переведите <?=number_format($model->btc_amount, 8, ".","");?> на адрес ниже или воспользуйтесь Qrcode</p>
                <?= $model->btc_form;?>
            </div>
        </div>
        <div class="col-sm-2"></div>
    </div>
</div>
