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

    public function isAuthorized($user) {
        // Admin can access every action
        if (isset($user['role']) && $user['role'] === 'admin') {
            return true;
        }

        // Default deny
        return $this->redirect(array('controller'=>'pages','action'=>'display'));
    }

} 