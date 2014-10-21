<?php

/**
 * LicotecaUserLevel
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    elperro
 * @subpackage model
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class LicotecaUserLevel extends BaseLicotecaUserLevel
{
    public function getFirstLevel() {
        $level = Doctrine_Query::create()
                    ->from('LicotecaUserLevel lul')
                    ->orderBy('bottom');
        return $level->fetchOne();
    }
}
