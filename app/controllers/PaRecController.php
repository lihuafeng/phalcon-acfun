<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class PaRecController extends AdminController
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
     * Searches for pa_rec
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "PaRec", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $pa_rec = PaRec::find($parameters);
        if (count($pa_rec) == 0) {
            $this->flash->notice("The search did not find any pa_rec");

            return $this->dispatcher->forward(array(
                "controller" => "pa_rec",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $pa_rec,
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
     * Edits a pa_rec
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $pa_rec = PaRec::findFirstByid($id);
            if (!$pa_rec) {
                $this->flash->error("pa_rec was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "pa_rec",
                    "action" => "index"
                ));
            }

            $this->view->id = $pa_rec->id;

            $this->tag->setDefault("id", $pa_rec->id);
            $this->tag->setDefault("url", $pa_rec->url);
            $this->tag->setDefault("aid", $pa_rec->aid);
            $this->tag->setDefault("position", $pa_rec->position);
            $this->tag->setDefault("description", $pa_rec->description);
            $this->tag->setDefault("status", $pa_rec->status);
            
        }
    }

    /**
     * Creates a new pa_rec
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "pa_rec",
                "action" => "index"
            ));
        }

        $pa_rec = new PaRec();

        $pa_rec->url = $this->request->getPost("url");
        $pa_rec->aid = $this->request->getPost("aid");
        $pa_rec->position = $this->request->getPost("position");
        $pa_rec->description = $this->request->getPost("description");
        $pa_rec->status = $this->request->getPost("status");
        

        if (!$pa_rec->save()) {
            foreach ($pa_rec->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "pa_rec",
                "action" => "new"
            ));
        }

        $this->flash->success("pa_rec was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "pa_rec",
            "action" => "index"
        ));

    }

    /**
     * Saves a pa_rec edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "pa_rec",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $pa_rec = PaRec::findFirstByid($id);
        if (!$pa_rec) {
            $this->flash->error("pa_rec does not exist " . $id);

            return $this->dispatcher->forward(array(
                "controller" => "pa_rec",
                "action" => "index"
            ));
        }

        $pa_rec->url = $this->request->getPost("url");
        $pa_rec->aid = $this->request->getPost("aid");
        $pa_rec->position = $this->request->getPost("position");
        $pa_rec->description = $this->request->getPost("description");
        $pa_rec->status = $this->request->getPost("status");
        

        if (!$pa_rec->save()) {

            foreach ($pa_rec->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "pa_rec",
                "action" => "edit",
                "params" => array($pa_rec->id)
            ));
        }

        $this->flash->success("pa_rec was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "pa_rec",
            "action" => "index"
        ));

    }

    /**
     * Deletes a pa_rec
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $pa_rec = PaRec::findFirstByid($id);
        if (!$pa_rec) {
            $this->flash->error("pa_rec was not found");

            return $this->dispatcher->forward(array(
                "controller" => "pa_rec",
                "action" => "index"
            ));
        }

        if (!$pa_rec->delete()) {

            foreach ($pa_rec->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "pa_rec",
                "action" => "search"
            ));
        }

        $this->flash->success("pa_rec was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "pa_rec",
            "action" => "index"
        ));
    }

}
