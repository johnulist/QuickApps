<?php
/**
 * User Model
 *
 * PHP version 5
 *
 * @category User.Model
 * @package  QuickApps
 * @version  1.0
 * @author   Christopher Castro <chris@qucikapps.es>
 * @link     http://cms.quickapps.es
 */
class User extends UserAppModel {
    public $name = 'User';
    public $useTable = "users";
    public $actsAs = array('Field.Fieldable');
    public $validate = array(
        'username' => array(
            'alphanumeric' => array(
                'rule' => 'alphaNumeric',  
                'message' => 'Only letters and numbers allowed'
            ),
            'minlength' => array(
                'rule' => array('minLength', '3'),
                'message' => 'Minimum length of 3 characters'
            ),
            'maxlength' => array(
                'rule' => array('maxLength', '32'),  
                'message' => 'Maximum length of 32 characters'
            ),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'Username already in use'
			)
        ),
        'name' => array(
			'required' => true,
			'allowEmpty' => false,
			'rule' => 'notEmpty',
			'message' => 'You must enter your real name.'
        ),
		'email' => array(
			'email' => array(
				'required' => true,
				'allowEmpty' => false,
				'rule' => 'email',
				'message' => 'Invalid email',
				'last' => true
			),
			'unique' => array(
				'required' => true,
				'allowEmpty' => false,
				'rule' => 'isUnique',
				'message' => 'Email already in use'
			)
		),
		'password' => array(
			'required' => true,
			'allowEmpty' => false,
			'rule' => 'comparePwd',
			'message' => 'Password mismatch or less than 6 characters'
		)
    );

    public $hasAndBelongsToMany = array(
        'Role' => array(
			'className' => 'User.Role',
			'joinTable' => 'users_roles',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'role_id'
        )
    );
    
    public function beforeValidate() {
        if (isset($this->data['User']['id'])) {
            $this->validate['password']['allowEmpty'] = true;
        }
        
        return true;
    }

    public function beforeSave() {
        App::uses('Security', 'Utility');
        App::uses('String', 'Utility');
            
        if (empty($this->data['User']['password'])) { # empty password -> do not update
            unset($this->data['User']['password']);
        } else {
            $this->data['User']['password'] = Security::hash($this->data['User']['password'], null, true);
        }
        
        $this->data['User']['key'] = String::uuid();
        
        return true;
    }
	
	public function comparePwd($check) {
        $check['password'] = trim($check['password']);
        
        if (!isset($this->data['User']['id']) && strlen($check['password']) < 6) {
            return false;
        }
        
        if (isset($this->data['User']['id']) && empty($check['password'])) {
            return true;
        }
        
        $r = ($check['password'] == $this->data['User']['password2'] && strlen($check['password']) >= 6);
        
		if (!$r) {
            $this->invalidate('password2', __d('user', 'Password missmatch') );
        }
        
        return $r;
	}
}