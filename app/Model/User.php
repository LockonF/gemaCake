<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 8/29/14
 * Time: 21:12
 */
App::uses('AppModel', 'Model');


class User extends AppModel {

    public $hasOne = array(
        'Profile'=>array(
            'foreignKey'=>'user_id'
        )
    );

    public $validate = array(
        'username'=>array(
            'unique'=>array(
                'rule'=>array('isUnique'),
                'message'=>'Usuario Ya existente'
            ),
            'maxLength'=>array(
                'rule'=>array('maxlength',18),
                'message'=>'El usuario debe tener maximo 18 caracteres'
            ),
            'alphanum'=>array(
                'rule'=>array('alphaNumeric'),
                'message'=>'Solo se admiten caracteres alfanumericos'
            ),
        ),
        'password'=>array(
               'required'=>array(
                'rule'=>array('notEmpty'),
                'message'=>'Se requiere una contraseña'
            )

        ),
        'password-confirm'=>array(
            'required'=>array(
                'rule'=>array('matchPassword'),
                'message'=>'Las contrase&ntilde;as deben ser iguales'
            )
        ),
        'role'=>array(
            'required'=>array(
                'rule'=>array('inList',array('1','2','3')),
                'message'=>'Se requiere un rol válido',
                'allowEmpty'=>'false'
            )
        ),
        'email'=>array(
            'emailRule'=>array(
                'rule'=>array('email',true),
                'message'=>'El campo tiene que ser un email'
            )
        )
    );


    public function matchPassword($check)
    {

        if($this->data['User']['password'] == $check['password-confirm'])
        {
            return TRUE;
        }
        else return FALSE;

    }
} 