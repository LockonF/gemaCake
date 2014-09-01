<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 8/30/14
 * Time: 13:00
 */

class AdministratorsController extends AppController{

    public function index(){
            $this->layout= 'layout-main';
    }

    public function isAuthorized($user=null)
    {
        if(isset($user) && $user['role_id']!=1)
            $this->redirect(array('controller' => 'users', 'action' => 'login'));

        return parent::isAuthorized($user);
    }



} 