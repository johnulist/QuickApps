<?php
/**
 * Field Controller Hooks
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Field.Controller.Component
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class FieldHookComponent extends Component {
	public $Controller = null;
	public $components = array('Hook');

	public function initialize(&$Controller) {
		$this->Controller = $Controller;
	}
/**
 * Return an associative array with field(s) information.
 * If $field is given, then only information for $field is return.
 * All fields information is returned otherwise.
 *
 * @param string $field optional, return only information for the specified field
 * @return array associative array with field(s) information
 */
    public function field_info($field = '') {
        $field = is_string($field) ? Inflector::underscore($field) : '';
        $field_modules = array();
        $plugins = App::objects('plugins');

        if(!empty($field) && in_array(Inflector::camelize($field), $plugins)) {
            $plugins = array();
            $plugins[] = Inflector::camelize($field);
        }

        foreach ($plugins as $plugin) {
            $_plugin = Inflector::underscore($plugin);
            $ppath = App::pluginPath($plugin);

            if (strpos($ppath, DS . 'Fields' . DS . $_plugin . DS) !== false) {
                $yaml = Spyc::YAMLLoad($ppath . "{$_plugin}.yaml");
                $yaml['path'] = $ppath;
                $field_modules[$_plugin] = $yaml;
            }
        }
 
        return $field_modules;
    }    
}