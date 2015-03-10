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
        $this->setUserName();
        $this->set('evaluacion_activa',$this->Session->read("Evaluacion.started"));
        $promedio = 0;
        $this->layout="layout-main";
        $this->loadModel("Evaluacion");

        $user_id = $this->Auth->user('id');
        $evaluaciones = $this->Evaluacion->find('all', array('conditions' => array('Evaluacion.user_id' => $user_id)));
        foreach($evaluaciones as $evaluacion)
        {
            $promedio =$promedio+$evaluacion['Evaluacion']['puntaje'];
        }
        if(count($evaluaciones)!=0)
        {
            $promedio= $promedio/count($evaluaciones);
        }

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

            $eval[] = array("id"=>$lastEvaluacion['Evaluacion']['id'],"fecha"=>$date->format('d-m-Y'),"promedio"=>$lastEvaluacion['Evaluacion']['promedio']);
        }

        $this->set("ultimos_resultados",$eval);
    }


    public function evaluacion()
    {
        $this->setUserName();
        $this->layout="layout-main";

        if($this->Session->read("Evaluacion.started")==false)
        {
            $this->set("x",$this->getNumPreguntas());
            $this->set("tiempo",150);
            $this->set("finalizado",false);

            $preguntas = $this->genPreguntas();
            $this->set($preguntas);

            $this->Session->write("Evaluacion.started",true);
            $this->Session->write("Evaluacion.preguntas",$preguntas);
        }
        else
        {
            //$this->errors("totalPreguntas",$this->getNumPreguntas());
            $this->set("tiempo",150);
            $this->set($this->Session->read("Evaluacion.preguntas"));
        }




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

            $ultimosResultados = $this->getLastEvalsResults($evaluaciones,count($newLabels));



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

    public function getLastEvalsResults($evaluaciones,$numLabels)
    {
        $this->loadModel('Evaluacion');
        $numRegistros = count($evaluaciones);
        if($numRegistros>0)
        {
            if($numRegistros==1)
            {
                $tempResult= $this->Evaluacion->Resultado->find('all',array(
                    'fields'=>array('Materia.id,SUM(Resultado.puntaje) as puntaje'),
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
                $evalResults['actual']=$tempResult;
                $evalResults['actual']= $this->filterResults($evalResults['actual'],$numLabels);
                $evalResults['anterior']=$evalResults['actual'];

            }
            else
            {
                $evalResults['actual'] = $this->Evaluacion->Resultado->find('all',array(
                    'fields'=>array('Materia.id,SUM(Resultado.puntaje) as puntaje'),
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
                    'fields'=>array('Materia.id,ROUND(AVG(Resultado.puntaje),2) as puntaje'),
                    'conditions'=>array("Evaluacion.user_id"=>$this->Auth->user('id')),
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
                $evalResults['actual']= $this->filterResults($evalResults['actual'],$numLabels);
                $evalResults['anterior']= $this->filterResults($evalResults['anterior'],$numLabels);
            }

            return $evalResults;
        }
        else
        {
            $newResults = array();
            for($i=0;$i<$numLabels;$i++)
            {
                $newResults[$i]="0";
            }
            $evalResults['actual']=$newResults;
            $evalResults['anterior']=$newResults;
            return $evalResults;

        }

    }

    /**
     *
     * Función para guardar, aseguramos la persistencia en el examen
     */

    public function guardar()
    {
        $this->autoRender = false;

        if($this->request->data!=null)
        {
        //Tomamos los keys de las preguntas así como la respuesta seleccionada
            foreach ($this->request->data as $key=>$respuesta_sel) {

                //En cada categoría buscamos las preguntas
                foreach ($this->Session->read("Evaluacion.preguntas.categorias") as $keycategoria=>$categoria) {
                        foreach ($categoria['preguntas'] as $keypregunta=>$pregunta) {
                            //Si nuestro id de pregunta empata con la llave que se le envía
                            if ($pregunta['qid'] == $key) {

                                //Entonces escribe seleccionado como true
                                $respuesta_sel=intval($respuesta_sel)-1;
                                $this->Session->write("Evaluacion.preguntas.categorias.".$keycategoria.".preguntas.".$keypregunta.".respuestas.".$respuesta_sel.".seleccionada",true);
                                //Borra las demás respuestas seleccionadas
                                for($i = 0;$i<4;$i++)
                                {
                                    if($respuesta_sel!=$i)
                                    {
                                        $this->Session->write("Evaluacion.preguntas.categorias.".$keycategoria.".preguntas.".$keypregunta.".respuestas.".$i.".seleccionada",false);
                                    }
                                }
                            }
                        }
                }

            }
        }

    }

    /**
     * Funcion para calificar
     */
    public function calificar()
    {
        $this->autoRender=false;
        $this->loadModel("Evaluacion");
        $this->loadModel("Pregunta");
        $this->loadModel("Resultado");
        $this->loadModel("Incorrecta");

        $evaluacion = array();
        $incorrectas = array();
        $puntajeTotal = 0;
        $iterador = 0;

        ksort($this->request->data);
        $preguntasData = $this->Pregunta->find('all',array('conditions'=>
            array("Pregunta.id"=>array_keys($this->request->data))));
        foreach($preguntasData as $key=>$pregunta)
        {
            if($this->request->data[(int)$pregunta['Pregunta']['id']] == $pregunta['Pregunta']['opcc'])
            {
                if(empty($evaluacion[$pregunta['Tema']['id']]))
                {
                    $evaluacion[$pregunta['Tema']['id']] = 1;
                }
                else{
                    $evaluacion[$pregunta['Tema']['id']] +=1;
                }
                $puntajeTotal += 1;
            }
            else{
                if(empty($evaluacion[$pregunta['Tema']['id']]))
                {
                    $evaluacion[$pregunta['Tema']['id']] = 0;
                }
                $incorrectas[$pregunta['Tema']['id']][]['Incorrecta'] = array("opcSel"=>$this->request->data[(int)$pregunta['Pregunta']['id']],'pregunta_id'=>$pregunta['Pregunta']['id']);
            }
        }

        foreach($evaluacion as $key=>$puntaje )
        {
            $resultado[$iterador]= array('tema_id'=>$key,'puntaje'=>$puntaje);
            foreach($incorrectas[$key] as $incorrecta)
            {

                $resultado[$iterador]['Incorrecta'][]=$incorrecta['Incorrecta'];
            }
            $iterador+=1;
        }
        $iterador=0;

            $data =
                array
                    (
                    "Evaluacion"=>array(
                            "user_id"=>$this->Auth->user('id'),
                            "puntaje"=>$puntajeTotal,
                            "tipo"=>1
                    ),
                    "Resultado"=>($resultado)
                );

        $this->Evaluacion->saveAssociated($data, array('deep' => true));
        $this->Session->write("Evaluacion.started",false);
        $this->Session->write("Evaluacion.preguntas",null);
        echo "success";

    }


    /*
     * Revision de evaluacion
     */


    public function revision()
    {
        $this->setUserName();
        $this->layout="layout-main";
        //Obtenemos el ID
        $evalID = $_GET['id'];
        //Cargamos los modelos
        $this->loadModel("Evaluacion");

        $numPreguntas = $this->getNumPreguntas();

        //Obtenemos la evaluación de acuerdo a la URL

        $evalData = $this->Evaluacion->find('all',array("conditions"=>array(
            "Evaluacion.id"=>$evalID
        )));

        //Obtenemos el objeto DateTime para darle formato al string

        $fecha = new DateTime($evalData[0]['Evaluacion']['created']);


        //Obtenemos los resultados por materia
        $materias = $this->getResultByMateria($evalData);

        //Obtenemos las preguntas incorrectas a partir de las materias
        $incorrectas = $this->getPreguntasIncorrectas($materias);

        //Formateamos las materias con el JSON correspondiente
        $materias = $this->formatMateriasJSON($materias);




        $this->set('totalPreguntas',$numPreguntas);
        $this->set('finalizado',true);
        //Llenamos el JSON de resultado con las categorias correspondientes
        $resultado = array
        (
            "resultado"=>array(
                'fecha'=>$fecha->format("d-m-Y"),
                'calificacion'=>1,
                'correctas'=>$evalData[0]['Evaluacion']['puntaje'],
                'categorias'=>$materias
            )
        );

    //Ponemos las categorias

    $this->set($resultado);
    $this->set('categorias',$incorrectas);
    }


    /**
     * @param $evaluaciones
     * @return mixed
     *
     * Obtenemos los resultados por materia
     */

    public function getResultByMateria($evaluaciones)
    {
        $conditionsArray = array();
        foreach($evaluaciones as $evaluacion)
        {
            $conditionsArray[]=array("Resultado.examen_id"=>$evaluacion['Evaluacion']['id']);
        }

        $evalResults = $this->Evaluacion->Resultado->find('all',array(
            'fields'=>array('Materia.id, Materia.nombre as nombre, Materia.label as codigo, SUM(Resultado.puntaje) as correctas, Materia.numpreguntas as total'),
            'conditions'=>array("Resultado.examen_id"=>$evaluaciones[0]['Evaluacion']['id']),
            'group'=>'Materia.id',
            'joins'=>array(
                array(
                    'table'=>'materias',
                    'alias'=>'Materia',
                    'type'=>'INNER',
                    'conditions'=>'Tema.id_materia = Materia.id'
                ),
            ),

        ));
        return $evalResults;
    }



    /*
     * Métodos Auxilares para el manejo de arreglos
     */

    public function genMaterias()
    {
        $this->loadModel("Materia");
        return $this->Materia->find('all');
    }


    public function genPreguntas()
    {
        $this->loadModel('Tema');
        $preguntas = array();
        $materias = $this->genMaterias();
        $validArray = array();
        $auxPreguntasArray = array();
        $respuestasArray= array();


        foreach($materias as $materia)
        {
            $preguntas = null;
            $preguntas=$this->Tema->find('all',
                array(
                    'fields'=>array("Materia.*,Pregunta.*"),
                    'conditions'=>array("Materia.id"=>$materia['Materia']['id']),
                    'joins'=>array(
                        array(
                            'table'=>'preguntas',
                            'alias'=>'Pregunta',
                            'type'=>'INNER',
                            'conditions'=>'Tema.id = Pregunta.id_tema'
                        ),
                    ),
                    'limit'=>$materia['Materia']['numpreguntas'],
                    'order'=>'rand()'
                )
            );
            foreach ($preguntas as $pregunta)
            {
                $correcta = (int) $pregunta['Pregunta']['opcc'];
                $opciones = array(
                    $pregunta['Pregunta']['opc1'],
                    $pregunta['Pregunta']['opc2'],
                    $pregunta['Pregunta']['opc3'],
                    $pregunta['Pregunta']['opc4']);
                $respuestasArray=null;
                for($i=0;$i<4;$i++)
                {
                        if(($i+1)==$correcta)
                        {
                            $respuestasArray[]=array("seleccionada"=>false,"correcta"=>true,"texto"=>$opciones[$i]);
                        }
                        else
                        {
                            $respuestasArray[]=array("seleccionada"=>false,"correcta"=>false,"texto"=>$opciones[$i]);
                        }
                }


                $auxPreguntasArray[]=
                    array(
                        'qid'=>$pregunta['Pregunta']['id'],
                        'titulo'=>$pregunta['Pregunta']['oracion'],
                        'recurso'=>array(
                            "titulo"=>$pregunta['Pregunta']['recurso'],
                            "referencia"=>$pregunta['Pregunta']['recurso']
                        ),
                        "contestada"=>'blank',
                        "respuestas"=>$respuestasArray,
                    );

            }


            if(!empty($auxPreguntasArray))
            {
                $validArray[]= array(
                    'nombre'=>$materia['Materia']['nombre'],
                    'codigo'=>$materia['Materia']['label'],
                    'totalPreguntas'=>$materia['Materia']['numpreguntas'],
                    'preguntas'=>$auxPreguntasArray
                );
            }
            $auxPreguntasArray=array();
        }
        $validArray= array("categorias"=>$validArray);
        return $validArray;

    }


    /**
     * @param $materias
     * @return array
     *
     * Obtenemos las preguntas incorrectas de cada materia
     */

    public function getPreguntasIncorrectas($materias)
    {

        $validArray = array();
        $auxPreguntasArray = array();
        $respuestasArray= array();

        $this->loadModel("Incorrecta");
        foreach($materias as $materia)
        {
            $preguntas = null;
            $auxPreguntasArray = null;
            $preguntas =$this->Incorrecta->find('all',
                array(
                    'fields'=>array("Incorrecta.*,Pregunta.*"),
                    'conditions'=>array('Evaluacion.id'=>$_GET['id'],"Materia.id"=>$materia['Materia']['id']),
                    'joins'=>
                        array(

                            array(
                                'table'=>'temas',
                                'alias'=>'Tema',
                                'type'=>'INNER',
                                'conditions'=>'Tema.id=Resultado.tema_id'
                            ),
                            array(
                                'table'=>'materias',
                                'alias'=>'Materia',
                                'type'=>'INNER',
                                'conditions'=>'Materia.id=Tema.id_materia'
                            ),
                            array(
                                'table'=>'evaluaciones',
                                'alias'=>'Evaluacion',
                                'type'=>'INNER',
                                'conditions'=>'Evaluacion.id=Resultado.examen_id'
                            )
                        )));

            foreach ($preguntas as $pregunta)
            {
                $opcSel =(int) $pregunta['Incorrecta']['opcSel'];
                $correcta = (int) $pregunta['Pregunta']['opcc'];
                $opciones = array(
                    $pregunta['Pregunta']['opc1'],
                    $pregunta['Pregunta']['opc2'],
                    $pregunta['Pregunta']['opc3'],
                    $pregunta['Pregunta']['opc4']);
                $respuestasArray=null;
                for($i=0;$i<4;$i++)
                {

                    if($opcSel==($i+1))
                    {
                        if($opcSel==$correcta)
                        {
                            $respuestasArray[]=array("seleccionada"=>true,"correcta"=>true,"texto"=>$opciones[$i]);
                        }
                        else
                        {
                            $respuestasArray[]=array("seleccionada"=>true,"correcta"=>false,"texto"=>$opciones[$i]);

                        }
                    }
                    else
                    {
                        if(($i+1)==$correcta)
                        {
                            $respuestasArray[]=array("seleccionada"=>false,"correcta"=>true,"texto"=>$opciones[$i]);
                        }
                        else
                        {
                            $respuestasArray[]=array("seleccionada"=>false,"correcta"=>false,"texto"=>$opciones[$i]);
                        }
                    }
                }


                $auxPreguntasArray[]=
                    array(
                        'qid'=>$pregunta['Pregunta']['id'],
                        'titulo'=>$pregunta['Pregunta']['oracion'],
                        'recurso'=>array(
                            "titulo"=>$pregunta['Pregunta']['recurso'],
                            "referencia"=>$pregunta['Pregunta']['recurso']
                        ),
                        "contestada"=>true,
                        "respuestas"=>$respuestasArray,
                        "justificacion"=>$pregunta['Pregunta']['just']
                    );

            }


            if(!empty($auxPreguntasArray))
            {
                $validArray[]= array(
                    'nombre'=>$materia['Materia']['nombre'],
                    'codigo'=>$materia['Materia']['codigo'],
                    'totalPreguntas'=>$materia['Materia']['total'],
                    'preguntas'=>$auxPreguntasArray
                );
            }


        }
        return $validArray;


    }

    /**
     * @param $elements
     * @return array
     * Funcion para recorrer un arreglo de modo que inicie en 0
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

    /**
     * @param $evaluaciones
     * @return array
     *
     * Formateamos promedios como los requiere la vista
     */

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

    /**
     * @param $evalResults
     * @return array
     * Solo regresamos los puntajes
     */

    public function filterResults($evalResults,$numLabels)
    {
        $newResults = array();
        for($i=0;$i<$numLabels;$i++)
        {
            $newResults[$i]="0";
        }

        foreach ($evalResults as $evalResult)
        {
                $newResults[$evalResult['Materia']['id']-1]=$evalResult[0]['puntaje'];

        }
        return $newResults;
    }

    /**
     * @param $materiasData
     * @return array
     *
     * Damos el formato a las materias como lo pide la vista
     */

    public function formatMateriasJSON($materiasData)
    {
        $validArray = array();
        foreach($materiasData as $materia)
        {
            $validArray[]=array('nombre'=>$materia['Materia']['nombre'],
                                'codigo'=>$materia['Materia']['codigo'],
                                'correctas'=>$materia[0]['correctas'],
                                'total'=>$materia['Materia']['total']
                                );
        }
        return $validArray;

    }


    public function getNumPreguntas()
    {
        $this->loadModel("Materia");

        //Obtenemos el numero de preguntas
        $preguntasData = $this->Materia->find('list',
            array("fields"=>
                array("Materia.numpreguntas")));


        //Obtenemos el total de preguntas
        $numPreguntas = 0;
        foreach($preguntasData as $numPregunta)
        {
            $numPreguntas = $numPreguntas + $numPregunta;
        }
        return $numPreguntas;
    }

    public function setUserName()
    {
        $this->set('activeUser',$this->Auth->user('Profile')['nombre']);
        $this->set('rol',$this->Auth->user('Role')['name']);
    }

} 