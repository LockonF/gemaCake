<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 8/30/14
 * Time: 13:00
 */

class AdministratorsController extends AppController{
    public function beforeFilter() {
        $this->Auth->allow();
    }

    public function index(){
            $this->layout= 'layout-main';
    }

} 