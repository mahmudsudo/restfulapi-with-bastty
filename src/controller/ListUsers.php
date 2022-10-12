<?php

namespace App\Controller;


use App\JsonResponse;
use React\Http\Message\Response;
use Exception;
use App\Users;
use Psr\Http\Message\ServerRequestInterface;


class ListUsers
{
    private $users;

    public function __construct(Users $users)
    {
        $this->users = $users;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        return $this->users->all()
        ->then(function(array $users) {
             return Response::json($users);
            
        },
        function (Exception $error) {
                       echo 'Error: ' . $error->getMessage() . PHP_EOL;});
                       return new Response(500);
    }
}