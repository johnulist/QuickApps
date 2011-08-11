<?php
/**
 * Term Model
 *
 * PHP version 5
 *
 * @category Taxonomy.Model
 * @package  QuickApps
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class Term extends TaxonomyAppModel {
	public $useTable = 'terms';
    public $actsAs = array('Tree', 'Sluggable' => array('label' => 'name'));
    public $validate = array(
        'name' => array('required' => true, 'allowEmpty' => false, 'rule' => 'notEmpty', 'message' => 'Term name can not be empty')
    );
}