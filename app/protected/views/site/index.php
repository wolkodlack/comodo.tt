<?php
/* @var $this SiteController */
/* @var $model TMDb_APIWrapper */

$this->pageTitle=Yii::app()->name;
?>

<h1>Movies List</h1>

<ul id="yw1" class="yiiPager">
    <li class="page<?=$listType==='popular'?' selected':''?>">
    <?= CHtml::link('Popular',array('site/index','listType'=>'popular'), array('class'=>'hello') ); ?>
    </li>
    <li class="page<?=$listType==='newest'?' selected':''?>">
    <?= CHtml::link('Newest',array('site/index','listType'=>'newest'), array('class'=>'hello') ); ?>
    </li>
</ul>



<?php
$search = $model->discoverMovie($listType, $page);


$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $search,
    'columns' => array(
        'id',
        'title',
        'release_date',
        [
            'class'=>'CLinkColumn',
            'header'=>'Movie Info',
            'label'=>'View Info',
            'urlExpression'=>'Yii::app()->createUrl("site/info", array("id" =>  $data["id"]))',
            'htmlOptions' => [
                'style' => 'width: 100px; text-align: center;',
            ],
        ],
    ),
));
?>

