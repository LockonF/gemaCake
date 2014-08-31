<?


class RolesController extends AppController{


    public function add()
    {

    }

    public function ver()
    {
        $this->set('roles', $this->Role->find('all'));

    }

    public function eliminar()
    {
        $this->Role->delete($this->request->data['id']);
        echo 'success';
    }

    public function createRole()
    {
        $this->autoRender = false;
        if($this->request->is('post'))
        {
            $roleData = array('name'=>$this->request->data['fieldNombre']);
            $this->Role->create();
            if($this->Role->save($roleData))
            {
                echo "success";
                $this->Role->clear();
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
