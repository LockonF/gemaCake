<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 4/17/15
 * Time: 19:27
 */

class PreguntasControl extends AppModel{

    public $useTable = 'preguntasControl';
    public $name = 'PreguntasControl';

    public $belongsTo = array(
        'Pregunta'=>array(
            'className'=>"Pregunta",
            'foreignKey'=>'pregunta_id'
        )
    );

    /**
     * @param $id
     */
    public function increaseBuenas($id)
    {
        $db = $this->getDataSource();
        $query = "UPDATE preguntasControl
                  SET preguntasControl.noBuenas = preguntasControl.noBuenas+1,
                      preguntasControl.noPreguntadas = preguntasControl.noPreguntadas+1
                  WHERE ID=".$db->value($id, 'int');
        if($this->query($query))
        {
            return true;
        }
        return false;

    }

    public function increaseMalas($id)
    {
        $db = $this->getDataSource();
        $query = "UPDATE preguntasControl
                  SET preguntasControl.noMalas = preguntasControl.noMalas +1,
                      preguntasControl.noPreguntadas=preguntasControl.noPreguntadas+1
                  WHERE ID=".$db->value($id, 'int');
        if($this->query($query))
        {
            return true;
        }
        return false;

    }



} 