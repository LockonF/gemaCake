<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 9/7/14
 * Time: 20:59
 */

class Resultado extends AppModel{
    public $useTable = "resultados";
    public $belongsTo = array('Evaluacion'=>
        array(
            "className"=>'Evaluacion',
            'foreignKey'=>'examen_id'
        )
    );
    public $hasOne = array("Tema"=>array(
        "className"=>'Tema',
        'foreignKey'=>"tema_id"
    ));

} 