<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . "/src/router.php";
require __DIR__ . "/src/controller/ListUsers.php";
require __DIR__ . "/src/controller/createUsers.php";
require __DIR__ . "/src/controller/ViewUser.php";
require __DIR__ . "/src/controller/updateUser.php";
require __DIR__ . "/src/controller/deleteUser.php";
use React\Http\HttpServer;
use React\Socket\SocketServer;
use Basttyy\ReactphpOrm\QueryBuilderWrapper;
use React\MySQL\Factory;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use App\Router;


$factory = new Factory();
$connection = (new QueryBuilderWrapper($factory))->createLazyConnectionPool('root:Bello@11@localhost/react-database', 5);
$socket = new SocketServer('127.0.0.1:8080');
$users = new \App\Users($connection);

// $listUsers = function () use ($connection){
//     return  $connection->from('users')->select()->get()->then(
//         function(Collection $data) {

//            $users = json_encode($data->all()) ;
//             return Response::json($users);
//             print_r($users);
//             echo $data->count() . ' row(s) in set' . PHP_EOL;
           
//         },
//         function (Exception $error) {
//             echo 'Error: ' . $error->getMessage() . PHP_EOL;
//         }  ) ; 
//     };

    // $createUser = function (ServerRequestInterface $request) use ($connection) {
    //     $user = json_decode((string) $request->getBody(), true);
    
    //      return $connection->from("users")->insert($user)
    //         ->then(function () { 
    //             return new Response(201,array(
    //             'Content-Type' => 'text/plain'
    //         ),
    //     "ran successfully");},
    //     function (Exception $error) {
    //         return new Response(
    //             400, ['Content-Type' => 'application/json'], json_encode(['error' => $error->getMessage()])
    //         );
    //     });
    // };

    $routes = new RouteCollector(new Std(), new GroupCountBased());
    
$routes->get('/users', new \App\Controller\ListUsers($users));
$routes->post('/users', new \App\Controller\CreateUser($users));
$routes->get('/users/{id}', new \App\Controller\ViewUser($users));
$routes->put('/users/{id}', new \App\Controller\UpdateUser($users));
$routes->delete('/users/{id}', new \App\Controller\DeleteUser($users));
$http = new HttpServer(new Router($routes));
$http->listen($socket);
echo 'Listening on ' . str_replace('tcp:', 'http:', $socket->getAddress()) . PHP_EOL;
echo "Server running at http://127.0.0.1:8080" . PHP_EOL;
// $http = new HttpServer(function (Psr\Http\Message\ServerRequestInterface $request) use ($routes){
//     return new \App\Router($routes);
// });;

