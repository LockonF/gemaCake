<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 9/7/14
 * Time: 20:59
 */

class Resultado extends AppModel{
    public $useTable = "examenResultado";
    public $belongsTo = array(
        'Evaluacion'=>
        array(
            "className"=>'Evaluacion',
            'foreignKey'=>'examen_id'
        ),
    );



} 