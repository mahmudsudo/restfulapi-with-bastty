 <?php
require __DIR__ . '/vendor/autoload.php';

use React\Http\HttpServer;
use React\Http\Message\Response;
use React\Socket\SocketServer;
use Basttyy\ReactphpOrm\QueryBuilderWrapper;
use Basttyy\ReactphpOrm\QueryBuilder;
use Illuminate\Support\Collection;
use React\MySQL\Factory;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;


$factory = new Factory();
$connection = (new QueryBuilderWrapper($factory))->createLazyConnectionPool('root:Bello@11@localhost/react-database', 5);
$socket = new SocketServer('127.0.0.1:8080');


$listUsers = function () use ($connection){
    return  $connection->from('users')->select()->get()->then(
        function(Collection $data) {

           $users = json_encode($data->all()) ;
            return Response::json($users);
            print_r($users);
            echo $data->count() . ' row(s) in set' . PHP_EOL;
           
        },
        function (Exception $error) {
            echo 'Error: ' . $error->getMessage() . PHP_EOL;
        }  ) ; 
    };

  

    $routes = new RouteCollector(new Std(), new GroupCountBased());
    $routes->get('/users', $listUsers);
   

$http = new HttpServer(function (Psr\Http\Message\ServerRequestInterface $request) use ($routes){
  
});

$http->listen($socket);

echo 'Listening on ' . str_replace('tcp:', 'http:', $socket->getAddress()) . PHP_EOL;


echo "Server running at http://127.0.0.1:8080" . PHP_EOL;
?> 

