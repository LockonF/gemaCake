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
            $date = new DateTime($lastEvaluacion['Evaluacion']['fecha']);

            $eval[] = array("fecha"=>$date->format('d-m-Y'),"promedio"=>$lastEvaluacion['Evaluacion']['promedio']);
        }

        $this->set("ultimos_resultados",$eval);
    }


    public function getDatosAlumno()
    {
        $this->autoRender=false;
        if($this->request->is('get'))
        {
            $this->loadModel('Materia');
            $this->loadModel('Evaluacion');


            $labels = $this->Materia->find('list',array(
                'fields'=>array('label')
            ));

            $evaluaciones = $this->Evaluacion->find('all',array('conditions'=>array(
                'Evaluacion.user_id'=>$this->Auth->user('id')),
                'order' => 'Evaluacion.created DESC',
            ));

            $newLabels = $this->formatArray($labels);
            $promediosData = $this->formatPromedios($evaluaciones);
            $ultimosResultados = $this->getLastEvalsResults($evaluaciones);



            $jsonResponse = array("avances"=>array(
                                 "labels"=>$newLabels,
                                 "periodos"=>array(
                                        "anterior"=>$ultimosResultados['anterior'],
                                        "actual"=>$ultimosResultados['actual'])),

                        "promedios"=>array(
                                  "labels"=>$promediosData['labels'],
                                  "data"=>$promediosData['data']
                        )
            );
            $jsonResponse = json_encode($jsonResponse);
            echo $jsonResponse;
        }
    }



    /**
     * @param $evaluaciones
     * @return array()
     */

    public function getLastEvalsResults($evaluaciones)
    {
        $this->loadModel('Evaluacion');
        $numRegistros = count($evaluaciones);
        if($numRegistros>0)
        {
            if($numRegistros==1)
            {
                $evalResults = $this->Evaluacion->Resultado->find('all',array(
                    'fields'=>array('SUM(Resultado.puntaje) as puntaje'),
                    'conditions'=>array("Resultado.examen_id"=>$evaluaciones[0]['Evaluacion']['id']),
                    'group'=>'Materia.id',
                    'joins'=>array(
                        array(
                                   'table'=>'materias',
                                   'alias'=>'Materia',
                                   'type'=>'INNER',
                                   'conditions'=>'Tema.id_materia = Materia.id'
                        )
                    ),

                ));

            }
            else
            {
                $evalResults['actual'] = $this->Evaluacion->Resultado->find('all',array(
                    'fields'=>array('SUM(Resultado.puntaje) as puntaje'),
                    'conditions'=>array("Resultado.examen_id"=>$evaluaciones[0]['Evaluacion']['id']),
                    'group'=>'Materia.id',
                    'joins'=>array(
                        array(
                            'table'=>'materias',
                            'alias'=>'Materia',
                            'type'=>'INNER',
                            'conditions'=>'Tema.id_materia = Materia.id'
                        )
                    )


                ));
                $evalResults['anterior'] = $this->Evaluacion->Resultado->find('all',array(
                    'fields'=>array('SUM(Resultado.puntaje) as puntaje'),
                    'conditions'=>array("Resultado.examen_id"=>$evaluaciones[1]['Evaluacion']['id']),
                    'group'=>'Materia.id',
                    'joins'=>array(
                        array(
                            'table'=>'materias',
                            'alias'=>'Materia',
                            'type'=>'INNER',
                            'conditions'=>'Tema.id_materia = Materia.id'
                        )
                    ),


                ));

                $evalResults['actual']= $this->filterResults($evalResults['actual']);
                $evalResults['anterior']= $this->filterResults($evalResults['anterior']);

            }
            return $evalResults;
        }
        else return null;

    }


    /*
     * Métodos Auxilares para el manejo de arreglos
     */


    public function formatArray($elements)
    {
        $newElements = array();
        foreach ($elements as $element)
        {
            $newElements[]=$element;
        }
        return $newElements;
    }

    public function formatPromedios($evaluaciones)
    {
        $promediosData = array();
        foreach ($evaluaciones as $evaluacion)
        {
            $date = new DateTime($evaluacion['Evaluacion']['created']);

            $promediosData['labels'][] = $date->format('d-m-Y');
            $promediosData['data'][] = $evaluacion['Evaluacion']['puntaje'];
        }
        return $promediosData;
    }

    public function filterResults($evalResults)
    {
        $newResults = array();
        foreach ($evalResults as $evalResult)
        {
            foreach($evalResult as $result)
            {
                $newResults[]=$result['puntaje'];
            }
        }
        return $newResults;
    }


} 