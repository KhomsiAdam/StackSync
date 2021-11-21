<?php

namespace App\Controller;

use App\Config\Middleware;
use App\Model\UserModel;

class UserApiController extends Middleware
{

    public function __construct()
    {
        parent::__construct();
    }

    //* 'validateToken()' specifies if the method requires token validation to run (must be in the first line of a controller method)
    //* 'validateParams()' data type argument defaults to STRING, modify to INTEGER, BOOLEAN or EMAIL if needed

    // User create
    public function create()
    {
        $user_id = Middleware::generateId();
        $email = $this->validateParams('email', $this->param['email'], EMAIL);
        $password = $this->validateParams('password', $this->param['password'], STRING);
        $model = new UserModel;
        $model->setUserId($user_id);
        $model->setEmail($email);
        $model->setPassword(Middleware::hash($password));
        $this->result = $model->create('user');
        if ($this->result == false) {
            $message = 'User already exists.';
        } else {
            $message = "User created successfully.";
        }
        Middleware::returnResponse(RESPONSE_MESSAGE, $message, $this->result);
    }

    // User read all
    public function readAll()
    {
        $this->validateToken();
        $model = new UserModel;
        $this->result = $model->readAll('user');
        if ($this->result == false) {
            $message = 'Failed to fetch all Users.';
        } else {
            $message = "All Users fetched successfully.";
        }
        Middleware::returnResponse(RESPONSE_MESSAGE, $message, $this->result);
    }

    // User read unique
    public function readUnique()
    {
        $this->validateToken();
        $user_id = $this->validateParams('user_id', $this->param['user_id'], STRING);
        $model = new UserModel;
        $model->setUserId($user_id);
        $this->result = $model->readUnique('user');
        if ($this->result == false) {
            $message = 'Failed to fetch unique User.';
        } else {
            $message = 'Unique User fetched successfully.';
        }
        Middleware::returnResponse(RESPONSE_MESSAGE, $message, $this->result);
    }

    // User update
    public function update()
    {
        $this->validateToken();
        $user_id = $this->validateParams('user_id', $this->param['user_id'], STRING);
        $email = $this->validateParams('email', $this->param['email'], EMAIL);
        $password = $this->validateParams('password', $this->param['password'], STRING);
        $model = new UserModel;
        $model->setUserId($user_id);
        $model->setEmail($email);
        $model->setPassword(Middleware::hash($password));
        $this->result = $model->update('user');
        if ($this->result == false) {
            $message = 'User does not exist.';
        } else {
            $message = "User informations updated successfully.";
        }
        Middleware::returnResponse(RESPONSE_MESSAGE, $message, $this->result);
    }

    // User Delete
    public function delete()
    {
        $this->validateToken();
        $user_id = $this->validateParams('user_id', $this->param['user_id'], STRING);
        $model = new UserModel;
        $model->setUserId($user_id);
        $this->result = $model->delete('user');
        if ($this->result == false) {
            $message = 'User does not exist.';
        } else {
            $message = "User deleted successfully.";
        }
        Middleware::returnResponse(RESPONSE_MESSAGE, $message, $this->result);
    }
}
