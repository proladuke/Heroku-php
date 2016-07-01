<?php
namespace Sprint;
require 'autoload.php';



# Servers
use \Ratchet\Http\HttpServer;
use \Ratchet\Server\IoServer;
use \Ratchet\WebSocket\WsServer;
# Components
use \Ratchet\ConnectionInterface;
use \Ratchet\MessageComponentInterface;

class Chat implements MessageComponentInterface
{
protected $clients;
private $arr;
private $cnt = "aaaaa";
var_dump($cnt);
  	public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
      $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $message)
    {
      	foreach ($this->clients as $client) {
            $client->send(json_encode(['data' => 'pong'])); 
        }
      	$arr = str_word_count($message, 1);
      	if($arr[0] == "bot"){
        	$data = [
          	 	"command" => $arr[1],
        	    "data" => $arr[2],
        	];
        	$bot = new Bot($data);
        	$bot->generateHash();
            foreach ($this->clients as $client) {
            	$client->send(json_encode(['data' => $bot->hash]));
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }
}

/**
 * Do NOT remove this code.
 * This code is needed for `codecheck` command to see whether server is running or not
 */
$docroot = __DIR__ . '/../public';
$deamon = popen("php -S 0.0.0.0:9000 --docroot {$docroot}", "r");

$base = new HttpServer(new WsServer(new Chat));
$server = IoServer::factory($base, 9000);
$server->run();
