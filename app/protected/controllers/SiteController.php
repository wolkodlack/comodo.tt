<?php

class SiteController extends Controller {

    /**
     * Declares class-based actions.
     * @return array
     */
    public function actions() {
        return array(
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * @param CAction $action
     * @return bool
     */
    public function beforeAction($action) {
        if (Yii::app()->user->isGuest && Yii::app()->controller->action->id != "login") {
            Yii::app()->user->loginRequired();
        }

        return true;
    }


    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $identity = Yii::app()->user->id;
        $model = TMDb_APIWrapper::instance($identity);

        $listType = Yii::app()->request->getQuery('listType', 'popular');
        $page = Yii::app()->request->getQuery('page', 1);


        $this->render('index', ['model' => $model, 'listType' => $listType, 'page' => $page]);
    }

    /**
     * Movie information page action
     * @param $id
     */
    public function actionInfo($id) {
        $url=CHtml::asset(Yii::getPathOfAlias('system.web.widgets.pagers.pager').'.css');
        Yii::app()->getClientScript()->registerCssFile($url);

        $identity = Yii::app()->user->id;
        $model = TMDb_APIWrapper::instance($identity);

        $this->render('info', ['model' => $model, 'idMovie' => $id]);
    }

    /**
     * Delete cache action
     * @param $id
     */
    public function actionDelete($id) {
        $model = TMDb_SqlileCache::model()->findByPk($id);
        $model->delete();

        $url = Yii::app()->createUrl('site/info', ['id' =>$id]);
        echo $url;
    }

    /**
     * Edits info data-cache
     * @param $id
     */
    public function actionEdit($id) {
        $model = TMDb_SqlileCache::model()->findByPk($id);

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'edit-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['TMDb_SqlileCache'])) {
            $data = $_POST['TMDb_SqlileCache'];
            $model->populateCacheData($id, $data);
            if ($model->validate() && $model->save()) {
                $this->redirect(Yii::app()->createUrl('site/info', ['id' => $id]));

            }
        }

        $this->render('edit', ['model' => $model, 'id' => $id]);
    }

    /**
     * Rate movie action
     * @param $id
     * @param $rating
     */
    public function actionRate($id, $rating) {
        $identity = Yii::app()->user->id;
        $model = TMDb_APIWrapper::instance($identity);

        if($model->setMovieRating($id, $rating) )
            echo 'Ok';
        else
            echo 'Fail';
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
//                $this->redirect(Yii::app()->user->returnUrl);
                $this->redirect(Yii::app()->createUrl('site/index'));
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }
}