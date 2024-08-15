<?php

class Model
{
    protected $pdo;

    public function __construct()
    {
      try{
        $this->pdo = new PDO('mysql:host=locahost;dbname=task_todo_list', 'root', '');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }
      catch (PDOException $e){
        error_log('Connection Failed: ' . $e->getMessage());
        die('Database Connection Failed');
      }

    }
}


?>

