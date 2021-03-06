<?php

/**
 * UserProfile
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    elperro
 * @subpackage model
 * @author     Jacobo Mart�nez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class UserProfile extends BaseUserProfile {

    /**
     * Returns 'New' if the user haven't activated his account, 'Reset' if the user
     * has requested for a password modification or false otherwise.
     * 
     * @return mixed string or boolean according to the cases above
     */
    public function getValidationType() {
        $t = substr($this->validate, 0, 1);

        if ($t == 'n') {
            return 'New';
        } elseif ($t == 'r') {
            return 'Reset';
        } elseif ($t == 'c') {
            return 'MembershipCard';
        } else {
            return false;
        }
    }

    public function save(Doctrine_Connection $conn = null) {
        if (!$this->birthdate) {
            $this->birthdate = '1900-01-01';
        }

        if ($this->isNew() && $this->getId()) {
            return parent::replace($conn);
        }

        return parent::save($conn);
    }

}