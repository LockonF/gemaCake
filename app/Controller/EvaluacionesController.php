<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 9/7/14
 * Time: 20:00
 */

class EvaluacionesController extends AppController {

    public function isAuthorized($user=null)
    {
        if(isset($user) && $user['role_id']!=3)
            $this->redirect(array('controller' => 'users', 'action' => 'login'));

        return parent::isAuthorized($user);
    }

    public function index()
    {
        $this->layout="layout-main";

    }
} 