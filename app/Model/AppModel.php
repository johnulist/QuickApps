<?php 
/**
 * Application Model
 *
 * PHP version 5
 *
 * @package  QuickApps.Model
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class AppModel extends Model {
	public $cacheQueries = false;
	public $listeners = array();
	public $events = array();
    public $actsAs = array(
        'WhoDidIt' => array(
            'auth_session' => 'Auth.User.id', 
            'user_model' => 'User.User'
        )
    ); 

	public function __construct($id = false, $table = null, $ds = null) {
		$this->__loadHooks();
		parent::__construct($id, $table, $ds);
		$this->__loadHookEvents();
    }

/**
 * Marks a field as invalid, optionally setting the name of validation
 * rule (in case of multiple validation for field) that was broken.
 *
 * @param string $field The name of the field to invalidate
 * @param mixed $value Name of validation rule that was not failed, or validation message to
 *    be returned. If no validation key is provided, defaults to true.
 * @return void
 */
	public function invalidate($field, $value = true) {
        $value = is_string($value) ? __t($value) : $value;
		parent::invalidate($field, $value);

        return;
	}

/**
 * Chech if hook exists
 *
 * @param string $hook Name of the hook to check
 * @return bool
 */
	public function hook_defined($hook) {
		return (in_array($hook, $this->events) == true);
	}    

/**
 * Trigger a callback method on every HookBehavior.
 *
 * ### Options
 *
 * - `breakOn` Set to the value or values you want the callback propagation to stop on.
 *    Can either be a scalar value, or an array of values to break on. 
 *    Defaults to `false`.
 *
 * - `break` Set to true to enabled breaking. When a trigger is broken, the last returned value
 *    will be returned.  If used in combination with `collectReturn` the collected results will be returned.
 *    Defaults to `false`.
 *
 * - `collectReturn` Set to true to collect the return of each object into an array.
 *    This array of return values will be returned from the hook() call. Defaults to `false`.
 *
 * - `alter` Allows each callback gets called on to modify the parameters to the next object.
 *    Defaults to true.
 * 
 * @param string $event name of the hook to call
 * @param mixed $data data for the triggered callback
 * @param array $option Array of options
 * @return mixed Either the last result or all results if collectReturn is on. Or null in case of no response
 */
	public function hook($hook, &$data = array(), $options = array()) {
		return $this->__dispatchEvent($hook, $data, $options);
	}    

/**
 * Dispatch Component-hooks from all the plugins and core
 *
 * @see AppModel::hook()
 * @return mixed Either the last result or all results if collectReturn is on. Or NULL in case of no response
 */
	private function __dispatchEvent($event, &$data = array(), $options = array()) {
		$options = array_merge(
			array(
				'break' => false,
				'breakOn' => false,
				'collectReturn' => false,
				'alter' => true
			),
			(array)$options
		);

        # protect original varriable
        if (!$options['alter']) {
            $_data = $data;
        }
        
		$collected = array();

		if (!$this->hook_defined($event)) {
            return null;
        }
        
		foreach ($this->listeners as $object => $methods) {
			foreach ($methods as $method) {
				if ($method == $event && is_callable(array($this->Behaviors->{$object}, $method))) {
                    if (!$options['alter']) {
                        $result = call_user_func(array($this->Behaviors->{$object}, $event), $_data);
                    } else {
                        $result = call_user_func(array($this->Behaviors->{$object}, $event), $data);
                    }
                    
                    if ($options['collectReturn'] === true) {
                        $collected[] = $result;
                    }
                    
                    if ($options['break'] && 
                        ($result === $options['breakOn'] || (is_array($options['breakOn']) && in_array($result, $options['breakOn'], true)))
                    ) {
                        return $result;
                    }
				}
			}
		}

        if (empty($collected) && empty($result)) {
            return null;
        }

        return $options['collectReturn'] ? $collected : $result;    
	}
    
	private function __loadHooks() {
        $b = Configure::read('Hook.behaviors');

        if (!$b){
            return false; # fix for AppController __preloadHooks()
        }

		foreach ($b as $hook) {
			$this->actsAs[$hook] = array();
		}
	}
	
	private function __loadHookEvents() {
		foreach ($this->actsAs as $behavior => $b_data) {
			$behavior = strpos($behavior, '.') !== false ? substr($behavior, strpos($behavior, '.')+1) : $behavior;
            
			if (strpos($behavior, 'Hook')) {
				$methods = array();
				$_methods = get_this_class_methods($this->Behaviors->{$behavior});

				foreach ($_methods as $method) {
					$methods[] = $method;
				}

				$this->listeners[$behavior] = $methods;
				$this->events = array_merge($this->events, $methods);
			}
		}
		
		$this->events = array_unique($this->events);

		return true;	
	}
}