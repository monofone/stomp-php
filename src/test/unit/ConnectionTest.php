<?php

use FuseSource\Stomp\Connection;


require_once __DIR__.'/../helper/ListeningServerStub.php';
/**
 * Description of ConnectionTest
 *
 * @author rohweder
 */
class ConnectionTest extends \PHPUnit_Framework_TestCase
{

    const ADDRESS = 'tcp://127.0.0.1:61613';
    public function setUp()
    {
        //$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        //socket_bind($this->socket, '127.0.0.1', '61613');
        //socket_listen($this->socket);
        $this->server = new ListeningServerStub();
        $this->server->listen();
        die('done');
    }

    public function tearDown(){
        unset($this->server);
    }
    /**
     * @test
     */
    public function connect(){

        $source = 'tcp://'.$this->server->getAddr().':'.$this->server->getPort();
        var_dump($source);die();
        $connection = new Connection($source);
        $this->assertTrue($connection->isConnected());
    }

}

?>
