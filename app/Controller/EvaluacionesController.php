<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 9/7/14
 * Time: 20:00
 */

class EvaluacionesController extends AppController {

    public $components = array (
        'Rest.Rest' => array(
            'catchredir' => true,
            'debug'=>2,
            'actions' => array(
                'evaluacion' => array(
                    'extract' => array('evaluacion'),
                ),

                'getDatosAlumno' => array(
                    'extract' => array('response'),
                )
            ),
            'log' => array(
                'pretty' => true,
            ),
            'ratelimit' => array(
                'enable' => false
            ),
        ),
    );


    public function beforeFilter () {
        $this->Auth->allow();
        if (!$this->Auth->user()) {
            // Try to login user via REST
            if ($this->Rest->isActive()) {
                $this->Auth->autoRedirect = false;
            }
        }
        //  parent::beforeFilter();
    }




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
        $this->layout="layout-main";
        $this->loadModel("Resultado");
        $this->loadModel("Evaluacion");
        $this->Resultado->virtualFields['promedio'] = 0;
        $user_id = $this->Auth->user('id');
        $promedio=0;

        $options['joins'] = array(
          array(
              'table'=>'Users',
              'alias'=>'USER',
              'type'=>'INNER',
              'conditions'=>array('Evaluacion.user_id=User.id')
          ),
          array(
              'table'=>'examenResultado',
              'alias'=>'Resultado',
              'type'=>'INNER',
              'conditions'=>array('Resultado.examen_id=Evaluacion.id')
          ),
          array(
              'table'=>'preguntas',
              'alias'=>'Pregunta',
              'type'=>'INNER',
              'conditions'=>array('Pregunta.id=Resultado.pregunta_id')
          ),
        );
        $options['conditions']=array('Evaluacion.user_id'=>$user_id);
        $options['group']=array('Evaluacion.id');
        $options['fields']=array('Evaluacion.id','Evaluacion.created AS fecha','SUM(Resultado.correcta) as Resultado__promedio');
        $options['recursive']=-1;
        $options['order']= array('Evaluacion.created DESC');

        $evaluaciones = $this->Evaluacion->find('all',$options);
        foreach($evaluaciones as $evaluacion)
        {
            $promedio += intval($evaluacion['Resultado']['promedio']);
        }

        $this->set("promedio",$promedio/count($evaluaciones));
        $this->set("ultimos_resultados",$evaluaciones);
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
            $evaluacion=  $this->Session->read("Evaluacion.preguntas");
            $this->set("evaluacion",$evaluacion);

        }
        else
        {
            $this->set("totalPreguntas",$this->getNumPreguntas());
            $this->set("tiempo",150);
            $evaluacion=  $this->Session->read("Evaluacion.preguntas");
            $this->set("evaluacion",$evaluacion);
            $this->set($this->Session->read("Evaluacion.preguntas"));
        }




    }


    /**
     * Funcion para las gráficas
     */
    public function getDatosAlumno()
    {
        if($this->request->is('get'))
        {
            //Carga de modelos
            $this->loadModel('Materia');
            $this->loadModel('Evaluacion');
            $this->loadModel("Resultado");
            $this->Resultado->virtualFields['promedio'] = 0;

            //Arrays Auxiliares
            $ultimosResultados = array();
            $arrayMaterias = $this->Materia->find('list',array('fields'=>'Materia.id'));
            $labels = $this->Materia->find('list',array('fields'=>'Materia.label'));

            //Funcion para poner en default las materias
            array_walk($arrayMaterias, function(&$value, $key){
                    $value=0;
            });


            //Opciones de Queries para encontrar evaluaciones
            $options['joins'] = array(
                array(
                    'table'=>'Users',
                    'alias'=>'USER',
                    'type'=>'INNER',
                    'conditions'=>array('Evaluacion.user_id=User.id')
                ),
                array(
                    'table'=>'examenResultado',
                    'alias'=>'Resultado',
                    'type'=>'INNER',
                    'conditions'=>array('Resultado.examen_id=Evaluacion.id')
                ),
                array(
                    'table'=>'preguntas',
                    'alias'=>'Pregunta',
                    'type'=>'INNER',
                    'conditions'=>array('Pregunta.id=Resultado.pregunta_id')
                ),
                array(
                    'table'=>'temas',
                    'alias'=>'Tema',
                    'type'=>'INNER',
                    'conditions'=>array('Tema.id=Pregunta.tema_id')
                ),
                array(
                    'table'=>'materias',
                    'alias'=>'Materia',
                    'type'=>'INNER',
                    'conditions'=>array('Materia.id=Tema.materia_id')
                ),
            );
            $options['group']=array('Evaluacion.id');
            $options['fields']=array('Evaluacion.id','Evaluacion.created AS fecha','SUM(Resultado.correcta) as Resultado__promedio');
            $options['recursive']=-1;
            $options['order']= array('Evaluacion.created DESC');
            $options['conditions']= array('User.id'=>$this->Auth->user('id'));

            $evaluaciones = $this->Evaluacion->find('all',$options);



            //Si existen evaluaciones
            if(count($evaluaciones)>0)
            {
                //Opciones para la última evaluación
                $options['group']=array('Materia.id');
                $options['fields']=array('Materia.id','AVG(Resultado.correcta)*10 as Resultado__promedio');
                $options['order'] = array('Materia.id');
                $options['conditions']=array('User.id'=>$this->Auth->user('id'),'Evaluacion.id'=>$evaluaciones[0]['Evaluacion']['id']);
                $ultimaEval=$this->Evaluacion->find('all',$options);

                //Procesamiento de la última evaluación
                foreach($ultimaEval as $evaluacion)
                {
                    $ultimosResultados['actual'][intval($evaluacion['Materia']['id'])]= $evaluacion['Resultado']['promedio'];
                }

                    $ultimosResultados['actual'] = $ultimosResultados['actual']+ $arrayMaterias;
                    ksort($ultimosResultados['actual']);

                //Opciones y procesamiento de las evaluaciones anteriores
                $options['conditions']=array('User.id'=>$this->Auth->user('id'),'Evaluacion.id !='=>$evaluaciones[0]['Evaluacion']['id']);
                $evalsAnteriores=$this->Evaluacion->find('all',$options);
                foreach($evalsAnteriores as $evaluacion)
                {
                    $ultimosResultados['anterior'][intval($evaluacion['Materia']['id'])]= $evaluacion['Resultado']['promedio'];
                }
                    $ultimosResultados['anterior'] = $ultimosResultados['anterior']+ $arrayMaterias;
                    ksort($ultimosResultados['anterior']);


                //Procesamiento del total de evaluaciones
                foreach($evaluaciones as $evaluacion)
                {
                    $date = new DateTime($evaluacion['Evaluacion']['fecha']);
                    $promediosData['labels'][] = $date->format('d-m-Y');
                    $promediosData['data'][] = $evaluacion['Resultado']['promedio'];
                }

                //Respuesta
                $jsonResponse = array("avances"=>array(
                    "labels"=>array_values($labels),
                    "periodos"=>array(
                        "anterior"=>array_values($ultimosResultados['anterior']),
                        "actual"=>array_values($ultimosResultados['actual']))),

                    "promedios"=>array(
                        "labels"=>$promediosData['labels'],
                        "data"=>$promediosData['data']
                    )
                );
            }
            else
            {
                $jsonResponse = array("avances"=>array(
                    "labels"=>array_values($labels),
                    "periodos"=>array(
                        "anterior"=>array_values($arrayMaterias),
                        "actual"=>array_values($arrayMaterias))),

                    "promedios"=>array(
                        "labels"=>array(),
                        "data"=>array()
                    )
                );
            }







            $this->set("response",$jsonResponse);
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
        $evalID = $this->request->query['id'];
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
                    utf8_decode($pregunta['Pregunta']['opc1']),
                    utf8_decode($pregunta['Pregunta']['opc2']),
                    utf8_decode($pregunta['Pregunta']['opc3']),
                    utf8_decode($pregunta['Pregunta']['opc4']));
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
                        'titulo'=>utf8_decode($pregunta['Pregunta']['oracion']),
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
                    'nombre'=>utf8_decode($materia['Materia']['nombre']),
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
        $this->set('rol',$this->Auth->user('Role')['nombre']);
    }

} 