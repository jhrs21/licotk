<?php

/**
 * Description of sfWidgetFormSchemaFormatterEpWebWithDelete
 *
 * @author jacobo
 */
class sfWidgetFormSchemaFormatterEpWebWithDelete extends sfWidgetFormSchemaFormatterEpWeb {

    protected
        $deleteAction = '',
        $deleteActionTrigger = '<div class=\"form_delete_container\"><button class=\"form_delete_button\" url="%delete-action%">Eliminar</button></div>',
        $decoratorFormat = "<div>\n%content% %delete-trigger%</div>";

    /**
     * Constructor
     *
     * @param sfWidgetFormSchema $widgetSchema
     */
    public function __construct(sfWidgetFormSchema $widgetSchema, $deleteAction = null) {
        parent::__construct($widgetSchema);
        
        $this->deleteAction = $deleteAction;
    }

    public function formatRow($label, $field, $errors = array(), $help = '', $hiddenFields = null) {
        $row = parent::formatRow($label, $field, $errors, $help, $hiddenFields);

        return strtr($row, array('%row_class%' => (count($errors) > 0) ? ' form_row_error' : '',));
    }

}

