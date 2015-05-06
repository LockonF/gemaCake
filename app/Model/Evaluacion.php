<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 9/7/14
 * Time: 19:54
 */

class Evaluacion extends AppModel {

    public $useTable = "examenes";

    public $belongsTo = array('User'=>
        array(
            "className"=>'User',
            'foreignKey'=>'user_id'
        )
    );

    public $hasMany = array(
      'Resultado'=>array(
          'className'=>'Resultado',
          'foreignKey'=>'examen_id'
      ),
        'Prototipo'=>array(
          'className'=>'Prototipo',
          'foreignKey'=>'examen_id'
      )

    );

    public function finishEvaluacion($id,$puntaje)
    {
        $db = $this->getDataSource();
        $query = "UPDATE examenes
                  SET puntaje =".$db->value($puntaje,'int').",
                  finished = 1
                  WHERE ID=".$db->value($id, 'int');
        if($this->query($query))
        {
            return true;
        }
        return false;

    }




}