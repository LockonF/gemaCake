<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 9/1/14
 * Time: 09:13
 */

class Tema extends AppModel{
    public $useTable = 'temas';
    public $belongsTo = array('Materia'=>
        array(
            "className"=>"Materia",
            "foreignKey"=>"materia_id"
        ));
    public $hasMany = array('Pregunta'=>
        array(
            "className"=>'Pregunta',
            'foreignKey'=>'tema_id'
        )
    );

    public $hasAndBelongsToMany = array(
        'Competencia'=>array(
            'className'=>'Competencia',
            'joinTable'=>'temaCompetencia',
            'unique'=>'keepExisting',
            'with'=>'TemaCompetencia'
        )
    );
} 