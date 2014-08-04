<?php

/**
 * Description of epSearchUserForm
 *
 * @author Jacobo Martínez <jacobo.amn87@lealtag.com>
 */
class epSearchUserPrizesForm extends BaseForm {

    public function configure() {
        if (!($this->getOption('user') instanceof sfGuardUser)) {
            throw new InvalidArgumentException("You must pass a user object as an option to this form!");
        } else {
            $user = $this->getOption('user');
        }

        $this->setWidgets(array(
            'user' => new sfWidgetFormInput(array(), 
                array(
                    'data-bvalidator' => 'required',
                    'data-bvalidator-msg' => 'Indica el email del usuario o el código de su tarjeta Licoteca'
                )),
            'promo' => new sfWidgetFormDoctrineChoice(
                array(
                    'model' => 'Promo',
                    'query' => Doctrine::getTable('Promo')->getPromosQuery(true,$user->getAffiliateId(),$user->getAssetId())
                ), 
                array(
                    'data-bvalidator' => 'required',
                    'data-bvalidator-msg' => 'Indica la promoción sobre la que buscar los premios del usuario'
                )),
        ));

        $this->widgetSchema->setLabels(array('user' => 'Email o Tarjeta Licoteca del cliente:', 'promo' => 'Promoción:'));

        $this->setValidators(array(
            'user' => new sfValidatorString(),
            'promo' => new sfValidatorDoctrineChoice(array(
                    'model' => 'Promo',
                    'query' => Doctrine::getTable('Promo')->getPromosQuery(true,$user->getAffiliateId())
                )),
        ));

        $this->validatorSchema->setPostValidator(new epValidatorSearchUserPrizes());

        $this->widgetSchema->setNameFormat('epSearchUserPrizes[%s]');

        $this->widgetSchema->setFormFormatterName('epWeb');
    }

}

?>
