<?php

/**
 * Feedback form.
 *
 * @package    elperro
 * @subpackage form
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class FeedbackForm extends BaseFeedbackForm
{
    public function configure()
    {
        unset($this['created_at'],$this['updated_at']);
        
        $this->widgetSchema['action'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['affiliate_id'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['promo_id'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['asset_id'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();
        
        $this->widgetSchema['message'] = new sfWidgetFormTextarea();        
        $this->widgetSchema->moveField('message', sfWidgetFormSchema::AFTER, 'valoration');
        
        $this->validatorSchema['message']->setOption('required',false);
    }
}
