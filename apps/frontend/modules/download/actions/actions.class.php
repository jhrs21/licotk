<?php

/**
 * download actions.
 *
 * @package    elperro
 * @subpackage download
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
// Include the Tera-WURFL file
require_once (sfConfig::get('sf_lib_dir') . '/vendor/terawurfl/TeraWurfl.php');

class downloadActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $this->setLayout(false);
        sfConfig::set('sf_web_debug', false);

        // instantiate the Tera-WURFL object
        $wurflObj = new TeraWurfl();

        // Get the capabilities of the current client.
        $matched = $wurflObj->getDeviceCapabilitiesFromAgent();

        // see if this client is on a wireless device (or if they can't be identified)
        if ($matched && !$wurflObj->getDeviceCapability("is_wireless_device")) {
            $url = $this->getController()->genUrl(array('sf_route' => 'homepage'), false);

            $this->redirect($url);
        }

        if (preg_match("/(blackberry)/i", $request->getHttpHeader('USER_AGENT'))) {
            return $this->redirect('http://appworld.blackberry.com/webstore/content/99034/');
        } elseif (preg_match('/(android)/i', $request->getHttpHeader('USER_AGENT'))) {
            $this->redirect('https://play.google.com/store/apps/details?id=com.mobmedianet.lealtag');
        } elseif (preg_match('/(iphone|ipad|ipod)/i', $request->getHttpHeader('USER_AGENT'))) {
            $this->redirect('http://itunes.apple.com/us/app/lealtag/id542239833');
        }

        $url = $this->getController()->genUrl(array('sf_route' => 'download_comingsoon'), false);

        $this->redirect($url);
    }

    public function executeComingSoon(sfWebRequest $request) {
        $this->setLayout(false);
    }

    public function executeNotAvilable(sfWebRequest $request) {
        $this->setLayout(false);
    }

}
