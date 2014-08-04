<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class epDateRangeForm extends sfForm {

    public function configure() {
        parent::configure();

        $this->setWidgets(array(
            'begin_date' => new sfWidgetFormInput(array(), array('class' => 'datepicker')),
            'end_date' => new sfWidgetFormInput(array(), array('class' => 'datepicker'))
        ));

        $this->setValidators(array(
            'begin_date' => new sfValidatorDate(array('date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required'=>false)),
            'end_date' => new sfValidatorDate(array('date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required'=>false))
        ));

        $this->widgetSchema->setLabels(array('begin_date'=>'Fecha de inicio', 'end_date'=>'Fecha de fin'));

//        $this->setDefault('end_date', array('month' => 10, 'day' => 13, 'year' => 2012));
        
        $this->widgetSchema->setNameFormat('dates[%s]');
        //$this->widgetSchema->setFormFormatterName('epWeb');
    }

}

?>
