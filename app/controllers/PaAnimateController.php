<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class PaAnimateController extends AdminController
{
    public function initialize()
    {
        $this->view->setTemplateAfter('admin');
        parent::initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for pa_animate
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "PaAnimate", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $pa_animate = PaAnimate::find($parameters);
        if (count($pa_animate) == 0) {
            $this->flash->notice("The search did not find any pa_animate");

            return $this->dispatcher->forward(array(
                "controller" => "pa_animate",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $pa_animate,
            "limit"=> 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a pa_animate
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $pa_animate = PaAnimate::findFirstByid($id);
            if (!$pa_animate) {
                $this->flash->error("pa_animate was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "pa_animate",
                    "action" => "index"
                ));
            }

            $this->view->id = $pa_animate->id;

            $this->tag->setDefault("id", $pa_animate->id);
            $this->tag->setDefault("aid", $pa_animate->aid);
            $this->tag->setDefault("name", $pa_animate->name);
            $this->tag->setDefault("description", $pa_animate->description);
            $this->tag->setDefault("content", $pa_animate->content);
            $this->tag->setDefault("pid", $pa_animate->pid);
            $this->tag->setDefault("vid", $pa_animate->vid);
            $this->tag->setDefault("created_at", $pa_animate->created_at);
            $this->tag->setDefault("published_at", $pa_animate->published_at);
            
        }
    }

    /**
     * Creates a new pa_animate
     */
    public function createAction()
    {
        
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "pa_animate",
                "action" => "index"
            ));
        }

        $pa_animate = new PaAnimate();

        $pa_animate->aid = $this->request->getPost("aid");
        $pa_animate->name = $this->request->getPost("name");
        $pa_animate->description = $this->request->getPost("description");
        $pa_animate->content = $this->request->getPost("content");
        $pa_animate->pid = $this->request->getPost("pid");
        $pa_animate->vid = $this->request->getPost("vid");
//        $pa_animate->created_at = $this->request->getPost("created_at");
        $pa_animate->published_at = date('Y-m-d H:i:s', strtotime($this->request->getPost("published_at")));
        

        if (!$pa_animate->save()) {
            foreach ($pa_animate->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "pa_animate",
                "action" => "new"
            ));
        }

        $this->flash->success("pa_animate was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "pa_animate",
            "action" => "index"
        ));

    }

    /**
     * Saves a pa_animate edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "pa_animate",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $pa_animate = PaAnimate::findFirstByid($id);
        if (!$pa_animate) {
            $this->flash->error("pa_animate does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "pa_animate",
                "action" => "index"
            ));
        }

        $pa_animate->aid = $this->request->getPost("aid");
        $pa_animate->name = $this->request->getPost("name");
        $pa_animate->description = $this->request->getPost("description");
        $pa_animate->content = $this->request->getPost("content");
        $pa_animate->pid = $this->request->getPost("pid");
        $pa_animate->vid = $this->request->getPost("vid");
        $pa_animate->created_at = date('Y-m-d H:i:s', strtotime($this->request->getPost("created_at")));
        $pa_animate->published_at = date('Y-m-d H:i:s', strtotime($this->request->getPost("published_at")));
        

        if (!$pa_animate->save()) {

            foreach ($pa_animate->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "pa_animate",
                "action" => "edit",
                "params" => array($pa_animate->id)
            ));
        }

        $this->flash->success("pa_animate was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "pa_animate",
            "action" => "index"
        ));

    }

    /**
     * Deletes a pa_animate
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $pa_animate = PaAnimate::findFirstByid($id);
        if (!$pa_animate) {
            $this->flash->error("pa_animate was not found");

            return $this->dispatcher->forward(array(
                "controller" => "pa_animate",
                "action" => "index"
            ));
        }

        if (!$pa_animate->delete()) {

            foreach ($pa_animate->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "pa_animate",
                "action" => "search"
            ));
        }

        $this->flash->success("pa_animate was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "pa_animate",
            "action" => "index"
        ));
    }

}
