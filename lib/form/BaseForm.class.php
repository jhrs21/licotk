<?php

/**
 * Base project form.
 * 
 * @package    elperro
 * @subpackage form
 * @author     Your name here 
 * @version    SVN: $Id: BaseForm.class.php 20147 2009-07-13 11:46:57Z FabianLange $
 */
class BaseForm extends sfFormSymfony {

    public function getVisibleFields() {
        $hidden_fields = $this->getFormFieldSchema()->getHiddenFields();
        $fields = array();
        foreach ($this as $name => $field) {
            if (!in_array($field, $hidden_fields))
                $fields[$name] = $field;
        }
        return $fields;
    }

}
