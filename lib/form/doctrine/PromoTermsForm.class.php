<?php

/**
 * PromoConditionsForm is a wrapper for the embedded forms (EmbeddedPromoConditionForm)
 * in PromoForm
 *
 * @author Jacobo Martínez
 */
class PromoTermsForm extends sfForm {

    protected $promo;
    protected $count;
    protected $routing;
    protected $defaultTerms = array(
        'Los Tags necesarios para reclamar un premio podrán ser acumulados desde %starts_at% hasta %ends_at%.',
        'Los premios obtenidos podrán ser canjeados hasta el %expires_at%.',
        'El limite veces que un usuario puede participar en la promoción es %max_uses%.',
    );
    protected $withDefaultTerms = false;

    public function __construct(Promo $promo, $count = null, $routing = null) {
        $this->promo = $promo;
        $this->count = $count;
        $this->routing = $routing;

        parent::__construct();
    }

    public function configure() {
        $i = 0;

        foreach ($this->promo->Terms as $term) {
            $this->embedForm($i, new EmbeddedPromoTermForm($term, array("routing" => $this->routing)));
            $i++;
        }

        $count = max(($this->promo->isNew() ? $i + 1 : $i), $this->count);

        for ($j = $i; $j < $count; $j++) {
            $this->embedForm($j, new EmbeddedPromoTermForm(null, array("routing" => $this->routing)));
        }
    }
    
    public function embedPromoTermForm($name, EmbeddedPromoTermForm $form = null) {
        if (is_null($form)) {
            $form = new EmbeddedPromoTermForm(null, array("routing" => $this->routing));
        }
        
        $this->embedForm($name, $form);
    }

    protected function defaultTerms() {
        $defaults = array();

        foreach ($this->defaultTerms as $key => $defaultTerm) {
            $term = new PromoTerm();

            $term->setTerm($defaultTerm);

            $defaults[$key] = $term;
        }

        return $defaults;
    }
}