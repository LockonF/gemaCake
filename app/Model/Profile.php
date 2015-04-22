<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 8/30/14
 * Time: 20:24
 */

class Profile extends AppModel {
    public $belongsTo = array(
        'User'=>array(
            'className'=>'User',
            'foreignKey'=>'user_id'
        )
    );


} 