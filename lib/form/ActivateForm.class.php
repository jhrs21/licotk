<?php
class ActivateForm extends BaseForm
{
    public function configure()
    {
        $promo_codes=array("a-a","b-b","c-c");
        $this->setWidgets(array(
            'promo' => new sfWidgetFormChoice(array('choices' => $promo_codes)),
//            'rango_superior' => new sfWidgetFormInput(),
//            'rango_inferior' => new sfWidgetFormInput()
            'serial inferior' => new sfWidgetFormInput(),
            'serial superior' => new sfWidgetFormInput()
        ));
    }
}