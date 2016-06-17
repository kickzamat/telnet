<?php

  class CTELNET {

    private $host,
            $login,
            $passwd,
            $port;


    public function __construct($host = NULL, $login = NULL, $passwd = NULL, $port = 23) {

      if ($host == NULL) die("!host\n");

      if (!$host | !$login | !$passwd) throw new Exception("Input param is empty", 1);      

      $this->host = $host;

      $this->port = $port;

      $this->login = $login;

      $this->passwd = $passwd;

    } //func

  } // class

?>