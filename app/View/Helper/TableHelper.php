<?php
/**
 * Table Helper
 *
 * PHP version 5
 *
 * @category View/Helper
 * @package  QuickApps
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class TableHelper extends AppHelper {
    var $helpers = array('Html', 'Paginator');
    
	var $_defaults = array(
		'columns' => array(),			# Array: db results
		'headerPosition' => 'top',		# Mix: render header at top, bottom, top&bottom, false (no render)
		'headerRowOptions' => array(),	# Array: header <tr> tag attributes
		'noItemsMessage' => 'There are no items to display',	# String: message when there are 0 records	
		'tableOptions' => array(),		# Array: table tag attributes
		'paginate' => array(    		# Array: or false for no pagination
			'options' => array(),
			'prev' => array(
				'title' => '« Previous ',
				'options' => array(),
				'disabledTitle' => null,
				'disabledOptions' => array('class' => 'disabled')
			),
			'numbers' => array(			
				'options' => array(
                    'before' => ' &nbsp; ', 
                    'after' => ' &nbsp; ', 
                    'modulus' => 10, 
                    'separator' => ' &nbsp; ', 
                    'tag' => 'span', 
                    'first' => 'first', 
                    'last' => 'last',
                    'ellipsis' => '...'
                )
			),
			'next' => array(
				'title' => ' Next »',
				'options' => array(),
				'disabledTitle' => null,
				'disabledOptions' => array('class' => 'disabled')
			),
			
			'position' => 'bottom',								# String: row position, 'top', 'top&bottom', 'bottom'
			'trOptions' => array('class' => 'paginator'),		# Array: <tr> tag attributes
			'tdOptions' => array('align' => 'center')			# Array: <td> tag attributes
		)				
	);
	
	var $_columnDefaults = array(
		'value' => '',								# String: cell content, 
		'thOptions' => array('align' => 'left'),	# Array: th attributes, header cells (text align left by default)
		'tdOptions' => array('align' => 'left'),	# Array: td attributes, body cells (text align left by default)
		'sort' => false								# Mix: sortable field name:String, false (no sort this col), paginate must be on (see paginate option)
	);
	
	var $_colsCount = 0;
	
	function create($data, $options){
        $this->_defaults['paginate']['prev']['title'] = __t('« Previous ');
        $this->_defaults['paginate']['next']['title'] = __t(' Next »');
		if ( isset($options['paginate']) && $options['paginate'] === true ){
			unset($options['paginate']); # default settings
		} else {
			$this->_defaults['paginate'] = !isset($options['paginate']) ? false : $this->_defaults['paginate'];
		}
		
		$options = array_merge($this->_defaults, $options);
		$this->_colsCount = count($options['columns']);
		
		
		$out = sprintf('<table%s>', $this->Html->_parseAttributes($options['tableOptions'])) . "\n";
		
		if ( count($data) > 0 ){
        
            $print_header_top = ($options['headerPosition'] !== false && in_array($options['headerPosition'], array('top', 'top&bottom') ));
            $print_paginator_top = ($options['paginate'] !== false && in_array($options['paginate']['position'], array('top', 'top&bottom') ));
        
			if ($print_header_top ||  $print_paginator_top){
                $out .= "\t<thead>\n";
                    $out .= $print_header_top ? $this->_renderHeader($options) : '';
                    $out .= $print_paginator_top ? $this->_renderPaginator($options) : '';
                $out .= "\n\t</thead>\n";
            }
				
			$out .= "\t<tbody>\n";
			foreach ( $data as $i => $r_data){
				$td = '';
				foreach ($options['columns'] as $name => $c_data){
					$c_data = array_merge($this->_columnDefaults, $c_data);
					
					$td .= "\n\t";
					$td .= $this->Html->useTag('tablecell', $this->Html->_parseAttributes($c_data['tdOptions']),$this->_renderCell($c_data['value'], $data[$i]) );
					$td .= "\t";
				}
				
				$tr_class = $i%2 ? 'even' : 'odd';
				$out .= $this->Html->useTag('tablerow', $this->Html->_parseAttributes(array('class' => $tr_class)), $td);
			}
			
			
			$out .= "\t</tbody>\n";
			
            $print_header_bottom = ($options['headerPosition'] !== false && in_array($options['headerPosition'], array('bottom', 'top&bottom') ));
            $print_paginator_bottom = ($options['paginate'] != false && in_array($options['paginate']['position'], array('bottom', 'top&bottom') ));
			
            if ($print_header_bottom || $print_paginator_bottom){
                $out .= "\t<tfoot>\n";
                    $out .= $print_header_bottom ? $this->_renderHeader($options) : '';
                    $out .= $print_paginator_bottom ? $this->_renderPaginator($options) : '';
                $out .= "\n\t</tfoot>\n";
            }

		} else {
			$td   = $this->Html->useTag('tablecell', $this->Html->_parseAttributes(array('colspan' => $this->_colsCount)), __t($options['noItemsMessage']));
			$out .= $this->Html->useTag('tablerow', $this->Html->_parseAttributes(array('class' => 'even')), $td);
		}
			
		$out .= "</table>\n";
		return $out;
	}
	
	function _renderCell($value, $row_data){
		# look for urls
		preg_match_all('/\{url\}(.+)\{\/url\}/iUs', $value, $matches);
		if ( isset($matches[1]) && !empty($matches[1]) ){
			foreach( $matches[0] as $i => $m ){
				$value = str_replace($m, $this->Html->url(trim($matches[1][$i]), true), $value);
			}
		}	
	
		# look for array paths
		preg_match_all('/\{([\{\}0-9a-zA-Z_\.]+)\}/iUs', $value, $matches);
		if ( isset($matches[1]) && !empty($matches[1]) ){
			foreach( $matches[0] as $i => $m ){
				if ( in_array($m, array('{php}', '{/php}') ) )
					continue;
					
				$value = str_replace($m, Set::extract(trim($matches[1][$i]), $row_data), $value);
			}
		}

		# look for php code
		preg_match_all('/\{php\}(.+)\{\/php\}/iUs', $value, $matches);
		if ( isset($matches[1]) && !empty($matches[1]) ){
			foreach( $matches[0] as $i => $m ){
				$value = str_replace($m, $this->__php_eval("<?php {$matches[1][$i]}", $row_data), $value);
			}
		}
		return $value;
	}
    
    function __php_eval($code, $row_data = array() ) {
        ob_start();
        print eval('?>' . $code);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }    
	
	function _renderHeader($options, $footer = false){
		$th = $out ='';
        
		if ( $footer && $options['paginate'] !== false && in_array($options['paginate']['position'], array('top', 'top&bottom') ) )
			@$out .= $this->_renderPaginator($options);
		
		foreach ($options['columns'] as $name => $data){
			$data = array_merge($this->_columnDefaults, $data);
			if ( $options['paginate'] !== false && is_string($data['sort']) )
				@$name = $this->Paginator->sort($data['sort'], $name);
				
			$th .= "\t\t". $this->Html->useTag('tableheader', $this->Html->_parseAttributes($data['thOptions']), $name) . "\n";
		}
		
		$out .= $this->Html->useTag('tablerow', null, $th);
		
		return $out;
	}
    
	function _renderPaginator($array){
		$out = $paginator = '';
		$array = $array['paginate'];
        
		$paginator .= $this->Paginator->options($array['options']);
		$paginator .= $this->Paginator->prev($array['prev']['title'], $array['prev']['options'], $array['prev']['disabledTitle'], $array['prev']['disabledOptions']);
		$paginator .= $this->Paginator->numbers($array['numbers']['options']);
		$paginator .= $this->Paginator->next($array['next']['title'], $array['next']['options'], $array['next']['disabledTitle'], $array['next']['disabledOptions']);

		$td	= $this->Html->useTag('tablecell', $this->Html->_parseAttributes(array_merge(array('colspan' => $this->_colsCount), $array['tdOptions'])), $paginator);
		$out .= $this->Html->useTag('tablerow', $this->Html->_parseAttributes($array['trOptions']), $td);

		return $out;
	}
}