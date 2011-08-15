<?php echo $this->Form->create('Vocabulary', array('url' => "/admin/taxonomy/vocabularies/edit/{$this->data['Vocabulary']['slug']}")); ?>
    <!-- Content -->
    <?php echo $this->Html->useTag('fieldsetstart', __t('Add Vocabulary')  ); ?>
        <?php echo $this->Form->hidden('id'); ?>
        <?php echo $this->Form->input('title', array('required' => 'required', 'label' => __t('Title *'), 'type' => 'text')); ?>
        <?php echo $this->Form->input('description', array('label' => __t('Description'), 'type' => 'textarea')); ?>
        <?php echo $this->Form->input('NodeType', array('empty' => false, 'selected' => Set::extract('/NodeType/id', $this->data) ,'options' => $types, 'label' => __t('Types'), 'multiple' => true)); ?>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
    
    <!-- Submit -->
    <?php echo $this->Form->input(__t('Save vocabulary'), array('type' => 'submit')); ?>
<?php echo $this->Form->end(); ?>