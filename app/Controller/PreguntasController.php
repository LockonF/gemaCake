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
            $this->deleteFile($pregunta['Pregunta']['recurso'],$pregunta['Tema']['nombre']);
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
            $tema = $this->Pregunta->Tema->find('first',array('conditions'=>array('Tema.id'=>$this->request->data['Pregunta']['id_tema'])));
            $tema = $tema['Tema']['nombre'];
            $name= $this->request->data['Pregunta']['recurso']['name'];
            $tempName = $this->request->data['Pregunta']['recurso']['tmp_name'];

            if($this->createFile($name,$tempName,$tema) || $this->request->data['Pregunta']['recurso']=="")
            {
                if($this->request->data['Pregunta']['recurso']!="")
                {
                    $this->request->data['Pregunta']['recurso']=$tema.DS.$name;
                }
                else
                {
                    $this->request->data['Pregunta']['recurso']=null;
                }

                if($this->Pregunta->save($this->request->data['Pregunta']))
                {
                    echo "success";
                }
                else
                {
                    $errors=array();
                    foreach($this->Pregunta->validationErrors as $error)
                    {
                        $errors[]=$error;
                    }
                    echo  json_encode($errors);
                }
            }
            else echo "No se pudo crear archivo";
        }
    }

    public function executeMod()
    {
        $this->autoRender=false;
        $pregunta = $this->Pregunta->find('first',array('conditions'=>array('Pregunta.id'=>$this->request->data['Pregunta']['id'])));
        $tema = $pregunta['Tema']['nombre'];

        if($this->request->data['changeFile']=='true')
        {
            $this->deleteFile($pregunta['Pregunta']['recurso'],$pregunta['Tema']['nombre']);
            $name= $this->request->data['Pregunta']['recurso']['name'];
            $tempName = $this->request->data['Pregunta']['recurso']['tmp_name'];

            if($this->createFile($name,$tempName,$tema) || $this->request->data['Pregunta']['recurso']=="")
            {
                if($this->request->data['Pregunta']['recurso']!="")
                {
                    $this->request->data['Pregunta']['recurso']=$tema.DS.$name;
                }
                else
                {
                    $this->request->data['Pregunta']['recurso']=null;
                }
                if($this->Pregunta->save($this->request->data['Pregunta']))
                 echo "success";
             else
                {
                    $errors=array();
                    foreach($this->Pregunta->validationErrors as $error)
                    {
                        $errors[]=$error;
                    }
                    echo  json_encode($errors);
                }
            }
        }
        else
        {
            $this->request->data['Pregunta']['recurso']=$pregunta['Pregunta']['recurso'];
            if($this->Pregunta->save($this->request->data['Pregunta']))
                echo "success";
            else
            {
                $errors=array();
                foreach($this->Pregunta->validationErrors as $error)
                {
                    $errors[]=$error;
                }
                echo  json_encode($errors);
            }
        }

    }


    public function deleteFile($name,$tema)
    {
        try{
            if($name!='')
            {
                $filename = WWW_ROOT.'files'.DS.$name;
                unlink($filename);
                return true;
            }
            return false;
        }catch (Exception $e)
        {
            echo "No se pudo eliminar el archivo";
        }

    }

    public function createFile($name,$tempName,$tema)
    {
        try{
            if (!file_exists(WWW_ROOT.'files'.DS.$tema)) {
                mkdir(WWW_ROOT.'files'.DS.$tema, 0777, true);
            }
            $filename = WWW_ROOT.'files'.DS.$tema.DS.$name;
            if(!file_exists($filename))
            {
                /* Copiar Archivo*/
                if (move_uploaded_file($tempName,$filename)) {
                    return true;
                }
            }
            else{
                $md5Temp = md5_file($tempName);
                $md5Existing = md5_file($filename);
                if($md5Existing==$md5Temp)
                {
                    return true;
                }
            }
            return false;
        }catch (Exception $e){
            echo "No se pudo crear el archivo";
        }

    }


} 