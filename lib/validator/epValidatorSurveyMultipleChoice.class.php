<?php
/**
 * Description of epValidatorSurveyMultipleChoice
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class epValidatorSurveyMultipleChoice extends sfValidatorDoctrineChoice {
    protected function doClean($value) {
        $value = parent::doClean($value);
        
        if (!is_array($value)) {
            $value = array($value);
        }
        
        return implode(';', $value);
    }
}
