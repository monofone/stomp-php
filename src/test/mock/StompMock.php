<?php

/**
 * Description of StompMock
 *
 * @author srohweder
 */
class StompMock
{

    protected $socket;
    protected $actualClientSocket;
    protected $running = true;
    protected $options;
    protected $streams;
    protected $readData;

    /**
     *
     * @param array $options 
     *  - [port] =  61613
     *  - [brokenConnectedFrame] = true
     */
    public function __construct(array $options)
    {
        $this->options = $options;
        $this->socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname('tcp'));
        if (!socket_bind($this->socket, '127.0.0.1', $options['port'])) {
            socket_close($this->socket);
            echo "Failed to bind Socket to port " . $options['port'] . "\n";
            exit();
        }
        echo "socket bound to " . $options['port'] . "\n";
        if (!socket_listen($this->socket)) {
            socket_close($this->socket);
            echo "failed to listen\n";
            exit();
        }
        echo "Socket is listening\n";
        socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 1);

        $this->handleCommunication();
    }

    protected function handleCommunication()
    {
        $this->actualClientSocket = $socket = socket_accept($this->socket);
        $this->streams = array($socket);
        do {
            if ($this->readData == "") {
                socket_select($this->streams, $write = null, $except = null, null);
            }

            echo "handling client\n";
            $this->handleClient();
        } while ($this->running);
    }

    protected function handleClient()
    {

        $this->readData = $data = socket_read($this->actualClientSocket, 2048, 0);
        if("" === $data){
            return;
        }
        list($command, $headers, $body) = $this->handleData($data);
        
        switch ($command) {
            case 'CONNECT':
                $this->handleConnect($headers, $body);
                echo "send connected command\n";
                break;
            case 'SEND':
                $this->handleSend($headers, $body);
                echo "handle send command\n";
                break;
        }
    }

    protected function handleData($data)
    {
        $headers = array();
        $body = '';
        $messageData = explode("\n", $data);
        $command = array_shift($messageData);
        while ($dataSet = array_shift($messageData)) {
            if (preg_match("/([a-z]+):([\sa-z]*)/", $dataSet, $matches) != 0) {
                $headers[$matches[1]] = trim($matches[2]);
            } else {
                $body = $dataSet;
            }
        }

        return array($command, $headers, $body);
    }

    protected function handleConnect($headers)
    {
        if (isset($this->options['brokenConnectedFrame']) && $this->options['brokenConnectedFrame']) {
            $connectMessage = "CONNECTER\nversion:1.0\nsession:1\n\n";
        } else {
            $connectMessage = "CONNECTED\nversion:1.0\nsession:1\n\n";
        }

        $this->send($connectMessage);
    }

    protected function handleSend($header, $body)
    {
        if (isset($header['receipt']) && $header['receipt']) {
            $message = "RECEIPT\nreceipt-id:12345\n\n";
            $this->send($message);
        }
    }

    protected function send($message)
    {
        $message .= "\00";

        if (socket_send($this->actualClientSocket, $message, strlen($message), MSG_EOF) === false) {
            echo "failed sending data\n";
            return false;
        }
        return true;
    }

    public function __destruct()
    {
        socket_close($this->socket);
    }

}
