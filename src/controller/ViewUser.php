<?php

namespace App\Controller;

use App\JsonResponse;
use App\Users;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use App\UserNotFoundError;


final class ViewUser
{
    private $users;

    public function __construct(Users $users)
    {
        $this->users = $users;
    }

    public function __invoke(ServerRequestInterface $request, string $id)
    {
        return $this->users->find($id)
            ->then(
                function (array $user) {
                    return new Response(200,["content-type"=> "application/json"],json_encode($user));
                },
                function (UserNotFoundError $error) {
                    return new Response(404,['Content-Type' => 'application/json'], json_encode(['error' => $error->getMessage()]));
                }
            );
    }
}