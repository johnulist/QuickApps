<?php 
$tSettings = array(
	'columns' => array(
		'<input type="checkbox" onclick="QuickApps.checkAll(this);">' => array(
			'value' => '<input type="checkbox" name="data[Items][id][]" value="{Language.id}">',
			'thOptions' => array('align' => 'center'),
			'tdOptions' => array('width' => '25', 'align' => 'center')
		),
		__t('English name') => array(
			'value' => '
                {Language.name} 
                {php} return ("{Language.icon}" != "" ? $this->_View->Html->image(\'/locale/img/flags/{Language.icon}\') : ""); {/php}
                {php} return ("{Language.code}" == "' . Configure::read('Variable.default_language') . '" ? $this->_View->Html->image(\'/locale/img/default.png\', array(\'title\' => \'' . __t('Default language') . '\')) : ""); {/php}
                ',
            'sort' => 'Language.name'
		),
		__t('Native name') => array(
			'value' => '{Language.native}',
			'sort'	=> 'Language.native'
		),
		__t('Code') => array(
			'value' => '{Language.code}',
			'sort'	=> 'Language.code'
		),
		__t('Direction') => array(
			'value' => '{php} return ( "{Language.direction}" == "ltr" ? "' . __t('Left to right') . '" : "' . __t('Right to left') . '");{/php}',
            'sort'	=> '{Language.direction}'
		),
		__t('Status') => array(
			'value' => '{php} return ( {Language.status} == 1 ? "' . __t('active') . '" : "' . __t('disabled') . '");{/php}',
            'sort'	=> '{Language.direction}'
		),
		__t('Actions') => array(
			'value' => '
                <a href="{url}/admin/locale/languages/edit/{Language.id}{/url}">' . __t('edit') . '</a> | 
                <a href="{url}/admin/locale/languages/set_default/{Language.id}{/url}">' . __t('set as default') . '</a>
            ',
            'thOptions' => array('align' => 'center'),
            'tdOptions' => array('width' => '180', 'align' => 'center'),
            'sort'	=> false
		)
	),
    'noItemsMessage' => __t('There are no languages to display. Critical error'),
	'paginate' => true,
	'headerPosition' => 'top',
	'tableOptions' => array('width' => '100%')	# table attributes
);
?>
<!-- Add form -->
    <?php echo $this->Html->useTag('fieldsetstart', '<span id="toggle-add_fieldset" style="cursor:pointer;">' . __t('Add New Language') . '</span>' ); ?>
        <div id="add_fieldset" class="horizontalLayout" style="display:none;">
            <?php echo $this->Form->create('Language', array('url' => '/admin/locale/languages/add')); ?>
                <div id="predefinedList">
                    <?php echo $this->Form->input('code', array('type' => 'select', 'options' => $languages, 'label' => __t('Language name'))); ?>
                    <p>
                        <?php echo $this->Form->input(__t('Add'), array('type' => 'submit', 'label' => false)); ?>
                    </p>
                </div>
            <?php echo $this->Form->end(); ?>
            
            <?php echo $this->Form->create('Language', array('url' => '/admin/locale/languages/add')); ?>
                <?php echo $this->Html->useTag('fieldsetstart', '<span id="toggle-addCustom_fieldset" style="cursor:pointer;">' . __t('Custom Language') . '</span>' ); ?>
                    <div id="addCustom_fieldset" class="verticalLayout" style="display:none;">
                        <?php echo $this->Form->input('status', array('type' => 'hidden', 'value' => 1)); ?>
                        
                        <?php echo $this->Form->input('custom_code', array('required' => 'required', 'maxlength' => 3, 'style' => 'width:50px;', 'type' => 'text', 'label' => __t('Language code *'))); ?>
                        <em><?php echo __t('<a href="%s" target="_blank">ISO 639-3</a> compliant language identifier.', 'http://www.sil.org/iso639-3/codes.asp'); ?></em>
                        
                        <?php echo $this->Form->input('name', array('required' => 'required', 'type' => 'text', 'label' => __t('Language name in English *'))); ?>
                        
                        <?php echo $this->Form->input('native', array('required' => 'required', 'type' => 'text', 'label' => __t('Native language name *'))); ?>
                        <em><?php echo __t('Name of the language in the language being added.'); ?></em>

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
                        <p>
                            <?php echo $this->Form->input(__t('Add'), array('name' => 'data[Language][addCustom]', 'type' => 'submit', 'label' => false)); ?>
                        </p>
                    </div>
                <?php echo $this->Html->useTag('fieldsetend'); ?>
            <?php echo $this->Form->end(); ?>
        </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>

<?php echo $this->Form->create('Language', array('onsubmit' => 'return confirm("' . __t('Are you sure ?') . '");')); ?>
    <!-- Update -->
    <?php echo $this->Html->useTag('fieldsetstart', '<span id="toggle-update_fieldset" style="cursor:pointer;">' . __t('Update Options') . '</span>' ); ?>
        <div id="update_fieldset" class="horizontalLayout" style="<?php echo isset($this->data['Comment']['update']) ? '' : 'display:none;'; ?>">
            <?php echo $this->Form->input('Language.update',
                    array(
                        'type' => 'select',
                        'label' => false,
                        'options' => array(
                            'enable' => __t('Enable selected languages'),
                            'disable' => __t('Disable selected languages'),
                            'delete' => __t('Delete selected languages')
                        )
                    )
                );
            ?>
            <?php echo $this->Form->input(__t('Update'), array('type' => 'submit', 'label' => false , 'confirm' => 'caca')); ?>
        </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
    <?php echo $this->Html->table($results, $tSettings); ?>
<?php echo $this->Form->end(); ?>

 
<script type="text/javascript">
    $("#toggle-update_fieldset").click(function () {
        $("#update_fieldset").toggle('fast', 'linear');
    });

    $("#toggle-add_fieldset").click(function () {
        $("#add_fieldset").toggle('fast', 'linear');
    });
    
    $("#toggle-addCustom_fieldset").click(function () {
        $("#addCustom_fieldset").toggle('fast', 'linear');
        $("#predefinedList").toggle('fast', 'linear');
    });
</script>
