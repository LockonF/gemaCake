<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 8/29/14
 * Time: 21:23
 */
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class UsersController extends AppController{


    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }


    public function login() {
        $this->layout='layout-main';
        if ($this->request->is('post')) {
            $this->request->data['User']=$this->User->create();
            $this->request->data['User']['username']=$this->request->data['username'];
            unset($this->request->data['username']);
            $this->request->data['User']['password']=$this->request->data['password'];
            unset($this->request->data['password']);

            if ($this->Auth->login()) {
                return $this->redirect($this->Auth->redirect());
            }
            $this->redirect(array('controller'=>'pages','action'=>'display'));
        }
    }

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }











    //Para renderear la vista de modificar

    public function modificar()
    {
        $user=$this->User->find('first',array('contitions'=>array('User.id'=>$this->request->data['id'])));
        $this->set($user);
     }

    //Para renderear la vista de eliminar

    public function eliminar()
    {
        $this->User->delete($this->request->data['id']);
        echo 'success';
    }


    //Para ejecutar la modificación


    public function executeMod()
    {
        $this->autoRender=false;


        $user = $this->User->find('first',array('conditions'=>array('User.username'=>$this->request->data['fieldUsuario'])));
        $userData = array('id'=>$user['User']['id'],'username'=>$this->request->data['fieldUsuario'],'password'=>$this->request->data['fieldPassword'],
            'password-confirm'=>$this->request->data['fieldPasswordConfirm'],'email'=>$this->request->data['fieldEmail'],
            'role_id'=>$this->request->data['fieldRol']);
        $this->User->create();
        if($this->User->save($userData))
        {

            $profile=$this->User->Profile->find('first',array('conditions'=>array('Profile.user_id'=>$this->User->id)));
            $profileData=array('id'=>$profile['Profile']['id'],'user_id'=>$this->User->id,'nombre'=>$this->request->data['fieldNombre'],'apaterno'=>$this->request->data['fieldPaterno'],
                'amaterno'=>$this->request->data['fieldMaterno']);
            $this->User->Profile->create();
            $this->User->Profile->save($profileData);
            echo "success";
            $this->User->clear();
        }
        else{
            $errors=array();
            foreach($this->User->validationErrors as $error)
            {
                $errors[]=$error;
            }
            echo  json_encode($errors);
        }
        $this->User->clear();

    }


    //Resultado de la busqueda

    public function resultado()
    {
        $this->set("usuarios",null);
        $users = $this->User->find('all',array('conditions'=>array('User.username LIKE'=>"%".$this->request->data['fieldBusqueda']."%")));
        $this->set('usuarios', $users);
    }

    //Ver los usuarios

    public function ver()
    {
        $this->set('usuarios', $this->User->find('all'));
    }

    public function createUser()
    {
        $this->autoRender = false;
        if($this->request->is('post'))
        {
            $userData = array('username'=>$this->request->data['fieldUsuario'],'password'=>$this->request->data['fieldPassword'],
            'password-confirm'=>$this->request->data['fieldPasswordConfirm'],'email'=>$this->request->data['fieldEmail'],
            'role_id'=>$this->request->data['fieldRol']);
             $this->User->create();
            if($this->User->save($userData))
            {

                $profile=array('user_id'=>$this->User->id,'nombre'=>$this->request->data['fieldNombre'],'apaterno'=>$this->request->data['fieldPaterno'],
                    'amaterno'=>$this->request->data['fieldMaterno']);
                $this->User->Profile->save($profile);
                echo "success";
                $this->User->clear();
            }
            else{
                $errors=array();
                foreach($this->User->validationErrors as $error)
                {
                    $errors[]=$error;
                }
                echo  json_encode($errors);
            }
        }
    }

} 