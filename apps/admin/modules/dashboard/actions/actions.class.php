<?php

/**
 * index actions.
 *
 * @package    elperro
 * @subpackage index
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class dashboardActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $this->form = new epDateRangeForm();
        $startDate = '2012-01-01';
        $endDate = '2021-01-01';

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                $startDate = $values['begin_date'];
                $endDate = $values['end_date'];
            }
        }

        $this->affiliates = array();
        $temp = Doctrine::getTable('Affiliate')->findByActive('1')->toArray();
        foreach ($temp as $t) {
            $this->affiliates[$t['id']] = array('name' => $t['name'], 'category' => '', 'subscriptions' => '0', 'exchanged' => '0', 'redeemed' => '0', 'tickets' => '0', 'pre_registered' => '0', 'color' => 'Rojo');
        }

        $query = Doctrine_Query::create()
                ->select('s.status, a.name, c.name as category')
                ->addSelect('COUNT(s.id) as subscriptions')
                ->from('Subscription s')
                ->leftJoin('s.Affiliate a')
                ->leftJoin('a.Category c')
                ->groupBy('a.id')
                ->where('a.active=1')
                ->addWhere('DATE(s.created_at) BETWEEN ? AND ?', array($startDate, $endDate))
                ->addWhere('s.status="active"');
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $subscriptions = $query->execute();

        foreach ($subscriptions as $subscription) {
            $affiliate = array_merge($this->affiliates[$subscription['Affiliate']['id']], array('category' => $subscription['category'], 'subscriptions' => $subscription['subscriptions']));
            $this->affiliates[$subscription['Affiliate']['id']] = $affiliate;
            $this->affiliates[$subscription['Affiliate']['id']]['color'] = self::getColors($subscription['Affiliate']['id']);
        }

        $query = Doctrine_Query::create()
                ->select('s.affiliate_id')
                ->addSelect('COUNT(s.id) as pre_registered')
                ->from('Subscription s')
                ->leftJoin('s.Affiliate a')
                ->leftJoin('s.User u')
                ->groupBy('s.affiliate_id')
                ->where('a.active=1')
                ->andWhere('u.pre_registered=1')
                ->addWhere('DATE(s.created_at) BETWEEN ? AND ?', array($startDate, $endDate))
                ->andWhere('s.status="active"');
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $pre_registers = $query->execute();

        foreach ($pre_registers as $pre_reg) {
            $this->affiliates[$pre_reg['affiliate_id']]['pre_registered'] = $pre_reg['pre_registered'];
        }

        $query = Doctrine_Query::create()
                ->select('t.via, p.affiliate_id')
                ->addSelect('COUNT(t.id) as tickets')
                ->from('Ticket t')
                ->leftJoin('t.Promo p')
                ->addWhere('DATE(t.created_at) BETWEEN ? AND ?', array($startDate, $endDate))
                ->groupBy('p.affiliate_id');
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $tickets = $query->execute();

        foreach ($tickets as $ticket) {
            $this->affiliates[$ticket['Promo']['affiliate_id']]['tickets'] = $ticket['tickets'];
        }

        $query = Doctrine_Query::create()
                ->select('p.affiliate_id, c.status')
                ->addSelect('COUNT(c.id) as counter')
                ->from('Card c')
                ->leftJoin('c.Promo p')
                ->where('c.status="redeemed"')
                ->addWhere('DATE(c.created_at) BETWEEN ? AND ?', array($startDate, $endDate))
                ->groupBy('p.affiliate_id');
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $cards = $query->execute();

        $this->total_exchanged = 0;
        foreach ($cards as $card) {
            $this->affiliates[$card['Promo']['affiliate_id']]['redeemed'] = $card['counter'];
            $this->total_exchanged += $card['counter'];
        }

        $query = Doctrine_Query::create()
                ->select('t.via')
                ->addSelect('COUNT(t.id) as tags')
                ->from('Ticket t')
                ->addWhere('DATE(t.created_at) BETWEEN ? AND ?', array($startDate, $endDate))
                ->groupBy('t.via');
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $tags = $query->execute();

        //app, web, web_email, web_card, tablet, tablet_email, tablet_card, other
        $this->tag_list = array('app' => '0', 'web' => '0', 'web_email' => '0', 'web_card' => '0', 'tablet' => '0', 'tablet_email' => '0', 'tablet_card' => '0', 'other' => '0');
        foreach ($tags as $tag) {
            $this->tag_list[$tag['via']] = $tag['tags'];
        }

        $query = Doctrine_Query::create()
                ->select('f.valoration')
                ->addSelect('COUNT(f.id) as feeds')
                ->from('Feedback f')
                ->addWhere('DATE(f.created_at) BETWEEN ? AND ?', array($startDate, $endDate))
                ->groupBy('f.valoration');
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $feedbacks = $query->execute();

        $this->feedback_list = array('0' => '0', '1' => '0', '2' => '0');
        foreach ($feedbacks as $feed) {
            $this->feedback_list[$feed['valoration']] = $feed['feeds'];
        }

        $query = Doctrine_Query::create()
                ->select('a.asset_type')
                ->addSelect('COUNT(a.id) as type')
                ->from('Asset a')
                ->addWhere('DATE(a.created_at) BETWEEN ? AND ?', array($startDate, $endDate))
                ->groupBy('a.asset_type');
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $assets = $query->execute();

        $this->asset_list = array('place' => '0', 'brand' => '0');
        foreach ($assets as $asset) {
            $this->asset_list[$asset['asset_type']] = $asset['type'];
        }

        $query = Doctrine_Query::create()
                ->select('u.pre_registered')
                ->addSelect('COUNT(u.id) as type')
                ->from('sfGuardUser u')
                ->addWhere('DATE(u.created_at) BETWEEN ? AND ?', array($startDate, $endDate))
                ->groupBy('u.pre_registered');
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $users = $query->execute();

        $this->user_list = array('0' => '0', '1' => '0');
        foreach ($users as $user) {
            $this->user_list[$user['pre_registered']] = $user['type'];
        }
        $this->total_user_list = ($this->user_list['0'] + $this->user_list['1']) == 0 ? 0 : ($this->user_list['1'] * 200) / ($this->user_list['0'] + $this->user_list['1']);

        $query = Doctrine_Query::create()
                ->from('sfGuardUserGroup ug')
                ->where('ug.group_id=19')
                ->addWhere('DATE(ug.created_at) BETWEEN ? AND ?', array($startDate, $endDate));
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $this->employees = $query->count();


        $query = Doctrine_Query::create()
                ->select('c.status')
                ->addSelect('COUNT(c.id) as counter')
                ->from('Card c')
                ->addWhere('DATE(c.created_at) BETWEEN ? AND ?', array($startDate, $endDate))
                ->groupBy('c.status');
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $cards = $query->execute();

        $this->card_list = array('active' => '0', 'complete' => '0', 'exchanged' => '0', 'redeemed' => '0');
        foreach ($cards as $card) {
            $this->card_list[$card['status']] = $card['counter'];
        }
        $this->totalCard = array_sum($this->card_list);
//        var_dump($this->card_list);

        $this->setLayout('layout_blueprint');
    }

    public function executeAnalytics(sfWebRequest $request) {
        $route_params = $this->getRoute()->getParameters();
        $this->form = new epDateRangeForm();

        $begin_date = '1900-01-01';
        $end_date = '2021-12-31';
        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                $begin_date = $values['begin_date'];
                $end_date = $values['end_date'];
            }
        }

        $id = array($route_params['affiliate_id']);
        $this->affiliate = Doctrine::getTable('Affiliate')->find($id[0]);

        $this->assets = Doctrine::getTable('Asset')->findByAffiliateId($id[0]);

        $this->regularFeeds = $this->badFeeds = $this->goodFeeds = 0;
        $this->newFans = $this->totalFans = 0;
        $totalTicketsM = $totalTicketsH = 0;
        $this->edadPromH = $this->edadPromM = 0;
        $this->porcentajeH = $this->porcentajeM = 0;
        $this->frecuenciaH = $this->frecuenciaM = 0;
        $this->width2 = $this->width3 = $this->width4 = 0;
        $this->height2 = $this->height3 = $this->height4 = 0;
        $this->type = $this->type3 = $this->type4 = 0;
        $this->data = array();
        $this->label = '0';
        $this->title4 = 0;
        $this->dataWeekday = array();
        $this->dataHour = array();
        $this->labelWeekday = '2';
        $this->labelHour = '2';
        $this->feedbacks = array();
        $this->cards_completed = 0;
        //Verificar esto con mejores datos
        $query = Doctrine_Query::create()
                ->select('s.id, s.affiliate_id, s.created_at, s.status, s.user_id')
                ->from('Subscription s')
                ->andWhereIn('s.affiliate_id', $id);
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $users = $query->execute(array());

        // Calculo de los fans totales de un negocio y de los nuevos fans en la ultima semana
        foreach ($users as $user) {
            if (($user['status'] == 'active') && (date($user["created_at"]) > $begin_date) && (date($user["created_at"]) < $end_date)) {
                $this->totalFans++;
            }
            if (date($user["created_at"]) > date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 7, date("Y")))) {
                $this->newFans++;
            }
        }

        $subdata1 = array(0, 0);
        $subdataM = $subdataH = array(0, 0, 0, 0, 0);
        $edadesH = $edadesM = array();

        foreach ($users as $user) {
            $query = Doctrine_Query::create()
                    ->select('up.id, up.user_id, up.birthdate, up.gender, u.created_at')
                    ->from('UserProfile up')
                    ->leftJoin('up.User u')
                    ->andWhere('up.user_id=?')
                    ->andWhere('CAST(u.created_at AS DATE) BETWEEN ? AND ?', array($begin_date, $end_date));
            $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
            $userProfile = $query->execute(array($user['user_id']));
            foreach ($userProfile as $profile) {
                //Verificar si el usuario tiene birthdate
                //$edad = getAge($profile['birthdate']);
                $edad = self::getAge($profile['birthdate']);
                if ($profile['gender'] == "male") {
                    $subdata1[0] += 1;
                    if (($edad < 20) && ($edad > 9)) {
                        $subdataH[0] += 1;
                        array_push($edadesH, $edad);
                    } elseif ($edad < 30) {
                        $subdataH[1] += 1;
                        array_push($edadesH, $edad);
                    } elseif ($edad < 40) {
                        $subdataH[2] += 1;
                        array_push($edadesH, $edad);
                    } elseif ($edad < 50) {
                        $subdataH[3] += 1;
                        array_push($edadesH, $edad);
                    } elseif (($edad >= 50) && ($edad < 71)) {
                        $subdataH[4] += 1;
                        array_push($edadesH, $edad);
                    }
                } elseif ($profile['gender'] == "female") {
                    $subdata1[1] += 1;
                    if (($edad < 20) && ($edad > 9)) {
                        $subdataM[0] += 1;
                        array_push($edadesM, $edad);
                    } elseif ($edad < 30) {
                        $subdataM[1] += 1;
                        array_push($edadesM, $edad);
                    } elseif ($edad < 40) {
                        $subdataM[2] += 1;
                        array_push($edadesM, $edad);
                    } elseif ($edad < 50) {
                        $subdataM[3] += 1;
                        array_push($edadesM, $edad);
                    } elseif (($edad >= 50) && ($edad < 71)) {
                        $subdataM[4] += 1;
                        array_push($edadesM, $edad);
                    }
                }
            }
        }

        $this->edadPromH = round(self::average($edadesH));
        $this->edadPromM = round(self::average($edadesM));

        $totalUsers = 0;

        // Contar todos los tickets que estan en la tabla Ticket y que esten dentro del periodo
        $query = Doctrine_Query::create()
                ->select('t.id, up.gender, t.promo_id, p.affiliate_id, t.created_at, u.id')
                ->from('Ticket t')
                ->leftJoin('t.Promo p')
                ->leftJoin('t.User u')
                ->leftJoin('u.UserProfile up')
                ->addWhere('CAST(created_at AS DATE) BETWEEN ? AND ?', array($begin_date, $end_date))
                ->andWhereIn('p.affiliate_id', $id);
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $tickets = $query->execute();
        $this->totalTickets = count($tickets);
        foreach ($tickets as $ticket) {
            if ($ticket['User']['UserProfile']['gender'] == "female") {
                $totalTicketsM++;
            }
            if ($ticket['User']['UserProfile']['gender'] == "male") {
                $totalTicketsH++;
            }
        }

        // Contar los user_id que estan en la tabla Ticket. Para calcular la frecuencia
        $query = Doctrine_Query::create()
                ->select('COUNT(DISTINCT t.user_id)')//->distinct()
                ->from('Ticket t')
                ->leftJoin('t.Promo p')
                ->leftJoin('t.User u')
                ->leftJoin('u.UserProfile up')
                ->andWhere('up.gender=?')
                ->addWhere('CAST(created_at AS DATE) BETWEEN ? AND ?', array($begin_date, $end_date))
                ->andWhereIn('p.affiliate_id', $id)
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY);
        $set = $query->execute(array("male"));
        $totalUsers = $set[0]['COUNT'];

        $this->frecuenciaH = 0;
        if ($totalUsers != 0)
            $this->frecuenciaH = number_format($totalTicketsH / $totalUsers, 1, '.', '');

        $set = $query->execute(array("female"));
        $totalUsers = $set[0]['COUNT'];

        $this->frecuenciaM = 0;
        if ($totalUsers != 0)
            $this->frecuenciaM = number_format($totalTicketsM / $totalUsers, 1, '.', '');
        $subdataDiasH = $subdataDiasM = array(0, 0, 0, 0, 0, 0, 0);

        foreach ($tickets as $ticket) {
            $date = $ticket['created_at'];
            switch (date('l', strtotime($date))) {
                case "Monday":
                    if ($ticket['User']['UserProfile']['gender'] == 'male')
                        $subdataDiasH[0] += 1;
                    if ($ticket['User']['UserProfile']['gender'] == 'female')
                        $subdataDiasM[0] += 1;
                    break;
                case "Tuesday":
                    if ($ticket['User']['UserProfile']['gender'] == 'male')
                        $subdataDiasH[1] += 1;
                    if ($ticket['User']['UserProfile']['gender'] == 'female')
                        $subdataDiasM[1] += 1;
                    break;
                case "Wednesday":
                    if ($ticket['User']['UserProfile']['gender'] == 'male')
                        $subdataDiasH[2] += 1;
                    if ($ticket['User']['UserProfile']['gender'] == 'female')
                        $subdataDiasM[2] += 1;
                    break;
                case "Thursday":
                    if ($ticket['User']['UserProfile']['gender'] == 'male')
                        $subdataDiasH[3] += 1;
                    if ($ticket['User']['UserProfile']['gender'] == 'female')
                        $subdataDiasM[3] += 1;
                    break;
                case "Friday":
                    if ($ticket['User']['UserProfile']['gender'] == 'male')
                        $subdataDiasH[4] += 1;
                    if ($ticket['User']['UserProfile']['gender'] == 'female')
                        $subdataDiasM[4] += 1;
                    break;
                case "Saturday":
                    if ($ticket['User']['UserProfile']['gender'] == 'male')
                        $subdataDiasH[5] += 1;
                    if ($ticket['User']['UserProfile']['gender'] == 'female')
                        $subdataDiasM[5] += 1;
                    break;
                case "Sunday":
                    if ($ticket['User']['UserProfile']['gender'] == 'male')
                        $subdataDiasH[6] += 1;
                    if ($ticket['User']['UserProfile']['gender'] == 'female')
                        $subdataDiasM[6] += 1;
                    break;
            }
        }
        //NUEVOS CALCULOS PARA LA NUEVA PAGINA DE ANALYTICS (SIN PIECHART)
        if (array_sum($subdata1) != 0) {
            $this->porcentajeH = number_format(($subdata1[0] / array_sum($subdata1)) * 100, 2, '.', '');
            $this->porcentajeM = number_format(($subdata1[1] / array_sum($subdata1)) * 100, 2, '.', '');
        } else {
            $this->porcentajeH = 0;
            $this->porcentajeM = 0;
        }
        //// FIN DE NUEVOS CALCULOS

        $this->width2 = '480';
        $this->height2 = '250';
        $this->title2 = 'Edad de mis fans';
        $this->data = array('Hombres' => $subdataH, 'Mujeres' => $subdataM);
        $this->label = array('menor 20', '20-30', '30-40', '40-50', 'mayor 50');
        $this->type = 'ColumnChartAges';

        $this->width3 = '480';
        $this->height3 = '250';
        $this->title3 = 'Que dias vienen mis fans';
        $this->dataWeekday = array('Hombres' => $subdataDiasH, 'Mujeres' => $subdataDiasM);
        $this->labelWeekday = array('Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom');
        $this->type3 = 'ColumnChartWeekday';

        //FEEDBACK-AREA
        $query = Doctrine_Query::create()
                ->from('Feedback f')
                ->andWhereIn('f.affiliate_id', $id)
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY);
        $feedbacks = $query->execute();
        foreach ($feedbacks as $feed) {
            if ($feed['valoration'] == '0') {
                $this->badFeeds++;
            }
            if ($feed['valoration'] == '1') {
                $this->regularFeeds++;
            }
            if ($feed['valoration'] == '2') {
                $this->goodFeeds++;
            }
        }
        $this->feedbacksCount = $this->goodFeeds + $this->regularFeeds + $this->badFeeds;
        $table = Doctrine::getTable('Feedback');
        $this->lastComments = $table->retrieveLastComments($id, 3);

        //GRAFICO PARA LA HORA DE ESCANEO
        $subdataHorasH = $subdataHorasM = array(0, 0, 0, 0, 0, 0);
        foreach ($tickets as $ticket) {
            $date = $ticket['created_at'];
            if (date('G', strtotime($date)) < '6') {
                if ($ticket['User']['UserProfile']['gender'] == 'male')
                    $subdataHorasH[0] += 1;
                if ($ticket['User']['UserProfile']['gender'] == 'female')
                    $subdataHorasM[0] += 1;
            }elseif (date('G', strtotime($date)) < '9') {
                if ($ticket['User']['UserProfile']['gender'] == 'male')
                    $subdataHorasH[1] += 1;
                if ($ticket['User']['UserProfile']['gender'] == 'female')
                    $subdataHorasM[1] += 1;
            }elseif (date('G', strtotime($date)) < '12') {
                if ($ticket['User']['UserProfile']['gender'] == 'male')
                    $subdataHorasH[2] += 1;
                if ($ticket['User']['UserProfile']['gender'] == 'female')
                    $subdataHorasM[2] += 1;
            }elseif (date('G', strtotime($date)) < '15') {
                if ($ticket['User']['UserProfile']['gender'] == 'male')
                    $subdataHorasH[3] += 1;
                if ($ticket['User']['UserProfile']['gender'] == 'female')
                    $subdataHorasM[3] += 1;
            }elseif (date('G', strtotime($date)) < '18') {
                if ($ticket['User']['UserProfile']['gender'] == 'male')
                    $subdataHorasH[4] += 1;
                if ($ticket['User']['UserProfile']['gender'] == 'female')
                    $subdataHorasM[4] += 1;
            }elseif (date('G', strtotime($date)) < '24') {
                if ($ticket['User']['UserProfile']['gender'] == 'male')
                    $subdataHorasH[5] += 1;
                if ($ticket['User']['UserProfile']['gender'] == 'female')
                    $subdataHorasM[5] += 1;
            }
        }

        // NOTA: posible mejora. En lugar de discriminar primero por la hora y luego por el genero, seria mejor
        // discriminar primero por genero, de esta forma se pueden unir el foreach de la hora del tag en conjunto
        // con la del dia del tag. Un solo recorrido del arreglo

        $this->width4 = '480';
        $this->height4 = '250';
        $this->title4 = '¿A qué hora vienen mis fans?';
        $this->dataHour = array('Hombres' => $subdataHorasH, 'Mujeres' => $subdataHorasM);
        $this->labelHour = array('12am - 6 am', '6 am - 9am', '9 am - 12pm', '12pm - 3pm', '3pm - 6pm', '6pm - 12am');
        $this->type4 = 'ColumnChartHour';

        $query = Doctrine_Query::create()
                ->select('c.id, c.status, c.completed_at, c.user_id')
                ->from('Card c')
                ->leftJoin('c.Promo p')
                ->andWhereIn('p.affiliate_id', $id)
                ->andWhere('CAST(c.created_at AS DATE) BETWEEN ? AND ?', array($begin_date, $end_date))
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY);
        $cards = $query->execute();
        $cards_array = array(0, 0, 0, 0);
        foreach ($cards as $card) {
            if ($card['status'] == 'active') {
                $cards_array[0]++;
            }
            if ($card['status'] == 'complete') {
                $cards_array[1]++;
            }
            if ($card['status'] == 'exchanged') {
                $cards_array[2]++;
            }
            if ($card['status'] == 'redeemed') {
                $cards_array[3]++;
            }
        }

        $this->width5 = '480';
        $this->height5 = '200';
        $this->title5 = '¿Cuál es el estatus de los premios de mis fans?';
        $this->dataCard = array("Premios" => $cards_array);
        $this->labelCard = array('Por completar', 'Completados', 'Cupón generado', 'Canjeados');
        $this->type5 = 'ColumnChartCard';
        
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $results = $q->execute("SELECT tags, COUNT(1) as total FROM (SELECT t.user_id, COUNT(1) AS tags FROM ticket t, promo p WHERE t.promo_id = p.id AND p.affiliate_id = " . $id[0] . " GROUP BY (t.user_id)) AS T GROUP BY (tags) ")->fetchAll();
        $tagsByUser = array_fill(-1,5,0);
        foreach ($results as $res) {
            if ($res['tags'] > 4)
                $tagsByUser['-1'] += $res['total'];
            else
                $tagsByUser[$res['tags'] - 1] = $res['total'];
        }
        $tagsByUser[4] = (string) $tagsByUser['-1'];
        unset($tagsByUser['-1']);
        $this->widthX = '600';
        $this->heightX = '250';
        $this->titleX = 'Número de tags por usuario';
        $this->dataX = array('Usuarios' => $tagsByUser);
        $this->labelX = array('1 tag', '2 tags', '3 tags', '4 tags', 'mayor a 5 tags');
        $this->typeX = 'ColumnChartX';
    }

    public function executeDetails(sfWebRequest $request) {
        $route_params = $this->getRoute()->getParameters();
        $this->form = new epDateRangeForm();
        $startDate = '2012-01-01';
        $endDate = '2021-01-01';

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                $startDate = $values['begin_date'];
                $endDate = $values['end_date'];
            }
        }

        $affiliate_id = $route_params['affiliate_id'];
        $this->aff_id = $affiliate_id;

        $this->assets = array();
        $this->affiliates = array();
        $temp = Doctrine::getTable('Asset')->findByAffiliateId($affiliate_id)->toArray();
        foreach ($temp as $t) {
            $this->assets[$t['id']] = array('name' => $t['name'], 'category' => '', 'subscriptions' => '0', 'exchanged' => '0', 'redeemed' => '0', 'tickets' => '0', 'pre_registered' => '0', 'color' => 'Rojo');
        }

        $query = Doctrine_Query::create()
                ->select('s.status, a.name, c.name as category')
                ->addSelect('COUNT(s.id) as subscriptions')
                ->from('Subscription s')
                ->leftJoin('s.Asset a')
                ->leftJoin('a.Category c')
                ->groupBy('a.id')
                ->addWhere('s.status="active"')
                ->addWhere('DATE(s.created_at) BETWEEN ? AND ?', array($startDate, $endDate))
                ->addWhere('s.affiliate_id=?', $affiliate_id);
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $subscriptions = $query->execute();

        foreach ($subscriptions as $subscription) {
            $asset = array_merge($this->assets[$subscription['Asset']['id']], array('subscriptions' => $subscription['subscriptions'], 'category' => $subscription['category'], 'exchanged' => '0', 'tickets' => '0', 'pre_registered' => '0'));
            $this->assets[$subscription['Asset']['id']] = $asset;
            $this->assets[$subscription['Asset']['id']]['color'] = self::getColor($subscription['Asset']['id']);
        }

        $query = Doctrine_Query::create()
                ->select('s.asset_id')
                ->addSelect('COUNT(s.id) as pre_registered')
                ->from('Subscription s')
                ->leftJoin('s.Asset a')
                ->leftJoin('s.User u')
                ->groupBy('s.asset_id')
                ->where('s.affiliate_id=?', $affiliate_id)
                ->andWhere('u.pre_registered=1')
                ->addWhere('DATE(s.created_at) BETWEEN ? AND ?', array($startDate, $endDate))
                ->andWhere('s.status="active"');
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $pre_registers = $query->execute();

        foreach ($pre_registers as $pre_reg) {
            $this->assets[$pre_reg['asset_id']]['pre_registered'] = $pre_reg['pre_registered'];
        }

        $query = Doctrine_Query::create()
                ->select('t.via, pc.asset_id')
                ->addSelect('COUNT(t.id) as tickets')
                ->from('Ticket t')
                ->leftJoin('t.Promo p')
                ->leftJoin('t.PromoCode pc')
                ->where('p.affiliate_id=?', $affiliate_id)
                ->addWhere('DATE(t.created_at) BETWEEN ? AND ?', array($startDate, $endDate))
                ->groupBy('pc.asset_id');
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $tickets = $query->execute();

        foreach ($tickets as $ticket) {
            $this->assets[$ticket['PromoCode']['asset_id']]['tickets'] = $ticket['tickets'];
        }

        $query = Doctrine_Query::create()
                ->select('c.asset_id, c.status, p.affiliate_id')
                ->addSelect('COUNT(c.id) as counter')
                ->from('Coupon c')
                ->leftJoin('c.Promo p')
                ->where('c.status="used"')
                ->addWhere('DATE(c.created_at) BETWEEN ? AND ?', array($startDate, $endDate))
                ->addWhere('c.asset_id IS NOT NULL')
                ->addWhere('p.affiliate_id=?', $affiliate_id)
                ->groupBy('c.asset_id');
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $cards = $query->execute();

        $this->total_exchanged = 0;
        foreach ($cards as $card) {
            $this->assets[$card['asset_id']]['exchanged'] = $card['counter'];
            $this->total_exchanged += $card['counter'];
        }

        $query = Doctrine_Query::create()
                ->select('t.via')
                ->addSelect('COUNT(t.id) as tags')
                ->from('Ticket t')
                ->leftJoin('t.Promo p')
                ->where('p.affiliate_id=?', $affiliate_id)
                ->addWhere('DATE(t.created_at) BETWEEN ? AND ?', array($startDate, $endDate))
                ->groupBy('t.via');
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $tags = $query->execute();

        //app, web, web_email, web_card, tablet, tablet_email, tablet_card, other
        $this->tag_list = array('app' => '0', 'web' => '0', 'web_email' => '0', 'web_card' => '0', 'tablet' => '0', 'tablet_email' => '0', 'tablet_card' => '0', 'other' => '0');
        foreach ($tags as $tag) {
            $this->tag_list[$tag['via']] = $tag['tags'];
        }

        $query = Doctrine_Query::create()
                ->select('f.valoration')
                ->addSelect('COUNT(f.id) as feeds')
                ->from('Feedback f')
                ->where('f.affiliate_id=?', $affiliate_id)
                ->addWhere('DATE(f.created_at) BETWEEN ? AND ?', array($startDate, $endDate))
                ->groupBy('f.valoration');
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $feedbacks = $query->execute();

        $this->feedback_list = array('0' => '0', '1' => '0', '2' => '0');
        foreach ($feedbacks as $feed) {
            $this->feedback_list[$feed['valoration']] = $feed['feeds'];
        }

        $query = Doctrine_Query::create()
                ->select('a.asset_type')
                ->addSelect('COUNT(a.id) as type')
                ->from('Asset a')
                ->where('a.affiliate_id=?', $affiliate_id)
                ->addWhere('DATE(a.created_at) BETWEEN ? AND ?', array($startDate, $endDate))
                ->groupBy('a.asset_type');
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $assets = $query->execute();

        $this->asset_list = array('place' => '0', 'brand' => '0');
        foreach ($assets as $asset) {
            $this->asset_list[$asset['asset_type']] = $asset['type'];
        }

        $query = Doctrine_Query::create()
                ->select('s.id, u.pre_registered')
                ->addSelect('COUNT(u.id) as type')
                ->from('Subscription s')
                ->leftJoin('s.User u')
                ->where('s.affiliate_id=?', $affiliate_id)
                ->addWhere('DATE(u.created_at) BETWEEN ? AND ?', array($startDate, $endDate))
                ->groupBy('u.pre_registered');
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $users = $query->execute();

        $this->user_list = array('0' => '0', '1' => '0');
        foreach ($users as $user) {
            $this->user_list[$user['User']['pre_registered']] = $user['type'];
        }
        $this->total_user_list = ($this->user_list['0'] + $this->user_list['1']) == 0 ? 0 : ($this->user_list['1'] * 200) / ($this->user_list['0'] + $this->user_list['1']);

        $query = Doctrine_Query::create()//REVISAR ESTA CONSULTA, CREO QUE ESTA MALA.
                ->from('sfGuardUser u')
                ->where('u.affiliate_id=?', $affiliate_id);
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $this->employees = $query->count();


        $query = Doctrine_Query::create()
                ->select('c.status')
                ->addSelect('COUNT(c.id) as counter')
                ->from('Card c')
                ->leftJoin('c.Promo p')
                ->where('p.affiliate_id=?', $affiliate_id)
                ->addWhere('DATE(c.created_at) BETWEEN ? AND ?', array($startDate, $endDate))
                ->groupBy('c.status');
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $cards = $query->execute();

        $this->card_list = array('active' => '0', 'complete' => '0', 'exchanged' => '0', 'redeemed' => '0');
        foreach ($cards as $card) {
            $this->card_list[$card['status']] = $card['counter'];
        }
        $this->totalCard = array_sum($this->card_list);
        $this->setLayout('layout_blueprint');
    }

    public function executeAnalyticsAsset(sfWebRequest $request) {
        $route_params = $this->getRoute()->getParameters();
        $this->form = new epDateRangeForm();

        $begin_date = '1900-01-01';
        $end_date = '2021-12-31';
        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                $begin_date = $values['begin_date'];
                $end_date = $values['end_date'];
            }
        }

        $id = array($route_params['affiliate_id']);
        $this->affiliate = Doctrine::getTable('Affiliate')->find($id[0]);

        $this->assets = Doctrine::getTable('Asset')->findByAffiliateId($id[0]);
        $this->asset = Doctrine::getTable('Asset')->find($route_params['asset_id']);
        
        $this->regularFeeds = $this->badFeeds = $this->goodFeeds = 0;
        $this->newFans = $this->totalFans = 0;
        $totalTicketsM = $totalTicketsH = 0;
        $this->edadPromH = $this->edadPromM = 0;
        $this->porcentajeH = $this->porcentajeM = 0;
        $this->frecuenciaH = $this->frecuenciaM = 0;
        $this->width2 = $this->width3 = $this->width4 = 0;
        $this->height2 = $this->height3 = $this->height4 = 0;
        $this->type = $this->type3 = $this->type4 = 0;
        $this->data = array();
        $this->label = '0';
        $this->title4 = 0;
        $this->dataWeekday = array();
        $this->dataHour = array();
        $this->labelWeekday = '2';
        $this->labelHour = '2';
        $this->feedbacks = array();
        $this->cards_completed = 0;
        //Verificar esto con mejores datos
        $query = Doctrine_Query::create()
                ->select('s.id, s.affiliate_id, s.created_at, s.status, s.user_id')
                ->from('Subscription s')
                ->andWhereIn('s.affiliate_id', $id);
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $users = $query->execute(array());

        // Calculo de los fans totales de un negocio y de los nuevos fans en la ultima semana
        foreach ($users as $user) {
            if (($user['status'] == 'active') && (date($user["created_at"]) > $begin_date) && (date($user["created_at"]) < $end_date)) {
                $this->totalFans++;
            }
            if (date($user["created_at"]) > date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 7, date("Y")))) {
                $this->newFans++;
            }
        }

        $subdata1 = array(0, 0);
        $subdataM = $subdataH = array(0, 0, 0, 0, 0);
        $edadesH = $edadesM = array();

        foreach ($users as $user) {
            $query = Doctrine_Query::create()
                    ->select('up.id, up.user_id, up.birthdate, up.gender, u.created_at')
                    ->from('UserProfile up')
                    ->leftJoin('up.User u')
                    ->andWhere('up.user_id=?')
                    ->andWhere('CAST(u.created_at AS DATE) BETWEEN ? AND ?', array($begin_date, $end_date));
            $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
            $userProfile = $query->execute(array($user['user_id']));
            foreach ($userProfile as $profile) {
                //Verificar si el usuario tiene birthdate
                //$edad = getAge($profile['birthdate']);
                $edad = self::getAge($profile['birthdate']);
                if ($profile['gender'] == "male") {
                    $subdata1[0] += 1;
                    if (($edad < 20) && ($edad > 9)) {
                        $subdataH[0] += 1;
                        array_push($edadesH, $edad);
                    } elseif ($edad < 30) {
                        $subdataH[1] += 1;
                        array_push($edadesH, $edad);
                    } elseif ($edad < 40) {
                        $subdataH[2] += 1;
                        array_push($edadesH, $edad);
                    } elseif ($edad < 50) {
                        $subdataH[3] += 1;
                        array_push($edadesH, $edad);
                    } elseif (($edad >= 50) && ($edad < 71)) {
                        $subdataH[4] += 1;
                        array_push($edadesH, $edad);
                    }
                } elseif ($profile['gender'] == "female") {
                    $subdata1[1] += 1;
                    if (($edad < 20) && ($edad > 9)) {
                        $subdataM[0] += 1;
                        array_push($edadesM, $edad);
                    } elseif ($edad < 30) {
                        $subdataM[1] += 1;
                        array_push($edadesM, $edad);
                    } elseif ($edad < 40) {
                        $subdataM[2] += 1;
                        array_push($edadesM, $edad);
                    } elseif ($edad < 50) {
                        $subdataM[3] += 1;
                        array_push($edadesM, $edad);
                    } elseif (($edad >= 50) && ($edad < 71)) {
                        $subdataM[4] += 1;
                        array_push($edadesM, $edad);
                    }
                }
            }
        }

        $this->edadPromH = round(self::average($edadesH));
        $this->edadPromM = round(self::average($edadesM));

        $totalUsers = 0;

        // Contar todos los tickets que estan en la tabla Ticket y que esten dentro del periodo
        $query = Doctrine_Query::create()
                ->select('t.id, up.gender, t.promo_id, p.affiliate_id, t.created_at, u.id')
                ->from('Ticket t')
                ->leftJoin('t.Promo p')
                ->leftJoin('t.User u')
                ->leftJoin('u.UserProfile up')
                ->addWhere('CAST(created_at AS DATE) BETWEEN ? AND ?', array($begin_date, $end_date))
                ->andWhere('t.asset_id=?',$this->asset->getId())
                ->andWhereIn('p.affiliate_id', $id);
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $tickets = $query->execute();
        $this->totalTickets = count($tickets);
        foreach ($tickets as $ticket) {
            if ($ticket['User']['UserProfile']['gender'] == "female") {
                $totalTicketsM++;
            }
            if ($ticket['User']['UserProfile']['gender'] == "male") {
                $totalTicketsH++;
            }
        }

        // Contar los user_id que estan en la tabla Ticket. Para calcular la frecuencia
        $query = Doctrine_Query::create()
                ->select('COUNT(DISTINCT t.user_id)')//->distinct()
                ->from('Ticket t')
                ->leftJoin('t.Promo p')
                ->leftJoin('t.User u')
                ->leftJoin('u.UserProfile up')
                ->andWhere('up.gender=?')
                ->andWhere('t.asset_id=?',$this->asset->getId())
                ->addWhere('CAST(created_at AS DATE) BETWEEN ? AND ?', array($begin_date, $end_date))
                ->andWhereIn('p.affiliate_id', $id)
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY);
        $set = $query->execute(array("male"));
        $totalUsers = $set[0]['COUNT'];

        $this->frecuenciaH = 0;
        if ($totalUsers != 0)
            $this->frecuenciaH = number_format($totalTicketsH / $totalUsers, 1, '.', '');

        $set = $query->execute(array("female"));
        $totalUsers = $set[0]['COUNT'];

        $this->frecuenciaM = 0;
        if ($totalUsers != 0)
            $this->frecuenciaM = number_format($totalTicketsM / $totalUsers, 1, '.', '');
        $subdataDiasH = $subdataDiasM = array(0, 0, 0, 0, 0, 0, 0);

        foreach ($tickets as $ticket) {
            $date = $ticket['created_at'];
            switch (date('l', strtotime($date))) {
                case "Monday":
                    if ($ticket['User']['UserProfile']['gender'] == 'male')
                        $subdataDiasH[0] += 1;
                    if ($ticket['User']['UserProfile']['gender'] == 'female')
                        $subdataDiasM[0] += 1;
                    break;
                case "Tuesday":
                    if ($ticket['User']['UserProfile']['gender'] == 'male')
                        $subdataDiasH[1] += 1;
                    if ($ticket['User']['UserProfile']['gender'] == 'female')
                        $subdataDiasM[1] += 1;
                    break;
                case "Wednesday":
                    if ($ticket['User']['UserProfile']['gender'] == 'male')
                        $subdataDiasH[2] += 1;
                    if ($ticket['User']['UserProfile']['gender'] == 'female')
                        $subdataDiasM[2] += 1;
                    break;
                case "Thursday":
                    if ($ticket['User']['UserProfile']['gender'] == 'male')
                        $subdataDiasH[3] += 1;
                    if ($ticket['User']['UserProfile']['gender'] == 'female')
                        $subdataDiasM[3] += 1;
                    break;
                case "Friday":
                    if ($ticket['User']['UserProfile']['gender'] == 'male')
                        $subdataDiasH[4] += 1;
                    if ($ticket['User']['UserProfile']['gender'] == 'female')
                        $subdataDiasM[4] += 1;
                    break;
                case "Saturday":
                    if ($ticket['User']['UserProfile']['gender'] == 'male')
                        $subdataDiasH[5] += 1;
                    if ($ticket['User']['UserProfile']['gender'] == 'female')
                        $subdataDiasM[5] += 1;
                    break;
                case "Sunday":
                    if ($ticket['User']['UserProfile']['gender'] == 'male')
                        $subdataDiasH[6] += 1;
                    if ($ticket['User']['UserProfile']['gender'] == 'female')
                        $subdataDiasM[6] += 1;
                    break;
            }
        }
        //NUEVOS CALCULOS PARA LA NUEVA PAGINA DE ANALYTICS (SIN PIECHART)
        if (array_sum($subdata1) != 0) {
            $this->porcentajeH = number_format(($subdata1[0] / array_sum($subdata1)) * 100, 2, '.', '');
            $this->porcentajeM = number_format(($subdata1[1] / array_sum($subdata1)) * 100, 2, '.', '');
        } else {
            $this->porcentajeH = 0;
            $this->porcentajeM = 0;
        }
        //// FIN DE NUEVOS CALCULOS

        $this->width2 = '480';
        $this->height2 = '250';
        $this->title2 = 'Edad de mis fans';
        $this->data = array('Hombres' => $subdataH, 'Mujeres' => $subdataM);
        $this->label = array('menor 20', '20-30', '30-40', '40-50', 'mayor 50');
        $this->type = 'ColumnChartAges';

        $this->width3 = '480';
        $this->height3 = '250';
        $this->title3 = 'Que dias vienen mis fans';
        $this->dataWeekday = array('Hombres' => $subdataDiasH, 'Mujeres' => $subdataDiasM);
        $this->labelWeekday = array('Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom');
        $this->type3 = 'ColumnChartWeekday';

        //FEEDBACK-AREA
        $query = Doctrine_Query::create()
                ->from('Feedback f')
                ->andWhere('f.asset_id=?',$this->asset->getId())
                ->andWhereIn('f.affiliate_id', $id)
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY);
        $feedbacks = $query->execute();
        foreach ($feedbacks as $feed) {
            if ($feed['valoration'] == '0') {
                $this->badFeeds++;
            }
            if ($feed['valoration'] == '1') {
                $this->regularFeeds++;
            }
            if ($feed['valoration'] == '2') {
                $this->goodFeeds++;
            }
        }
        $this->feedbacksCount = $this->goodFeeds + $this->regularFeeds + $this->badFeeds;
        $table = Doctrine::getTable('Feedback');
        $this->lastComments = $table->retrieveLastComments($id, 3);

        //GRAFICO PARA LA HORA DE ESCANEO
        $subdataHorasH = $subdataHorasM = array(0, 0, 0, 0, 0, 0);
        foreach ($tickets as $ticket) {
            $date = $ticket['created_at'];
            if (date('G', strtotime($date)) < '6') {
                if ($ticket['User']['UserProfile']['gender'] == 'male')
                    $subdataHorasH[0] += 1;
                if ($ticket['User']['UserProfile']['gender'] == 'female')
                    $subdataHorasM[0] += 1;
            }elseif (date('G', strtotime($date)) < '9') {
                if ($ticket['User']['UserProfile']['gender'] == 'male')
                    $subdataHorasH[1] += 1;
                if ($ticket['User']['UserProfile']['gender'] == 'female')
                    $subdataHorasM[1] += 1;
            }elseif (date('G', strtotime($date)) < '12') {
                if ($ticket['User']['UserProfile']['gender'] == 'male')
                    $subdataHorasH[2] += 1;
                if ($ticket['User']['UserProfile']['gender'] == 'female')
                    $subdataHorasM[2] += 1;
            }elseif (date('G', strtotime($date)) < '15') {
                if ($ticket['User']['UserProfile']['gender'] == 'male')
                    $subdataHorasH[3] += 1;
                if ($ticket['User']['UserProfile']['gender'] == 'female')
                    $subdataHorasM[3] += 1;
            }elseif (date('G', strtotime($date)) < '18') {
                if ($ticket['User']['UserProfile']['gender'] == 'male')
                    $subdataHorasH[4] += 1;
                if ($ticket['User']['UserProfile']['gender'] == 'female')
                    $subdataHorasM[4] += 1;
            }elseif (date('G', strtotime($date)) < '24') {
                if ($ticket['User']['UserProfile']['gender'] == 'male')
                    $subdataHorasH[5] += 1;
                if ($ticket['User']['UserProfile']['gender'] == 'female')
                    $subdataHorasM[5] += 1;
            }
        }

        // NOTA: posible mejora. En lugar de discriminar primero por la hora y luego por el genero, seria mejor
        // discriminar primero por genero, de esta forma se pueden unir el foreach de la hora del tag en conjunto
        // con la del dia del tag. Un solo recorrido del arreglo

        $this->width4 = '480';
        $this->height4 = '250';
        $this->title4 = '¿A qué hora vienen mis fans?';
        $this->dataHour = array('Hombres' => $subdataHorasH, 'Mujeres' => $subdataHorasM);
        $this->labelHour = array('12am - 6 am', '6 am - 9am', '9 am - 12pm', '12pm - 3pm', '3pm - 6pm', '6pm - 12am');
        $this->type4 = 'ColumnChartHour';

        $query = Doctrine_Query::create()
                ->select('t.id,c.id, c.status, c.completed_at, c.user_id')
                ->from('Ticket t')
                ->leftJoin('t.Card c')
                ->leftJoin('c.Promo p')
                ->andWhere('t.asset_id=?',$this->asset->getId())
                ->andWhereIn('p.affiliate_id', $id)
                ->andWhere('CAST(c.created_at AS DATE) BETWEEN ? AND ?', array($begin_date, $end_date))
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY);
        $cards = $query->execute();
        $cards_array = array(0, 0, 0, 0);
        foreach ($cards as $card) {
            if ($card['Card']['status'] == 'active') {
                $cards_array[0]++;
            }
            if ($card['Card']['status'] == 'complete') {
                $cards_array[1]++;
            }
            if ($card['Card']['status'] == 'exchanged') {
                $cards_array[2]++;
            }
            if ($card['Card']['status'] == 'redeemed') {
                $cards_array[3]++;
            }
        }

        $this->width5 = '480';
        $this->height5 = '200';
        $this->title5 = '¿Cuál es el estatus de los premios de mis fans?';
        $this->dataCard = array("Premios" => $cards_array);
        $this->labelCard = array('Por completar', 'Completados', 'Cupón generado', 'Canjeados');
        $this->type5 = 'ColumnChartCard';
    }

    public function executeFeedback(sfWebRequest $request) {
        $params = $this->getRoute()->getParameters();
        $id = $params['affiliate_id'];

        $table = Doctrine::getTable('Feedback');

        $this->goodFeeds = $table->addByValorationQuery(2, $table->addByAffiliateQuery($id))->count();

        $this->regularFeeds = $table->addByValorationQuery(1, $table->addByAffiliateQuery($id))->count();

        $this->badFeeds = $table->addByValorationQuery(0, $table->addByAffiliateQuery($id))->count();

        $this->counter = $this->goodFeeds + $this->regularFeeds + $this->badFeeds;

        $query = $table->addByMessageQuery('', true, $table->addByAffiliateQuery($id));

        $this->valoration = $request->getParameter('valoration', false);
        if ($this->valoration !== false) { // Debe ser exactamente puesto que uno de los valores para el parametro 'valoration' puede ser 0
            if (!strcasecmp($this->valoration, 'all') == 0) {
                $query = $table->addByValorationQuery($this->valoration, $query);
                //echo "valoration=".$valoration;
            }
        }

        $this->pager = new sfDoctrinePager('Feedback', sfConfig::get('app_ep_feedbacks_per_page', 20));
        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
    }
    
    public function executeFeedbackAsset(sfWebRequest $request) {
        $params = $this->getRoute()->getParameters();
        $id = $params['affiliate_id'];

        $table = Doctrine::getTable('Feedback');

        $this->goodFeeds = $table->addByValorationQuery(2, $table->addByAffiliateQuery($id))->count();

        $this->regularFeeds = $table->addByValorationQuery(1, $table->addByAffiliateQuery($id))->count();

        $this->badFeeds = $table->addByValorationQuery(0, $table->addByAffiliateQuery($id))->count();

        $this->counter = $this->goodFeeds + $this->regularFeeds + $this->badFeeds;

        $query = $table->addByMessageQuery('', true, $table->addByAffiliateQuery($id));

        $this->valoration = $request->getParameter('valoration', false);
        if ($this->valoration !== false) { // Debe ser exactamente puesto que uno de los valores para el parametro 'valoration' puede ser 0
            if (!strcasecmp($this->valoration, 'all') == 0) {
                $query = $table->addByValorationQuery($this->valoration, $query);
//                echo "valoration=".$valoration;
            }
        }

        $this->pager = new sfDoctrinePager('Feedback', sfConfig::get('app_ep_feedbacks_per_page', 20));
        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
    }

    function getAge($birthdate) {
        return floor((time() - strtotime($birthdate)) / (60 * 60 * 24 * 365.2425));
    }

    function average($arr) {
        $count = count($arr); //total numbers in array
        $total = 0;
        foreach ($arr as $value) {
            $total = $total + $value; // total value of array numbers
        }
        if ($count != 0)
            $average = ($total / $count); // get average value
        else
            $average = 0;
        return $average;
    }

    protected function getColors($aff_id) {
        $query = Doctrine_Query::create()
                ->select('a.id')
                ->from('Asset a')
                ->where('a.affiliate_id=?', $aff_id);
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $temp = $query->execute();
        $assets = array();
        foreach ($temp as $t) {
            $assets[$t['id']] = self::getColor($t['id']);
        }
        if (in_array("Rojo", $assets)) {
            return "Rojo";
        }
        if (in_array("Amarillo", $assets)) {
            return "Amarillo";
        }
        return "Verde";
    }

    protected function getColor($ass_id) {
        $query = Doctrine_Query::create()
                ->select('t.created_at')
                ->from('Ticket t')
                ->where('t.asset_id=?', $ass_id)
                ->orderBy('t.created_at DESC')
                ->limit(1);
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $color = $query->fetchOne();
        $hours = (strtotime(date('Y-m-d h:m:s')) - strtotime($color['created_at'])) / 3600;
        if ($hours < 4)
            return "Verde";
        if ($hours < 6)
            return "Amarillo";
        else
            return "Rojo";
    }

}
