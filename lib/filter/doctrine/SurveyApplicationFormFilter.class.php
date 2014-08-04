<?php

/**
 * SurveyApplication filter form.
 *
 * @package    elperro
 * @subpackage filter
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SurveyApplicationFormFilter extends BaseSurveyApplicationFormFilter {

    public function configure() {
        parent::configure();
        
        $this->setWidget(
                'created_at',
                new sfWidgetFormFilterDate(array(
                        'from_date' => new sfWidgetFormDate(), 
                        'to_date' => new sfWidgetFormDate()
                    )
                )
            );
        
        $this->setValidator(
                'created_at', 
                new sfValidatorDateRange(array(
                        'required' => false, 
                        'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 
                        'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59'))
                    )
                )
            );
    }
}
