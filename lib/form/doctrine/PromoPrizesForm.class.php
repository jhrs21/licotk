<?php

/**
 * PromoPrizesForm is a wrapper for the embedded forms (EmbeddedPromoPrizeForm)
 * in PromoForm
 *
 * @author Jacobo Martínez
 */
class PromoPrizesForm extends sfForm {

    protected $promo;
    protected $count;
    protected $routing;

    public function __construct(Promo $promo, $count = null, $routing = null) {
        $this->promo = $promo;
        $this->count = $count;
        $this->routing = $routing;

        parent::__construct();
    }

    public function configure() {
        $i = 0;

        foreach ($this->promo->Prizes as $prize) {
            $this->embedForm($i, new EmbeddedPromoPrizeForm($prize, array("routing" => $this->routing)));
            $i++;
        }

        $count = max(($this->promo->isNew() ? $i + 1 : $i), $this->count);

        for ($j = $i; $j < $count; $j++) {
            $this->embedForm($j, new EmbeddedPromoPrizeForm(null, array("routing" => $this->routing)));
        }
    }
}