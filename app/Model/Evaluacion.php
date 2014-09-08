<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 9/7/14
 * Time: 19:54
 */

class Evaluacion extends AppModel {

    public $useTable = "evaluacioens";

    public $hasOne = array('User'=>
        array(
            "className"=>'User',
            'foreignKey'=>'user_id'
        )
    );}