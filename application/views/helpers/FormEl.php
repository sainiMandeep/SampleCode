<?php
class Zend_View_Helper_FormEl extends Zend_View_Helper_Abstract{
	public function FormEl($element,$params=null){
		if (!isset($element))
			return '';
		if (is_string($element)){
			$label = '';
			if(is_array($params)){
				if(isset($params['label'])){
					$label = $params['label'];
				}
			}
			return '
			<div>
			<label class="control-label text">'.$label.'</label>
			<div class="controls">
			'.$element.'
			</div>
			</div>';			
		}
		$style='';
		if ($element->getAttrib('hide'))
			$style = "display:none";
		if ($element->getAttrib('help'))
			$help = '<i class="icon-question-sign pop" data-content="'.$element->getAttrib('help').'" style="float:left;margin-top:7px;cursor:pointer"></i>';
		else
			$help = '';
		$message = Ontraq_Form_Error::render($element);
		if ($element->isRequired()) 
			$appendLabel = ' *';
		else 
			$appendLabel = '';
		switch($element->getType()) {
			case 'Zend_Form_Element_Text':
			case 'Zend_Form_Element_Textarea':
			case 'Zend_Form_Element_Password':
			case 'Zend_Form_Element_Select':
			case 'Osha_Forms_Employee':
			case 'Osha_Forms_Select':
				return
				'
				<div id="control-'.$element->getId().'">
				'.$help.'
				<label for="'.$element->getId().'" class="control-label">'.$element->getLabel().$appendLabel.'</label>
				<div class="controls">
				'.$element.$message.'
				</div>
				</div>';
			case 'Zend_Form_Element_Checkbox':
				return
				'<label class="control-checkbox">'.$element.'</label>
				<div class="controls">
				<label for="'.$element->getId().'">
				'.$element->getLabel().'
				</label>
				</div>';
		}
		return $element;
	}
}
?>