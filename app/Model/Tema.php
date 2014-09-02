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
            "foreignKey"=>"id_materia"
        ));

} 