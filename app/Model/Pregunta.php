<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 9/1/14
 * Time: 08:56
 */



class Pregunta extends AppModel{
    public $name = 'Pregunta';
    public $findMethods = array('formattedArray' =>  true);


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

    public function _findFormattedArray ($state, $query, $results)
    {
        if ($state === 'before') {
            return $query;
        }
        array_walk($results, function(&$value, $key){
            $value=$value['Pregunta'];
            $value['respuestas'] = array($value['opc1'],$value['opc2'],$value['opc3'],$value['opc4']);
            $value['pregunta'] = utf8_decode($value['oracion']);
            unset($value['opc1'],$value['opc2'],$value['opc3'],$value['opc4']);

            unset($value['tema_id'],$value['dificultad'],$value['opcc'],$value['competencia_id'],$value['just'],$value['oracion']);

        });

        return $results;
    }

    public function evaluate($id,$selected)
    {
        $correct = $this->find('first',array(
            'conditions'=>array('Pregunta.id'=>$id),
            'fields'=>'opcc'
        ));
        if(intval($correct['Pregunta']['opcc']) == $selected)
            return true;
        return false;
    }

} 