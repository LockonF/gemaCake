<?php
/**
 * Created by PhpStorm.
 * Pregunta: LockonDaniel
 * Date: 9/3/14
 * Time: 10:46
 */

class PreguntasController extends AppController {

    public function ver()
    {
        $preguntas= $this->Pregunta->find('all');
        $this->set('preguntas', $preguntas);
    }

    public function eliminar()
    {
        $this->Pregunta->delete($this->request->data['id']);
        echo 'success';
    }

    public function resultado()
    {
        $this->set("preguntas",null);
        $preguntas = $this->Pregunta->find('all',array('conditions'=>array('Pregunta.oracion LIKE'=>"%".$this->request->data['fieldBusqueda']."%")));
        $this->set('preguntas', $preguntas);
    }

    public function modificar()
    {
        $temas = $this->Pregunta->Tema->find('all');
        $this->set('temas', $temas );
    }

    public function createPregunta()
    {
        $this->autoRender = false;
        if($this->request->is('post'))
        {
            if($this->request->data['recurso']=="")
                $this->request->data['recurso'==null];
            $this->Pregunta->create();
            $preguntaData = array('oracion'=>$this->request->data['oracion'],'opc1'=>$this->request->data['opc1'],
            'opc2'=>$this->request->data['opc2'],'opc3'=>$this->request->data['opc3'],'opc4'=>$this->request->data['opc4'],
            'just'=>$this->request->data['just'],'id_tema'=>$this->request->data['id_tema'],'opcc'=>$this->request->data['opcc'],
            'recurso'=>$this->request->data['recurso']);
            if($this->Pregunta->save($preguntaData))
            {
                echo "success";
                $this->Pregunta->clear();
            }
            else{
                $errors=array();
                foreach($this->Pregunta->validationErrors as $error)
                {
                    $errors[]=$error;
                }
                echo  json_encode($errors);
            }

        }
    }
} 