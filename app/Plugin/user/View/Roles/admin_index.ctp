<?php echo $this->Form->create('Role'); ?>
    <!-- Filter -->
    <?php echo $this->Html->useTag('fieldsetstart', '<span id="toggle-add_fieldset" style="cursor:pointer;">' . __t('Add role') . '</span>' ); ?>
        <div id="add_fieldset" class="horizontalLayout" style="<?php echo isset($this->data['Role']['name']) ? '' : 'display:none;'; ?>">
            <?php echo $this->Form->input('name',
                    array(
                        'type' => 'text',
                        'label' => __t('Name')
                    )
                );
            ?>
            <?php echo $this->Form->input(__t('Add role'), array('type' => 'submit', 'label' => false ) ); ?>
        </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
<?php echo $this->Form->end(); ?>

<?php 
$tSettings = array(
	'columns' => array(
		__t('Name') => array(
			'value' => '{Role.name}',
			'sort' => 'Role.name'
		),
		__t('Actions') => array(
			'value' => "
                <a href='{url}/admin/user/roles/edit/{Role.id}{/url}'>" . __t('edit') . "</a> 
                {php} return !in_array({Role.id}, array(1, 2, 3) ) ? \" | <a href='{url}/admin/user/roles/delete/{Role.id}{/url}' onClick='return confirm(\\\"" . __t('Are you sure?') . "\\\");'>" . __t('delete') . "</a>\" : \"\"; {/php}",
			'thOptions' => array('align' => 'right'),
			'tdOptions' => array('align' => 'right')
		),
	),
    'noItemsMessage' => __t('There are no roles to display'),
	'paginate' => false,
	'headerPosition' => 'top',
	'tableOptions' => array('width' => '100%')	# table attributes
); 

echo $this->Html->table($results, $tSettings);

?>

<script>
    $("#toggle-add_fieldset").click(function () {
        $("#add_fieldset").toggle('fast', 'linear');
    }); 

</script>
