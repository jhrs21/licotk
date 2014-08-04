<?php
/**
 * Description of epRedeemPrizeForm
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class epRedeemPrizeForm extends BaseForm {
    protected   $prize, 
                $canBeRedeemed = false;
    
    public function getPrize() {
        return $this->prize;
    }
    
    public function getCanBeRedeemed() {
        return $this->canBeRedeemed;
    }

    public function configure() {
        if (!($this->getOption('user') instanceof sfGuardUser)) {
            throw new InvalidArgumentException("You must pass a user object as an option to this form!");
        } else {
            $user = $this->getOption('user');
        }
        
        if (!($this->getOption('promo') instanceof Promo)) {
            throw new InvalidArgumentException("You must pass a Promo object as an option to this form!");
        } else {
            $promo = $this->getOption('promo');
        }
        
        $this->prize = $this->getOption('prize', false);
        
        $card = $this->getOption('card', false);
        
        if ($card) {
            $this->canBeRedeemed = true;
        }

        $this->setWidgets(array(
            'promo' => new sfWidgetFormInputHidden(),
            'prize' => new sfWidgetFormInputHidden(),
            'user' => new sfWidgetFormInputHidden(),
            'card' => new sfWidgetFormInputHidden(),
        ));
        
        $this->setDefaults(array(
                'promo' => $promo->getAlphaId(),
                'prize' => $this->prize ? $this->prize->getAlphaId() : '',
                'user'  => $user->getAlphaId(),
                'card'  => $card ? $card->getAlphaId() : '')
            );

        $this->setValidators(array(
            'promo' => new sfValidatorDoctrineChoice(array(
                    'model' => 'Promo',
                    'column' => 'alpha_id'
                )),
            'prize' => new sfValidatorDoctrineChoice(array(
                    'model' => 'PromoPrize',
                    'query' => Doctrine::getTable('PromoPrize')->addByPromoQuery($promo->getId()),
                    'column' => 'alpha_id'
                )),
            'user' => new sfValidatorDoctrineChoice(array(
                    'model' => 'sfGuardUser',
                    'column' => 'alpha_id'
                )),
            'card' => new sfValidatorDoctrineChoice(array(
                    'model' => 'Card',
                    'query' => Doctrine::getTable('Card')->addByUserQuery($user->getId(),Doctrine::getTable('Card')->addByPromoQuery($promo->getId())),
                    'column' => 'alpha_id'
                )),
        ));

        $this->validatorSchema->setPostValidator(new epValidatorRedeemPrize());

        $this->widgetSchema->setNameFormat('epRedeemPrize[%s]');

        $this->widgetSchema->setFormFormatterName('epWeb');
    }
}
