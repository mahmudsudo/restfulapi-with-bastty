<?php

namespace App;

use Illuminate\Support\Collection;
use Basttyy\ReactphpOrm\QueryBuilder;
use React\Promise\PromiseInterface;
use Exception;

final class Users
{
    private $db;

    public function __construct(QueryBuilder $db)
    {
        $this->db = $db;
    }

    public function all()
    {
        return  $this->db->from('users')->select()->get()->then(
            function(Collection $data) {
    
               $users = ($data->all()) ;
                return $users;
                print_r($users);
                echo $data->count() . ' row(s) in set' . PHP_EOL;
               
            },
            function (Exception $error) {
                echo 'Error: ' . $error->getMessage() . PHP_EOL;
            } ) ; 
    }


    public function create(string $name, string $email)
    {
         $user = ["name"=>$name,"email"=>$email];
    
        return $this->db->from("users")->insert($user);
       }

       public function find(string $id): PromiseInterface
    {
        return $this->db->from("users")->where("id",$id)->get()
            ->then(function (Collection $result) {
                if (empty($result->all())) {
                    throw new UserNotFoundError();
                }
                
                return ($result->all())[0];
            });
    }
    public function update(string $id, string $newName): PromiseInterface
    {
        return $this->find($id)
            ->then(function () use ($id, $newName) {
                $this->db->from("users")->where("id",$id)->update(["name"=>$newName]);
            });
    }

    public function delete(string $id): PromiseInterface
    {
        return $this->db
            ->from("users")->where("id",$id)->delete();
            
    }
    
}