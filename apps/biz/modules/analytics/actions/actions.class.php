<?php

/**
 * analytics actions.
 *
 * @package    elperro
 * @subpackage analytics
 * @author     Jacobo Martinez <jacobo.amn87@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class analyticsActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    // Acción NO UTILIZADA
    public function executeIndex(sfWebRequest $request) {
        // Calculo para el PieChart de sexos
        $profiles = Doctrine::getTable('UserProfile')->findAll();
        $subdata1 = array(0, 0);
        foreach ($profiles as $profile) {
            if ($profile->getGender() == "male") {
                $subdata1[0] += 1;
            }
            if ($profile->getGender() == "female") {
                $subdata1[1] += 1;
            }
        }

        // Calculo para saber los usuarios por edades y por sexo (ColumnBar)
        $subdataM = array(0, 0, 0, 0, 0);
        $subdataH = array(0, 0, 0, 0, 0);
        foreach ($profiles as $profile) {
            if ((date("Y-m-d") - $profile->getBirthdate() < 20) && (date("Y-m-d") - $profile->getBirthdate() > 9)) {
                if ($profile->getGender() == "male") {
                    $subdataH[0] += 1;
                } elseif ($profile->getGender() == "female") {
                    $subdataM[0] += 1;
                }
            } elseif (date("Y-m-d") - $profile->getBirthdate() < 30) {
                if ($profile->getGender() == "male") {
                    $subdataH[0] += 1;
                } elseif ($profile->getGender() == "female") {
                    $subdataM[0] += 1;
                }
            } elseif (date("Y-m-d") - $profile->getBirthdate() < 40) {
                if ($profile->getGender() == "male") {
                    $subdataH[0] += 1;
                } elseif ($profile->getGender() == "female") {
                    $subdataM[0] += 1;
                }
            } elseif (date("Y-m-d") - $profile->getBirthdate() < 50) {
                if ($profile->getGender() == "male") {
                    $subdataH[0] += 1;
                } elseif ($profile->getGender() == "female") {
                    $subdataM[0] += 1;
                }
            } elseif ((date("Y-m-d") - $profile->getBirthdate() > 50) && (date("Y-m-d") - $profile->getBirthdate() < 70)) {
                if ($profile->getGender() == "male") {
                    $subdataH[0] += 1;
                } elseif ($profile->getGender() == "female") {
                    $subdataM[0] += 1;
                }
            }
        }
    }
    
    //TODO: verificar que los usuarios esten activos
    public function executeShow(sfWebRequest $request) {
        $user = $this->getUser();
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

        if ($user->hasGroup('admin')) {
            $t = Doctrine::getTable('Affiliate')->findAll();
            $id = collectionToIdArray($t);
            $this->affiliate = "Administrador";
        } else {
            $user = $user->getGuardUser();

            $id = array($user->getAffiliateId());
            $this->affiliate = $user->getAffiliate();
        }

        $this->assets = Doctrine::getTable('Asset')->findByAffiliateId($id[0]);

        $this->newFans = $this->totalTickets = $this->totalFans = 0;
        $totalTicketsM = $totalTicketsH = 0;
        $this->edadPromH = $this->edadPromM = 0;
        $this->porcentajeH = $this->porcentajeM = 0;
        $this->frecuenciaH = $this->frecuenciaM = 0;
        $this->width2 = $this->width3 = $this->width4 = $this->width5 = 0;
        $this->height2 = $this->height3 = $this->height4 = $this->height5 = 0;
        $this->type = $this->type3 = $this->type4 = $this->type5 = 0;
        $this->data = array();
        $this->label = '0';
        $this->title4 = $this->title5 = 0;
        $this->dataWeekday = $this->dataHour = $this->dataCard = array();
        $this->labelWeekday = $this->labelHour = $this->labelCard = '2';
        $this->feedbacks = array();
        $this->goodFeeds = $this->regularFeeds = $this->badFeeds = $this->feedbacksCount = 0;
        $this->lastComments = $subdataHorasH = $subdataHorasM = array();
        $this->cards_completed = 0;
        //Verificar esto con mejores datos
        $query = Doctrine_Query::create()
                ->select('s.id, s.affiliate_id, s.created_at, s.status, u.id, up.gender, up.birthdate')
                ->from('Subscription s')
                ->leftJoin('s.User u')
                ->leftJoin('u.UserProfile up')
                ->andWhereIn('s.affiliate_id', $id)
                ->andWhere('CAST(s.created_at AS DATE) BETWEEN ? AND ?', array($begin_date, $end_date));
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
            //$userProfile = Doctrine::getTable('UserProfile')->findBy('user_id', $user['id']);
//            $query = Doctrine_Query::create()
//                    ->select('up.id, up.user_id, up.birthdate, up.gender, u.created_at')
//                    ->from('UserProfile up')
//                    ->leftJoin('up.User u')
//                    ->andWhere('up.user_id=?')
//                    ->andWhere('CAST(u.created_at AS DATE) BETWEEN ? AND ?', array($begin_date, $end_date));
//            $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
//            $userProfile = $query->execute(array($user['user_id']));
                //Verificar si el usuario tiene birthdate
                $edad = getAge($user['User']['UserProfile']['birthdate']);
                if ($user['User']['UserProfile']['gender'] == "male") {
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
                } elseif ($user['User']['UserProfile']['gender'] == "female") {
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

        $this->edadPromH = round(average($edadesH));
        $this->edadPromM = round(average($edadesM));

        //$periodo = 90;
        $totalUsers = 0;

        // Contar todos los tickets que estan en la tabla Ticket y que esten dentro del periodo
        $query = Doctrine_Query::create()
                ->select('t.id, up.gender, t.promo_id, p.affiliate_id, t.created_at, u.id')
                ->from('Ticket t')
                ->leftJoin('t.Promo p')
                ->leftJoin('t.User u')
                ->leftJoin('u.UserProfile up')
                ->addWhere('CAST(created_at AS DATE) BETWEEN ? AND ?', array($begin_date, $end_date))
                //->andWhere('t.created_at > FROM_DAYS(TO_DAYS(NOW())- ?)', $periodo)
                ->andWhereIn('p.affiliate_id', $id);
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $tickets = $query->execute();
        $this->totalTickets = count($tickets);
        //var_dump($totalTickets);
//        echo $totalTickets;
        foreach ($tickets as $ticket) {
//            var_dump($ticket);
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
                //->andWhere('t.created_at > FROM_DAYS(TO_DAYS(NOW())- ?)', $periodo)
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

        $this->width2 = '600';
        $this->height2 = '250';
        $this->title2 = 'Edad de mis afiliados';
        $this->data = array('Hombres' => $subdataH, 'Mujeres' => $subdataM);
        $this->label = array('menor 20', '20-30', '30-40', '40-50', 'mayor 50');
        $this->type = 'ColumnChartAges';

        $this->width3 = '600';
        $this->height3 = '250';
        $this->title3 = 'Que dias vienen mis afiliados';
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

        $this->width4 = '600';
        $this->height4 = '250';
        $this->title4 = '¿A qué hora vienen mis afiliados?';
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

        $this->width5 = '600';
        $this->height5 = '200';
        $this->title5 = '¿Cuál es el estatus de los premios de mis afiliados?';
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
        $this->labelX = array('1 visita', '2 visitas', '3 visitas', '4 visitas', 'mayor a 5 visitas');
        $this->typeX = 'ColumnChartX';
    }

    public function executeShowAsset(sfWebRequest $request) {
        $user = $this->getUser();
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

        if ($user->hasGroup('admin')) {
            $t = Doctrine::getTable('Affiliate')->findAll();
            $id = collectionToIdArray($t);
        } else {
            $user = $user->getGuardUser();

            $id = array($user->getAffiliateId());
        }

        $this->asset = $this->getRoute()->getObject();
        $this->assets = $user->getAffiliate()->getAssets();
        $this->assets_ids = $user->getAffiliate()->getAssets()->getPrimaryKeys();

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
                ->addWhere('s.asset_id=?', $this->asset->getId())
                ->andWhereIn('s.affiliate_id', $id);
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $users = $query->execute();

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

        //PRUEBAS
//        $this->lastComments = $subdataHorasH = $subdataHorasM = array();
        $this->goodFeeds = $this->regularFeeds = $this->badFeeds = $this->feedbacksCount = 0;
        //FIN PRUEBAS

        foreach ($users as $user) {
            //$userProfile = Doctrine::getTable('UserProfile')->findBy('user_id', $user['id']);
            $query = Doctrine_Query::create()
                    ->select('up.id, up.user_id, up.birthdate, up.gender')
                    ->from('UserProfile up')
                    ->leftJoin('up.User u')
                    ->where('up.user_id=?')
                    ->andWhere('CAST(u.created_at AS DATE) BETWEEN ? AND ?', array($begin_date, $end_date));
            $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
            $userProfile = $query->execute(array($user['user_id']));
            foreach ($userProfile as $profile) {
                //Verificar si el usuario tiene birthdate
                $edad = getAge($profile['birthdate']);
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

        $this->edadPromH = round(average($edadesH));
        $this->edadPromM = round(average($edadesM));

        $periodo = 90;
        $totalUsers = 0;

        // Contar todos los tickets que estan en la tabla Ticket y que esten dentro del periodo
        $query = Doctrine_Query::create()
                ->select('t.id, up.gender, t.promo_id, p.affiliate_id, t.created_at, u.id')
                ->from('Ticket t')
                ->leftJoin('t.Promo p')
                ->leftJoin('t.PromoCode pc')
                ->leftJoin('t.User u')
                ->leftJoin('u.UserProfile up')
                ->andWhere('pc.asset_id=?', $this->asset->getId())
                ->andWhere('CAST(created_at AS DATE) BETWEEN ? AND ?', array($begin_date, $end_date))
                ->andWhereIn('p.affiliate_id', $id);
        $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
        $tickets = $query->execute();
        $this->totalTickets = count($tickets);
        //var_dump($totalTickets);
//        echo $totalTickets;
        foreach ($tickets as $ticket) {
//            var_dump($ticket);
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
                ->leftJoin('t.PromoCode pc')
                ->andWhere('up.gender=?')
                ->andWhere('pc.asset_id=?', $this->asset->getId())
                ->andWhere('CAST(created_at AS DATE) BETWEEN ? AND ?', array($begin_date, $end_date))
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

        $this->width2 = '600';
        $this->height2 = '250';
        $this->title2 = 'Edad de mis afiliados';
        $this->data = array('Hombres' => $subdataH, 'Mujeres' => $subdataM);
        $this->label = array('menor 20', '20-30', '30-40', '40-50', 'mayor 50');
        $this->type = 'ColumnChartAges';

        $this->width3 = '600';
        $this->height3 = '250';
        $this->title3 = 'Que dias vienen mis afiliados';
        $this->dataWeekday = array('Hombres' => $subdataDiasH, 'Mujeres' => $subdataDiasM);
        $this->labelWeekday = array('Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom');
        $this->type3 = 'ColumnChartWeekday';

        //FEEDBACK-AREA
        $query = Doctrine_Query::create()
                ->from('Feedback f')
                ->andWhereIn('f.affiliate_id', $id)
                ->andWhere('f.asset_id=?', $this->asset->getId())
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
        $this->lastComments = $table->retrieveAssetLastComments($this->asset->getId(), 3);

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

        $this->width4 = '600';
        $this->height4 = '250';
        $this->title4 = '¿A qué hora vienen mis afiliados?';
        $this->dataHour = array('Hombres' => $subdataHorasH, 'Mujeres' => $subdataHorasM);
        $this->labelHour = array('12am - 6 am', '6 am - 9am', '9 am - 12pm', '12pm - 3pm', '3pm - 6pm', '6pm - 12am');
        $this->type4 = 'ColumnChartHour';

        $query = Doctrine_Query::create()
                ->select('c.id, c.status, c.used_at, c.user_id')
                ->from('Coupon c')
                ->leftJoin('c.Promo p')
                ->andWhereIn('p.affiliate_id', $id)
                ->andWhere('c.asset_id=?', $this->asset->getId())
                ->andWhere('CAST(c.created_at AS DATE) BETWEEN ? AND ?', array($begin_date, $end_date))
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY);
        $cards = $query->execute();
        $cards_array = array(0, 0, 0);
        foreach ($cards as $card) {
            if ($card['status'] == 'active') {
                $cards_array[0]++;
            }
            if ($card['status'] == 'used') {
                $cards_array[1]++;
            }
            if ($card['status'] == 'expired') {
                $cards_array[2]++;
            }
        }

        $this->width5 = '600';
        $this->height5 = '200';
        $this->title5 = '¿Cuál es el estatus de los premios de mis afiliados?';
        $this->dataCard = array("Premios" => $cards_array);
        $this->labelCard = array('Sin canjear', 'Canjeadas', 'Expiradas');
        $this->type5 = 'ColumnChartCard';
        
        $ids = join(',',$this->assets_ids);
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $results = $q->execute("SELECT tags, COUNT(1) as total FROM (SELECT t.user_id, COUNT(1) AS tags FROM ticket t WHERE t.asset_id IN (" . $ids . ") GROUP BY (t.user_id)) AS T GROUP BY (tags) ")->fetchAll();
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
        $this->titleX = 'Número de visitas por usuario';
        $this->dataX = array('Usuarios' => $tagsByUser);
        $this->labelX = array('1 visita', '2 visitas', '3 visitas', '4 visitas', 'mayor a 5 visitas');
        $this->typeX = 'ColumnChartX';
    }

    public function executeFeedbacks(sfWebRequest $request) {
        $user = $this->getUser();

        $user = $user->getGuardUser();
        $id = array($user->getAffiliateId());

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

        if ($request->isXmlHttpRequest()) {
            return $this->renderPartial('feedbacksList', array('pager' => $this->pager, 'valoration' => $this->valoration));
        }
    }

    public function executeFeedbacksByAsset(sfWebRequest $request) {
        $user = $this->getUser();

        $user = $user->getGuardUser();
        $id = $user->getAffiliateId();

        $routeParms = $this->getRoute()->getParameters();

        if (!$this->asset = Doctrine::getTable('Asset')->findOneByAlphaId($routeParms['alpha_id'])) {
            return 'Error';
        } else if ($this->asset->getAffiliateId() != $id) {
            return 'Error';
        }

        $table = Doctrine::getTable('Feedback');

        $this->goodFeeds = $table->addByValorationQuery(2, $table->addByAssetQuery($this->asset->getId()))->count();

        $this->regularFeeds = $table->addByValorationQuery(1, $table->addByAssetQuery($this->asset->getId()))->count();

        $this->badFeeds = $table->addByValorationQuery(0, $table->addByAssetQuery($this->asset->getId()))->count();

        $this->counter = $this->goodFeeds + $this->regularFeeds + $this->badFeeds;

        $query = $table->addByMessageQuery('', true, $table->addByAssetQuery($this->asset->getId(), $table->addOrderBy('created_at', 'DESC')));

        $valoration = $request->getParameter('valoration', false);
        if ($valoration !== false) { // Debe ser exactamente puesto que uno de los valores para el parametro 'valoration' puede ser 0
            if (!strcasecmp($valoration, 'all') == 0) {
                $query = $table->addByValorationQuery($valoration, $query);
            }
        }

        $this->pager = new sfDoctrinePager('Feedback', sfConfig::get('app_ep_feedbacks_per_page', 20));
        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();

        if ($request->isXmlHttpRequest()) {
            return $this->renderPartial('feedbacksList', array('pager' => $this->pager));
        }
    }

    public function executePruebaQR(sfWebRequest $request) {
        $this->setLayout(false);

        $query = "";
        $pc_id = 23;
        $top = 200;

        for ($i = 0; $i < $top; $i++) {
            while (($new_code = Util::GenSecret(5, 0)) && (count(Doctrine::getTable('ValidationCode')->findBy('code', $new_code)) != 0)) {
                
            }

            while (($new_serial = Util::GenSecret(5, 1)) && (count(Doctrine::getTable('ValidationCode')->findByPromoCodeIdAndSerial($pc_id, $new_serial)) != 0)) {
                
            }
            $query .= "INSERT INTO validation_code (serial, code, active, promo_code_id) VALUES(" . $new_serial . ",'" . $new_code . "',1," . $pc_id . ");<br>";


            $validation_code = new ValidationCode();
            $validation_code->setActive(true);
            $validation_code->setPromoCodeId($pc_id);
            $validation_code->setCode($new_code);
            $validation_code->setSerial($new_serial);
            $validation_code->save();

            echo $query;
        }
    }

    public function executeAmCharts(sfWebRequest $request) {
        $user = $this->getUser();
        $this->form = new epDateRangeForm();

        $edadesH = $edadesM = array();
        $hombres = $mujeres = $totalTicketsH = $totalTicketsM = 0;
        $this->frecuenciaH = $this->frecuenciaM = $this->newFans = 0;
        //$subdataDiasH = $subdataDiasM = array_fill(0, 7, 0);
        
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

        if ($user->hasGroup('admin')) {
            $t = Doctrine::getTable('Affiliate')->findAll();
            $id = collectionToIdArray($t);
            $this->affiliate = "Administrador";
        } else {
            $user = $user->getGuardUser();

            $id = array($user->getAffiliateId());
            $this->affiliate = $user->getAffiliate();
        }

        $this->assets = Doctrine::getTable('Asset')->findByAffiliateId($id[0]);

        //Verificar esto con mejores datos
        $users = Doctrine_Query::create()
                ->select('s.id, s.affiliate_id, s.created_at, s.status, u.id, u.pre_registered, up.gender, up.birthdate')
                ->from('Subscription s')
                ->leftJoin('s.User u')
                ->leftJoin('u.UserProfile up')
                ->andWhereIn('s.affiliate_id', $id)
                ->andWhere('CAST(s.created_at AS DATE) BETWEEN ? AND ?', array($begin_date, $end_date))
                ->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY)
                ->execute(array());
        
        // Calculo de los fans totales de un negocio y de los nuevos fans en la ultima semana
        foreach ($users as $user) {
            if (($user['status'] == 'active') && (date($user["created_at"]) > $begin_date) && (date($user["created_at"]) < $end_date)) {
                $this->totalFans++;
            }
            if (date($user["created_at"]) > date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 7, date("Y")))) {
                $this->newFans++;
            }
        }
        
        $tickets = Doctrine_Query::create()
                ->select('t.id, up.gender, t.promo_id, p.affiliate_id, t.created_at, u.id, u.pre_registered')
                ->from('Ticket t')
                ->leftJoin('t.Promo p')
                ->leftJoin('t.User u')
                ->leftJoin('u.UserProfile up')
                ->addWhere('CAST(created_at AS DATE) BETWEEN ? AND ?', array($begin_date, $end_date))
                ->andWhereIn('p.affiliate_id', $id)
                ->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY)
                ->execute();
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

        if ($totalUsers != 0)
            $this->frecuenciaH = number_format($totalTicketsH / $totalUsers, 1, '.', '');

        $set = $query->execute(array("female"));
        $totalUsers = $set[0]['COUNT'];

        if ($totalUsers != 0)
            $this->frecuenciaM = number_format($totalTicketsM / $totalUsers, 1, '.', '');
        
        // Calculo de la edad segun genero
        $values = array('menor 21'=>array('male'=>0,'female'=>0),'21-30'=>array('male'=>0,'female'=>0),'31-40'=>array('male'=>0,'female'=>0),
            '41-50'=>array('male'=>0,'female'=>0),'mayor 50'=>array('male'=>0,'female'=>0));
        
        foreach ($users as $user) {
            //Verificar si el usuario tiene birthdate
            $edad = getAge($user['User']['UserProfile']['birthdate']);
            $gender = $user['User']['UserProfile']['gender'];
            
            if ($gender = "male") {
                $hombres ++;
                array_push ($edadesH, $edad);
            }
            elseif ($gender = "female") {
                $mujeres ++;
                array_push ($edadesM, $edad);
            }
            
            if(!$user['User']['pre_registered']){
                if (($edad < 21) && ($edad > 9)) {
                    $values['menor 21'][$gender] += 1;
                } elseif ($edad < 31) {
                    $values['21-30'][$gender] += 1;
                } elseif ($edad < 41) {
                    $values['31-40'][$gender] += 1;
                } elseif ($edad < 51) {
                    $values['41-50'][$gender] += 1;
                } elseif (($edad >= 51) && ($edad < 71)) {
                    $values['mayor 50'][$gender] += 1;
                }
            }
        }

        $this->edadPromH = round(average($edadesH));
        $this->edadPromM = round(average($edadesM));
        
        if ($hombres + $mujeres != 0) {
            $this->porcentajeH = number_format(($hombres / ($hombres+$mujeres)) * 100, 2, '.', '');
            $this->porcentajeM = number_format(($mujeres / ($hombres+$mujeres)) * 100, 2, '.', '');
        } else {
            $this->porcentajeH = 0;
            $this->porcentajeM = 0;
        }
        
        // ageAmChart
        $keys = array_keys($values);
        $ageAmChart = array_values($values);
        for($i=0;$i<sizeof($keys);$i++){
            $ageAmChart[$i]['ages'] = $keys[$i];
        }
        $this->ageAmChart = json_encode($ageAmChart);
        // Fin de ageAmChart
        
        $values = array('Monday'=>array('male'=>0,'female'=>0),'Tuesday'=>array('male'=>0,'female'=>0),'Wednesday'=>array('male'=>0,'female'=>0),
            'Thursday'=>array('male'=>0,'female'=>0),'Friday'=>array('male'=>0,'female'=>0),'Saturday'=>array('male'=>0,'female'=>0),
            'Sunday'=>array('male'=>0,'female'=>0));
        
        foreach ($tickets as $ticket) {
            if($ticket['User']['UserProfile']['gender']){
                $date = date('l', strtotime($ticket['created_at']));
                $gender = $ticket['User']['UserProfile']['gender'];
                $values[$date][$gender] ++ ;
            }
        }       
        
        // weekdayAmChart
        $keys = array_keys($values);
        $weekdayAmChart = array_values($values);
        for($i=0;$i<sizeof($keys);$i++){
            $weekdayAmChart[$i]['days'] = $keys[$i];
        }
        $this->weekdayAmChart = json_encode($weekdayAmChart);
        // Fin de weekdayAmChart
        
        $values = array('12am-6am'=>array('male'=>0,'female'=>0),'6am-9am'=>array('male'=>0,'female'=>0),'9am-12pm'=>array('male'=>0,'female'=>0),
            '12pm-3pm'=>array('male'=>0,'female'=>0),'3pm-6pm'=>array('male'=>0,'female'=>0),'6pm-12am'=>array('male'=>0,'female'=>0));
        foreach ($tickets as $ticket) {
            if($ticket['User']['UserProfile']['gender']){
                $date = date('G', strtotime($ticket['created_at']));
                $gender = $ticket['User']['UserProfile']['gender'];
                if ($date < '6') {
                    $values['12am-6am'][$gender] ++;
                }elseif ($date < '9') {
                    $values['6am-9am'][$gender] ++;
                }elseif ($date < '12') {
                    $values['9am-12pm'][$gender] ++;
                }elseif ($date < '15') {
                    $values['12pm-3pm'][$gender] ++;
                }elseif ($date < '18') {
                    $values['3pm-6pm'][$gender] ++;
                }elseif ($date < '24') {
                    $values['6pm-12am'][$gender] ++;
                }
            }
        }
        
        // hourAmChart
        $keys = array_keys($values);
        $hourAmChart = array_values($values);
        for($i=0;$i<sizeof($keys);$i++){
            $hourAmChart[$i]['hour'] = $keys[$i];
        }
        $this->hourAmChart = json_encode($hourAmChart);
        // Fin de hourAmChart
        
        $cards = Doctrine_Query::create()
                ->select('c.id, c.status, c.completed_at, c.user_id')
                ->from('Card c')
                ->leftJoin('c.Promo p')
                ->andWhereIn('p.affiliate_id', $id)
                ->andWhere('CAST(c.created_at AS DATE) BETWEEN ? AND ?', array($begin_date, $end_date))
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                ->execute();
        $values = array('por completar'=>array('card'=>0),'completados'=>array('card'=>0),'cupon generado'=>array('card'=>0),'canjeados'=>array('card'=>0));
        foreach ($cards as $card) {
            if ($card['status'] == 'active') {
                $values['por completar']['card']++;
            }
            if ($card['status'] == 'complete') {
                $values['completados']['card']++;
            }
            if ($card['status'] == 'exchanged') {
                $values['cupon generado']['card']++;
            }
            if ($card['status'] == 'redeemed') {
                $values['canjeados']['card']++;
            }
        }
        
        // cardAmChart
        $keys = array_keys($values);
        $cardAmChart = array_values($values);
        for($i=0;$i<sizeof($keys);$i++){
            $cardAmChart[$i]['cards'] = $keys[$i];
        }
        $this->cardAmChart = json_encode($cardAmChart);
        // Fin de cardAmChart
        
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $results = $q->execute("SELECT tags, COUNT(1) as total FROM (SELECT t.user_id, COUNT(1) AS tags FROM ticket t, promo p WHERE t.promo_id = p.id AND p.affiliate_id = " . $id[0] . " GROUP BY (t.user_id)) AS T GROUP BY (tags) ")->fetchAll();
        $tagsByUser = array_fill(-1,5,array("total"=>0));
        $values = array('1 visita'=>array("total"=>0),'2 visitas'=>array("total"=>0),'3 visitas'=>array("total"=>0),'4 visitas'=>array("total"=>0),
            'mayor de 5 visitas'=>array("total"=>0));
        foreach ($results as $res) {
            if ($res['tags'] > 4)
                $values['mayor de 5 visitas'] += array("total"=>$res['total']);
            else
                $tagsByUser[$res['tags'] - 1] = array("total"=>$res['total']);
        }
        //$tagsByUser[4] = $tagsByUser['-1'];
        //unset($tagsByUser['-1']);
        
        // tagAmChart
        $keys = array_keys($values);
        $tagAmChart = array_values($tagsByUser);
        for($i=0;$i<sizeof($keys);$i++){
            $tagAmChart[$i]['tags'] = $keys[$i];
        }
        $this->tagAmChart = json_encode($tagAmChart);
        // Fin de tagAmChart
        
        //FEEDBACK-AREA
        $feedbacks = Doctrine_Query::create()
                ->from('Feedback f')
                ->andWhereIn('f.affiliate_id', $id)
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                ->execute();
        $values = array('malo' => array("feedbacks" => 0), 'neutro' => array("feedbacks" => 0), 'bueno' => array("feedbacks" => 0));
        foreach ($feedbacks as $feed) {
            if ($feed['valoration'] == '0') {
                $values['malo']['feedbacks']++;
            }
            if ($feed['valoration'] == '1') {
                $values['neutro']['feedbacks']++;
            }
            if ($feed['valoration'] == '2') {
                $values['bueno']['feedbacks']++;
            }
        }
        
        // feedAmChart
        $keys = array_keys($values);
        $feedAmChart = array_values($values);
        for($i=0;$i<sizeof($keys);$i++){
            $feedAmChart[$i]['type'] = $keys[$i];
        }
        $this->feedbackAmChart = json_encode($feedAmChart);
        // Fin de feedAmChart
        
        $this->feedbacksCount = $values['malo']['feedbacks'] + $values['neutro']['feedbacks'] + $values['bueno']['feedbacks'];
        $table = Doctrine::getTable('Feedback');
        $this->lastComments = $table->retrieveLastComments($id, 3);
    }
}

function getAge($birthdate) {
    return floor((time() - strtotime($birthdate)) / 31556952); //(60 * 60 * 24 * 365.2425) = 31556952
}

function getGender($user_id) {
    $user = Doctrine_Query::create()->from('UserProfile up')->where('up.user_id = ?', $user_id);
    return $user->fetchOne()->getGender();
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

function collectionToIdArray($collection) {
    $result = array();
    foreach ($collection as $object) {
        array_push($result, $object->getId());
    }
    return $result;
}
