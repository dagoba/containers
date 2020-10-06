<p>Номер счета клиента: <b><?=$application->account_id;?></b></p> 
<p>Платежная система: <b><?= $application->paymentSystemName;?></b></p> 
<p>Сумма: <b><?= $application->amount;?></b></p>
<p>Номер заявки: <b><?= $application->id;?></b></p>
<?=($application->usercomment!=NULL||$application->usercomment!='')? '<p>Примечание: '.$application->usercomment.'</p>': '';?>
