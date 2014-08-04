<?php

/**
 * promo_code actions.
 *
 * @package    elperro
 * @subpackage promo_code
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class promo_codeActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $user = $this->getUser();

        if ($user->hasGroup('admin')) {
            $id = Doctrine::getTable('Affiliate')->findAll(); // ESTA LINEA ME DA ERROR EN LA VISTA 
        } else {
            $user = $user->getGuardUser();

            $this->affiliate = $user->getAffiliate();
        }

        $query = Doctrine_Query::create()
                ->from('PromoCode pc')
                ->leftJoin('pc.Promo p')
                ->addWhere('pc.status=?', 'active')
                ->addWhere('p.affiliate_id=?', $this->affiliate->getId());

        $this->promo_codes = $query->execute();

        //$this->promo_codes = Doctrine_Core::getTable('PromoCode')->findBy('promo_id', $affiliate_id)->findBy('user_id', $affiliate_id);
        //->createQuery('a')
        //->execute();
    }

    public function executeShow(sfWebRequest $request) {
        $user = $this->getUser();

        if ($user->hasGroup('admin')) {
            $id = Doctrine::getTable('Affiliate')->findAll(); // ESTA LINEA ME DA ERROR EN LA VISTA 
        } else {
            $user = $user->getGuardUser();

            $this->affiliate = $user->getAffiliate();
        }
        $this->promo_code = Doctrine_Core::getTable('PromoCode')->find(array($request->getParameter('id')));
        $this->forward404Unless($this->promo_code);
    }

    public function executeNew(sfWebRequest $request) {
        $promoCode = new PromoCode();
        $promoCode->setStatus('unassigned');
        $promo_array = Doctrine::getTable('Promo')->findBy('affiliate_id', $this->getUser()->getGuardUser()->getAffiliateId());
        $asset_array = Doctrine::getTable('Asset')->findBy('affiliate_id', $this->getUser()->getGuardUser()->getAffiliateId());
        $user_array = Doctrine::getTable('sfGuardUser')->findBy('affiliate_id', $this->getUser()->getGuardUser()->getAffiliateId());
        $formOptions = array('promos' => $promo_array, 'assets' => $asset_array, 'users' => $user_array);
        $this->form = new PromoCodeForm($promoCode, $formOptions);
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST));

        $this->form = new PromoCodeForm();

        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request) {
        $this->forward404Unless($promo_code = Doctrine_Core::getTable('PromoCode')->find(array($request->getParameter('id'))), sprintf('Object promo_code does not exist (%s).', $request->getParameter('id')));
        $this->form = new PromoCodeForm($promo_code);
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($promo_code = Doctrine_Core::getTable('PromoCode')->find(array($request->getParameter('id'))), sprintf('Object promo_code does not exist (%s).', $request->getParameter('id')));
        $this->form = new PromoCodeForm($promo_code);

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $promo_code = $form->save();

            $this->redirect('promo_code/index');
        }
    }

    public function executeDeactivate(sfWebRequest $request) {
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $query = $q->fetchAll("SELECT DISTINCT pc.promo_id, p.name, p.description 
                                FROM promo_code pc LEFT JOIN promo p ON pc.promo_id = p.id 
                                WHERE p.affiliate_id = ?", array($request->getParameter('id')));
        $resultSet = $query;
        $promo_codes = array();
        foreach ($resultSet as $promo_code_object) {
            array_push($promo_codes, $promo_code_object['name'] . " - " . $promo_code_object['description']);
        }

        $this->form = new sfForm();
        $this->form->setWidgets(array(
            'promo' => new sfWidgetFormChoice(array('choices' => $promo_codes)),
            'rango_superior' => new sfWidgetFormInput(),
            'rango_inferior' => new sfWidgetFormInput()
        ));

        if ($request->isMethod('post')) {
            // Handle the form submission
            $name = $request->getParameter('name');
            // Do stuff
            $this->redirect('');
        }
    }

    public function executeActivate(sfWebRequest $request) {
        $this->affiliate = $this->getRoute()->getObject();

        $this->form = new epActivateForm(array(), array('affiliate' => $request->getParameter('id')));

        if ($request->isMethod("post")) {
            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {
                $values = $this->form->getValues();

                $pc = $values['promo'];
                $pc->setStatus('active');

                for ($inf = $values['inferior']; $inf <= $values['superior']; $inf++) {
                    $validation_code = new ValidationCode();
                    $validation_code->setActive(1);
                    $validation_code->setSerial($inf);
                    $validation_code->setCode(Util::GenSecret(5, 2));

                    $pc->getValidationCodes()->add($validation_code);
                }

                $pc->save();

                $this->redirect($this->getController()->genUrl(array('sf_route' => 'promo_code', 'id' => $this->affiliate->getId()), false));
            }
        }
    }

    public function executeQrGenerator(sfWebRequest $request) {
        $user = $this->getUser()->getGuardUser();
        $this->suffix = '';
        $this->vcode = '';
        //$promo = Doctrine::getTable('Promo')->findOneBy('affiliate_id', $user->getAffiliateId());
        $promo = $this->getRoute()->getObject();

        $pc = Doctrine::getTable('PromoCode')->findOneByTypeAndPromoIdAndStatusAndDigital('validation_required', $promo->getId(), 'active', true);

        if (!$pc) {
            $this->getUser()->setFlash('no_pc_validation_required', 'No posees esta funcionalidad. Por favor solicitarla a su administrador del sistema');
            return;
        }
        while (($new_code = Util::GenSecret(5, 0)) && (count(Doctrine::getTable('ValidationCode')->findBy('code', $new_code)) != 0)) {
            
        }
        while (($new_serial = Util::GenSecret(5, 1)) && (count(Doctrine::getTable('ValidationCode')->findByPromoCodeIdAndSerial($pc->getId(), $new_serial)) != 0)) {
            
        }

        $validation_code = new ValidationCode();
        $validation_code->setActive(true);
        $validation_code->setPromoCodeId($pc->getId());
        $validation_code->setCode($new_code);
        $validation_code->setSerial($new_serial);
        $validation_code->save();
        $string = str_replace(
                array('á', 'Á', 'é', 'É', 'í', 'Í', 'ó', 'Ó', 'ú', 'Ú', 'ñ', 'Ñ'), array('a', 'A', 'e', 'E', 'i', 'I', 'o', 'O', 'u', 'U', 'n', 'N'), $user->getAffiliate()
        );
        $this->vcode = $validation_code->getCode();
        require(sfConfig::get('sf_lib_dir') . '/vendor/phpqrcode/qrlib.php');
        $this->suffix = '/qr-pc/' . $string . '-generadorQR.png';
        $filename = sfConfig::get('sf_web_dir') . $this->suffix;
        $data = "http://club.licoteca.com.ve/" . $pc->getAlphaId() . "?vcode=" . $validation_code->getCode();
        $errorCorrectionLevel = "L";
        $matrixPointSize = "8";
	if (ob_get_length() > 0) {
	        ob_end_clean();
	}
        QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2, true);
    }

    public function executePrintQR(sfWebRequest $request) {

        //print_r($result);

        $this->setLayout(false);

        require(sfConfig::get('sf_lib_dir') . '/vendor/fpdf17/fpdf.php');

        $pdf = new FPDF("P", 'mm', array(130, 82));
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(176, 28, 44);

        $query = Doctrine_Query::create()
                ->select('pc.serial,pc.alpha_id')
                ->from('PromoCode pc')
                ->addWhere('pc.id=?', $request->getParameter('id'))
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY);

        $result = $query->execute();

        $promocode = $result[0]['alpha_id'];
        $serial = $result[0]['serial'];

        ob_end_clean();

        $pdf->AddPage();
        $pdf->SetXY(5, 5);
        $pdf->Image(sfConfig::get('sf_web_dir') . '/images/caja-super-qr.png', 5, 5, 72, 100);
        $pdf->Image('http://chart.apis.google.com/chart?cht=qr&chs=207x207&chl=http://www.lealtag.com/' . $promocode . '&chld=L|0&chco=111C3D', 13.5, 30.5, 0, 0, 'PNG');
        $pdf->SetXY(5, 5);
        $pdf->Cell(72, 93, '', 0, 2);
        $pdf->Cell(72, 7.5, 'serial: ' . $serial, 0, 0, 'C');

        $pdf->Output();
        return sfView::NONE;
    }

    public function executeSuperqr(sfWebRequest $request) {

        $query = Doctrine_Query::create()
                ->select('pc.serial,pc.alpha_id')
                ->from('PromoCode pc')
                ->addWhere('pc.status=?', 'unassigned')
                ->limit(120)
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY);

        $result = $query->execute();

        //print_r($result);

        $this->setLayout(false);

        ob_end_clean();

        require(sfConfig::get('sf_lib_dir') . '/vendor/fpdf17/fpdf.php');

        $pdf = new FPDF("P", 'mm', array(430, 300));
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(176, 28, 44);

        $num_pagina = 1;
        $num_qr_por_fila = 4; //Maximo 4
        $num_qr_por_columna = 4; //Maximo 4

        for ($i = 0; $num_pagina > $i; $i++) {
            $pdf->AddPage();
            $pdf->SetXY(5, 5);
            for ($j = 0; $j < $num_qr_por_fila; $j++) {
                for ($k = 0; $k < $num_qr_por_columna; $k++) {
                    $promocode = $result[$k + ($j * $num_qr_por_fila) + ($i * $num_qr_por_fila * $num_qr_por_columna)]['alpha_id'];
                    $serial = $result[$k + ($j * $num_qr_por_fila) + ($i * $num_qr_por_fila * $num_qr_por_columna)]['serial'];

                    $pdf->Image(sfConfig::get('sf_web_dir') . '/images/caja-super-qr.png', 5 + ($k * 72), 5 + ($j * 101), 72, 100);
                    $pdf->Image('http://chart.apis.google.com/chart?cht=qr&chs=207x207&chl=http://www.lealtag.com/' . $promocode . '&chld=L|0&chco=111C3D', 13.5 + ($k * 72), 30.5 + ($j * 101), 0, 0, 'PNG');
                    $pdf->SetXY(5 + ($k * 72), 5 + ($j * 101));
                    $pdf->Cell(72, 93, '', 0, 2);
                    $pdf->Cell(72, 7.5, 'serial: ' . $serial, 0, 0, 'C');
                }
            }
        }

        $pdf->Output();
        return sfView::NONE;
    }

    public function executeSuperqrparte1(sfWebRequest $request) {

        $query = Doctrine_Query::create()
                ->select('pc.serial,pc.alpha_id')
                ->from('PromoCode pc')
                ->addWhere('pc.status=?', 'unassigned')
                ->limit(120)
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY);

        $result = $query->execute();

        //print_r($result);

        $this->setLayout(false);

        ob_end_clean();

        require(sfConfig::get('sf_lib_dir') . '/vendor/fpdf17/fpdf.php');

        $pdf = new FPDF("P", 'mm', array(430, 300));
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(176, 28, 44);

        $num_pagina = 2;
        $num_qr_por_fila = 4; //Maximo 4
        $num_qr_por_columna = 4; //Maximo 4

        for ($i = 0; $num_pagina > $i; $i++) {
            $pdf->AddPage();
            $pdf->SetXY(5, 5);
            for ($j = 0; $j < $num_qr_por_fila; $j++) {
                for ($k = 0; $k < $num_qr_por_columna; $k++) {
                    $promocode = $result[16 + $k + ($j * $num_qr_por_fila) + ($i * $num_qr_por_fila * $num_qr_por_columna)]['alpha_id'];
                    $serial = $result[16 + $k + ($j * $num_qr_por_fila) + ($i * $num_qr_por_fila * $num_qr_por_columna)]['serial'];

                    $pdf->Image(sfConfig::get('sf_web_dir') . '/images/caja-super-qr.png', 5 + ($k * 72), 5 + ($j * 101), 72, 100);
                    $pdf->Image('http://chart.apis.google.com/chart?cht=qr&chs=207x207&chl=http://www.lealtag.com/' . $promocode . '&chld=L|0&chco=111C3D', 13.5 + ($k * 72), 30.5 + ($j * 101), 0, 0, 'PNG');
                    $pdf->SetXY(5 + ($k * 72), 5 + ($j * 101));
                    $pdf->Cell(72, 93, '', 0, 2);
                    $pdf->Cell(72, 7.5, 'serial: ' . $serial, 0, 0, 'C');
                }
            }
        }

        $pdf->Output();
        return sfView::NONE;
    }

    public function executeSuperqrparte2(sfWebRequest $request) {

        $query = Doctrine_Query::create()
                ->select('pc.serial,pc.alpha_id')
                ->from('PromoCode pc')
                ->addWhere('pc.status=?', 'unassigned')
                ->limit(120)
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY);

        $result = $query->execute();

        //print_r($result);

        $this->setLayout(false);

        ob_end_clean();

        require(sfConfig::get('sf_lib_dir') . '/vendor/fpdf17/fpdf.php');

        $pdf = new FPDF("P", 'mm', array(430, 300));
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(176, 28, 44);

        $num_pagina = 2;
        $num_qr_por_fila = 4; //Maximo 4
        $num_qr_por_columna = 4; //Maximo 4

        for ($i = 0; $num_pagina > $i; $i++) {
            $pdf->AddPage();
            $pdf->SetXY(5, 5);
            for ($j = 0; $j < $num_qr_por_fila; $j++) {
                for ($k = 0; $k < $num_qr_por_columna; $k++) {
                    $promocode = $result[48 + $k + ($j * $num_qr_por_fila) + ($i * $num_qr_por_fila * $num_qr_por_columna)]['alpha_id'];
                    $serial = $result[48 + $k + ($j * $num_qr_por_fila) + ($i * $num_qr_por_fila * $num_qr_por_columna)]['serial'];

                    $pdf->Image(sfConfig::get('sf_web_dir') . '/images/caja-super-qr.png', 5 + ($k * 72), 5 + ($j * 101), 72, 100);
                    $pdf->Image('http://chart.apis.google.com/chart?cht=qr&chs=207x207&chl=http://www.lealtag.com/' . $promocode . '&chld=L|0&chco=111C3D', 13.5 + ($k * 72), 30.5 + ($j * 101), 0, 0, 'PNG');
                    $pdf->SetXY(5 + ($k * 72), 5 + ($j * 101));
                    $pdf->Cell(72, 93, '', 0, 2);
                    $pdf->Cell(72, 7.5, 'serial: ' . $serial, 0, 0, 'C');
                }
            }
        }

        $pdf->Output();
        return sfView::NONE;
    }

    public function executeSuperqrparte3(sfWebRequest $request) {

        $query = Doctrine_Query::create()
                ->select('pc.serial,pc.alpha_id')
                ->from('PromoCode pc')
                ->addWhere('pc.status=?', 'unassigned')
                ->limit(120)
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY);

        $result = $query->execute();

        //print_r($result);

        $this->setLayout(false);

        ob_end_clean();

        require(sfConfig::get('sf_lib_dir') . '/vendor/fpdf17/fpdf.php');

        $pdf = new FPDF("P", 'mm', array(430, 300));
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(176, 28, 44);

        $num_pagina = 2;
        $num_qr_por_fila = 4; //Maximo 4
        $num_qr_por_columna = 4; //Maximo 4

        for ($i = 0; $num_pagina > $i; $i++) {
            $pdf->AddPage();
            $pdf->SetXY(5, 5);
            for ($j = 0; $j < $num_qr_por_fila; $j++) {
                for ($k = 0; $k < $num_qr_por_columna; $k++) {
                    $promocode = $result[80 + $k + ($j * $num_qr_por_fila) + ($i * $num_qr_por_fila * $num_qr_por_columna)]['alpha_id'];
                    $serial = $result[80 + $k + ($j * $num_qr_por_fila) + ($i * $num_qr_por_fila * $num_qr_por_columna)]['serial'];

                    $pdf->Image(sfConfig::get('sf_web_dir') . '/images/caja-super-qr.png', 5 + ($k * 72), 5 + ($j * 101), 72, 100);
                    $pdf->Image('http://chart.apis.google.com/chart?cht=qr&chs=207x207&chl=http://www.lealtag.com/' . $promocode . '&chld=L|0&chco=111C3D', 13.5 + ($k * 72), 30.5 + ($j * 101), 0, 0, 'PNG');
                    $pdf->SetXY(5 + ($k * 72), 5 + ($j * 101));
                    $pdf->Cell(72, 93, '', 0, 2);
                    $pdf->Cell(72, 7.5, 'serial: ' . $serial, 0, 0, 'C');
                }
            }
        }

        $pdf->Output();
        return sfView::NONE;
    }

    public function executeCuponQr(sfWebRequest $request) {
        $query = Doctrine_Query::create()
                ->select('vc.code,vc.serial,pc.alpha_id,pc.serial')
                ->from('ValidationCode vc')
                ->leftJoin('vc.PromoCode pc')
                ->addWhere('pc.status=?', 'unassigned')
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY);
        $result = $query->execute();

        $promoCode = array();
        $validationCode = array();
        $serialValidation = array();
        $serialPromo = array();

        //print_r($result);
        foreach ($result as $object) {
            foreach ($object as $attribute => $v1) {
                if ($attribute == 'code') {
                    array_push($validationCode, $v1);
                }
                if ($attribute == 'serial') {
                    array_push($serialValidation, $v1);
                }
                if ($attribute == 'PromoCode') {
                    foreach ($v1 as $promo => $v2) {
                        if ($promo == 'serial') {
                            array_push($serialPromo, $v2);
                        }
                        if ($promo == 'alpha_id') {
                            array_push($promoCode, $v2);
                        }
                    }
                }
            }
        }

        $this->setLayout(false);
        ob_end_clean();
        require(sfConfig::get('sf_lib_dir') . '/vendor/fpdf17/fpdf.php');

        $pdf = new FPDF("P", "mm", array(430, 300));
        $pdf->SetFont('Helvetica', '', 11);
        $num_pagina = 250;
        $num_qr_por_fila = 6; //Maximo 6
        for ($i = 0; $num_pagina > $i; $i++) {
            $pdf->AddPage();
            $pdf->SetXY(5, 5);
            $promocode = "0";
            for ($j = 0; $j < $num_qr_por_fila; $j++) {
                $promocode0 = $promoCode[$j * 2 + ($i * $num_qr_por_fila * 2)];
                $promocode1 = $promoCode[($j * 2) + 1 + ($i * $num_qr_por_fila * 2)];
                $codigo0 = $validationCode[$j * 2 + ($i * $num_qr_por_fila * 2)];
                $codigo1 = $validationCode[($j * 2) + 1 + ($i * $num_qr_por_fila * 2)];
                $serial0 = str_pad($serialPromo[$j * 2 + ($i * $num_qr_por_fila * 2)], 5, '0', STR_PAD_LEFT) . '-' . str_pad($serialValidation[$j * 2 + ($i * $num_qr_por_fila * 2)], 5, '0', STR_PAD_LEFT);
                $serial1 = str_pad($serialPromo[($j * 2) + 1 + ($i * $num_qr_por_fila * 2)], 5, '0', STR_PAD_LEFT) . '-' . str_pad($serialValidation[($j * 2) + 1 + ($i * $num_qr_por_fila * 2)], 5, '0', STR_PAD_LEFT);
                $pdf->Image(sfConfig::get('sf_web_dir') . '/images/cupon-cv-qr.jpg', 5, 5 + ($j * 62), 135, 62);
                $pdf->Image('http://chart.apis.google.com/chart?cht=qr&chs=175x175&chl=http://www.lealtag.com/' . $promocode0 . '&chld=L|0', 90.5, 9 + ($j * 62), 0, 0, 'PNG');
                $pdf->Image(sfConfig::get('sf_web_dir') . '/images/cupon-cv-qr.jpg', 140, 5 + ($j * 62), 135, 62);
                $pdf->Image('http://chart.apis.google.com/chart?cht=qr&chs=175x175&chl=http://www.lealtag.com/' . $promocode1 . '&chld=L|0', 225.5, 9 + ($j * 62), 0, 0, 'PNG');
                $pdf->SetXY(5, 5 + ($j * 62));
                $pdf->Cell(55, 50, '', 0, 0);
                $pdf->Cell(40, 50, '', 0, 0);
                $pdf->Cell(40, 50, '', 0, 0);

                $pdf->SetXY(140, 5 + ($j * 62));
                $pdf->Cell(55, 50, '', 0, 0);
                $pdf->Cell(40, 50, '', 0, 0);
                $pdf->Cell(40, 50, '', 0, 1);

                $pdf->SetTextColor(176, 28, 44);
                $pdf->SetX($pdf->GetX() - 5);
                $pdf->Cell(55, 12, $serial0, 0, 0, 'C');
                $pdf->Cell(40, 12, '', 0, 0, 'C');
                $pdf->SetTextColor(82, 82, 84);
                $pdf->Cell(40, 12, $codigo0, 0, 0, 'C');

                $pdf->SetTextColor(176, 28, 44);
                $pdf->SetX(140);
                $pdf->Cell(55, 12, $serial1, 0, 0, 'C');
                $pdf->Cell(40, 12, '', 0, 0);
                $pdf->SetTextColor(82, 82, 84);
                $pdf->Cell(40, 12, $codigo1, 0, 0, 'C');
            }
        }

        $pdf->Output();
        return sfView::NONE;
    }

}
