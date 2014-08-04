<?php
/**
 * Description of epSurveyForm
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class epSurveyForm extends SurveyApplicationForm {

    public function configure() {
        parent::configure();
        
        unset($this['id'],$this['action'],$this['via'],$this['user_id'],$this['survey_id'],$this['asset_id']);
        
        if (!($this->getOption('items') instanceof Doctrine_Collection)) {
            throw new InvalidArgumentException("You must pass a SurveyItem collection as an option to this form!");
        } else {
            $items = $this->getOption('items');
        }
        
        if (!($this->getOption('user') instanceof sfGuardUser)) {
            throw new InvalidArgumentException("You must pass an User object as an option to this form!");
        } else {
            $user = $this->getOption('user');
        }
        
        $itemsForms = new SfForm();
//        $usesAlphaId = $this->getOption('usesAlphaId', false);
//        $count = 0;
        foreach ($items as $key => $item) {
            if (!($item instanceof SurveyItem)) {
                throw new InvalidArgumentException("You must pass a SurveyItem collection/array as an option to this form!");
            }

            $itemAnswer = new SurveyItemAnswer();
            
            $itemAnswer->setUser($user);
            $itemAnswer->setItem($item);
            $itemAnswer->setSurvey($item->getSurvey());
            $itemAnswer->setApplication($this->getObject());

            $itemForm = new epSurveyItemAnswerForm($itemAnswer, array('item' => $item));
            //$itemForm = new epSurveyItemAnswerForm($itemAnswer, array('item' => $item, 'usesAlphaId' => $usesAlphaId));
            
            $itemsForms->embedForm($item->getAlphaId(), $itemForm);

//            $itemsForms->embedForm(($usesAlphaId ? $item->getAlphaId() : $count), $itemForm);
//            $count++;
        }
        
        $this->embedForm('items', $itemsForms);
        
        $this->widgetSchema->setFormFormatterName('epWeb');
    }
}