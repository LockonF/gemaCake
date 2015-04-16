<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 8/29/14
 * Time: 21:23
 */
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class UsersController extends AppController{


    public $components = array(
        'RequestHandler',
        "OAuth.OAuth",
        'Rest.Rest' => array(
            'catchredir' => true,
            'debug'=>2,
            'actions' => array(
                'createOAuthClient' => array(
                    'extract' => array('client'),
                )
            ),
            'log' => array(
                'pretty' => true,
            ),
            'ratelimit' => array(
                'enable' => false
            ),
        ),

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

        ),
        'Session','DebugKit.Toolbar','RequestHandler'
    );




    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
        $this->OAuth->allow();

    }








    /**
     * Login
     */

    public function login() {
        $this->layout='layout-main';
        if ($this->request->is('get'))
        {
            if(isset($this->request->query))
            {
                $this->Session->write('Query',$this->request->query);
            }
            else{
                $this->Session->delete("Query");
            }
        }
        if ($this->request->is('post')) {
            $this->request->data['User']=$this->User->create();
            $this->request->data['User']['username']=$this->request->data['username'];
            unset($this->request->data['username']);
            $this->request->data['User']['password']=$this->request->data['password'];
            unset($this->request->data['password']);


            if ($this->Auth->login()) {
                $query = $this->Session->read("Query");
                if(isset($query) && count($query)!=0)
                {
                    $this->Session->delete("Query");
                    return $this->redirect(array('plugin'=>'oauth','controller'=>'','action'=>'authorize',"?"=>$query));
                }
                return $this->redirect($this->Auth->redirect());
            }

            $this->redirect(array('controller'=>'pages','action'=>'display'));
        }
    }


    public function redirectAction()
    {
        $this->autoRender=false;

        switch($this->Auth->user('role_id'))
        {
            case "1":
                return $this->redirect(
                    array('controller' => 'administrators', 'action' => 'index')
                );
            case "2":
                return $this->redirect(
                    array('controller' => 'profesores', 'action' => 'index')
                );
            case "3":
                return $this->redirect(
                    array('controller' => 'evaluaciones', 'action' => 'index')
                );
        }
    }



    public function logout() {
        return $this->redirect($this->Auth->logout());
    }











    //Para renderear la vista de modificar

    public function modificar()
    {
        $this->set("users",null);
        $user = $this->User->find('first', array(
            'conditions' => array('User.id' => $this->request->data['id'])));
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
        $userData = array('id'=>$this->request->data['fieldId'],'username'=>$this->request->data['fieldUsuario'],'password'=>$this->request->data['fieldPassword'],
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
        $users = $this->User->find('all');
        $this->set('usuarios', $users);
    }




    /*
     * Crear Usuario
     */
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