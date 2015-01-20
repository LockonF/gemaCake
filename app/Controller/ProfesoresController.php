<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 8/30/14
 * Time: 13:37
 */

class ProfesoresController extends AppController {

    public function index()
    {

        $this->layout = 'layout-main';
        $this->set(array("nombre"=>$this->Session->read("Auth.User.Profile.nombre").' '.$this->Session->read("Auth.User.Profile.apaterno"),
            "rol"=>"Profesor"));

    }
} 