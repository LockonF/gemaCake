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
        $promedio = 0;
        $this->layout="layout-main";
        $this->loadModel("Evaluacion");

        $user_id = $this->Auth->user('id');
        $evaluaciones = $this->Evaluacion->find('all', array('conditions' => array('Evaluacion.user_id' => $user_id)));
        foreach($evaluaciones as $evaluacion)
        {
            $promedio =$promedio+$evaluacion['Evaluacion']['puntaje'];
        }
        $promedio= $promedio/count($evaluaciones);
        $this->set("promedio",$promedio);

        $lastEvaluaciones = $this->Evaluacion->find('all', array(
            'conditions' => array('Evaluacion.user_id' => $user_id
            ),
            'fields'=> array('Evaluacion.created AS fecha','Evaluacion.puntaje AS promedio'),
            'group by' => 'Evaluacion.user_id',
            'order' => 'Evaluacion.created DESC',
            'limit'=> '3'
        ));

        $eval = array();

        foreach($lastEvaluaciones as $lastEvaluacion)
        {
            $eval[] = array("fecha"=>$lastEvaluacion['Evaluacion']['fecha'],"promedio"=>$lastEvaluacion['Evaluacion']['promedio']);
        }

        $this->set("ultimos_resultados",$eval);
    }


    public function getDatosAlumno()
    {
        if($this->request->is('get'))
        {
            echo "Hola";
        }
    }


} 