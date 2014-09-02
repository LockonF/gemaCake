<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 8/31/14
 * Time: 21:51
 */

class Materia extends AppModel {
    public $useTable = 'materias';

    public $validate = array(
        'nombre'=>array(
                'unique'=>array(
                    'rule'=>array('isunique'),
                    'message'=>'Materia ya existente'
                )
        ),
        'numPreguntas'=>array(
                'numerico'=>array(
                    'rule'=>array('custom','/^0|[1-9]\d*$/'),
                    'message'=>'Solo se acepta numeros'
                )
        )
    );

    public $hasmany = array(
        'Tema'=>array(
            'className'=>'Tema',
        )
    );

} 