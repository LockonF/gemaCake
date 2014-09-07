<?php
/**
 * Created by PhpStorm.
 * Pregunta: LockonDaniel
 * Date: 9/3/14
 * Time: 10:46
 */

class PreguntasController extends AppController {

    /**
     * Proporciona todas las preguntas en existencia
     */

    public function ver()
    {
        $preguntas= $this->Pregunta->find('all');
        $this->set('preguntas', $preguntas);
    }

    /**
     * Elimina la pregunta de acuerdo a un parámetro AJAX que se localiza en $this->request->data['id']
     *
     * Elimina los archivos asociados si es que también existen
     */


    public function eliminar()
    {
        $pregunta = $this->Pregunta->find('first',array('conditions'=>array('Pregunta.id'=>$this->request->data['id'])));
        if($pregunta['Pregunta']['recurso']!='')
        {
            $filename = WWW_ROOT.'files'.DS.$pregunta['Pregunta']['recurso'];
            unlink($filename);
        }
        $this->Pregunta->delete($this->request->data['id']);
        echo 'success';
    }

    /**
     * Busca todas las preguntas que coincidan con el campo de búsqueda (fieldBusqueda)
     *
     */

    public function resultado()
    {
        $this->set("preguntas",null);
        $preguntas = $this->Pregunta->find('all',array('conditions'=>array('Pregunta.oracion LIKE'=>"%".$this->request->data['fieldBusqueda']."%")));
        $this->set('preguntas', $preguntas);
    }

    /**
     * Método para la vista modificar, pone los temas como temas en sesión
     */

    public function modificar()
    {
        if(isset($this->request->data['id']))
        {
            $this->set($this->Pregunta->find('first',array('conditions'=>array('Pregunta.id'=>$this->request->data['id']))));
        }
        $temas = $this->Pregunta->Tema->find('all');
        $this->set('temas', $temas );
    }

    public function createPregunta()
    {
        $this->autoRender = false;
        if($this->request->is('post'))
        {
            $filename = WWW_ROOT.'files'.DS.$this->request->data['Pregunta']['recurso']['name'];
            if(!file_exists($filename))
            {
                /* Copiar Archivo*/
                if (move_uploaded_file($this->request->data['Pregunta']['recurso']['tmp_name'],$filename)) {
                    $this->request->data['Pregunta']['recurso']=$this->request->data['Pregunta']['recurso']['name'];
                    if($this->Pregunta->save($this->request->data['Pregunta']))
                    {

                        echo "success";
                        $this->Pregunta->clear();
                    }
                    else{
                        unlink($filename);
                        $errors=array();
                        foreach($this->Pregunta->validationErrors as $error)
                        {
                            $errors[]=$error;
                        }
                        echo  json_encode($errors);
                    }

                }
                else echo "No se pudo mover el archivo";

            }
            else echo "Archivo ya existente";
        }
    }

    public function executeMod()
    {
        $this->autoRender=false;
        $pregunta = $this->Pregunta->find('first',array('conditions'=>array('Pregunta.id'=>$this->request->data['Pregunta']['id'])));

        if($this->request->data['changeFile']=='true')
        {
            if($pregunta['Pregunta']['recurso']!='')
            {
                $filename = WWW_ROOT.'files'.DS.$pregunta['Pregunta']['recurso'];
                unlink($filename);
            }
            $filename = WWW_ROOT.'files'.DS.$this->request->data['Pregunta']['recurso']['name'];
            if(!file_exists($filename))
            {
                /* Copiar Archivo*/
                if (move_uploaded_file($this->request->data['Pregunta']['recurso']['tmp_name'],$filename)) {
                    $this->request->data['Pregunta']['recurso']=$this->request->data['Pregunta']['recurso']['name'];

                }
                else echo "No se pudo mover el archivo";

            }
        }
        else
        {
            $this->request->data['Pregunta']['recurso']=$pregunta['Pregunta']['recurso'];
        }
        if($this->Pregunta->save($this->request->data['Pregunta']))
        {

            echo "success";
            $this->Pregunta->clear();
        }
        else{
            unlink($filename);
            $errors=array();
            foreach($this->Pregunta->validationErrors as $error)
            {
                $errors[]=$error;
            }
            echo  json_encode($errors);
        }



    }



} 