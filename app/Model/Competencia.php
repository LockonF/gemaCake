<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 4/21/15
 * Time: 20:40
 */

class Competencia extends AppModel {
    public $useTable = 'competencias';
    public $name = 'Competencia';
    public $hasAndBelongsToMany = array(
        'Tema'=>array(
            'className'=>'Tema',
            'joinTable'=>'temaCompetencia',
            'unique'=>'keepExisting',
            'with'=>'TemaCompetencia'
        )
    );

} 