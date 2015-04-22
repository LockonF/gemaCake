<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 8/31/14
 * Time: 21:51
 */

class Materia extends AppModel {
    public $useTable = 'materias';
    public $name = "Materia";

    public $validate = array(
        'nombre'=>array(
                'unique'=>array(
                    'rule'=>array('isunique'),
                    'message'=>'Materia ya existente'
                )
        )

    );

    public $hasMany = array(
        "Tema"=>array(
            'className'=>'Tema',
            'foreignKey'=>'materia_id'
        )
    );

} 