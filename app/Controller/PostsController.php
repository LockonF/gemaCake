<?PHP

class PostsController extends AppController {




    public function ajaxExample()
    {
        if($this->request->is("ajax"))
        {
            echo "Yay";
            $this->Post->save($this->request->data);

        }

    }


    public function index() {
        $this->layout="layout";
        $this->autorender = false;

        $this->set('posts', $this->Post->find('all'));


    }

    public function view($id) {
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }

        $post = $this->Post->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
        $this->set('post', $post);
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->Post->create();
            if ($this->Post->save($this->request->data)) {
                $this->Session->setFlash(__('Your post has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Unable to add your post.'));
        }
    }
}