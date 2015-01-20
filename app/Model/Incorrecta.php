<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 9/10/14
 * Time: 18:40
 */

class Incorrecta extends AppModel{

    public $useTable = 'incorrectas';

    public $belongsTo =
        array(
            'Resultado'=>
                array(
                    'className'=>'Resultado',
                    'foreignKey'=>'resultado_id'
                    ),
            'Pregunta'=>array(
                    'className'=>'Pregunta',
                    'foreginKey'=>'pregunta_id'
                    )
    )

    ;



} 