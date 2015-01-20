<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 1/18/15
 * Time: 13:01
 */

class ApiTestController extends AppController {

    public $components = array('RequestHandler');

    public function view(){
        $this->layout = "";
        $this->autoRender = false;
        $this->loadModel("Evaluacion");

        $this->request->allowMethod("ajax","GET","POST");
        if($this->request->is("GET"))
        {
            $data = $this->request->input('json_decode');
            $evaluaciones = $this->Evaluacion->find('all',array('conditions'=>array(
                'Evaluacion.user_id'=>$this->Auth->user('id')),
                'order' => 'Evaluacion.created DESC',
            ));

            echo json_encode($evaluaciones);
        }
        else
        {

        }




    }

} 