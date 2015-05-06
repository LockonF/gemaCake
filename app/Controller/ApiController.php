<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 4/14/15
 * Time: 20:17
 */

class ApiController extends AppController{

    public $components = array(
            'RequestHandler',
            "OAuth.OAuth",
            'Rest.Rest' => array(
                'catchredir' => true,
                'debug'=>2,
                'actions' => array(
                    'createOAuthClient' => array(
                        'extract' => array('data'),
                    ),
                    'user' => array(
                        'extract' => array('data'),
                    ),
                    'createUser' => array(
                        'extract' => array('data'),
                    ),
                    'updateUser' => array(
                        'extract' => array('data'),
                    ),
                    'deleteUser' => array(
                        'extract' => array('data'),
                    ),
                    'searchPregunta' => array(
                        'extract' => array('data'),
                    ),
                    'getPreguntasByTema' => array(
                        'extract' => array('data'),
                    ),
                    'pregunta' => array(
                        'extract' => array('data'),
                    ),
                    'createPregunta' => array(
                        'extract' => array('data'),
                    ),
                    'updatePregunta' => array(
                        'extract' => array('data'),
                    ),
                    'deletePregunta' => array(
                        'extract' => array('data'),
                    ),
                    'materia' => array(
                        'extract' => array('data'),
                    ),
                    'createMateria' => array(
                        'extract' => array('data'),
                    ),
                    'searchMateria' => array(
                        'extract' => array('data'),
                    ),
                    'updateMateria' => array(
                        'extract' => array('data'),
                    ),
                    'deleteMateria' => array(
                        'extract' => array('data'),
                    ),
                    'tema' => array(
                        'extract' => array('data'),
                    ),
                    'createTema' => array(
                        'extract' => array('data'),
                    ),
                    'searchTema' => array(
                        'extract' => array('data'),
                    ),
                    'updateTema' => array(
                        'extract' => array('data'),
                    ),
                    'deleteTema' => array(
                        'extract' => array('data'),
                    ),
                    'evaluacion' => array(
                        'extract' => array('data'),
                    ),
                    'resultadosEvaluacion' => array(
                        'extract' => array('data'),
                    ),
                    'resultadosByTema' => array(
                        'extract' => array('data'),
                    ),
                    'generateEvaluacion' => array(
                        'extract' => array('data'),
                    ),
                    'createEvaluacion' => array(
                        'extract' => array('data'),
                    ),
                    'evaluar' => array(
                        'extract' => array('data'),
                    ),
                    'evaluacionToInferencia' => array(
                        'extract' => array('data'),
                    ),
                    'allTemaCompetencia' => array(
                        'extract' => array('data'),
                    ),
                    'magicPromedios' => array(
                        'extract' => array('data'),
                    ),
                    'magicResults' => array(
                        'extract' => array('data'),
                    ),
                    'genCode' => array(
                        'extract' => array('data'),
                    ),
                ),
                'log' => array(
                    'pretty' => true,
                ),
                'ratelimit' => array(
                    'enable' => false
                ),
            )
        );
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
        $this->OAuth->allow(array('createOAuthClient','genCode','demoGetToken','login','token'));

    }

    /**
     *OAuth Related Functions: Create
     */
    public function createOAuthClient()
    {
        if($this->request->is("post"))
        {
            if(trim($this->request->data["uri"]==""))
            {
                $redirectURI = "/api/genCode";
            }
            else{
                $redirectURI = $this->request->data["uri"];
            }
            $client = $this->OAuth->Client->add($redirectURI);
            $client['Client']['uri'] = "/oauth/authorize?response_type=code&client_id=".$client['Client']['client_id']."&redirect_url=".$redirectURI;
            $this->set('data',$client['Client']);
        }

    }

    /**
     * Método para encontrar usuarios por username
     */
    public function user()
    {
        if($this->request->is('get'))
        {
            $this->loadModel('User');
            $user = $this->User->find('first',
                array(
                    "conditions"=>array("User.username"=>$this->request->query['username'])
                ));
            if(isset($user))
            {
                    $this->set('data',$user);

            }
            else
            {
                $this->set('data','Usuario no encontrado');
            }
        }
    }

    /**
     * Metodo para crear usuarios nuevos
     */
    public function createUser()
    {
        if($this->request->is('post'))
        {
            $this->loadModel('User');
            $this->User->create();
            if($this->User->saveAll($this->request->data,array("validate"=>true)))
            {
                $this->set('data','Success');
            }
            else{
                $this->set('data',$this->User->validationErrors);
            }
        }
    }

    /**
     * Método para modificar usuarios. La única condicionante es que el nombre de usuario no se puede cambiar
     */
    public function updateUser()
    {
        if($this->request->is('post'))
        {
            $this->loadModel('User');
            $id = $this->User->find('first',
                array(
                    "conditions"=>array("User.username"=>$this->request->data['username']),
                    "fields"=> array("User.id","Profile.id")
            ));
            if(isset($id))
            {
                $this->request->data['id'] = $id['User']['id'];
                $this->User->set($this->request->data);

                if(isset($this->request->data['Profile']))
                {
                    $dataSource = $this->User->getDataSource();
                    $dataSource->begin();
                    if($this->User->save($this->request->data,array("validate"=>true)))
                    {

                        $this->loadModel('Profile');
                        $this->request->data['Profile']['id']=$id['Profile']['id'];
                        $this->Profile->set($this->request->data['Profile']);
                        if($this->Profile->save())
                        {
                            $dataSource->commit();
                            $this->set('data','Success');
                        }
                        else
                        {
                            $dataSource->rollback();
                            $this->set('data','No se pudo grabar el perfil');
                        }
                    }
                    else
                    {
                        $dataSource->rollback();
                        $this->set('data','No se pudo grabar el usuario');
                    }

                }
                elseif($this->User->save($this->request->data,array("validate"=>true)))
                {
                    $this->set('data','Success');

                }
                else{
                    $this->set('data',$this->User->validationErrors);
                }
            }
            else
            {
                $this->set('data','Usuario no encontrado');
            }
        }
    }

    /**
     * Método para eliminar usuarios
     */
    public function deleteUser()
    {
        if($this->request->is('post'))
        {
            $this->loadModel('User');
            if($this->User->deleteAll(array('User.username'=>$this->request->data['username']),true,true))
            {
                $this->set('data','Success');
            }
            else{
                $this->set('data','No se pudo eliminar el usuario');
            }
        }
    }

    /**
     * Método para buscar preguntas por oración
     */
    public function searchPregunta()
    {

        if($this->request->is('get'))
        {
            $this->loadModel('Pregunta');
            $preguntas = $this->Pregunta->find('all',
                array(
                    'conditions'=>array('Pregunta.oracion LIKE'=>"%".$this->request->query['oracion']."%"),
                    "fields"=>array("Pregunta.*","PreguntasControl.*"),
                    "recursive"=>0
                ));
            if(isset($preguntas))
            {
                $this->set('data', $preguntas);
            }
            else
            {
                $this->set('data', "No se encontraron preguntas");
            }
        }

    }

    /**
     * Metodo para obtener todas las preguntas mesde el API
     */
    public function getPreguntasByTema()
    {
        if($this->request->is('get'))
        {
            $this->loadModel('Pregunta');
            $preguntas = $this->Pregunta->find('all',
                array("conditions"=>array(
                    "tema_id"=>$this->request->query['tema_id']),
                    "fields"=>array("Pregunta.*","PreguntasControl.*"),
                    "recursive"=>0));
            $this->set('data',$preguntas);
        }
    }

    /**
     * Metodo para obtener todas las preguntas mesde el API
     */
    public function pregunta()
    {
        if($this->request->is('get'))
        {
            $this->loadModel('Pregunta');
            $preguntas = $this->Pregunta->find('all',
                array(
                    "conditions"=>array("Pregunta.id"=>$this->request->query['id']),
                    "fields"=>array("Pregunta.*","PreguntasControl.*"),
                    "recursive"=>0
                ));
            $this->set('data',$preguntas);
        }
    }

    /**
     * Metodo para crear pregunta desde el API
     */
    public function createPregunta()
    {
        if($this->request->is('post'))
        {
           $this->loadModel('Pregunta');
           $this->Pregunta->create();
            $this->request->data['PreguntasControl']=array("noBuenas"=>0,"noMalas"=>0,"noPreguntadas"=>0);
           if($this->Pregunta->saveAll($this->request->data))
           {
               $this->set('data','Success');
           }
           else
           {
               $this->set('data',$this->Pregunta->validationErrors);
           }
        }
    }

    /**
     * Metodo para eliminar pregunta desde el API
     */
    public function deletePregunta()
    {
        if($this->request->is('delete'))
        {
            $this->loadModel('Pregunta');
            if($this->Pregunta->delete($this->request->data['id']))
            {
                $this->set('data','Success');
            }
            {
                $this->set('data','Error');
            }
        }
    }

    /**
     * Método para modificar pregunta desde el API
     */
    public function updatePregunta()
    {
        if($this->request->is('post'))
        {
            $this->loadModel("Pregunta");
            $pregunta = $this->Pregunta->find("first",array("conditions"=>array("Pregunta.id"=>$this->request->data['id'])));
            if(isset($pregunta))
            {
                if($this->Pregunta->save($this->request->data))
                {
                    $this->set("data","Success");
                }
                else
                {
                    $this->set('data',$this->Pregunta->validationErrors);
                }
            }
            else
            {
                $this->set("data","Pregunta no encontrada");
            }

        }
    }

    /**
     *  Método para obtener materias
     */
    public function materia()
    {
        if($this->request->is('get'))
        {
            $this->loadModel('Materia');
            $materia = $this->Materia->find('first',array('conditions'=>array('id'=>$this->request->query['id'])));
            $materia['Materia']['nombre']=utf8_decode($materia['Materia']['nombre']);
            $this->set('data',$materia);
        }
    }

    /**
     *  Método para crear materia
     */
    public function createMateria()
    {
        if($this->request->is('post'))
        {
            $this->loadModel('Materia');
            $this->Materia->create();
            if($this->Materia->save($this->request->data))
            {
                $this->set("data","Success");
            }
            else
            {
                $this->set("data","No se puede grabar la materia");
            }
        }
    }

    /**
     *  Método para modificar materia
     */
    public function updateMateria()
    {
        if($this->request->is('post'))
        {
            $this->loadModel('Materia');
            $id= $this->Materia->find('first',
                array(
                    "conditions"=>array("id"=>$this->request->data['id']),
                    "fields"=>array("Materia.id")
                ));
            $this->request->data['id']=$id['Materia']["id"];
            if($this->Materia->save($this->request->data))
            {
                $this->set("data","Success");
            }
            else
            {
                $this->set("data","No se pudo modificar la materia");
            }

        }
    }

    /**
     * Metodo para eliminar materia
     */
    public function deleteMateria()
    {
        if($this->request->is('post'))
        {
            $this->loadModel("Materia");
            if($this->Materia->delete($this->request->data['id']))
            {
                $this->set("data","Success");
            }
            else
            {
                $this->set("data","No se pudo eliminar la materia");
            }
        }

    }

    /**
     * Método para buscar materia por nombre
     */
    public function searchMateria()
    {

        if($this->request->is('get'))
        {
            $this->loadModel('Materia');
            $materias = $this->Materia->find('all',array('conditions'=>array('Materia.nombre LIKE'=>"%".$this->request->query['nombre']."%")));
            if(isset($materias))
            {
                $this->set('data', $materias);
            }
            else
            {
                $this->set('data', "No se encontraron preguntas");
            }
        }

    }

    /**
     *  Método para obtener materias
     */
    public function tema()
    {
        if($this->request->is('get'))
        {
            $this->loadModel('Materia');
            $tema = $this->Materia->find('first',array('conditions'=>array('id'=>$this->request->query['id'])));
            $tema['Materia']['nombre']=utf8_decode($tema['Materia']['nombre']);
            $this->set('data',$tema);
        }
    }

    /**
     *  Método para crear tema
     */
    public function createTema()
    {
        if($this->request->is('post'))
        {
            $this->loadModel('Tema');
            $this->Tema->create();
            if($this->Tema->save($this->request->data))
            {
                $this->set("data","Success");
            }
            else
            {
                $this->set("data","No se puede grabar el tema");
            }
        }
    }

    /**
     *  Método para modificar tema
     */
    public function updateTema()
    {
        if($this->request->is('post'))
        {
            $this->loadModel('Tema');
            $id= $this->Tema->find('first',
                array(
                    "conditions"=>array("Tema.id"=>$this->request->data['id']),
                    "fields"=>array("Tema.id")
                ));
            $this->request->data['id']=$id['Tema']["id"];
            if($this->Tema->save($this->request->data))
            {
                $this->set("data","Success");
            }
            else
            {
                $this->set("data","No se pudo modificar la tema");
            }

        }
    }

    /**
     * Metodo para eliminar tema
     */
    public function deleteTema()
    {
        if($this->request->is('post'))
        {
            $this->loadModel("Tema");
            if($this->Tema->delete($this->request->data['id']))
            {
                $this->set("data","Success");
            }
            else
            {
                $this->set("data","No se pudo eliminar la tema");
            }
        }

    }

    /**
     * Método para buscar tema por nombre
     */
    public function searchTema()
    {

        if($this->request->is('get'))
        {
            $this->loadModel('Tema');
            $temas = $this->Tema->find('all',array(
                'conditions'=>array('Tema.nombre LIKE'=>"%".$this->request->query['nombre']."%"),
                'recursive'=>-1
            ));
            if(isset($temas))
            {
                $this->set('data', $temas);
            }
            else
            {
                $this->set('data', "No se encontraron preguntas");
            }
        }

    }

    /**
     *  Método para buscar evaluaciones
     */
    public function evaluacion(){
        if($this->request->is('get'))
        {
            $this->loadModel("Evaluacion");
            $evaluacion = $this->Evaluacion->find('first',array(
               'conditions'=>array('Evaluacion.user_id'=>$this->OAuth->user()['id']),
               'order' => array('Evaluacion.created DESC'),
                'recursive'=>-1
            ));
            $this->set('data',$evaluacion);
        }
    }

    /**
     * Método para obtener los resultados de la última evaluación
     */
    public function resultadosEvaluacion()
    {
        if($this->request->is('get'))
        {
            $this->loadModel("Evaluacion");
            $this->loadModel("Tema");
            $evaluacion = $this->Evaluacion->find('first',array(
                'conditions'=>array('Evaluacion.user_id'=>$this->OAuth->user()['id']),
                'fields'=>array('Evaluacion.*'),
                'order' => array('Evaluacion.created DESC'),
            ));
            $this->set('data',array('Resultado'=>$evaluacion['Resultado']));
        }
    }

    /**
     * Método para obtener los resultados de la última evaluacion por tema
     */
    public function resultadosByTema()
    {
        if($this->request->is('get'))
        {
            $this->loadModel("Evaluacion");
            $this->loadModel('Resultado');
            //Campos virtuales
            $this->Resultado->virtualFields['correctas'] = 0;
            $this->Evaluacion->virtualFields['correctas']=$this->Resultado->virtualFields['correctas'];
            $this->Resultado->virtualFields['incorrectas'] = 0;
            $this->Evaluacion->virtualFields['incorrectas']=$this->Resultado->virtualFields['incorrectas'];
            $this->Resultado->virtualFields['total'] = 0;
            $this->Evaluacion->virtualFields['total']=$this->Resultado->virtualFields['total'];

            //Queries

            $evaluacion = $this->Evaluacion->find('first',array(
                'conditions'=>array('Evaluacion.user_id'=>$this->OAuth->user()['id']),
                'fields'=>array('Evaluacion.id'),
                'order' => array('Evaluacion.created DESC'),
            ));
            $options['joins']=array(
                    array(
                        'table'=>'users',
                        'alias'=>'USER',
                        'type'=>'INNER',
                        'conditions'=>array('Evaluacion.user_id = User.id')
                    ),
                    array(
                        'table'=>'examenResultado',
                        'alias'=>'Resultado',
                        'type'=>'INNER',
                        'conditions'=>array('Resultado.examen_id = Evaluacion.id')
                    ),
                    array(
                        'table'=>'preguntas',
                        'alias'=>'Pregunta',
                        'type'=>'INNER',
                        'conditions'=>array('Resultado.pregunta_id = Pregunta.id')
                    ),
                    array(
                        'table'=>'temas',
                        'alias'=>'Tema',
                        'type'=>'INNER',
                        'conditions'=>array('Pregunta.tema_id= Tema.id')
                    ),

            );
            $options['conditions']= array('Evaluacion.id'=>$evaluacion['Evaluacion']['id']);
            $options['fields'] = array(
                'Tema.id',
                'SUM(Resultado.correcta) as Resultado__correctas',
                'COUNT(Resultado.id) - SUM(Resultado.correcta) as Resultado__incorrectas',
                'COUNT(Resultado.id) as Resultado__total');
            $options['group'] = array('Tema.id');
            $options['recursive'] = -1;
            $resultados = $this->Evaluacion->find('all', $options);
            $this->set("data",$resultados);

        }

    }

    /**
     *  Método para crear evaluación
     */
    public function createEvaluacion()
    {
        if($this->request->is('post'))
        {
            $this->loadModel('Evaluacion');

            $evaluaciones = $this->Evaluacion->findAllByUserIdAndFinished($this->OAuth->user()['id'],0);

            if(count($evaluaciones)==0)
            {
                $this->loadModel('TemaCompetencia');
                $this->Evaluacion->create();
                $this->request->data['Evaluacion']['user_id'] = $this->OAuth->user()['id'];
                $this->request->data['Evaluacion']['puntaje'] = 0;
                $this->request->data['Evaluacion']['finished'] = 0;
                $this->request->data['Evaluacion']['type'] = 'P';
                $evaluacionControl = false;

                if(!isset($this->request->data['setLater']))
                {
                    $this->request->data['setLater']=false;
                }

                if(!isset($this->request->data['Prototipo']) && $this->request->data['setLater'] == false)
                {
                    $temas_competencias = $this->TemaCompetencia->find('all');
                    $counter = 0;
                    $this->request->data['Evaluacion']['type'] = 'S';
                    foreach($temas_competencias as $dataTC)
                    {

                        $this->request->data['Prototipo'][$counter]['tema_competencia_id'] = $dataTC['TemaCompetencia']['id'];
                        $this->request->data['Prototipo'][$counter]['numpreguntas'] = $dataTC['TemaCompetencia']['numpreguntas'];
                        $this->request->data['Prototipo'][$counter]['dificultad'] = 1;

                        $counter++;
                    }
                    $evaluacionControl = true;

                }

                if($this->Evaluacion->saveAll($this->request->data))
                {
                    if($evaluacionControl==true)
                    {
                        $data[0]= 'Success';
                        $data[1]= 'Creaste una evaluación de control';
                    }
                    else{
                        if($this->request->data['setLater']==true)
                        {
                            $data[0]= 'Success';
                            $data[1]= 'Creaste una evaluación sin prototipos';
                        }
                        else
                        {
                            $data[0]= 'Success';
                            $data[1]= 'Creaste una evaluación con prototipos';
                        }

                    }

                    $this->set('data',$data);
                }
                else
                {
                    $this->set('data','No se pudo guardar la evaluación');
                }
            }
            else
            {
                $this->set('data',array('error'=>'406','message'=>'Not Acceptable','cause'=>utf8_decode('Solo puede haber una evaluación sin terminar')));
            }
            }


    }

    /**
     *  Método para generar las evaluaciones
     */

    public function generateEvaluacion()
    {
        if($this->request->is('get'))
        {
            $this->loadModel('Prototipo');
            $this->loadModel('Evaluacion');
            $this->loadModel('Pregunta');
            $this->loadModel('Materia');
            $this->loadModel('Tema');

            $evaluacion = $this->Evaluacion->find('first',array(
                'conditions'=>array('Evaluacion.user_id'=>$this->OAuth->user()['id'], 'Evaluacion.finished'=>0),
                'fields'=>array('Evaluacion.id','Evaluacion.created','Evaluacion.type'),
                'order' => array('Evaluacion.created DESC'),
                'recursive'=>-1
            ));


            if(count($evaluacion)>0)
            {
                $prototipos = $this->Prototipo->find('all',array(
                    'conditions'=>array("examen_id"=>$evaluacion['Evaluacion']['id'])

                ));

                $examen = array();
                $tempPreguntas= array();
                $oldMateria=0;
                $oldTema=0;
                $joins = array(
                    array(
                        'table'=>'preguntas',
                        'alias'=>'Pregunta',
                        'type'=>'INNER',
                        'conditions'=>array('Pregunta.tema_id = Tema.id')
                    ),
                );

                foreach($prototipos as $key=>$prototipo)
                {
                    $competencia = $prototipo['TemaCompetencia']['competencia_id'];
                    $preguntas=$this->Pregunta->find('formattedArray',array(
                        'conditions'=>array(
                            'Pregunta.tema_id'=>$prototipo['TemaCompetencia']['tema_id'],
                            'Pregunta.competencia_id'=>$competencia,
                            'Pregunta.dificultad'=>$prototipo['Prototipo']['dificultad']
                        ),
                        'limit'=>$prototipo['TemaCompetencia']['numpreguntas'],
                        'order'=>'rand()',
                        'recursive'=>-1,

                    ));

                    $tema = $this->Tema->find('first', array(
                        'conditions'=>array(
                            'Tema.id'=>$prototipo['TemaCompetencia']['tema_id']),
                        'recursive'=>0,
                        'fields'=>array('Tema.*','Materia.*')
                    ));


                    if($oldTema!=$tema['Tema']['id'])
                    {
                        if(count($examen)==0)
                        {
                            $examen['materias']=array($tema['Materia']);
                            $examen['materias'][0]['preguntas'] = array();
                            $tempPreguntas= array_merge($tempPreguntas,$preguntas);
                        }
                        else
                        {
                            if($tema['Materia']['id']!=$oldMateria)
                            {
                                $examen['materias'][count($examen['materias'])]=$tema['Materia'];
                                $examen['materias'][count($examen['materias'])-2]['preguntas']=$tempPreguntas;
                                $tempPreguntas = array();
                                $tempPreguntas = array_merge($tempPreguntas,$preguntas);
                            }
                            else
                            {
                                $tempPreguntas= array_merge($tempPreguntas,$preguntas);
                            }
                        }
                    }
                    else
                    {
                        $tempPreguntas= array_merge($tempPreguntas,$preguntas);
                    }

                    if($key==count($prototipos)-1)
                    {
                        if($tema['Materia']['id']!=$examen['materias'][count($examen['materias'])-1]['id'])
                        {
                            $examen['materias'][count($examen['materias'])]=$tema['Materia'];
                            $examen['materias'][count($examen['materias'])-2]['preguntas']=$preguntas;
                        }
                        else
                        {
                            $examen['materias'][count($examen['materias'])-1]['preguntas']=$tempPreguntas;
                        }
                    }


                    $oldMateria = $tema['Materia']['id'];
                    $oldTema = $tema['Tema']['id'];





                }
                $examen['id']=$evaluacion['Evaluacion']['id'];
                $examen['tipo']=$evaluacion['Evaluacion']['type'];
                $examen['fechaLimite']=strtotime($evaluacion['Evaluacion']['created'])+ 60*3;

                $this->set('data',$examen);

            }
            else
            {
                $this->set('data',array("error"=>'500','message'=>'Internal Server Error','cause'=>utf8_decode('No existe evaluación')));
            }

        }


    }

    /**
     *  Método para evaluar()
     *
     */

    public function evaluar()
    {
        if($this->request->is('post'))
        {
            $this->loadModel('Pregunta');
            $this->loadModel('PreguntasControl');
            $this->loadModel('Resultado');
            $this->loadModel('Evaluacion');
            $count = 0;
            $examen = $this->request->data['id'];
            $materias = $this->request->data['materias'];
            foreach($materias as $materia)
            {
                foreach($materia['preguntas'] as $pregunta)
                {
                    $id = $pregunta['qid'];
                    $selected = (isset($pregunta['sel']) ?  intval($pregunta['sel'])+1 : null);
                    $result = $this->Pregunta->evaluate($id,$selected);
                    if($result)
                    {
                        $this->PreguntasControl->increaseBuenas($id);
                        $count++;
                        $this->Resultado->createResult($id,1,$examen,$selected);

                    }
                    else
                    {
                        $this->PreguntasControl->increaseMalas($id);
                        $this->Resultado->createResult($id,0,$examen,$selected);
                    }
                }
            }
            $this->Evaluacion->finishEvaluacion($examen,$count);
            $this->set('data',$count);
        }
    }


    public function evaluacionToInferencia()
    {
        if($this->request->is('post'))
        {
            $this->loadModel('Resultado');
            $this->loadModel('Evaluacion');
            $this->loadModel('Tema');
            $this->loadModel('Materia');
            $this->loadModel('Parametro');

            $parametros = $this->Parametro->find('all');
            array_walk($parametros,function(&$value,&$key){
               $value['id']=  $value['Parametro']['id'];
               $value['dificultad']=  $value['Parametro']['dificultad'];
               $value['parametro']=  $value['Parametro']['parametro'];
               $value['porcentaje']=  $value['Parametro']['porcentaje'];
               unset($value['Parametro']);
            });

                $materias = array_values($this->Materia->find('all',array(
                'fields'=>array('Materia.id'),
                'order' => 'Materia.id'
            )));
            array_walk($materias,function(&$value,&$key){
                $value['id'] = $value['Materia']['id'];
                for($i = 0; $i<count($value['Tema']);$i++)
                {
                    $value['Temas'][$i]= array('id'=>$value['Tema'][$i]['id'],'correctas'=>0,'totales'=>0);
                }
                unset($value['Materia'],$value['Tema']);

            });

            $results = $this->Evaluacion->find('first',array(
                'fields'=> array('Evaluacion.id'),
                'conditions'=> array('Evaluacion.user_id'=>$this->OAuth->user()['id'],'Evaluacion.finished'=>'1'),
                'order' => 'Evaluacion.created DESC',
                'limit' => '1',
                'recursive'=>-1
            ));

            $results = $this->Resultado->find('motor',array('conditions'=>array('Evaluacion.id'=>$results['Evaluacion']['id'])));
            foreach($results as $result)
            {

                $keyMateria = array_search($result['materia'],array_column($materias,'id'));
                $keyTema = array_search($result['tema'],array_column($materias[$keyMateria]['Temas'],'id'));
                $keyParametro = array_search($result['dificultad'],array_column($parametros,'dificultad'));

                if($result['correcta'])
                {
                    $materias[$keyMateria]['Temas'][$keyTema]['correctas']+=$parametros[$keyParametro]['parametro'];
                }
                $materias[$keyMateria]['Temas'][$keyTema]['totales']+=$parametros[$keyParametro]['parametro'];
            }

            $results = array();
            foreach($materias as $materia)
            {
                $results['materias'][$materia['id']] = array();
                foreach($materia['Temas'] as $tema)
                {

                    if(($tema['correctas']/$tema['totales'])*100 >= 60 && $tema['totales']!=0)
                    {
                        $results['materias'][$materia['id']][]=1;
                    }
                    else
                    {
                        $results['materias'][$materia['id']][] = 0;
                    }
                }

            }

            $results['alumno']= $this->OAuth->user()['id'];

            $this->set('data',$results);
        }

    }





    /**
     *  Método para obtener relaciones entre temas y competencia
     */
    public function allTemaCompetencia()
    {
        if($this->request->is('get'))
        {
            $this->loadModel('TemaCompetencia');
            $this->set('data',$this->TemaCompetencia->find('all'));
        }
    }


    /**
     *  Método para generar el promedio
     */

    public function magicPromedios()
    {

        if ($this->request->is('get')) {


            //Obtenemos la última evaluacion
            $this->loadModel("Evaluacion");
            $this->loadModel("Resultado");
            $this->loadModel('Tema');

            //Declaramos los campos virtuales
            $this->Resultado->virtualFields['promedio'] = 0;
            $this->Evaluacion->virtualFields['promedio'] = $this->Resultado->virtualFields['promedio'];
            $this->Resultado->virtualFields['correctas'] = 0;
            $this->Evaluacion->virtualFields['correctas'] = $this->Resultado->virtualFields['correctas'];
            $this->Resultado->virtualFields['incorrectas'] = 0;
            $this->Evaluacion->virtualFields['incorrectas'] = $this->Resultado->virtualFields['incorrectas'];
            $this->Resultado->virtualFields['total'] = 0;
            $this->Evaluacion->virtualFields['total'] = $this->Resultado->virtualFields['total'];


            //ID de la última evaluación
            $lastEvaluacion = $this->Evaluacion->find('first', array(
                'conditions' => array('Evaluacion.user_id' => $this->OAuth->user()['id']),
                'fields' => array('Evaluacion.id'),
                'order' => array('Evaluacion.created DESC'),
                'recursive' => -1
            ));

            //IDs de todas las evaluaciones

            $allEvals = $this->Evaluacion->find('list', array(
                'conditions' => array('Evaluacion.user_id' => $this->OAuth->user()['id']),
                'fields' => array('Evaluacion.id'),
                'order' => array('Evaluacion.created DESC'),
                'recursive' => -1
            ));

            //Opciones para obtener los resultados de la última evaluación

            $options['joins'] = array(
                array(
                    'table' => 'users',
                    'alias' => 'USER',
                    'type' => 'INNER',
                    'conditions' => array('Evaluacion.user_id = User.id')
                ),
                array(
                    'table' => 'examenResultado',
                    'alias' => 'Resultado',
                    'type' => 'INNER',
                    'conditions' => array('Resultado.examen_id = Evaluacion.id')
                ),
                array(
                    'table' => 'preguntas',
                    'alias' => 'Pregunta',
                    'type' => 'INNER',
                    'conditions' => array('Resultado.pregunta_id = Pregunta.id')
                ),
                array(
                    'table' => 'temas',
                    'alias' => 'Tema',
                    'type' => 'INNER',
                    'conditions' => array('Pregunta.tema_id= Tema.id')
                ),

            );

            $options['conditions'] = array('Evaluacion.id' => $lastEvaluacion['Evaluacion']['id']);
            $options['fields'] = array(
                'Tema.*',
                'SUM(Resultado.correcta) as Resultado__promedio');
            $options['group'] = array('Tema.id');
            $options['recursive'] = -1;


            //Obtenemos los resultados de la última evaluación

            $resultadosLastEval = $this->Evaluacion->find('all', $options);


            //Cambios para el resto de las evaluaciones
            $options['conditions'] = array('Evaluacion.id' => array_values($allEvals));
            $options['fields'] = array(
                'Tema.id',
                'Tema.nombre',
                'AVG(Resultado.correcta) as Resultado__promedio');


            //Obtenemos los resultados de todas las evaluaciones
            $resultadosAllEval = $this->Evaluacion->find('all', $options);


            //Generamos dos listas de Temas
            $listTemas = $this->Tema->find('all',
                array(
                    'order' => array('Tema.id'),
                    'fields' => array('Tema.id', 'Tema.nombre'),
                    'recursive' => -1
                ));


            foreach ($resultadosAllEval as $resultado) {
                $key = array_search(array("Tema" => $resultado['Tema']), $listTemas, false);
                if ($key != false || $key == 0) {
                    $listTemas[$key] = $resultado;
                }

            }
            foreach ($resultadosLastEval as $resultado) {
                $key = array_search(array("Tema" => $resultado['Tema']), $listTemas, false);
                if ($key != false || $key == 0) {
                    $listTemas[$key]['Resultado']['actual'] = $resultado['Resultado']['promedio'];
                }

            }


            //Funcion Lambda para cambiar los valores de $listTemas
            array_walk($listTemas, function (&$value, $key) {
                $value['Tema']['nombre'] = utf8_decode($value['Tema']['nombre']);
                if (!isset($value['Resultado']['promedio'])) {
                    $value['Resultado']['promedio'] = 0;
                }
                if (!isset($value['Resultado']['actual'])) {
                    $value['Resultado']['actual'] = 0;
                }

            });


            $this->set('data', $listTemas);
        }
    }

    /**
     *  Método para generar los últimos resultados
     */

    public function magicResults()
    {
        $this->loadModel("Evaluacion");
        $this->loadModel('Resultado');
        //Campos virtuales
        $this->Resultado->virtualFields['correctas'] = 0;
        $this->Evaluacion->virtualFields['correctas']=$this->Resultado->virtualFields['correctas'];
        $this->Resultado->virtualFields['incorrectas'] = 0;
        $this->Evaluacion->virtualFields['incorrectas']=$this->Resultado->virtualFields['incorrectas'];
        $this->Resultado->virtualFields['total'] = 0;
        $this->Evaluacion->virtualFields['total']=$this->Resultado->virtualFields['total'];

        //Queries

        $evaluacion = $this->Evaluacion->find('list',array(
            'conditions'=>array('Evaluacion.user_id'=>$this->OAuth->user()['id']),
            'fields'=>array('Evaluacion.id'),
            'order' => array('Evaluacion.created DESC'),
            'limit' => 10
        ));
        $options['joins']=array(
            array(
                'table'=>'users',
                'alias'=>'USER',
                'type'=>'INNER',
                'conditions'=>array('Evaluacion.user_id = User.id')
            ),
            array(
                'table'=>'examenResultado',
                'alias'=>'Resultado',
                'type'=>'INNER',
                'conditions'=>array('Resultado.examen_id = Evaluacion.id')
            )
        );
        $options['conditions']= array('Evaluacion.id'=>array_values($evaluacion));
        $options['fields'] = array(
            'Evaluacion.id',
            'SUM(Resultado.correcta) as Resultado__correctas',
            'COUNT(Resultado.id) - SUM(Resultado.correcta) as Resultado__incorrectas',
            'COUNT(Resultado.id) as Resultado__total');
        $options['group'] = array('Evaluacion.id');
        $options['recursive'] = -1;
        $resultados = $this->Evaluacion->find('all', $options);
        $this->set("data",$resultados);

    }




    /**
     * PreguntasControl
     */
    public function controlPregunta()
    {
        if($this->request->is('get'))
        {
            $this->loadModel('PreguntasControl');
            $control =$this->PreguntasControl->find('first',array("conditions"=>array('PreguntasControl.pregunta_id'=>$this->request->data['pregunta_id'])));
            if(isset($control))
            {
                $this->set("data",$control);
            }
            else
            {
                $this->set("data","No se encuentra el control de la pregunta");
            }
        }
    }









    /**
     * Otros métodos
     */
    public function genCode()
    {
        $this->autoRender=false;
        echo "Please visit this url: /oauth/token?grant_type=authorization_code&code=".$this->request->query['code']."&client_id=xxxx&client_secret=xxxx";
        $this->set("data",$this->request->query['code']);
    }

    public function login()
    {
        $this->loadModel('User');
        if ($this->request->is('get'))
        {
            if(isset($this->request->query))
            {
                $this->Session->write('Query',$this->request->query);
            }
            else{
                $this->Session->delete("Query");
            }
        }
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $query = $this->Session->read("Query");
                $this->Session->delete("Query");
                return $this->redirect(array('plugin'=>'oauth','controller'=>'','action'=>'authorize',"?"=>$query));
            }
            else
            {
                $this->set("errors","Usuario o contraseña incorrectos");
            }
        }

    }



}