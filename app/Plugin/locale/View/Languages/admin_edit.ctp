<?php echo $this->Form->create('Language', array('url' => "/admin/locale/languages/edit/{$this->data['Language']['id']}") ); ?>
    <?php echo $this->Html->useTag('fieldsetstart', __t('Editing language')); ?>
        <?php if (  !in_array($this->data['Language']['code'], array('eng', Configure::read('Variable.default_language'))) ): ?>
            <?php echo $this->Form->input('status', array('type' => 'checkbox', 'label' => __t('Active') ) ); ?>
        <?php endif; ?>
        <?php echo $this->Form->input('id', array('type' => 'hidden') ); ?>
        <?php echo $this->Form->input('code', array('type' => 'hidden') ); ?>
        
        <?php echo $this->Form->input('name', array('required' => 'required', 'type' => 'text', 'label' => __t('Language name in English *') ) ); ?>
        <em><?php echo __t('Name of the language in English. Will be available for translation in all languages.'); ?></em>
        
        <?php echo $this->Form->input('native', array('required' => 'required', 'type' => 'text', 'label' => __t('Native language name *') ) ); ?>
        <em><?php echo __t('Name of the language in the language being added.'); ?></em>
        <?php
            echo $this->Form->input('icon', 
                array(
                    'type' => 'select', 
                    'label' => __t('Flag icon'),
                    'options' => $flags,
                    'empty' => __t('-- None --'),
                    'onChange' => 'showFlag(this);',
                    'after' => " <img src=\"" . $this->Html->url("/locale/img/flags/{$this->data['Language']['icon']}") . "\" id=\"flag-icon\" style=\"" . ( empty($this->data['Language']['icon']) ? 'display:none;' : '' ) . "\" /> "
                ) 
            );
        ?>
        
        <?php 
            echo $this->Form->input('custom_icon', 
                array(
                    'type' => 'text', 
                    'label' => __t('Custom flag icon'),
                    'value' => (strpos($this->data['Language']['icon'], '://') !== false ? $this->data['Language']['icon'] : '')
                ) 
            );
        ?>
        <em><?php echo __t("Optional URL of your language flag icon, if your language flag isn't in the above list."); ?></em>
        
        <?php 
            echo $this->Form->input('direction', 
                array(
                    'required' => 'required', 
                    'type' => 'radio', 
                    'separator' => '<br/>', 
                    'options' => array(
                        'ltr' => __t('Left to Right'),
                        'rtl' => __t('Right to Left')
                    ), 
                    'label' => true,
                    'legend' => __t('Direction *'),
                    'after' => __t('Direction that text in this language is presented.')
                ) 
            );
        ?>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
    
    <!-- Submit -->
    <?php echo $this->Form->input(__t('Save language'), array('type' => 'submit') ); ?>
<?php echo $this->Form->end(); ?>

<script>
    function showFlag(s){
        if ( s.value == '' ){
             $('#flag-icon').hide();
        } else {
            $('#flag-icon').attr('src', QuickApps.settings.base_url + 'locale/img/flags/' + s.value);
            $('#flag-icon').show();
        }
    }
</script>