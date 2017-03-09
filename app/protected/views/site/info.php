<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Movie Info';
$this->breadcrumbs=array(
    'Info',
);
?>

<?php
/* @var $idMovie int */
/* @var $model TMDb_APIWrapper */
$data = $model->getMovieInfo($idMovie);

$this->widget('zii.widgets.CDetailView', array(
    'data'=>$data,
    'attributes'=>array(
        'title',             // title attribute (in plain text)
        'original_title',        // an attribute of the related object "owner"
        'release_date',
        'runtime',
        'overview',
        [
            'label' => 'Genres',
            'value' => function($data) {
                array_walk($data['genres'], function(&$value) {
                    $value = $value['name'];
                });

                return join($data['genres'], ', ');
            },

        ],
        [
            'label' => 'Poster',
            'type'  => 'raw',
            'value' => function($data) {
                return TMDb_LoadPoster::load($data['poster_path']);
            }
        ],
    ),
));
?>

<ul id="yw1" class="yiiPager">
    <li class="page">
        <?php echo CHtml::ajaxLink('Delete',
            Yii::app()->createUrl('site/delete', ['id' => $idMovie]),
            [
                'type'=>'GET',
                'success'=>'js:function(resp){ if("" == resp){}else{window.location.href = resp; } }'
            ],
            ['class'=>'someCssClass']
        );
        ?>
    </li>
    <li class="page">
        <?php echo CHtml::linkButton('Edit', [
            'submit'    => $this->createUrl('site/edit', ['id'=>$idMovie])
            ]
        );
        ?>
    </li>
</ul>

<div style="margin-top: 15px;">
<?php
$url = Yii::app()->createUrl('site/rate', ['id'=> $idMovie]);
$this->widget('CStarRating', [
        'name' => 'ratingAjax[' . $value->ques_id . ']',
        'id' => $value->ques_id,
        'readOnly' => false,
        'minRating' => .5,
        'maxRating' => 10,
        'ratingStepSize' => .5,
        'callback' => '
        function() {
            $.ajax({
                type: "POST",
                url: "' . $url . '&rating="+ $(this).val(),
                data: "' . Yii::app()->request->csrfTokenName . '=' . Yii::app()->request->getCsrfToken() . '&rate=" + $(this).val(),
                success: function(msg){}
            })
        }'
    ]
);
?>
</div>
