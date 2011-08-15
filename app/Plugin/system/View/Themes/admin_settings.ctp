<?php $e = $this->element('settings_form', array(), array('plugin' => Inflector::camelize("theme_{$theme_name}"))); ?>

<?php echo $this->Form->create('Module', array('url' => "/admin/system/themes/settings/{$theme_name}")); ?>
    <?php echo $this->Form->input('Module.name', array('type' => 'hidden', 'value' => Inflector::underscore('Theme' . $theme_name))); ?>
    
    <?php if ($e ): ?>
    <?php echo $this->Html->useTag('fieldsetstart', __t('"%s" Theme', $theme_name)); ?>
        <?php  echo $e; ?>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
    <?php endif; ?>
    
    <?php echo $this->Html->useTag('fieldsetstart', __t('Toggle display')); ?>
        <?php echo $this->Form->input('Module.settings.site_logo', array('type' => 'checkbox', 'label' => __t('Logo'))); ?>
        <?php echo $this->Form->input('Module.settings.site_name', array('type' => 'checkbox', 'label' => __t('Site name'))); ?>
        <?php echo $this->Form->input('Module.settings.site_slogan', array('type' => 'checkbox', 'label' => __t('Site slogan'))); ?>
        <?php echo $this->Form->input('Module.settings.site_favicon', array('type' => 'checkbox', 'label' => __t('Shortcut icon'))); ?>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
        
    <?php echo $this->Html->useTag('fieldsetstart', __t('Logo')); ?>
        <?php echo $this->Form->input('Module.settings.site_logo_url', array('type' => 'text', 'label' => __t('Logo image URL'))); ?>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
        
    <?php echo $this->Html->useTag('fieldsetstart', __t('Shortcut icon')); ?>
        <?php echo $this->Form->input('Module.settings.site_favicon_url', array('type' => 'text', 'label' => __t('Shortcut icon URL'))); ?>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
    
    <?php echo $this->Form->input(__t('Save'), array('type' => 'submit')); ?>
<?php echo $this->Form->end(); ?>