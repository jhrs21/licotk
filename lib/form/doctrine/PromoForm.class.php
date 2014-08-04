<?php

/**
 * Promo form.
 *
 * @package    elperro
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PromoForm extends BasePromoForm {

    public function configure() {
        unset($this['hash'], $this['alpha_id'], $this['permanent'], $this['created_at'], $this['updated_at']);

        if (!($this->getOption('user') instanceof sfUser)) {
            throw new InvalidArgumentException("You must pass a user object as an option to this form!");
        } else {
            $user = $this->getOption('user');
        }

        if (!($this->getOption('routing') instanceof sfRouting)) {
            throw new InvalidArgumentException("You must pass a routing object as an option to this form!");
        } else {
            $routing = $this->getOption('routing');
        }

        if ($user->isAuthenticated() && $user->hasGroup('admin')) {
            $this->widgetSchema['affiliate_id'] = new sfWidgetFormDoctrineChoice(
                            array('model' => $this->getRelatedModelName('Affiliate'), 'add_empty' => false),
                            array('id' => 'affiliate-select')
            );
            $this->widgetSchema->moveField('affiliate_id', sfWidgetFormSchema::FIRST);
        } else {
            $this->widgetSchema['affiliate_id'] = new sfWidgetFormInputHidden();
            $this->validatorSchema['affiliate_id'] = new sfValidatorChoice(array('choices' => array($this->getObject()->getAffiliateId())));
        }

        $this->widgetSchema->setHelp('max_uses', 'Cero (0) es equivalente a ilimitado.');
        $this->widgetSchema->setHelp('max_daily_tags', 'Cero (0) es equivalente a ilimitado.');

        $years = range(date('Y'), date('Y') + 10);

        $this->widgetSchema['starts_at'] = new sfWidgetFormDate(array(
                    'format' => '%day%/%month%/%year%',
                    'years' => array_combine($years, $years),
                ));

        $this->widgetSchema['ends_at'] = new sfWidgetFormDate(array(
                    'format' => '%day%/%month%/%year%',
                    'years' => array_combine($years, $years),
                ));

        $this->widgetSchema['begins_at'] = new sfWidgetFormDate(array(
                    'format' => '%day%/%month%/%year%',
                    'years' => array_combine($years, $years),
                ));

        $this->widgetSchema['expires_at'] = new sfWidgetFormDate(array(
                    'format' => '%day%/%month%/%year%',
                    'years' => array_combine($years, $years),
                ));

        $this->widgetSchema['photo'] = new sfWidgetFormInputFileEditable(array(
                    'file_src' => '/uploads/' . $this->getObject()->getPhoto(),
                    'edit_mode' => !$this->isNew(),
                    'is_image' => true,
                    'with_delete' => false,
                ));

        $this->validatorSchema['photo'] = new sfValidatorFile(array(
                    'required' => false,
                    'mime_types' => 'web_images',
                    'path' => sfConfig::get('sf_upload_dir'),
                ));

        $this->widgetSchema['thumb'] = new sfWidgetFormInputFileEditable(array(
                    'file_src' => '/uploads/' . $this->getObject()->getThumb(),
                    'edit_mode' => !$this->isNew(),
                    'is_image' => true,
                    'with_delete' => false,
                ));

        $this->validatorSchema['thumb'] = new sfValidatorFile(array(
                    'required' => false,
                    'mime_types' => 'web_images',
                    'path' => sfConfig::get('sf_upload_dir'),
                ));

        //Embedding at least a PromoPrize form
        $prizes = $this->getObject()->getPrizes();
        if (!$prizes->count() && $this->getObject()->isNew()) {
            $prize = new PromoPrize();
            $prize->setPromo($this->getObject());
            $prizes = array($prize);
        }
        //An empty form will act as a container for all the PromoPrizes
        $prizes_forms = new SfForm();
        $count = 0;
        foreach ($prizes as $prize) {
            $prize_form = new EmbeddedPromoPrizeForm($prize, array('routing' => $routing));
            //Embedding each form in the container
            $prizes_forms->embedForm($count, $prize_form);
            $count++;
        }
        //Embedding the container for all the PromoPrizes in the main form
        $this->embedForm('prizes', $prizes_forms);

        //Embedding at least a PromoTerm form
        $terms = $this->getObject()->getTerms();
        if (!$terms->count() && $this->getObject()->isNew()) {
            $term = new PromoTerm();
            $term->setPromo($this->getObject());
            $terms = array($term);
        }
        //An empty form will act as a container for all the PromoTerms
        $terms_forms = new SfForm();
        $count = 0;
        foreach ($terms as $term) {
            $term_form = new EmbeddedPromoTermForm($term, array('routing' => $routing));
            //Embedding each form in the container
            $terms_forms->embedForm($count, $term_form);
            $count++;
        }
        //Embedding the container for all the PromoTerms in the main form
        $this->embedForm('terms', $terms_forms);

        $this->validatorSchema->setPostValidator(new sfValidatorAnd(
                        array(
                            new sfValidatorSchemaCompare(
                                    'starts_at',
                                    sfValidatorSchemaCompare::LESS_THAN_EQUAL,
                                    'ends_at',
                                    array(),
                                    array('invalid' => 'La fecha de inicio para acumular Tags no puede ser mayor que la fecha de fin.')
                            ),
                            new sfValidatorSchemaCompare(
                                    'begins_at',
                                    sfValidatorSchemaCompare::LESS_THAN_EQUAL,
                                    'expires_at',
                                    array(),
                                    array('invalid' => 'La fecha de inicio para canjear Premios no puede ser mayor que la fecha de fin para el canje.')
                            ),
                            new sfValidatorSchemaCompare(
                                    'begins_at',
                                    sfValidatorSchemaCompare::GREATER_THAN_EQUAL,
                                    'starts_at',
                                    array(),
                                    array('invalid' => 'La fecha de inicio para el canje de Premios debe ser mayor o igual la fecha de inicio para acumular Tags.')
                            ),
                            new sfValidatorSchemaCompare(
                                    'expires_at',
                                    sfValidatorSchemaCompare::GREATER_THAN_EQUAL,
                                    'ends_at',
                                    array(),
                                    array('invalid' => 'La fecha de expiración de los Premios debe ser mayor o igual la fecha de fin para acumular Tags.')
                            ),
                        )
        ));

        if ($this->getOption('affiliate', false)) {
            $this->setDefault('affiliate_id', $this->getOption('affiliate'));
        } else if (!$this->object->isNew()) {
            $this->setDefault('affiliate_id', $this->object->getAffiliateId());
        } else {
            $this->setDefault('affiliate_id', Doctrine::getTable('Affiliate')->findAll()->getFirst()->getId());
        }

        $assetsQuery = Doctrine::getTable('Asset')->addByAffiliateQuery($this->getDefault('affiliate_id'));

        $this->widgetSchema['assets_list'] = new sfWidgetFormDoctrineChoice(
                        array(
                            'multiple' => true,
                            'expanded' => true,
                            'model' => 'Asset',
                            'query' => $assetsQuery,
                            'add_empty' => false
                        ),
                        array('id' => 'assets_list')
        );
    }

    public function addPrize($num) {
        $prize = new PromoPrize();
        $prize->setPromo($this->getObject());
        $prize_form = new EmbeddedPromoPrizeForm($prize, array('routing' => $this->getOption('routing')));

        //Embedding the new PromoPrize in the container
        $this->embeddedForms['prizes']->embedForm($num, $prize_form);
        //Re-embedding the container
        $this->embedForm('prizes', $this->embeddedForms['prizes']);
    }

    public function addTerm($num) {
        $term = new PromoTerm();
        $term->setPromo($this->getObject());
        $term_form = new EmbeddedPromoTermForm($term, array('routing' => $this->getOption('routing')));

        //Embedding the new PromoTerm in the container
        $this->embeddedForms['terms']->embedForm($num, $term_form);
        //Re-embedding the container
        $this->embedForm('terms', $this->embeddedForms['terms']);
    }

    public function bind(array $taintedValues = null, array $taintedFiles = null) {
        foreach ($taintedValues['prizes'] as $key => $prize) {
            if (!isset($this['prizes'][$key])) {
                $this->addPrize($key);
            }
        }
        
        foreach ($taintedValues['terms'] as $key => $term) {
            if (!isset($this['terms'][$key])) {
                $this->addTerm($key);
            }
        }
        parent::bind($taintedValues, $taintedFiles);
    }
}
