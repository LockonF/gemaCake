<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 8/31/14
 * Time: 21:24
 */

class MateriasController extends AppController {



    public function resultado()
    {
        $materias = $this->Materia->find('all',array('conditions'=>array('Materia.nombre LIKE'=>"%".$this->request->data['fieldBusqueda']."%")));
        $this->set('materias', $materias);
    }



    //Solo para la vista de modiicar

    public function modificar()
    {
        $materia = $this->Materia->find("first",array("conditions"=>array("Materia.id"=>$this->request->data['id'])));
        $this->set($materia);
    }



    public function executeMod()
    {
        $this->autoRender=false;
       if($this->Materia->save($this->request->data['Materia']))
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


    public function createMateria()
    {
        $this->autoRender=false;
        if($this->request->is('post'))
        {
            if($this->Materia->save($this->request->data['Materia']))
            {
                echo "success";
                $this->Materia->clear();

            }
        }
        else{
            $errors=array();
            foreach($this->User->validationErrors as $error)
            {
                $errors[]=$error;
            }
            echo  json_encode($errors);
        }
    }


    public function ver()
    {
        $materias = $this->Materia->find('all');
        $this->set('materias', $materias);
    }

    public function eliminar()
    {
        $this->autoRender=false;
        $this->Materia->delete($this->request->data['id']);
        echo 'success';
    }

}