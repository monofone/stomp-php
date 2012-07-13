<?php

class ListeningServerStub
{
    protected $client;
    protected $sock;
    protected $addr;
    protected $port;

    public function listen()
    {
        $this->sock = socket_create(AF_INET, SOCK_STREAM, 0);

        // Bind the socket to an address/port
        if(socket_bind($this->sock, 'localhost', 0) === false){
            throw new RuntimeException('Could not bind to address');
        };
        socket_getsockname($this->sock, $this->addr, $this->port);
        // Start listening for connections
        socket_listen($this->sock);

        // Accept incoming requests and handle them as child processes.
        $this->client = socket_accept($this->sock);
    }

    public function read()
    {
        // Read the input from the client &#8211; 1024 bytes
        $input = socket_read($this->client, 1024);
        return $input;
    }

    public function __destruct()
    {
        socket_close($this->sock);
    }

    public function getAddr()
    {
        return $this->addr;
    }

    public function getPort()
    {
        return $this->port;
    }


}

?>
