<?php
/**
 * Description of sfWidgetFormSchemaFormatterEpWeb
 *
 * @author jacobo
 */
class sfWidgetFormSchemaFormatterEpWeb extends sfWidgetFormSchemaFormatter 
{
    protected
        $rowFormat       = "<div class=\"form_row %row_class%\">
                                <div class=\"form_row_label\">%label%</div>
                                <div class=\"form_row_field\">
                                    %error%
                                    %field%
                                </div>
                                %help% 
                                %hidden_fields%\n
                            </div>\n",
        $errorRowFormat  = "<div class=\"form_errors\">%errors%</div>",
        $namedErrorRowFormatInARow = "    <li>%error%</li>\n",
        $helpFormat      = "<div class=\"form_help\">%help%</div>",
        $decoratorFormat = "<div>\n%content%</div>";
    
    public function formatRow($label, $field, $errors = array(), $help = '', $hiddenFields = null) 
    {
        $row = parent::formatRow($label, $field, $errors, $help, $hiddenFields);

        return strtr($row, array('%row_class%' => (count($errors) > 0) ? ' form_row_error' : '',));
    }
}

