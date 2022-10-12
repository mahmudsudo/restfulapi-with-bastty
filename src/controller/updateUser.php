<?php

namespace App\Controller;
use React\Http\Message\Response;
use App\UserNotFoundError;
use App\Users;
use Psr\Http\Message\ServerRequestInterface;

final class UpdateUser
{
    private $users;

    public function __construct(Users $users)
    {
        $this->users = $users;
    }

    public function __invoke(ServerRequestInterface $request, string $id)
    {
        $name = $this->extractName($request);
        if (empty($name)) {
            return new Response(500,["content-type"=>"text/plain"],"name field is required");
        }

        return $this->users->update($id, $name)
            ->then(
                function () {
                    return new Response(Response::STATUS_NO_CONTENT);
                },
                function (UserNotFoundError $error) {
                    return new Response(404,['Content-Type' => 'application/json'], json_encode(['error' => $error->getMessage()]));
                }
            );
    }

    private function extractName(ServerRequestInterface $request): ?string
    {
        $params = json_decode((string)$request->getBody(), true);
        return $params['name'] ?? null;
    }
}