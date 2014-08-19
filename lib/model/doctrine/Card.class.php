<?php

/**
 * Card
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    elperro
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Card extends BaseCard {

    public function countTickets() {
        return Doctrine::getTable('Ticket')->countByCardId($this->getId());
    }

    public function hasCoupon() {
        $has = false;

        if ($coupon = Doctrine::getTable('Coupon')->findOneByCardId($this->getId())) {
            $this->Coupon = $coupon;

            $has = true;
        }

        return $has;
    }

    public function hasStatus($status = 'active') {
        return strcasecmp($this->getStatus(), $status) == 0;
    }

    public function asArray($with_promo = false, $with_affiliate = false, $host = null, $add_one_ticket = false) {
        $can_redeem = $this->getCanBeExchangedFor($add_one_ticket);

        $card = array(
            'id' => $this->getAlphaId(),
            'status' => $this->getStatus(),
            'tickets' => $this->countTickets(),
            'creation_date' => $this->getCreatedAt(),
            'can_redeem' => (strcasecmp($this->getStatus(), 'redeemed') != 0) && count($can_redeem) ? 1 : 0,
        );

        if (strcasecmp($this->getStatus(), 'redeemed') == 0) {
            if ($this->getCoupon()->getUsedAt()) {
                $card['redeemed_at'] = $this->getCoupon()->getDateTimeObject('used_at')->format('d/m/Y');
            } else {
                $card['redeemed_at'] = $this->getDateTimeObject('updated_at')->format('d/m/Y');
            }
        }

        if (count($can_redeem)) {
            $card['redeemable'] = $can_redeem;
        }

        if ($with_promo) {
            $promo = $this->getPromo();
            $card['promo'] = $promo->asArray($with_affiliate);
        }

        return $card;
    }

    public function getAvailableTickets() {
        $q = Doctrine::getTable('ticket')->addByCardAndUsedQuery($this->getId(),false);
        return $q->execute();
    }

    public function getAvailableTicketsCount() {
        $q = Doctrine::getTable('ticket')->addByCardAndUsedQuery($this->getId(),false);
        return $q->count();
    }

    public function getCanBeExchangedFor() {
        if ($this->hasStatus('exchanged') || $this->hasStatus('redeemed')) {
            return array($this->getCoupon()->getPrize()->getAlphaId());
        }

        $exchangeable_for = array();

        $tickets = $this->getTickets()->count();

        foreach ($this->getPromo()->getPrizes() as $prize) {
            if ($tickets >= (int) $prize->getThreshold()) {
                $exchangeable_for[] = $prize->getAlphaId();
            }
        }


        return $exchangeable_for;
    }

    /**
     * Verifica si todas las condiciones asociadas a la promoción relacionada
     * con la tarjeta han sido alcanzadas.
     * 
     * @param boolean $add_one Adds 1 to the number of tickets
     * 
     * @return boolean True si ha alcanzado todas las condiciones, False en caso contrario.
     */
    public function hasReachedTheLimit() {
        if ($this->hasStatus('complete')) {
            return true;
        }

        //$prizes = count($this->getCanBeExchangedFor(true));
        $prizes = count($this->getCanBeExchangedFor());

        if ($prizes > 0 && $prizes == $this->getPromo()->getPrizes()->count()) {
            return true;
        }

        return false;
    }

    public function save(Doctrine_Connection $conn = null) {
        if (!$this->getHash()) {
            $this->setHash(hash('sha256', time() . rand(11111, 99999)));
        }

        if (!$this->getAlphaId()) {
            $this->setAlphaId(Util::gen_uuid($this->getHash()));
        }

        if ($this->hasStatus('complete') && !$this->getCompletedAt()) {
            $this->setCompletedAt(date(DateTime::W3C));
        }

        return parent::save($conn);
    }

    public function reset() {
        $this->free(true);
        $this->_data = $this->_table->getData();
        $this->_values = $this->cleanData($this->_data);
        foreach ((array) $this->_table->getIdentifier() as $id) {
            $this->_data[$id] = null;
        }
    }
}