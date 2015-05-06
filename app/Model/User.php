<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 8/29/14
 * Time: 21:12
 */
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
App::uses('AppModel', 'Model');
App::uses('Auth', 'Controller/Component');



class User extends AppModel {

    public $components = array(
        'Auth' => array(
            'authenticate' =>
                array(
                    'Form' => array(
                        'passwordHasher'=>'Blowfish',
                        'userModel' => 'User',
                        'fields' => array(
                            'username' => 'username',
                            'password' => 'password'
                        )
                    ))

        )
    );

    /*****Validaciones******/

    public $hasOne = array(
        'Profile'=>array(
            'foreignKey'=>'user_id'
        )
    );

    public $belongsTo = array(
        'Role'=>array(
            'className'=>'Role',
            'foreignKey'=>'role_id'
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

    public function beforeSave($options = array())
    {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }
        return true;
    }

} 