<?php
class DefaultController extends yupe\components\controllers\BackController
{
    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array('model' => $this->loadModel($id)));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Comment;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Comment']))
        {
            $model->attributes = $_POST['Comment'];

            $saveStatus = false;
            $parentId = $model->getAttribute('parent_id');
            // Если указан parent_id просто добавляем новый комментарий.
            if($parentId > 0)
            {
                $rootForComment = Comment::model()->findByPk($parentId);
                $saveStatus = $model->appendTo($rootForComment);
            }else{ // Иначе если parent_id не указан...

                $rootNode = Comment::createRootOfCommentsIfNotExists($model->getAttribute("model"),
                    $model->getAttribute("model_id"));

                // Добавляем комментарий к корню.
                if ($rootNode!==false && $rootNode->id > 0)
                {
                    $saveStatus = $model->appendTo($rootNode);
                }
            }

            if ($saveStatus)
            {
                Yii::app()->cache->delete("Comment{$model->model}{$model->model_id}");
                Yii::app()->user->setFlash(YFlashMessages::SUCCESS_MESSAGE,Yii::t('CommentModule.comment','Comment was created!'));

                $this->redirect(
                    (array) Yii::app()->request->getPost(
                        'submit-type', array('create')
                    )
                );
            }

        }
        $this->render('create', array('model' => $model));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        Yii::app()->cache->delete("Comment{$model->model}{$model->model_id}");
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Comment']))
        {
            $model->attributes = $_POST['Comment'];

            if ($model->saveNode())
            {
                Yii::app()->user->setFlash(YFlashMessages::SUCCESS_MESSAGE,Yii::t('CommentModule.comment','Comment was updated!'));

                if (!isset($_POST['submit-type']))
                    $this->redirect(array('update', 'id' => $model->id));
                else
                    $this->redirect(array($_POST['submit-type']));
            }
        }
        $this->render('update', array('model' => $model));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            $model = $this->loadModel($id);
            Yii::app()->cache->delete("Comment{$model->model}{$model->model_id}");
            $model->deleteNode();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
        else
            throw new CHttpException(400, Yii::t('CommentModule.comment', 'Bad request. Please don\'t repeate similar requests anymore'));
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model = new Comment('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Comment']))
            $model->attributes = $_GET['Comment'];
        $this->render('index', array('model' => $model));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = Comment::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('CommentModule.comment', 'Requested page was not found!'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'comment-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    
}