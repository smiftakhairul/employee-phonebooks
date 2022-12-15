<?php
    class DbSettings {
        private $host = 'localhost';
        private $db = 'employee_phonebooks';
        private $username = 'root';
        private $password = 'password';

        public function __construct($host = '', $db = '', $username = '', $password = '')
        {
            $this->host = $host ?? $this->host;
            $this->db = $db ?? $this->db;
            $this->username = $username ?? $this->username;
            $this->password = $password ?? $this->password;
        }

        public function getSettings()
        {
            return [
                'host' => $this->host,
                'db' => $this->db,
                'username' => $this->username,
                'password' => $this->password,
            ];
        }
    }
?>