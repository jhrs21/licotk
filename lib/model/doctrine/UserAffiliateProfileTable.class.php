<?php

/**
 * UserAffiliateProfileTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class UserAffiliateProfileTable extends sfGuardUserProfileTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object UserAffiliateProfileTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('UserAffiliateProfile');
    }
}