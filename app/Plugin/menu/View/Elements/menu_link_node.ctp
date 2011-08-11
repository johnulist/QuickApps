<?php 
    $opts = array();
    $opts[] = $this->Html->link(__t('edit'), '/admin/menu/manage/edit_link/' . $data['MenuLink']['id']);
    $opts[] = $data['MenuLink']['module'] !== 'menu' ? '' :  $this->Html->link(__t('delete'), "/admin/menu/manage/delete_link/{$data['MenuLink']['id']}", array(), __t('Delete selected link ?') );
    $disabled = !$data['MenuLink']['status'] ? ' (' . __t('disabled') . ') ' : '';
    $opts = implode(' | ', Set::filter($opts));
    $return = "<div>" . __t($data['MenuLink']['link_title']) . " <em>{$disabled}</em> <span style='float:right;'>{$opts}</span></div>";
    echo $return;