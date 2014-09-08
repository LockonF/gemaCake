<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 9/1/14
 * Time: 09:20
 */

class TemasController extends AppController{




    public function resultado()
    {

        $this->set("temas", $this->Tema->find('all',array('conditions'=>array('Tema.nombre LIKE'=>"%".$this->request->data['fieldBusqueda']."%"))));
    }

    public function ver()
    {
        $this->set('temas', $this->Tema->find('all'));
    }


    public function eliminar()
    {
        $this->Tema->delete($this->request->data['id']);
        echo 'success';
    }

    public function executeMod()
    {
        $this->autoRender=false;
        if($this->Tema->save($this->request->data['Tema']))
        {
            echo "success";
        }
        else
        {
            $errors=array();
            foreach($this->User->validationErrors as $error)
            {
                $errors[]=$error;
            }
            echo  json_encode($errors);
        }
    }

    public function modificar()
    {
        if(isset($this->request->data['id']))
        {
            $this->set(  $this->Tema->find('first', array(
                'conditions' => array('Tema.id' => $this->request->data['id']))));
        }
        $this->set('materias',$this->Tema->Materia->find('all'));
    }

    public function createTema()
    {
        if($this->Tema->save($this->request->data['Tema']))
        {
            echo "success";
        }
        else
        {
            $errors=array();
            foreach($this->User->validationErrors as $error)
            {
                $errors[]=$error;
            }
            echo  json_encode($errors);
        }
    }

} 