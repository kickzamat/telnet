<?php

  class CTELNET {

    private $host,

            $login,

            $passwd,

            $port,

            $specSym,

            $socket = NULL,

            $socketTimeout = array('sec' => 3, 'usec' => 0),
    
            $readBufferSize = 1,

            $globalOut,
        
            $socketWriteDelay = 50000; // us

    
    public function __construct($host = NULL, $login = NULL, $passwd = NULL, $port = 23) {

      if (!$host || !$login || !$passwd) throw new Exception("Input param is empty", 1);      

      $this->host = $host;

      $this->port = $port;

      $this->login = $login;

      $this->passwd = $passwd;


      $this->specSym = array(
        
        'NULL' => chr(0),
        
        'CR' => chr(13),  
        
        'DC1' => chr(13),

        'IAC' => chr(255),

        'WILL' => chr(251),

        'WONT' => chr(252),

        'DO' => chr(253),

        'DONT' => chr(254),

      );


      if (!$this->socketCreate()) throw new Exception("# Error socketCreate()", 1);

      $this->socketRead();
      

    } // func


    private function socketClose() {

      if ($this->socket) socket_close($this->socket);

    } // func


    public function __desctruct() {

      $this->socketClose();

    } // func


    private function socketCreate() {

      if (!($this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP))) return false;

      socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, $this->socketTimeout);

      if (!socket_connect($this->socket, $this->host, $this->port)) return false;

      return true;

    } // func


    private function readChar() {
    
      $out = socket_read($this->socket, 1);
      
      $this->globalOut .= $out;
      
      return $out;    
    
    }
    
    
    private function negotinate() {

      $out = $this->readChar();
      
      $msg = '';

      if ($out == $this->specSym['IAC']) {

        throw new Exception(" # Error negotinate()", 1);
        
      } // if


      if (($out == $this->specSym['DO']) || ($out == $this->specSym['DONT'])) {

          $opt = $this->readChar();
        
          $msg = $this->specSym['IAC'] . $this->specSym['WONT'] . $opt;

          $this->globalOut .= "\nSending: #$msg#\n";
        
          if (!socket_write($this->socket, $msg)) {

            throw new Exception(" # Error socket_write()", 1);
            
          } // if
        
          usleep($this->socketWriteDelay);
        
      } else if (($out == $this->specSym['WILL']) || ($out == $this->specSym['WONT'])) {

          $opt = $this->readChar();
        
          $msg = $this->specSym['IAC'] . $this->specSym['DONT'] . $opt;
        
          $this->globalOut .= "\nSending: #$msg#\n";

          if (!socket_write($this->socket, $msg)) {

            throw new Exception(" # Error socket_write()", 1);

          } // if 
        
          usleep($this->socketWriteDelay);

      } else {

        throw new Exception(" # Error Unknown negotinaion string", 1);
      
      } // else
      
      return $out;

    } // func


    private function socketRead() {
      
      while (1) {
          
        $out = $this->readChar();
          
        if ($out === $this->specSym['NULL'] || $out === FALSE) break;
        
        printf("$out");
        
        if ($out == $this->specSym['IAC']) {
          
          $out = $this->negotinate();    
          
        } // if

      } // while

    } // funct 

  } // class

?>