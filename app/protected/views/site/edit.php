<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Movie Edit';
$this->breadcrumbs=array(
    'Info' => ['site/info', 'id' => $id],
    'Edit   '
);
?>

<div class="form">
    <?php
    /**
     * @var $form CActiveForm
     */
    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'edit-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>

    <?php echo $form->errorSummary($model); ?>


    <div class="row">
        <?php echo $form->labelEx($model,'title'); ?>
        <?php echo $form->textField($model,'title') ?>
        <?php echo $form->error($model,'title'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'original_title'); ?>
        <?php echo $form->textField($model,'original_title') ?>
        <?php echo $form->error($model,'original_title'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'release_date'); ?>
        <?php echo $form->textField($model,'release_date'); ?>
        <?php echo $form->error($model,'release_date'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'runtime'); ?>
        <?php echo $form->textField($model,'runtime') ?>
        <?php echo $form->error($model,'runtime'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'overview'); ?>
        <?php echo $form->textField($model,'overview') ?>
        <?php echo $form->error($model,'overview'); ?>
    </div>





    <div class="row submit">
        <?php echo CHtml::submitButton('Save'); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->
