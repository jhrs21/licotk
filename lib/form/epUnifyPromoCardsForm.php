<?php
/**
 * 
 */
class epUnifyPromoCardsForm extends BaseForm
{
    public function configure()
    {
        $this->setWidgets(array(
            'promo' => new sfWidgetFormDoctrineChoice(
                        array(
                            'model'    => 'Promo',
                            'query'    => Doctrine::getTable('Promo')->addActivePromosQuery(),
                            'expanded' => true
                        ), 
                        array()
                    ),
        ));
        
        $this->setValidators(array(
            'promo' => new sfValidatorDoctrineChoice(array(
                    'model'    => 'Promo',
                    'query'    => Doctrine::getTable('Promo')->addActivePromosQuery(),
                )),
        ));
        
        $this->validatorSchema->setPostValidator(new epValidatorUnifyCards());
        
        $this->widgetSchema->setNameFormat('upcf[%s]');
    }
}