<?php
/**
 * Manage Controller
 *
 * PHP version 5
 *
 * @category Menu.Controller
 * @package  QuickApps
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class ManageController extends MenuAppController {

	var $name = 'Manage';
	var $uses = array('Menu.Menu');
    var $helpers = array('Menu.Tree');
	
	function admin_index(){
		$this->setCrumb('/admin/system/structure');
		$this->setCrumb(array(__t('Menu'), ''));
		$this->title( __t('Menus') );
    
        $this->Menu->recursive = -1;
		$this->set('results', $this->paginate('Menu'));
	}
    
    // delete menu
    function admin_delete($menu_id){
        if ( in_array($menu_id, array('main-menu', 'management', 'navigation', 'user-menu') ) )
            $this->redirect('/admin/menu/manage/');
        $this->Menu->delete($menu_id);
        $this->redirect($this->referer());
    }
    
    // add menu 
    function admin_add(){
		$this->setCrumb('/admin/system/structure');
		$this->setCrumb(
            array(
                array(__t('Menu'), '/admin/menu'),
                array(__t('Add menu'), '')
            )
        );
		$this->title( __t('Menus') );
        
        if ( isset($this->data['Menu']) ){
            $data['Menu']['locale'] = !empty($data['Menu']['locale']) ? array_values($data['Menu']['locale']) : array();
            $data = $this->data;
            $data['Menu']['module'] = 'menu';
            if ( $this->Menu->save($data) ){
                $this->flashMsg(__t('Menu has been saved'), 'success');
                $this->redirect('/admin/menu/manage/add_link/' . $this->Menu->id );
            } else {
                $this->flashMsg(__t('Menu could not be saved. Please, try again.'), 'error');
            }
        }
    }
    
    // edit menu info
    function admin_edit($menu_id){

        if ( isset($this->data['Menu']) ){
            $d = $this->data;
            $d['Menu']['id'] = $menu_id;
            if ( $this->Menu->save($d) ){
                $this->flashMsg(__t('Menu has been saved'), 'success');
            } else {
                $this->flashMsg(__t('Menu could not be saved. Please, try again.'), 'error');
            }
        } else {
            $this->data = $this->Menu->find('first', 
                array(
                    'conditions' => array('Menu.id' => $menu_id),
                    'recursive' => -1
                )
            );
        }
        
		$this->setCrumb('/admin/system/structure');
		$this->setCrumb( array(array(__t('Menu'), '/admin/menu/manage'), array(__t('Editing menu'), '')) );
        $this->title( __t('Editing Menu') );
    }
    
    function admin_delete_link($link_id){
        $link = $this->MenuLink->findById($link_id);
        if ( !$link || $link['MenuLink']['module'] != 'menu' )
            $this->redirect('/admin/menu');
        
        $this->MenuLink->Behaviors->detach('Tree');
        $this->MenuLink->Behaviors->attach('Tree', array('parent' => 'parent_id', 'left' => 'lft', 'right' => 'rght', 
        'scope' => "MenuLink.menu_id = '{$link['MenuLink']['menu_id']}'" ));
        $this->MenuLink->removeFromTree($link_id, true);
        $this->redirect($this->referer());
    }
    
    // add link to menu
    function admin_add_link($menu_id){
        $this->Menu->recursive = 1;
        $this->Menu->unbindModel( array('belongsTo' => array('Block') ) );
        $menu = $this->Menu->findById($menu_id) or $this->redirect('/admin/menu');
        if ( isset($this->data['MenuLink']) ){
            $data = $this->data;
            $data['MenuLink']['module'] = 'menu';
            $data['MenuLink']['parent_id'] = empty($data['MenuLink']['parent_id']) ? 0 : $data['MenuLink']['parent_id'];
            
            $this->MenuLink->Behaviors->detach('Tree');
            $this->MenuLink->Behaviors->attach('Tree', array('parent' => 'parent_id', 'left' => 'lft', 'right' => 'rght', 'scope' => "MenuLink.menu_id = '{$menu_id}'" ));
            
            if ( $this->MenuLink->save($data) ){
                $this->flashMsg(__t('Menu link has been saved'), 'success');
                $this->redirect('/admin/menu/manage/add_link/' . $menu_id);
            } else {
                $this->flashMsg(__t('Menu link could not be saved. Please, try again.'), 'error');
            }
        }
    
        $this->setCrumb('/admin/system/structure');
        $this->setCrumb(
            array(
                array(__t('Menu'), '/admin/menu/manage'),
                array($menu['Menu']['title'], '/admin/menu/manage/links/' . $menu['Menu']['id'], __t('Menu links list')),
                array( __t('Add menu link') )
            )
        );
        $this->title( __t('Add menu link') );
        $links = $this->MenuLink->generateTreeList("MenuLink.menu_id = '{$menu_id}'", null, null, '&nbsp;&nbsp;|- ');
        $this->set(compact('menu_id', 'links'));

    }
    
    // edit single menu link
    function admin_edit_link($id){
        if ( isset($this->data['MenuLink']) ){
            $data = $this->data;
            $data['MenuLink']['id'] = $id;
            if ( $this->Menu->MenuLink->save($data) ){
                $this->flashMsg(__t('Menu link has been saved'), 'success');
                $this->redirect('/admin/menu/manage/edit_link/' . $this->Menu->MenuLink->id);
            } else {
                $this->flashMsg(__t('Menu link could not be saved. Please, try again.'), 'error');
            }
        }
    
        $data = $this->Menu->MenuLink->find('first', 
            array(
                'conditions' => array('MenuLink.id' => $id),
                'recursive' => -1
            )
        );
        
        if ( empty($data) ) $this->redirect('/admin/menu');
        $data['MenuLink']['router_path'] = !empty($data['MenuLink']['link_path']) ? $data['MenuLink']['link_path'] : $data['MenuLink']['router_path'];
        $this->data = $data;
        
        $this->Menu->recursive = 1;
        $this->Menu->unbindModel( array('belongsTo' => array('Block') ) );
        $menu = $this->Menu->findById($this->data['MenuLink']['menu_id']);
        
		$this->setCrumb('/admin/system/structure');
		$this->setCrumb(
            array(
                array(__t('Menu'), '/admin/menu/manage'), 
                array($menu['Menu']['title'], '/admin/menu/manage/edit/' . $menu['Menu']['id']),
                array(__t('Editing menu links'), '/admin/menu/manage/links/' . $this->data['MenuLink']['menu_id']), 
                array(__t('Editing link'), '')
            )
        );

        $this->title(__t('Editing Link'));
    }
    
    // edit order menu links list
	function admin_links($menu_id){
        $this->Menu->recursive = -1;
        $this->Menu->unbindModel( array('belongsTo' => array('Block') ) );
        $menu = $this->Menu->findById($menu_id) or $this->redirect('/admin/menu/manage');
            
        if ( isset($this->data['MenuLink']) ) {
            $this->Menu->MenuLink->Behaviors->detach('Tree');
            $items = json_decode($this->data['MenuLink']);
            unset($items[0]);
            foreach ( $items as $key => &$item){
                $item->parent_id = $item->parent_id == 'root' ? 0 : (int) $item->parent_id;
                $item->left--;
                $item->right--;
                $data['MenuLink'] = array(
                    'id' => $item->item_id,
                    'parent_id' => $item->parent_id,
                    'lft' => $item->left,
                    'rght' => $item->right
                );
                $this->Menu->MenuLink->save($data, false);
            }
            die('ok');
        }

		$this->setCrumb('/admin/system/structure');
		$this->setCrumb(
            array(
                array(__t('Menu'), '/admin/menu/manage'), 
                array($menu['Menu']['title'], '/admin/menu/manage/edit/' . $menu['Menu']['id']),
                array(__t('Editing menu links'), '')
            )
        );
        $this->title( __t('Editing Menu Links') );

        $links = $this->Menu->MenuLink->find('all', 
            array(
                'conditions' => array('MenuLink.menu_id' => $menu_id), 
                'order' => 'lft ASC'
            )
        );

		$this->set('links', $links);
        if ( empty($links) )
            $this->flashMsg(__t('There are no menu links yet. <a href="%s">Add link.</a>', Router::url("/admin/menu/manage/add_link/{$menu_id}") ), 'error');
	}

}