<?php

namespace App\Controller;

use App\JsonResponse;
use App\Users;
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

 class CreateUser
{
    private $users;

    public function __construct(Users $users)
    {
        $this->users = $users;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $user = json_decode((string) $request->getBody(), true);
        $name = $user['name'] ?? '';
        $email = $user['email'] ?? '';

        return $this->users->create($name, $email)
            ->then(
                function (bool $status) {
                    echo "record added sucessfully";
                  return new Response(201,["content-type"=>"text/plain"]," record created successfully");
                },
                function (Exception $error) {
                    echo $error->getMessage().PHP_EOL;
                    return new Response(
                        400, ['Content-Type' => 'application/json'], json_encode(['error' => $error->getMessage()])
                    );
                }
            );
    }
}