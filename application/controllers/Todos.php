<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Todos extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->methods['todos_get']['limit'] = 50;
        $this->methods['todos_post']['limit'] = 50;
        $this->methods['todos_delete']['limit'] = 50;
    }

    public function index_get()
    {
        $todo = $this->db->get('todo')->result();
        $id = $this->get('id');

        if ($id === NULL)
        {
            if ($todo)
            {
                $this->response($todo, REST_Controller::HTTP_OK);
            }
            else
            {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No users were found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        } else {
            $id = (int) $id;

            if ($id <= 0)
            {
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }

            $todos = $this->todo_model->getData($id);

            if (!empty($todos))
            {   
                $this->set_response($todos, REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'User could not be found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }
    }

    public function index_post()
    {
        if(empty($this->post('title')) && empty($this->post('description')))
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'No Data For Insert'
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            $data = array(
                'title' => $this->post('title'),
                'description' => $this->post('description'),
                );
    
            $insert = $this->todo_model->insert($data);
    
            $message = [
                'id' => $insert,
                'title' => $this->post('title'),
                'description' => $this->post('description'),
                'message' => 'Added a todo'
            ];
    
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }
       
    }

    public function index_put()
    {

        if(empty($this->put('id')) && empty($this->put('title')) && empty($this->put('description')))
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'No Data For Update'
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            $data = array(
                'id' => $this->put('id'),
                'title' => $this->put('title'),
                'description' => $this->put('description'),
                );
    
            $update = $this->todo_model->update($data);
    
            $message = [
                'id' => $this->put('id'),
                'title' => $this->put('title'),
                'description' => $this->put('description'),
                'message' => 'Updated a todo'
            ];
    
            $this->set_response($message, REST_Controller::HTTP_CREATED);
        }
       
    }

    public function index_delete()
    {  
        $id = $this->delete('id');

        $delete = $this->todo_model->delete($id);

        if($delete){
            $message = [
                'id' => $id,
                'message' => 'Deleted the resource'
            ];
    
            $this->set_response($message, REST_Controller::HTTP_NO_CONTENT);    
        }
        
    }

}
