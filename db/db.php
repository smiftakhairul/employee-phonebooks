<?php
    require_once('settings.php');

    class DB extends DbSettings {
        public $link;
        public $sql;

        public function __construct()
        {
            $settings = $this->getSettings();
            // print_r($settings);
            $this->link = new mysqli(
                $settings['host'],
                $settings['username'],
                $settings['password'],
                $settings['db']
            );
        }

        public function query($query)
        {
            $this->sql = $query;
            return $this->link->query($query);
        }

        public function escapeString($query)
        {
            return $this->link->escape_string($query);
        }

        public function numRows($result)
        {
            return $result->num_rows;
        }

        public function lastInsertId()
        {
            return $this->link->insert_id;
        }

        public function fetchAssoc($result)
        {
            return $result->fetch_assoc();
        }

        public function fetchArray($result, $resultType = MYSQLI_ASSOC)
        {
            return $result->fetch_array($resultType);
        }

        public function fetchAll($result, $resultType = MYSQLI_ASSOC)
        {
            return $result->fetch_all($resultType);
        }

        public function fetchRow($result)
        {
            return $result->fetch_row();
        }

        public function freeResult($result)
        {
            $this->link->free_result($result);
        }

        public function close()
        {
            $this->link->close();
        }

        public function sqlError()
        {
            $errno = $this->link->errno ?? '';
            $error = $this->link->error ?? '';
            return $errno . ' : ' . $error;
        }
    }
?>