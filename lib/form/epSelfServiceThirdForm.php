<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of epSelfServiceThirdForm
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class epSelfServiceThirdForm extends BaseForm {

    public function configure() {
        $this->setWidgets(array(
            'promo' => new sfWidgetFormInputText(),
            'description' => new sfWidgetFormTextarea(),
            'max_uses' => new sfWidgetFormInput(),
            'max_daily_tags' => new sfWidgetFormInput(),
            'starts_at' => new sfWidgetFormInput(array(), array('class' => 'datepicker')),
            'ends_at' => new sfWidgetFormInput(array(), array('class' => 'datepicker')),
            'begins_at' => new sfWidgetFormInput(array(), array('class' => 'datepicker')),
            'expires_at' => new sfWidgetFormInput(array(), array('class' => 'datepicker'))
        ));

        $this->widgetSchema->setLabels(array(
            'promo' => 'Nombre de la promoción',
            'description' => 'Descripción',
            'max_uses' => 'Cantidad de premios posibles por usuario',
            'max_daily_tags' => 'Cantidad de "tags" diarios',
            'starts_at' => 'Fecha de inicio de promoción',
            'ends_at' => 'Fecha final de promoción',
            'begins_at' => 'Fecha de inicio del canje',
            'expires_at' => 'Fecha final del canje'
        ));

        $this->setValidators(array(
            'promo' => new sfValidatorString(),
            'description' => new sfValidatorString(),
            'max_uses' => new sfValidatorInteger(),
            'max_daily_tags' => new sfValidatorInteger(),
            'starts_at' => new sfValidatorDate(array('date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~')),
            'ends_at' => new sfValidatorDate(array('date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~')),
            'begins_at' => new sfValidatorDate(array('date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~')),
            'expires_at' => new sfValidatorDate(array('date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~'))
        ));

        $this->widgetSchema->setFormFormatterName('epWeb');
        $this->widgetSchema->setNameFormat('thirdStep[%s]');
    }

}

?>
