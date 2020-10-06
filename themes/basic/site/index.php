<?php
 use yii\bootstrap\Html;
$this->title = Yii::$app->name;
?>
<style type="text/css">
    .containersl {
        width:100%;
        padding: 0px;
    }
</style>
<div class="shippiting">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h1>SEA LINES</h1>
                <h3><?= Yii::t('app','Морские перевозки')?></h3>
                <p><?= Yii::t('app','Мы имеем возможность предложить вам выгодные условия<br/>
                    доставки и оптимальные для вас маршруты, соединяющие<br/>
                    страны и континенты.')?></p>
                <div class="shippiting_button">
                    <div class="clear"></div>
                    <!--<a href="#"><?= Yii::t('app','Выбрать маршрут')?></a>-->
                <button type="button" data-toggle="modal" data-target="#myModal"><?= Yii::t('main','Видео презентация');?></button>

                <div id="myModal" class="modal fade">
                  <div class="modal-footer"><!--<button class="close_mpdal" type="button" data-dismiss="modal"></button>--></div>
                  <div id="vidos" style="margin: 0 auto;width: 970px;">
                    <iframe width="950" height="534" src="https://www.youtube.com/embed/<?= Yii::$app->language == 'ru' ? 'TvvGcHNKQHU' : 'b2OoRQz9YhY'?>" frameborder="0" allowfullscreen></iframe>
                  </div>
                </div>
                <script type="text/javascript">
                  $('#myModal').click(function(){
                    if($('#myModal').css('display') == 'none'){
                      $('#vidos').html('')
                    }else{
                      $('#vidos').html(' <iframe width="950" height="534" src="https://www.youtube.com/embed/<?= Yii::$app->language == 'ru' ? 'TvvGcHNKQHU' : 'b2OoRQz9YhY'?>" frameborder="0" allowfullscreen></iframe>')
                    }
                  });
                </script>
                    <?php if(!Yii::$app->user->id):?>
                        <?=Html::a(Yii::t('app','Регистрация'),['/signup']);?>
                    <?php endif;?>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="col-md-5">
            
            </div>
        </div>
    </div>
</div>

<div class="about_use">
    <div class="container">
        <div class="row">
            <p class="about_use_first text-center"><?= Yii::t('app','Морские контейнерные перевозки')?></p>
            <h3 class="text-center"><?= Yii::t('app','Мы открываем новые возможноcти.')?></h3>
            <div class="col-md-12">
                <div class="col-md-6 about_use_text">
                    <p><?= Yii::t('app','При сотрудничестве с нами вам доступны перевозки из Китая,
                    Юго-Восточной Азии, Ближнего Востока, Индии, Африки, Северной
                        Америки и стран Европы в Россию.')?></p>
                    <p><?= Yii::t('app','Возможна доставка «от двери до двери». Для получения грузов
                    используется прибалтийские и европейские порты и склады.')?></p>
                    <p><?= Yii::t('app','Морские перевозки позволяют организовать доставку всевозможных
                    грузов: как не габаритных, тяжелых или требующих
                    особого обращения, так и грузов в контейнерах.')?></p>
                </div>
                <div class="col-md-6">
                    <div class="about_use_img_map"></div>
                </div>
                <div class="col-md-12">
                    <button type="button" data-toggle="modal" class="about_use_a" data-target="#new_opportunities"><?= Yii::t('app','Подробнее');?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="advantages">
    <div class="container">
        <div class="row">
            <p class="advantages_first text-center"><?= Yii::t('app','Морские контейнерные перевозки')?></p>
            <h3 class="text-center"><?= Yii::t('app','Почему стоит доверять нам перевозку?')?></h3>
            <div class="col-md-12">
                <div class="col-md-4 advantages_block">
                    <div class="advantages_img advantages_icon1"></div>
                    <h5 class="text-center"><?= Yii::t('app','Помощь в оформлении')?></h5>
                    <p class="text-center"><?= Yii::t('app','Помощь в оформлении экспортных и таможенных документов.')?></p>
                </div>
                <div class="col-md-4 advantages_block">
                    <div class="advantages_img advantages_icon2"></div>
                    <h5 class="text-center"><?= Yii::t('app','Наработанные схемы')?></h5>
                    <p class="text-center"><?= Yii::t('app','Отработанные логические схемы по доставке грузов из любой точки мира.')?></p>
                </div>
                <div class="col-md-4 advantages_block">
                    <div class="advantages_img advantages_icon3"></div>
                    <h5 class="text-center"><?= Yii::t('app','Доверие клиентов')?></h5>
                    <p class="text-center"><?= Yii::t('app','Большое доверие клиентов на протяжении многих лет.')?></p>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-4 advantages_block">
                    <div class="advantages_img advantages_icon4"></div>
                    <h5 class="text-center"><?= Yii::t('app','Доступные тарифы')?></h5>
                    <p class="text-center"><?= Yii::t('app','Доступные тарифы на все виды перевозок морских контейнеров.')?></p>
                </div>
                <div class="col-md-4 advantages_block">
                    <div class="advantages_img advantages_icon5"></div>
                    <h5 class="text-center"><?= Yii::t('app','Ведущие грузоперевозчики')?></h5>
                    <p class="text-center"><?= Yii::t('app','Наша компания работает с ведущими грузоперевозчиками Европы и Азии уже много лет.')?></p>
                </div>
                <div class="col-md-4 advantages_block">
                    <div class="advantages_img advantages_icon6"></div>
                    <h5 class="text-center"><?= Yii::t('app','Грузоперевозки по всему миру')?></h5>
                    <p class="text-center"><?= Yii::t('app','Эффективная транспортная система по всему миру')?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="about_company">
    <div class="container">
        <div class="row">
            <p class="about_company_first text-center"><?= Yii::t('app','Морские контейнерные перевозки')?></p>
            <h3 class="text-center"><?= Yii::t('app','Наша компания работает с ведущими грузоперевозчиками Европы и Азии уже много лет.')?></h3>
            <div class="col-md-12">
                <!-- <button class="about_use_a" type="button" data-toggle="modal" data-target="#partners"><?= Yii::t('app','Подробнее')?></button> -->
                <a href="#footer_blue" class="about_use_a" data-toggle="modal" data-target="#partners_certificate"><?= Yii::t('app','Подробнее');?></a>
            </div>
        </div>
    </div>
</div>

<div class="logical_service">
    <div class="container">
        <div class="row">
            <h3 class="text-center"><?= Yii::t('app','Логический сервис')?></h3>
            <p class="text-center"><?= Yii::t('app','Комплекс услуг, оказываемых производителем или сторонней фирмой в процессе доставки ресурсов потреблению. Логический сервис включает в себя 3 группы работ')?></p>
            <div class="col-md-12">
                <div class="col-md-4 logical_service_point">
                    <div>01 <span>- • - • - • - • - • - • - • - • - • - • - • -</span></div>
                    <h4><?= Yii::t('app','Предпродажные')?></h4>
                    <p><?= Yii::t('app','Работы по созданию логического сервиса, определение политики фирмы в сфере логического сервиса')?></p>
                </div>
                <div class="col-md-4 logical_service_point">
                    <div>02 <span>- • - • - • - • - • - • - • - • - • - • - • -</span></div>
                    <h4><?= Yii::t('app','Продажные')?></h4>
                    <p><?= Yii::t('app','Предоставлении информации о передвижении товара, подбор ассортимента, упаковка.')?></p>
                </div>
                <div class="col-md-4 logical_service_point">
                    <div>03</div>
                    <h4><?= Yii::t('app','Послепродажные')?></h4>
                    <p><?= Yii::t('app','Гарантия, обмен товаров, предоставление документаций, обучение пользователей, реализация запчастей.')?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="types_of_containers">
    <div class="container">
        <div class="row">
            <p class="types_of_containers_first text-center"><?= Yii::t('app','Морские контейнерные перевозки')?></p>
            <h3 class="text-center"><?= Yii::t('app','Типы контейнеров для Вашего груза')?></h3>
            <div class="col-md-12">
                <div class="your-class" style="margin: 0;">
                    <!-- <div class="slider slide_0">
                        <h4  class="text-center text_slider_h4"><?= Yii::t('app','Универсальный контейнер')?></h4>
                        <pre class="text-center text_slider"><?= Yii::t('app','площадь  /  высота  /  ширина')?></pre>

                        <div class="slide_link">
                            <img src="http://sealines.company/web/images/slider/universal.png" class="slide_img"/>
                            <div><?= Yii::t('app','Для перевозки "плотных" или тяжеловестных
                                грузов небольшого размера больше подхлдят
                                20-ти футовые контейнеры.')?></div>
                            <a href="#" class="about_company_a mc"><?= Yii::t('app','Подробнее')?></a>
                        </div>
                    </div> -->
                    <div class="slider slide_0">
                        <h4  class="text-center text_slider_h4"><?= Yii::t('app','20-ти футовый контейнер')?></h4>
                        <p class="text-center text_slider"><?= Yii::t('main','Внутренняя длина')?>: 5.895 m / <?= Yii::t('main','Внутренняя ширина')?>: 2.350 m / <?= Yii::t('main','Внутренняя высота')?>: 2.392 m</p>

                        <div class="slide_link">
                            <?=Html::img('@web/images/slider/20_pound.png',['class'=>'slide_img']);?>
                            <div><?= Yii::t('slider','Стандартные контейнеры также называются универсальными контейнерами.')?></div>
                            <button type="button" data-toggle="modal" class="about_company_a mc" data-target="#20_pound_desc"><?= Yii::t('app','Подробнее')?></button>
                        </div>
                    </div>
                    <div class="slider slide_1">
                        <h4  class="text-center text_slider_h4"><?= Yii::t('app','40-ка футовый контейнер')?></h4>
                        <p  class="text-center text_slider"><?= Yii::t('main','Внутренняя длина')?>: 12.029 m / <?= Yii::t('main','Внутренняя ширина')?>: 2.350 m / <?= Yii::t('main','Внутренняя высота')?>: 2.392 m</p>
                        <div class="slide_link">
                            <?=Html::img('@web/images/slider/40_pound.png',['class'=>'slide_img']);?>
                            <div><?= Yii::t('slider','Стандартные контейнеры также называются универсальными контейнерами.')?></div>
                            <button type="button" data-toggle="modal" class="about_company_a mc" data-target="#20_pound_desc"><?= Yii::t('app','Подробнее')?></button>
                        </div>
                    </div>
                    <div class="slider slide_2">
                        <h4  class="text-center text_slider_h4"><?= Yii::t('app','Рефрижераторный контейнер')?></h4>
                        <p  class="text-center text_slider"><?= Yii::t('main','Внутренняя длина')?>: 5.724 m / <?= Yii::t('main','Внутренняя ширина')?>: 2.286 m / <?= Yii::t('main','Внутренняя высота')?>: 2.014 m</p>
                        <div class="slide_link">
                            <?=Html::img('@web/images/slider/refrigerated_container.png',['class'=>'slide_img']);?>
                            <div><?= Yii::t('slider','Рефрежираторная установка размещена таким образом, чтобы внешние размеры контейнера соответствовали стандартам ISO и установка оптимально помещалась вовнутрь.')?></div>
                            <button type="button" data-toggle="modal" class="about_company_a mc" data-target="#refrigerated_container"><?= Yii::t('app','Подробнее')?></button>
                        </div>
                    </div>
                    <div class="slider slide_3">
                        <h4  class="text-center text_slider_h4"><?= Yii::t('app','OPEN TOP контейнер')?></h4>
                        <p  class="text-center text_slider"><?= Yii::t('main','Внутренняя длина')?>: 5.888 m / <?= Yii::t('main','Внутренняя ширина')?>: 2.345 m / <?= Yii::t('main','Внутренняя высота')?>: 2.315 m</p>

                        <div class="slide_link">
                            <?=Html::img('@web/images/slider/open_top.png',['class'=>'slide_img']);?>
                            <div><?= Yii::t('slider','Контейнер имеет отличительную структуру. Крыша состоит из передвижных рам и тента. Дверная перемычка может быть вращающейся.')?></div>
                            <button type="button" data-toggle="modal" class="about_company_a mc" data-target="#open_top"><?= Yii::t('app','Подробнее')?></button>
                        </div>
                    </div>
                    <div class="slider slide_4">
                        <h4  class="text-center text_slider_h4"><?= Yii::t('app','FLAT RACK контейнер')?></h4>
                        <p  class="text-center text_slider"><?= Yii::t('main','Внутренняя длина')?>: 5.698 m / <?= Yii::t('main','Внутренняя ширина')?>: 2.230 m / <?= Yii::t('main','Внутренняя высота')?>: 2.255 m</p>
                        <div class="slide_link">
                            <?=Html::img('@web/images/slider/flat_rack.png',['class'=>'slide_img']);?>
                            <div><?= Yii::t('slider','Погрузочный поддон состоит из напольного перекрытия с высокой несущей способностью и стального каркаса с настилом из мягкой древесины и двух перегородок')?></div>
                            <button type="button" data-toggle="modal" class="about_company_a mc" data-target="#flat_rack"><?= Yii::t('app','Подробнее')?></button>
                        </div>
                    </div>
                    <div class="slider slide_5">
                        <h4  class="text-center text_slider_h4"><?= Yii::t('app','TANK-контейнер')?></h4>
                        <p class="text-center text_slider"><?= Yii::t('main','Внутренняя длина')?>: 6.058 m / <?= Yii::t('main','Внутренняя ширина')?>: 2.438 m / <?= Yii::t('main','Внутренняя высота')?>: 2.438 m</p>
                        <div class="slide_link">
                            <?=Html::img('@web/images/slider/tank_container.png',['class'=>'slide_img']);?>
                            <div><?= Yii::t('slider','Такие контейнеры должны быть загружены не менее чем на 80 процентов для предотвращения колебания жидкого груза. С другой стороны, контейнеры не должны заполняться больше чем на 95 процентов, в противном случае возникнет нехватка свободного пространства')?></div>
                            <button type="button" data-toggle="modal" class="about_company_a mc" data-target="#tank_container"><?= Yii::t('app','Подробнее')?></button>
                        </div>
                    </div>

                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $('.your-class').slick({
                            dots: true,
                            infinite: true,
                            speed: 300,
                            slidesToShow: 1,
                            adaptiveHeight: true
                        });
                        // $('#slick-slide00').append('<p>Универсальный</p>');
                        $('.your-class #slick-slide00').append('<p><?= Yii::t('app','20-ти футовый')?></p>');
                        $('.your-class #slick-slide01').append('<p><?= Yii::t('app','40-ка футовый')?></p>');
                        $('.your-class #slick-slide02').append('<p><?= Yii::t('app','Рефрижераторный')?></p>');
                        $('.your-class #slick-slide03').append('<p><?= Yii::t('app','OPEN TOP')?></p>');
                        $('.your-class #slick-slide04').append('<p><?= Yii::t('app','FLAT RACK')?></p>');
                        $('.your-class #slick-slide05').append('<p><?= Yii::t('app','ТАНК-контейнер')?></p>');
                        $('.your-class .slick-list').css('height: 600px;');
                    });

                </script>
            </div>
        </div>
    </div>
</div>

<div class="online_consultation">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3><?= Yii::t('app','Online консультация 24/7')?></h3>
                <p><?= Yii::t('app','Это не только позволит вам получить всю необходимую информацию, но<br/>
                также даст возможность получить цены и сделать заказ в один клик.')?></p>
                <div class="shippiting_button">
                    <div class="clear"></div>

                    <button type="button" data-toggle="modal" data-target="#сonsultation"><?= Yii::t('app','Консультация')?></button>
                    <!-- <a href="#"><?= Yii::t('app','Консультация')?></a> -->
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="prices_and_tariffs">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="text-center"><?= Yii::t('app','Цены и тарифы')?></h3>
                <p class="text-center"><?= Yii::t('app','Наш сервис является бесплатным. В отличии от многих других компаний, мы не взимаем платы за нашу работу и не прибавляем дополнительную комиссию к ставке.')?></p>
            </div>
            <div class="col-md-12">
                <div class="col-md-6">
                    <?= Yii::t('app','Цены на нашем сайте очень конкурентоспособны. Все цены на сайте sealines.company указаны за контейнер и включают НДС и другие налоги (которые могут изменятся), если не указано иначе на нашем веб-сайте или в электронном подтверждении бронирования.')?>
                </div>
                <div class="col-md-6">
                    <?= Yii::t('app','Иногда на нашим веб-сайте предоставляются более низкие тарифы для определенных грузов, однако данные тарифы, предлагаемые агентами и экспедиторами могут иметь специальные ограничения и правила, например, при отмене или возврате средств за бронирование.')?>
                </div>
            </div>
            <div class="col-md-12" style="height: 50px;">
                <!-- <a href="#" class="about_use_a"><?= Yii::t('app','Посчитать тариф')?></a> -->
            </div>
        </div>
    </div>
</div>

<div class="our_offers">
    <div class="container">
        <div class="row">
            <h3><?= Yii::t('app','Также вы можете использовать наиболее популярные приложения на нашем сайте:')?></h3>
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="our_offers_img1"></div>
                </div>
                <div class="col-md-6">
                    <h3><?= Yii::t('app','Отслеживание контейнера')?></h3>
                    <p><?= Yii::t('app','Система слежения позволяет определить текущую позицию контейнера на карте мира ( Google карты ) и определить порт и время, потраченное в порту перегрузки.')?></p>
                        <?=Html::a(Yii::t('app','Подробнее'),['/user/profile/my-routes'],['class'=>'about_company_a']);?>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-6">
                    <h3><?= Yii::t('app','Расстояние и время')?></h3>
                    <p><?= Yii::t('app','Если Вы нуждаетесь в сервисе от порта до порта, выберите любое место погрузки и конечный пункт назначения и получите краткое описание выбранного маршрута, транзитного времени и изображения на карте Google.')?></p>
                    <?php if(Yii::$app->user->isGuest):?>
                        <?=Html::a(Yii::t('app','Подробнее'),['/signup'],['class'=>'about_company_a']);?>
                    <?php else:?>
                        <?=Html::a(Yii::t('app','Подробнее'),['/site/route'],['class'=>'about_company_a']);?>
                    <?php endif;?>
                </div>
                <div class="col-md-6">
                    <div class="our_offers_img2"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if(Yii::$app->user->isGuest) :?>
<div class="registration_block">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-6">
                    <h3><?= Yii::t('app','Создайте свой <span>бесплатный</span> аккаунт')?></h3>
                    <p><?= Yii::t('app','Регистрация расширяет Ваши возможности!')?></p>
                    <div class="registration_info1"><?= Yii::t('app','Сохраните подходящие тарифы и маршруты,<br/> просматривайте уже осуществившие.')?></div>
                    <div class="registration_info2"><?= Yii::t('app','Отслеживайте перевозки, время и даты рейсов у<br/> себя в личном кабинете.')?></div>
                </div>
                <div class="col-md-6 simple-signup">
                   <?=$this->render('@app/modules/user/views/default/simple_signup',[
            'model'=>new \app\modules\user\models\SignupForm()],true);?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif;?>

<div id="footer_blue" class="footer_blue">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <?=Html::img('@web/images/pic/logo_footer.png',['class'=>'center','style'=>'margin: 20px auto;']);?>
                <p>Chiltern House, 45 Station Road, Henley-On-Thames, Oxfordshire, RG9 1AT</p>
                <br/>
                <p class="">Сopyright 2017. SeaLines.</p>
            </div>
            <div class="col-md-8">
                <h3><?= Yii::t('app','Наши партнеры')?></h3>
                <div class="partners_slide">
                    <div class="bg_partners_slide" data-toggle="modal" data-target="#samsc">
                        <?=Html::img('@web/images/partners/503192549_1280x720.jpg',['class'=>'width_img','title'=>'Mediterranean Shipping Company S.A.']);?>
                    </div>
                    <div class="bg_partners_slide" data-toggle="modal" data-target="#cosgc">
                        <?=Html::img('@web/images/partners/China-Ocean-Shipping-Company-logo.gif',['class'=>'width_img','title'=>'China Ocean Shipping Container Line']);?>
                    </div>
                    <div class="bg_partners_slide" data-toggle="modal" data-target="#emc">
                        <?=Html::img('@web/images/partners/13212.jpg',['class'=>'width_img','title'=>'Evergreen Marine Corporation.']);?>
                    </div>
                    <div class="bg_partners_slide" data-toggle="modal" data-target="#cma">
                        <?=Html::img('@web/images/partners/CMA-CGM_new.svg.png',['class'=>'width_img','title'=>'CMA CGM S.A.']);?>
                    </div>
                    <div class="bg_partners_slide" data-toggle="modal" data-target="#hapag">
                        <?=Html::img('@web/images/partners/download_logo_HLAG.png',['class'=>'width_img','title'=>'Hapag Lloyd']);?>
                    </div>
                    <div class="bg_partners_slide" data-toggle="modal" data-target="#maersk">
                        <?=Html::img('@web/images/partners/Maersk_Group_Logo.jpeg',['class'=>'width_img','title'=>'A.P. Moller – Maersk Group']);?>
                    </div>
                    <div class="bg_partners_slide" data-toggle="modal" data-target="#oocl">
                        <?=Html::img('@web/images/partners/oocl-logo.png',['class'=>'width_img','title'=>'Orient Overseas Container Line']);?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('.partners_slide').slick({
        dots: true,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        centerMode: true,
        autoplay: true,
        variableWidth: true
    });
</script>


<div id="samsc" class="modal fade">
    <div class="modal-footer"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 modal_info_block" >
                <button class="close_mpdal" type="button" data-dismiss="modal"></button>
                <h4 class="strong">Mediterranean Shipping Company S.A.</h4>
                <br/>
                <?=Html::img('@web/images/partners/503192549_1280x720.jpg',['class'=>'width_img popupimg','title'=>'Mediterranean Shipping Company S.A.']);?>
                <br/>
                <p><span class="strong"><?= Yii::t('slider','Средиземноморская судоходная компания')?> SA ( MSC )</span> - <?= Yii::t('slider','вторая по величине судоходная линия в мире по емкости контейнерных судов.  Как частная компания, он не обязан публиковать ежегодные отчеты, заверенные независимыми сторонами; как следствие, данные MSC-релизы о себе не поддаются проверке. По состоянию на конец декабря 2014 года MSC эксплуатировала 471 контейнерный контейнер с пропускной способностью 2 435 000 единиц эквивалента в двадцать футов (TEU).  Компания Geneva- headquartered  работает во всех крупных портах мира.Наиболее важным портом MSC является Антверпен в Бельгии . MSC Cruises является подразделением компании, ориентированной на праздничные круизы')?>
                </p>
            </div>   
            <div class="col-md-2"></div>         
        </div> 
    </div>                    
</div>

<div id="cosgc" class="modal fade">
    <div class="modal-footer"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 modal_info_block" >
                <button class="close_mpdal" type="button" data-dismiss="modal"></button>
                <h4 class="strong">China Ocean Shipping Container Line</h4>
                <br/>
                <?=Html::img('@web/images/partners/China-Ocean-Shipping-Company-logo.gif',['class'=>'width_img popupimg','title'=>'China Ocean Shipping Container Line']);?>
                <br/>
                <p><span class="strong">China Ocean Shipping (Group) Company (COSCO Group)</span> — <?= Yii::t('slider','мировой лидер в секторе навалочных грузов, входит в число 10 крупнейших мировых контейнерных операторов и ежегодно перевозит порядка 180 млн тонн груза')?>.</p>
                <br/>
                <p><span class="strong">COSCO</span> <?= Yii::t('slider','была основана 27 апреля 1961 года как первый международный судоходный курьер в Китае, в 1993 году компания превратилась в корпорацию с капиталом в 17 миллиардов долларов.')?></p>
                <br/>
                <p><?= Yii::t('slider','Компания владеет и управляет 800 торговыми судами общим дедвейтом 30 млн тонн.')?></p>
                <br/>
                <p><?= Yii::t('slider','Численность работников — 80 тыс. чел., из которых 5 тыс. — иностранцы.')?></p>
                <br/>
                <p><span class="strong">COSCO</span> <?= Yii::t('slider','разделена на предприятия специализирующиеся на грузовых перевозках, в т. ч. и контейнеров, и логистических операциях.')?> <span class="strong">COSCO Group</span> <?= Yii::t('slider','объединяет 46 дочерних компаний.')?></p>
            </div>   
            <div class="col-md-2"></div>         
        </div> 
    </div>                    
</div>

<div id="emc" class="modal fade">
    <div class="modal-footer"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 modal_info_block" >
                <button class="close_mpdal" type="button" data-dismiss="modal"></button>
                <h4 class="strong">Evergreen Marine Corporation.</h4>
                <br/>
                <?=Html::img('@web/images/partners/13212.jpg',['class'=>'width_img popupimg','title'=>'Evergreen Marine Corporation.']);?>
                <br/>
                <p>Evergreen Group — <?= Yii::t('slider','концерн тайваньского конгломерата судоходных и транспортных компаний, и других ассоциированных сервисных компаний.')?></p>
                <br/>
                <p><?= Yii::t('slider','Evergreen Group возникла в 1975 году в результате диверсификации бизнеса судоходной компании Evergreen Marine Corporation (EMC), которая была создана в 1968 году, и является, по состоянию на 2013 год, 5-м крупнейшим морским контейнерным перевозчиком в мире.')?></p>
                <br/>
                <p><?= Yii::t('slider','На сегодняшний день Evergreen Group - это около 18 тыс. работников, более 240 офисов/агентств по всему миру, структура из около 50-ти компаний по всему миру, три из которых имеют листинг на Тайбейской фондовой бирже.')?></p>
            </div>   
            <div class="col-md-2"></div>         
        </div> 
    </div>                    
</div>

<div id="cma" class="modal fade">
    <div class="modal-footer"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 modal_info_block" >
                <button class="close_mpdal" type="button" data-dismiss="modal"></button>
                <h4 class="strong">CMA CGM S.A.</h4>
                <br/>
                <?=Html::img('@web/images/partners/CMA-CGM_new.svg.png',['class'=>'width_img popupimg','title'=>'CMA CGM S.A.']);?>
                <br/>
                <p><span class="strong">CMA CGM SA</span> <?= Yii::t('slider','является французской контейнерной перевозкой и судоходной компанией . Это ведущая мировая судоходная группа , использующая 200 маршрутов доставки от 420 портов в 150 разных странах.  Его штаб-квартира находится в Марселе , а его североамериканская штаб-квартира находится в Норфолке, штат Вирджиния , США.')?></p>
            </div>   
            <div class="col-md-2"></div>         
        </div> 
    </div>                    
</div>

<div id="hapag" class="modal fade">
    <div class="modal-footer"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 modal_info_block" >
                <button class="close_mpdal" type="button" data-dismiss="modal"></button>
                <h4 class="strong">Hapag Lloyd</h4>
                <br/>
                <?=Html::img('@web/images/partners/download_logo_HLAG.png',['class'=>'width_img','title'=>'Hapag Lloyd']);?>
                <br/>
                <p><span class="strong">Hapag-Lloyd</span> - <?= Yii::t('slider','многонациональная немецкая транспортная компания. Он состоит из грузовой контейнерной судоходной линии <span class="strong">Hapag-Lloyd AG</span> , которая, в свою очередь, владеет другими дочерними компаниями, такими как <span class="strong">Hapag-Lloyd Cruises</span> . Грузовое подразделение <span class="strong">Hapag-Lloyd AG</span> в настоящее время является пятым по величине контейнерным перевозчиком в мире по мощности судна .')?></p>
            </div>   
            <div class="col-md-2"></div>         
        </div> 
    </div>                    
</div>

<div id="maersk" class="modal fade">
    <div class="modal-footer"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 modal_info_block" >
                <button class="close_mpdal" type="button" data-dismiss="modal"></button>
                <h4 class="strong">A.P. Moller – Maersk Group</h4>
                <br/>
                <?=Html::img('@web/images/partners/Maersk_Group_Logo.jpeg',['class'=>'width_img popupimg','title'=>'A.P. Moller – Maersk Group']);?>
                <br/>
                <p><span class="strong">Maersk</span>  — <?= Yii::t('slider','датская компания, оперирующая в различных секторах экономики, по большей части известная портовым и грузовым судоходным бизнесом. Штаб-квартира базируется в Копенгагене, а дочерние предприятия и офисы, в которых занято около 88 тысяч сотрудников, располагаются в более чем 135 странах мира. На 2016 год Maersk является мировым лидером в сфере контейнерных перевозок с долей на рынке более 15 %.')?></p>
            </div>   
            <div class="col-md-2"></div>         
        </div> 
    </div>                    
</div>

<div id="oocl" class="modal fade">
    <div class="modal-footer"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 modal_info_block" >
                <button class="close_mpdal" type="button" data-dismiss="modal"></button>
                <h4 class="strong">Orient Overseas Container Line</h4>
                <br/>
                <?=Html::img('@web/images/partners/oocl-logo.png',['class'=>'width_img popupimg','title'=>'Orient Overseas Container Line']);?>
                <br/>
                <p>Orient Overseas (International) — <?= Yii::t('slider','зарегистрированный на Бермудах и базирующийся в Гонконге конгломерат с интересами в судоходстве, логистике и недвижимости. Принадлежит очень влиятельной гонконгской семье Тунг (Дун).')?></p>
            </div>   
            <div class="col-md-2"></div>         
        </div> 
    </div>                    
</div>



<div id="20_pound_desc" class="modal fade">
    <div class="modal-footer"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 modal_info_block" >
                <button class="close_mpdal" type="button" data-dismiss="modal"></button>
                <h4 class="strong"><?= Yii::t('app','Описание')?></h4>
                <br/>
                <p><?= Yii::t('slider','Стандартные контейнеры также называются универсальными контейнерами.')?></p>
                <br/>
                <ul class="list-unstyled"><?= Yii::t('slider','Погрузочные поддоны закрыты со всех сторон. Отличия между видами контейнеров:')?>
                    <li><?= Yii::t('slider',' • Стандартные контейнеры с дверцами с одной или обеих сторон')?></li>
                    <li><?= Yii::t('slider',' • Стандартные контейнеры с дверцами на одной или обеих сторонах и дверцами по всей длине')?></li>
                    <li><?= Yii::t('slider',' • Стандартные контейнеры с дверцами на одной или обеих сторонах')?></li>
                </ul>
                <br/>
                <p><?= Yii::t('slider','Стандартные контейнеры также отличаются между собой в зависимости от размеров, массы')?></p>
                <br/>
                <p><?= Yii::t('slider','Как правило используются 20 и 40 футовые контейнеры. Контейнеры меньших размеров используются в редких случаях. Часто используются контейнеры даже больших размеров, например, 45 футов.')?></p>
            </div>   
            <div class="col-md-2"></div>         
        </div> 
    </div>                    
</div>

<div id="refrigerated_container" class="modal fade">
    <div class="modal-footer"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 modal_info_block" >
            <button class="close_mpdal" type="button" data-dismiss="modal"></button>
                <h4 class="strong"><?= Yii::t('app','Описание')?></h4>
                <br/>
                <p><?= Yii::t('slider','Рефрежираторная установка размещена таким образом, чтобы внешние размеры контейнера соответствовали стандартам ISO и установка оптимально помещалась вовнутрь. Наличие рефрижераторной установки приводит к уменьшению внутреннего объема и подвижной нагрузки.')?></p>
                <br/>
                <?=Html::img('@web/images/container_desc/ref.gif');?>
                <br/>
                <p><?= Yii::t('slider','на борту судна груз должен быть подключен к системе электропитания. количество перевозимых рефрижераторных контейнеров зависит от мощности системы электропитания судна. при слабой мощности, могут использоваться силовые агрегаты, которые устанавливаются радом с дизельными генераторами согласно требованиям ISO по размещению 20 футовых контейнеров. на терминале контейнеры подключают к системе электропитания терминала. при транспортировке автомобильным или железнодорожным транспортом, рефрижераторные блоки работают от электрогенераторов, которые в свою очередь могут быть подключены к холодильной установке.')?></p>
                <br/>
                <p><?= Yii::t('slider','Поток воздуха через контейнер с нижней и верхней частей; теплый воздух поднимается изнутри контейнера, охлаждается в рефрижераторной установке и затем обратно вдувается в контейнер в уже охложденном виде.')?></p>
                <br/>
                <p><?= Yii::t('slider','Для обеспечения правильной циркуляции охложденного воздуха, на напольном перекрытии встроена решетка. Поддоны образуют дополнительное пространство между напольным пекрытием и грузом, что обеспечивает продув. Кроме того, боковые стенки контейнера гофрированы, что обспечивает необходимый поток воздуха.')?></p>
                <br/>
                <?=Html::img('@web/images/container_desc/ref2.jpg');?>
                <br/>
                <p><?= Yii::t('slider','В верхней части контейнера, должно оставаться пространство (не менее 12 см) для продува. Поэтому, при упаковке контейнера, над грузом необходимо оставить свободное пространство. Максимальная высота загрузки отмечена на боковых стенках.')?></p>
                <br/>
                <p><?= Yii::t('slider','Для обеспечения вертикального потока воздуха снизу вверх, конструкция упаковки должна быть соответствующая, и груз размещен правильно')?></p>
                <br>
                <p><?= Yii::t('slider','Регулирование температуры, и наличие интегрального блока, также позволяют контролировать поступление свежего воздуха, например, для выведения метаболических продуктов, таких как СО2 и этилена при перевозке фруктов.')?></p>
                <br/>
                <p><?= Yii::t('slider','в холодильных установках измеряется температура поступающего и отработанного воздуха, и в зависимости от рабочего режима данные температуры изпользуются для регулирования потока холодного воздуха. температуру можно измерить разными способами. устройство записи Partlow фиксирует температуру отработанного воздуха, тем самым определяется температурный режим для груза. устройство регистрации данных определяет уровень температуры цифровым способом и выводит данные на дисплей. после передачи данных на компьютер, информация подвергается анализу.')?></p>
                <br/>
                <p><?= Yii::t('slider','Температурный датчик прикреплен с наружной стороны рефрижераторной установки для контроля за работой установки.')?></p>
                <br/>
                <p><?= Yii::t('slider','Устройства для записи аналоговых и цифровых данных также могут быть размещены непосредственно на грузе для измерения температуры внутри контейнера. Устройсво записи должно быть уставновлено таким образом, чтобы фиксировать критические температуры (внутри упаковки).')?></p>
                <br/>
                <p><?= Yii::t('slider','Встроенные блоки могут быть размещены на верхней и нижней палубах судна. Размещение на верхней палубе имеет свои преимущества, т.к. тепло от воздуха может легко рассеиваться. Однако контейнеры подвержены солнечной радиации, что требует высокой хладопроизводительности холодильных установок.')?></p>
                <br/>
                <p class="strong"><?= Yii::t('slider','Показатели')?></p>
                <br/>
                <?=Html::img('@web/images/container_desc/ref1.jpg');?>
                <?=Html::img('@web/images/container_desc/ref3.jpg');?>
                <?=Html::img('@web/images/container_desc/ref_ins.jpg');?>
                <br/>
                <p class="strong"><?= Yii::t('slider','Использование')?></p>
                <br/>
                <p><?= Yii::t('slider','контейнер-рефрижератор используется для перевозки груза, требующего наличия одинаковой температуры как верхней так и нижней точек замерзания. К такому грузу относятся охлаждённые и замороженные продукты, требующие определенной температуры. Это могут быть фрукты, овощи, мясные и молочные продукты, масло и сыр.')?></p>
                <br/>
                <p><?= Yii::t('slider','Агрегаты с большими объемами используются для объемных и легких грузов (фрукты, цветы)')?></p>
                <br/>
                <p><?= Yii::t('slider','На сегодняшний день, грузы, требующие заморозки, перевозятся во встроенных агрегатах, которые намного лучше, чем контейнеры с вентиляционными отверстиями.')?></p>
                <br/>
                <p><?= Yii::t('slider','Мясо иногда перевозят в подвешенном состоянии, для этого потолки в контейнерах оборудованы специальными передвижными крюками.')?></p>
                <br/>
            </div>   
            <div class="col-md-2"></div>         
        </div> 
    </div>                    
</div>


<div id="open_top" class="modal fade">
    <div class="modal-footer"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 modal_info_block" >
            <button class="close_mpdal" type="button" data-dismiss="modal"></button>
                <h4 class="strong"><?= Yii::t('app','Описание')?></h4>
                <br/>                
                <p><?= Yii::t('slider','Стенки контейнера сделаны из гофрированной стали. Настил деревянный.')?></p>
                <br/>
                <p><?= Yii::t('slider','Контейнер имеет отличительную структуру. Крыша состоит из передвижных рам и тента. Дверная перемычка может быть вращающейся.')?></p>
                <br/>
                <p><?= Yii::t('slider','Эти две особенности значительно упрощают процесс упаковки и распаковки контейнера. В частности, очень легко упаковать и распаковать контейнер сверху или через двери пр попомщи подъемника или домкрата, если открыта крыша и повернута дверная перемычка')?></p>
                <br/>
                <p><?= Yii::t('slider','Необходимо отметить, что опора крыши в открытом контейнере предназначена для поддержания тента и обеспечить устойчивость контейнера. погрузочный поддон в этом случае наиболее подходящее средство для высоких грузов.')?></p>
                <br/>
                <p><?= Yii::t('slider','Крепежные кольца установлены на верхней и нижней боковых балках и угловых опорах. Кольца могут выдержать груз до 1000 кг.')?></p>
                <br/>
                <p><?= Yii::t('slider','Стандартные размеры контейнера с открытым верхом 20 и 40 футов.')?></p>
                <br/>
                <p class="strong"><?= Yii::t('slider','Показатели')?></p>
                <br/>
                <?=Html::img('@web/images/container_desc/ot1.jpg');?>
                <?=Html::img('@web/images/container_desc/ot2.jpg');?>
                <?=Html::img('@web/images/container_desc/ot3.jpg');?>
                <br/>                    
                <p class="strong"><?= Yii::t('slider','Использование')?></p>
                <br/>
                <ul><?= Yii::t('slider','Контейнеры с открытым верхом используются для всех видов генеральных грузов (сухой груз). Назначение:')?>
                <br/>
                    <li><?= Yii::t('slider',' • упаковка и распаковка сверху или через двери при помощи крана или домкрата')?></li>
                    <li><?= Yii::t('slider',' • для высоких грузов')?></li>
                </ul>
            </div>   
            <div class="col-md-2"></div>         
        </div> 
    </div>                    
</div>

<div id="flat_rack" class="modal fade">
    <div class="modal-footer"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 modal_info_block" >
            <button class="close_mpdal" type="button" data-dismiss="modal"></button>
                <h4 class="strong"><?= Yii::t('app','Описание')?></h4>
                <br/>                
                <p><?= Yii::t('slider','Погрузочный поддон состоит из напольного перекрытия с высокой несущей способностью и стального каркаса с настилом из мягкой древесины и двух перегородок; поддон может быть закрепленный или разборной. Перегородки защищают груз, при этом несколько поддонов крепятся один на другой. Размеры погрузочного поддона 20 и 40 футов.')?></p>
                <br/> 
                <p><?= Yii::t('slider','Крепежные кольца, к которым крепится груз, устанавливаются на боковых балках, угловых опорах и полу. Кольца могут выдержать нагрузку до 2000 кг, в случае с 20 футовыми погрузочными поддонами или до 4000 кг с 40 футовыми поддонами.')?></p>
                <br/> 
                <p><?= Yii::t('slider','У некоторых видов 20 футовых контейнеров имеются отверстия для вилочныых погрузчиков.')?></p>
                <br/> 
                <p><?= Yii::t('slider','40 футовые поддоны имеют входы s- образной формы с каждой стороны. Иногда на них крепятся крепежная лебедка с 2 тонными крепежными канатами.')?></p>
                <br/> 
                <p><?= Yii::t('slider','для перевозки отдельных видов грузов, погрузочные поддоны могут быть оснащены подпорами.')?></p>
                <br/> 
                <p class="strong"><?= Yii::t('slider','Показатели')?></p>
                <br/> 
                <?=Html::img('@web/images/container_desc/fr1.jpg');?>
                <?=Html::img('@web/images/container_desc/fr2.jpg');?>
                <br/> 
                <p class="strong"><?= Yii::t('slider','Использование')?></p>
                <br/> 
                <p><?= Yii::t('slider','Поддоны используются для перевозки тяжелых и крупногабатиных грузов.')?></p>
                <br/> 
            </div>   
            <div class="col-md-2"></div>         
        </div> 
    </div>                    
</div>


<div id="tank_container" class="modal fade">
    <div class="modal-footer"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 modal_info_block" >
            <button class="close_mpdal" type="button" data-dismiss="modal"></button>
                <h4 class="strong"><?= Yii::t('app','Описание')?></h4>
                <br/>                
                <?= Yii::t('slider','Такие контейнеры должны быть загружены не менее чем на 80 процентов для предотвращения колебания жидкого груза. С другой стороны, контейнеры не должны заполняться больше чем на 95 процентов, в противном случае возникнет нехватка свободного пространства, необходимого для термального расширения, которое может быть рассчитано для каждого груза по формуле:')?>
                <br/> <br/> 
                <ul>
                    <li> • ΔV = Va · γ · ΔT</li>
                    <li> • Ve = Va (1 + γ · ΔT)</li>
                </ul>
                <br/> 
                <p>ΔV :    <?= Yii::t('slider','изменение объема')?></p>
                <p>Va :    <?= Yii::t('slider','объем при начальной температуре')?></p>
                <p>Ve :    <?= Yii::t('slider','окончательный объем при температуре')?></p>
                <p>γ : <?= Yii::t('slider','коэффициент термального расширения')?></p>
                <p>ΔT :    <?= Yii::t('slider','разница температур в градусах Кельвина')?></p>
                <br/> 
                <p><?= Yii::t('slider','контейнер для жидких грузов, предназначенный для перевозки пищевых продуктов, должен быть маркирован "только для жидких грузов".')?></p>
                <br/> 
                <p><?= Yii::t('slider','Некоторые виды опасных веществ должны перевозиться в контейнерах для жидких грузов с внутренними или внешними отверстиями в нижней части корпуса контейнера.')?></p>
                <br/> 
                <p><?= Yii::t('slider','Контейнеры для жидких грузов должны находиться при давлении до 3 бар. испытательное давление составляет 4,5 бар')?></p>
                <br/> 
                <p><?= Yii::t('slider','если для перевозки груза требуется транспортное средства с регулировкой температурного режима, контейнеры-цистерны могут быть оснащены системами охлаждения или подогрева. температурный режим для груза может быть точно определен при помощи датчиков температуры')?></p>
                <br/> 
                <p class="strong"><?= Yii::t('slider','Показатели')?></p>
                <br/> 
                <?=Html::img('@web/images/container_desc/t1.jpg');?>
                <?=Html::img('@web/images/container_desc/t2.jpg');?>
                <?=Html::img('@web/images/container_desc/t3.jpg');?>
                <br/><br/>  
                <p class="strong"><?= Yii::t('slider','Использование')?></p>
                <br/> 
                <p><?= Yii::t('slider','Контейнеры для жидкостей используются для перевозки жидких грузов, например:')?></p>
                <br/> 
                <ul>
                    <li><?= Yii::t('slider',' • пищевые продукты: соки, спирт, оливковое масло')?></li>
                    <li><?= Yii::t('slider',' • химикаты: опасные вещества, топливо, токсичные вещества, вещества для защиты от коррозии')?></li>
                </ul>
                <br/>
            </div>   
            <div class="col-md-2"></div>         
        </div> 
    </div>                    
</div>

<div id="сonsultation" class="modal fade">
    <div class="modal-footer"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 modal_info_block" >
            <button class="close_mpdal" type="button" data-dismiss="modal"></button>
                <?= Yii::t('main','Если Вы являетесь партнером нашей компании или совладельцем контейнера, а так же если у Вас есть другие интересующие вопросы относительно работы  с нашей компанией, пожалуйста, свяжитесь с нами любой удобной формой связи и мы ответим на все интересующие Вас вопросы.')?>
            </div>   
            <div class="col-md-2"></div>         
        </div> 
    </div>                    
</div>

<div id="partners_certificate" class="modal fade">
    <div class="modal-footer"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 modal_info_block" >
            <button class="close_mpdal" type="button" data-dismiss="modal"></button>
                <?=Html::img('@web/images/partners/certificate/certificate.png',['class'=>'certeficate','title'=>'Сertificate']);?>

                <a href="web/download/InvestmentAgreement.pdf" class="btn btn-default" download><?= Yii::t('app','Скачать "ИНВЕСТИЦИОННЫЙ ДОГОВОР"')?></a>
            </div>   
            <div class="col-md-2"></div>         
        </div> 
    </div>                    
</div>

<div id="new_opportunities" class="modal fade">
    <div class="modal-footer"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 modal_info_block" >
            <button class="close_mpdal" type="button" data-dismiss="modal"></button>
                <p><span class="strong">SeaLines Company</span> – <?= Yii::t('slider','организовывает контейнерную перевозку грузов морем в любую точку мира, мы имеем возможность предложить вам выгодные условия доставки и оптимальные для вас маршруты, соединяющие страны и континенты.
                При сотрудничестве с нами вам доступны перевозки из Китая Юго-Восточной Азии, Ближнего Востока, Индии, Африки, Северной Америки, и стран Европы в Россию. Возможна доставка «от двери до двери». Для получения грузов используются прибалтийские и европейские порты и склады.</p>
                <br/>
                <p>Компания SeaLines разрабатывает логистическую цепочку и осуществляет доставку грузов «от двери до двери», начиная с этапа формирования документации и заканчивая выгрузкой контейнера на вашем складе.</p>
                <br/>
                <p>Наша компания предоставляет большой перечень услуг. Став нашим партнером, у Вас появятся возможности, которые другие компании Вам не смогут предоставить. С нашей помощью Вы сможете: доставить свой груз, забыть про оформление документации, про портовые услуги и прочие факторы. Так же, компания SeaLines предлагает своим партнерам приумножить свой капитал. Один из видов деятельности компании заключается в том, что Мы Вам можем предложить взять в субаренду\аренду\аренду с правом выкупа контейнера, для которых уже налажена логистическая цепочка, загрузка его более рентабельным товаром. Вам остается отслеживать онлайн свой контейнер. Также мы предоставляем удобную для клиента опцию выкупа контейнеров (buy-back).</p>
                <br/>
                <p>Мы считаем своей миссией сохранить ваше время, сделать ваш продукт конкурентным и оптимизировать транспортные расходы. Мы помогаем перевезти груз из любой точки мира легко и быстро, без лишних хлопот и затрат.<br/>
                Мы завоевали доверие наших клиентов тем, что предложили оптимальные решения по перевозке грузов и исключили любые ошибки, связанные с человеческим фактором.<br/>
                Контейнерные перевозки морем — лучший, в некоторых случаях даже единственный вариант межконтинентальной доставки грузов. Надежно и экономично.</p>
                <br/>
                <ul>Преимущества перевозок по-морю:<br/>
                    <li> - низкая стоимость доставки морем во многом объясняется возможностью комплектации сборных грузов.</li>
                    <li> - высокая грузоподъемность, в случае с крупногабаритными грузами, перевозка контейнеров морем зачастую становится не только лучшим, но и единственно возможным вариантом доставки.</li>
                    <li> - сохранность груза при транспортировке контейнеров морем практически отсутствуют — риск повреждения грузов минимальный.
                    Sea Lines Company  является заметным игроком на контейнерном рынке в Европе, Балтии, России и СНГ. Мы специализируемся на контейнерах самого широкого спектра типов, габаритов и целевого назначения - сухогрузные или универсальные (морские) контейнеры, рефрижераторные контейнеры (реферы), танк-контейнеры, специализированные контейнеры и т.д. Помимо этого мы поставляем и налаживаем вспомогательное оборудование для контейнеров: генераторные установки (дженсеты) и т.д.</li>
                </ul>
                <br/>
                <p>SeaLines Company работает на контейнерном рынке более 5 лет, и достигнутый за годы работы высокий уровень понимания бизнес-потребностей клиентов помогает быстро и эффективно решать все вопросы, связанные с использованием контейнеров для мультимодальных перевозок и складирования всех видов грузов. Мы обеспечиваем клиентам готовые и индивидуальные решения в транспортировке, логистике, строительстве и любых других видах деятельности, где находится применение контейнерам.</p>
                <br/>
                <p>Уже более 5ти лет SeaLines Company обеспечивает высокую надежность и безопасность контейнерного сервиса для своих клиентов. Мы ручаемся за конфиденциальность и сохранность грузов при перевозке и хранении (в частности, на охраняемой территории, прилегающей к портовой зоне).</p>
                <br/>')?>
            </div>   
            <div class="col-md-2"></div>         
        </div> 
    </div>                    
</div>
