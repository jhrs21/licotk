<?php

/**
 * tag actions.
 *
 * @package    elperro
 * @subpackage tag
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tagActions extends sfActions {

    /**
     * Executes masive tag action
     *
     * @param sfRequest $request A request object
     */
    public function executeMassiveTag(sfWebRequest $request) {
        $this->form = new epMassiveTagByAssetForm();

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {
                $formValues = $this->form->getValues();
                
                $q = $this->buildQuery($formValues['asset']);
                
                $users = $q->execute();
                
                foreach ( $users as $user ) {
                    for( $i = 0; $i < $formValues['tags']; $i++ ) {
                        $this->tag($user['email_address']);
                    }
                }
                
                $this->getUser()->setFlash('massive_tags', 'Se ha(n) enviado '.$formValues['tags'].' Tag(s) a '.count($users).' usuario(s)');
            }
        }

        $this->setLayout('layout_blueprint');
    }
    
    protected function buildQuery($asset) {
        $q = Doctrine_Query::create()->from('sfGuardUser u')->setHydrationMode(Doctrine::HYDRATE_ARRAY);
        
        $q->leftJoin('u.Subscriptions s');
        $q->andWhere('s.asset_id = ?', $asset);
        
        return $q;
    }

    protected function tag($email) {
        $cH = curl_init();
        curl_setopt($cH, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($cH, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cH, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($cH, CURLOPT_URL, "http://api.lealtag.com/api/5FCWFUBj7rHwMu3K/L34lT4gB1zT4bl3t4pp/2226528782VC/tag.json?user=$email");

        $result = curl_exec($cH);
        $error = curl_error($cH);
        curl_close($cH);
        $pp = strip_tags($result);
        return json_decode($pp, true);
    }

}
