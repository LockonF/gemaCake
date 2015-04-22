<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 9/1/14
 * Time: 08:56
 */



class Pregunta extends AppModel{
    public $name = 'Pregunta';


    public $useTable = 'preguntas';

    public $belongsTo = array(
        'Tema'=>array(
            'classname'=>'Tema',
            'foreignKey'=>'tema_id'
        )
    );

    public $hasOne = array(
        'PreguntasControl'=>array(
            'className'=>'PreguntasControl',
            'foreignKey'=>'pregunta_id'
        )
    );


    public function isUploadedFile($params) {
        $val = array_shift($params);
        if ((isset($val['error']) && $val['error'] == 0) ||
            (!empty( $val['tmp_name']) && $val['tmp_name'] != 'none')
        ) {
            return is_uploaded_file($val['tmp_name']);
        }
        return false;
    }
} 