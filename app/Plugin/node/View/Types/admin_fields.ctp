<?php 
$tSettings = array(
	'columns' => array(
		__t('Label') => array(
			'value' => '{label}',
			'tdOptions' => array('width' => '15%')
		),    
		__t('Name') => array(
			'value' => '{name}',
            'tdOptions' => array('width' => '15%')
		),
		__t('Type') => array(
			'value' => '{field_module}',
            'tdOptions' => array('width' => '15%')
		),
		__t('Required') => array(
			'value' => '{php} return ( "{required}" == "1" ) ? "' . __t('Yes') . '" : "' . __t('No') . '";  {/php}'
		),
		__t('Actions') => array(
			'value' => "
                <a href='{url}/admin/node/types/field_settings/{id}{/url}'>" . __t('configure') . "</a> | 
                <a href='{url}/admin/field/handler/move/{id}/up{/url}'>" . __t('move up') . "</a> | 
                <a href='{url}/admin/field/handler/move/{id}/down{/url}'>" . __t('move down') . "</a> | 
                <a href='{url}/admin/field/handler/delete/{id}{/url}' onclick=\"return confirm('" . __t('Delete selected field and all related data, this can not be undone ?') . "');\">" . __t('delete') . "</a>",
			'thOptions' => array('align' => 'right'),
			'tdOptions' => array('align' => 'right')
		),
	),
    'noItemsMessage' => __t('There are no fields to display'),
	'paginate' => false,
	'headerPosition' => 'top',
	'tableOptions' => array('width' => '100%')	# table attributes
); 
?>

<?php echo $this->Form->create(); ?>
    <!-- Add -->
    <?php echo $this->Html->useTag('fieldsetstart', '<span id="toggle-addfield_fieldset" style="cursor:pointer;">' . __t('Add field') . '</span>' ); ?>
        <div id="addfield_fieldset" class="horizontalLayout" style="<?php echo isset($this->data['Field']) ? '' : 'display:none;'; ?>">
            <?php echo $this->Form->input('Field.label',
                    array(
                        'required' => 'required', 
                        'type' => 'text',
                        'size' => 15,
                        'style' => 'width:140px;',
                        'label' => __t('Label *')
                    )
                );
            ?>
        
            <?php echo $this->Form->input('Field.name',
                    array(
                        'required' => 'required', 
                        'type' => 'text',
                        'label' => __t('Name *'),
                        'between' => 'field_',
                        'size' => 15,
                        'style' => 'width:140px;',
                        'after' => ' <em>(a-z, 0-9, _)</em>'
                    )
                );
            ?>        
        
            <?php echo $this->Form->input('Field.field_module',
                    array(
                        'type' => 'select',
                        'label' => __t('Type *'),
                        'empty' => true,
                        'options' => $field_modules
                    )
                );
            ?>        
        
            <?php echo $this->Form->input(__t('Add'), array('type' => 'submit', 'label' => false)); ?>
        </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
<?php echo $this->Form->end(); ?>


<?php echo $this->Html->table($result['Field'], $tSettings); ?>

<script type="text/javascript">
    $("#toggle-addfield_fieldset").click(function () {
        $("#addfield_fieldset").toggle('fast', 'linear');
    }); 
</script>