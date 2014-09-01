<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 8/31/14
 * Time: 21:24
 */

class MateriasController extends AppController {


    public function modificar()
    {
        $materia=$this->Materia->find('first',array('contitions'=>array('Materia.id'=>$this->request->data['id'])));
        $this->set($materia);
    }

    public function createMateria()
    {
        $this->autoRender=false;
        if($this->request->is('post'))
        {
            $materiaData = array("nombre"=>$this->request->data['fieldNombre'],
                'numpreguntas'=>$this->request->data['fieldNumPreguntas']);
            $this->Materia->create();
            if($this->Materia->save($materiaData))
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
        $this->autoRender=$false;
        $this->Materia->delete($this->request->data['id']);
        echo 'success';
    }

}