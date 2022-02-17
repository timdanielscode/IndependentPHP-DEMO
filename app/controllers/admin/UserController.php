<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use app\models\User;
use app\models\UserRole;
use app\models\Roles;
use parts\Session;
use database\DB;
use core\CSRF;
use validation\Rules;
use parts\Response;


class UserController extends Controller {

    public function index() {

        $user = new User();
        $all = DB::try()->all($user->t)->fetch();
        $data['all'] = $all;

        return $this->view('admin/users/index', $data);
    }

    public function create() {

        return $this->view('admin/users/create');

    }

    public function read($id) {

        $user = new User();

        $current = DB::try()->all($user->t)->where($user->id, 'LIKE BINARY', $id['id'])->fetch();
        
        if(empty($current)) {
            return Response::statusCode(404)->view("/404/404");
        } else {
            $data['current'] = $current;
            return $this->view('admin/users/show', $data);
        }

    }

    public function edit($id) {
       
        $user = new User();
        $user_role = new UserRole();
        $role = new Roles();

        $user = DB::try()->select($user->t.'.*', $role->t.'.'.$role->name)->from($user->t)->join($user_role->t)->on($user->t.'.'.$user->id, '=', $user_role->t.'.'.$user_role->user_id)->join($role->t)->on($user_role->t.'.'.$user_role->role_id, '=', $role->t.'.'.$role->id)->where($user->t.'.'.$user->id, '=', $id["id"])->first();
       
        $data['user'] = $user;
        $data['rules'] = [];

        return $this->view('admin/users/edit', $data);
    }

    public function update($request) {

        $id = $request["id"];

        if(submitted('submit')) {

            if(CSRF::validate(CSRF::token('get'), post('token'))) {
                
                $rules = new Rules();
                $user = new User();
                $user_role = new UserRole();
                $role = new Roles();

                $id = $request["id"];
                $username = $request["username"];
                $email = $request["email"];

                if($rules->user_edit()->validated()) {
                
                    DB::try()->update($user->t)->set([
                        $user->username => $username,
                        $user->email => $email,
                    ])->where($user->id, '=', $id)->run();
                    
                    Session::set('updated', 'User updated successfully!');
                    redirect("/users");
                    
                } else {
                    $data['rules'] = $rules->errors;
                }

            } else {
                Session::set('csrf', 'Cross site request forgery!');
                redirect("/users/$id");
            }
        }
        
        $user = DB::try()->select($user->t.'.*', $role->t.'.'.$role->name)->from($user->t)->join($user_role->t)->on($user->t.'.'.$user->id, '=', $user_role->t.'.'.$user_role->user_id)->join($role->t)->on($user_role->t.'.'.$user_role->role_id, '=', $role->t.'.'.$role->id)->where($user->t.'.'.$user->id, '=', $id)->first();
        $data['user'] = $user;

        return $this->view('admin/users/edit', $data);
    }

    public function delete($id) {
        
        $id = $id['id'];

        $user = new User();
        $user = DB::try()->delete($user->t)->where($user->id, "=", $id)->run();

        redirect("/users");
    }


}