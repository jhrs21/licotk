<?php
/**
 * 
 */
class epActivateForm extends BaseForm
{
    public function configure()
    {
        
        $promos = Doctrine::getTable('Promo')->findBy('affiliate_id', $this->getOption('affiliate'));
        $promocodes = Doctrine::getTable('PromoCode')->findBy('status', 'unassigned');
        
        $choices = array();
        
        foreach($promos as $promo)
        {
            $choices[$promo->getId()] = $promo->getName().' - '.$promo->getDescription();
        }
        
        $choices2 = array();
        
        foreach($promocodes as $pc)
        {
            $choices2[$pc->getId()] = $pc->getSerial()." - ".$pc->getStatus();
        }
        
        $promocodes = Doctrine_Query::create()
                                        ->from('PromoCode pc')
                                        ->leftJoin('pc.Promo p')
                                        ->addWhere('p.affiliate_id = ?',$this->getOption('affiliate'))
                                        ->addWhere('pc.status = ?', 'active')
                                        ->execute();
        foreach($promocodes as $pc)
        {
            $choices2[$pc->getId()] = $pc->getSerial()." - ".$pc->getStatus();
        }
   
        $this->setWidgets(array(
            'promo' => new sfWidgetFormChoice(array('choices' => $choices)),
            'promocode' => new sfWidgetFormChoice(array('choices' => $choices2)),
            'serial inferior' => new sfWidgetFormInput(),
            'serial superior' => new sfWidgetFormInput()
        ));
        
        $this->setValidators(array(
            'promo' => new sfValidatorChoice(array('choices' => array_keys($choices))),
            'promocode' => new sfValidatorChoice(array('choices' => array_keys($choices2))),
            'serial inferior' => new sfValidatorInteger(),
            'serial superior' => new sfValidatorInteger()
        ));
        
        $this->validatorSchema->setPostValidator(new epValidatorActivateQr());
        
        $this->widgetSchema->setNameFormat('epValidatorActivateQrForm[%s]');
    }
}