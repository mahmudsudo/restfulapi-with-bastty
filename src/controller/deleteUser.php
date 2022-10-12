<?php

namespace App\Controller;

use App\JsonResponse;
use App\UserNotFoundError;
use App\Users;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

final class DeleteUser
{
    private $users;

    public function __construct(Users $users)
    {
        $this->users = $users;
    }

    public function __invoke(ServerRequestInterface $request, string $id)
    {
        return $this->users->delete($id)
            ->then(
                function (int $int) use ($id){
                    return new Response(204,["content-type"=>"text/plain"],"record with ".$id." deleted");
                },
                function (UserNotFoundError $error) {
                    return new Response(404,['Content-Type' => 'application/json'], json_encode(['error' => $error->getMessage()]));
                }
            );
    }
}