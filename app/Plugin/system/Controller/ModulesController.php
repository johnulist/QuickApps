<?php
/**
 * Modules Controller
 *
 * PHP version 5
 *
 * @category System.Controller
 * @package  QuickApps
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class ModulesController extends SystemAppController {
	var $name = 'Modules';
	var $uses = array('System.Module');
	var $components = array('Installer');
    	
	function admin_index(){
		$results = $this->Module->find('all');
	}
    	
	function admin_settings($module){
        if ( !in_array(Inflector::camelize($module), App::objects('plugins')) )
            $this->redirect('/admin/system/modules'); 
        if ( isset($this->data['Module']['name']) || isset($this->data['Variable']) ){
            if ( isset($this->data['Module']['name']) ){
                $this->Module->save($this->data);
                Cache::delete('Modules'); # regenerate modules cache
                $this->_loadModules();
            } else {
                $this->Variable->save($this->data);
            }
            $this->flashMsg(__t('Module changes has been saved!'), 'success');
            $this->redirect($this->referer());
        }
        
        $data = array();
        $data['Module'] = Configure::read("Modules.{$module}");
        $this->data = $data;
        $this->setCrumb('/admin/system/modules');
        $this->setCrumb( array($data['Module']['yaml']['name']) );
        $this->setCrumb( array(__t('Settings')) );
        $this->title( __t('Configure Module') );
	}
    
    function admin_toggle($plugin){
        if ( !in_array(Inflector::camelize($plugin), Configure::read('coreModules') ) ){
            $to = Configure::read("Modules.{$plugin}.status") == 1 ? 0 : 1;
            $this->Install = $this->Components->load(Inflector::camelize($plugin) . '.Install');
            if($to == 1){
                if(method_exists($this->Install, 'beforeEnable'))
                    $this->Install->beforeEnable();
            } else {
                if(method_exists($this->Install, 'beforeDisable'))
                    $this->Install->beforeDesactivate();
            }
            
            # turn off related blocks
            ClassRegistry::init('Block.Block')->updateAll(
                array('Block.status' => $to),
                array('Block.status <>' => 0, 'Block.module' => $plugin)
            );
            
            # turn off related menu links
            ClassRegistry::init('Menu.MenuLink')->updateAll(
                array('Menu.status' => $to),
                array('MenuLink.status <>' => 0, 'MenuLink.module' => $plugin)
            );
            
            # turn off module
            $this->Module->updateAll(
                array('Module.status' => $to),
                array('Module.name' => $plugin)
            );
            
            Cache::delete('Modules'); # regenerate modules cache
            $this->_loadModules();
            
            
            if($to == 1){
                if(method_exists($this->Install, 'afterEnable'))
                    $this->Install->afterEnable();
            } else {
                if(method_exists($this->Install, 'afterDisable'))
                    $this->Install->afterDisable();
            }
        }
        $this->redirect($this->referer());
    }
    
    function admin_uninstall($plugin){
        if ( $this->Installer->uninstall($plugin) ){
            $this->flashMsg(__t("Module '%s' has been uninstalled", $plugin), 'success');
        } else {
            $this->flashMsg(__t("Error uninstalling module '%s'", $plugin), 'error');
        }
        $this->redirect('/admin/system/modules');
    }
    
    function admin_install(){
        if ( !isset($this->data['Package']['data']) )
            $this->redirect('/admin/system/modules');
        if ( !$this->Installer->install($this->data, array('type' => 'module') ) ){
            $errors = implode('', $this->Installer->errors);
            $this->flashMsg($errors, 'error');
        } else {
            $this->flashMsg(__t('Module has been installed'), 'success');
        }
        $this->redirect('/admin/system/modules');
    }
}