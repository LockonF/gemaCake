<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 9/7/14
 * Time: 19:54
 */

class Evaluacion extends AppModel {

    public $useTable = "evaluaciones";

    public $belongsTo = array('User'=>
        array(
            "className"=>'User',
            'foreignKey'=>'user_id'
        )
    );
    public $hasMany = array('Resultado'=>
        array(
            "className"=>'Resultado',
            'foreignKey'=>'examen_id'
        )
    );



}