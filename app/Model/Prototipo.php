<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 4/19/15
 * Time: 20:12
 */

class Prototipo extends AppModel {
    public $useTable = "examenPrototipo";
    public $name = "Prototipo";

    public $belongsTo = array(
        'TemaCompetencia'=>array(
            'className'=>'TemaCompetencia',
            'foreignKey'=>'tema_competencia_id'
        )

    );

} 