<?php echo $this->Form->create('Field', array('url' => "/admin/node/types/field_settings/{$this->data['Field']['id']}") ); ?>
    <!-- Basic -->
    <?php echo $this->Html->useTag('fieldsetstart', __t('Basic settings')  ); ?>
        <?php echo $this->Form->input('Field.id', array('type' => 'hidden') ); ?>
        <?php echo $this->Form->input('Field.label', array('required' => 'required', 'type' => 'text', 'label' => __t('Label *')) ); ?>
        <p><?php echo $this->Form->input('Field.required', array('type' => 'checkbox', 'label' => __t('Requied field')) ); ?></p>
        <?php echo $this->Form->input('Field.description', array('after' => __t('Instructions to present to the user below this field on the editing form. (hookTags are allowed)'),'type' => 'textarea', 'label' => __t('Help text')) ); ?>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
    
    <!-- Especifics -->
    <?php echo $this->Html->useTag('fieldsetstart', __t('%s field settings', $this->data['Field']['label'])  ); ?>
        <?php echo $this->element('settings_from', array(), array('plugin' => Inflector::camelize($this->data['Field']['field_module'])) ); ?>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
    
    <!-- Submit -->
    <?php echo $this->Form->input(__t('Save field'), array('type' => 'submit') ); ?>
<?php echo $this->Form->end(); ?>
