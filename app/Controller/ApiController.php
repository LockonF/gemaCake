<?php
/**
 * Created by PhpStorm.
 * User: LockonDaniel
 * Date: 4/14/15
 * Time: 20:17
 */

class ApiController extends AppController{

        public $components = array(
            'RequestHandler',
            "OAuth.OAuth",
            'Rest.Rest' => array(
                'catchredir' => true,
                'debug'=>2,
                'actions' => array(
                    'createUser' => array(
                        'extract' => array('data'),
                    ),
                    'createOAuthClient' => array(
                        'extract' => array('data'),
                    ),
                    'getPreguntas' => array(
                        'extract' => array('data'),
                    )
                ),
                'log' => array(
                    'pretty' => true,
                ),
                'ratelimit' => array(
                    'enable' => false
                ),
            )
        );


    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
        $this->OAuth->allow(array('createOAuthClient','genCode','demoGetToken','login'));

    }

    public function createUser()
    {
        if($this->request->is('post'))
        {
            $this->loadModel('User');
            $this->User->create();
            if($this->User->saveAll($this->request->data,array("validate"=>true)))
            {
                $this->set('data','Success');
            }
            else{
                $this->set('data',$this->User->validationErrors);
            }
        }
    }

    public function getPreguntas()
    {
        if($this->request->is('get'))
        {
            $this->loadModel('Preguntas');
            $preguntas = $this->Preguntas->find('all');
            $this->set('data',$preguntas);
        }
    }

    /**
     *OAuth Related Functions: Create
     */
    public function createOAuthClient()
    {
        if($this->request->is("get"))
        {
            if(trim($this->request->query["uri"]==""))
            {
                $redirectURI = "/cakephp/api/genCode";
            }
            else{
                $redirectURI = $this->request->query["uri"];
            }
            $client = $this->OAuth->Client->add($redirectURI);
            $client['Client']['uri'] = "/oauth/authorize?response_type=code&client_id=".$client['Client']['client_id']."&redirect_url=/cakephp/users/demoCode.json";
            $this->set('data',$client['Client']);
            $this->Session->write(array("user"=>
                array("id"=>$client['Client']['client_id'],
                    "secret"=>$client['Client']['client_secret']
                )));
            $redirectURI="";
        }

    }

    public function genCode()
    {
        $this->autoRender=false;
        echo "Please visit this url: /oauth/token?grant_type=authorization_code&code=".$this->request->query['code']."&client_id=xxxx&client_secret=xxxx";
        $this->set("code",$this->request->query['code']);

        //$client_id =$this->Session->read("user.id");
        //$client_secret = $this->Session->read("user.secret");
        //$this->redirect("/oauth/token?grant_type=authorization_code&code=".$this->request->query['code']."from_above&client_id=".$client_id."&client_secret=".$client_secret);


    }

    public function demoGetToken()
    {
        $this->autoRender=false;
    }


    public function login()
    {
        $this->loadModel('User');
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
            if ($this->Auth->login()) {
                $query = $this->Session->read("Query");
                $this->Session->delete("Query");
                return $this->redirect(array('plugin'=>'oauth','controller'=>'','action'=>'authorize',"?"=>$query));
            }
            else
            {
                $this->set("errors","Usuario o contraseña incorrectos");
            }
        }

    }

}