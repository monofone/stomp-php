<?php

use FuseSource\Stomp\Stomp;

/**
 * Description of StompTest
 *
 * @author srohweder
 */
class StompTest extends \PHPUnit_Framework_TestCase
{


//    public function testConnect()
//    {
//        $stomp = new Stomp('tcp://localhost:61613');
//        $stomp->connect();
//        $this->assertTrue($stomp->isConnected());
//    }
//
//    public function testConnectNoPort()
//    {
//        $this->markTestIncomplete('missing check for port');
//        $stomp = new Stomp('tcp://localhost');
//        $stomp->connect();
//        $this->assertTrue($stomp->isConnected());
//    }
//
//    /**
//     * @expectedException FuseSource\Stomp\Exception\StompException
//     * 
//     */
//    public function testNoConnect()
//    {
//        $stomp = new Stomp('hailtofail');
//    }
//
//    public function testFailOver()
//    {
//        $stomp = new Stomp('failover://(tcp://localhost:61611,tcp://localhost:61613)?randomize=false');
//        $stomp->connect();
//        $this->assertTrue($stomp->isConnected());
//        $stomp = new Stomp('failover://(tcp://localhost:61611,tcp://localhost:61613)?randomize=true');
//        $stomp->connect();
//        $this->assertTrue($stomp->isConnected());
//    }
//    
//    public function testConnectUserPass()
//    {
//        $stomp = new Stomp('tcp://localhost:61613');
//        $stomp->clientId = "55";
//        $stomp->connect('user','pass');
//        $this->assertTrue($stomp->isConnected());
//    }
    
    public function testSend()
    {
        $stomp = new Stomp('tcp://localhost:61613');
        $stomp->connect();
        //$this->assertTrue($stomp->send('/queue/test', 'test'));
    }

}
