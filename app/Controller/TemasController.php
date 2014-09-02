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
        $temas = $this->Tema->find('all',array('conditions'=>array('Tema.nombre LIKE'=>"%".$this->request->data['fieldBusqueda']."%")));
        $this->set('temas', $temas);
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

    public function modificar()
    {
        $this->set('materias',$this->Tema->Materia->find('all'));
    }

} 