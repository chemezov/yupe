<?php
    $this->breadcrumbs = array(
        Yii::app()->getModule('queue')->getCategory() => array(),
        Yii::t('QueueModule.queue', 'Tasks') => array('/queue/default/index'),
        $model->id,
    );

    $this->pageTitle = Yii::t('QueueModule.queue', 'Tasks - show');

    $this->menu = array(
        array('icon' => 'list-alt', 'label' => Yii::t('QueueModule.queue', 'Task list'), 'url' => array('/queue/default/index')),
        array('icon' => 'plus-sign', 'label' => Yii::t('QueueModule.queue', 'Create task'), 'url' => array('/queue/default/create')),
        array('label' => Yii::t('QueueModule.queue', 'Task') . ' «' . $model->id . '»'),
        array('icon' => 'pencil', 'label' => Yii::t('QueueModule.queue', 'Edit task.'), 'url' => array(
            '/queue/default/update',
            'id' => $model->id
        )),
        array('icon' => 'eye-open', 'label' => Yii::t('QueueModule.queue', 'Show task'), 'url' => array(
            '/queue/default/view',
            'id' => $model->id
        )),
        array('icon' => 'trash', 'label' => Yii::t('QueueModule.queue','Remove task'), 'url' => '#', 'linkOptions'=> array(
            'submit' => array('/queue/default/delete', 'id' => $model->id),
            'params' => array(Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken),
            'confirm'=> Yii::t('QueueModule.queue', 'Do you really want to delete?'),
        )),
    );
?>
<div class="page-header">
    <h1>
        <?php echo Yii::t('QueueModule.queue', 'View task'); ?><br/>
        <small>&laquo;<?php echo $model->id; ?>&raquo;</small>
    </h1>
</div>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data'      => $model,
    'attributes'=> array(
        'id',
        'worker',
        'create_time',
        'task',
        'start_time',
        'complete_time',
        array(
            'name'  => 'priority',
            'value' => $model->getPriority()
        ),
        array(
            'name'  => 'status',
            'value' => $model->getStatus()
        ),
        'notice',
    ),
)); ?>
