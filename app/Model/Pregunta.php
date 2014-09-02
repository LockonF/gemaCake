<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 9/1/14
 * Time: 08:56
 */

namespace Datasource;


class Pregunta extends AppModel{
    public $belongsTo = array(
        'Tema'=>array(
            'classname'=>'Tema',
            'foreignKey'=>'id_tema'
        )
    );
} 