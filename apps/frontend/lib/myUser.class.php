<?php

class myUser extends sfGuardSecurityUser {

    public function getAskForFeedback() {
        return $this->getAttribute('ask_for_feedback', false);
    }

    /**
     * Returns the PromoCode object related to a Tag that the user has just done
     * 
     * @return mixed a PromoCode object or false if itsn't found
     */
    public function getTaggedPromoCode() {
        $id = $this->getAttribute('tagged_promocode', array());

        if (!empty($id)) {
            return Doctrine::getTable('PromoCode')->findOneBy('id', $id);
        }

        return false;
    }

    public function resetFeedbackAttributes() {
        $this->getAttributeHolder()->remove('ask_for_feedback');
        $this->getAttributeHolder()->remove('tagged_promocode');
    }

}

