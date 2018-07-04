<?php

namespace DataProviders {

    require_once 'classDataProvider.php';

    class SQL extends DataProvider
    {
        /**
         * @var \PDO
         */
        private $pdo;

        public function connect()
        {
            $this->pdo = new \PDO($this->settings['connectionString'], $this->settings['user'], $this->settings['password']);
        }

        public function query($query)
        {
            if (!$this->pdo)
                $this->connect();
            $result = $this->pdo->query($query);
            if (!$result){
                $this->error = $this->pdo->errorInfo();
                throw new \Exception('SQL error: ' . $this->error[2]);
            }
            return $result;
        }

        public function quote($str) {
            if (!$this->pdo)
                $this->connect();
            return $this->pdo->quote($str);
        }

    }

}