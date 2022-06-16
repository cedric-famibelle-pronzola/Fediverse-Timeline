<?php

class Database
{
  private $db;

  public function __construct()
  {

    try {
      $this->db = new PDO('sqlite:./db/sntl.db');
      $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->db->query("CREATE TABLE IF NOT EXISTS instances (
        id INTEGER NOT NULL,
        name TEXT NOT NULL,
        global INTEGER DEFAULT 0,
        PRIMARY KEY (id AUTOINCREMENT))");
    } catch (PDOException $e) {
      echo  $e->getMessage();
      return;
    }

    return $this->db;
  }

  public function query($query)
  {
    return $this->db->query($query);
  }

  public function getInstanceList()
  {
    $query = $this->query('SELECT * FROM instances');
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }

  public function setDefaultInstance()
  {
    try {
      $instance = $this->getInstanceById(1);

      if ($instance) {
        $lastInsertId = $this->lastInsert();
        return $this->getInstanceById($lastInsertId);
      }

      $query = $this->db->prepare('INSERT INTO instances(name, global) VALUES("mamot.fr", 0)');
      $query->execute();

      if (!$query) {
        throw new Exception();
      }

      $lastInsertId = $this->lastInsert();
      return $this->getInstanceById($lastInsertId);

    } catch (Exception $e) {
      echo 'Bad Request';
      die;
    }

    return (int) $this->db->lastInsert();
  }

  public function setInstance(Instance $instance)
  {
    try {
      $query = $this->db->prepare('INSERT INTO instances(name, global) VALUES(:name, :global)');
      if(!$query)
      throw new Exception();
      $query->bindValue(':name', $instance->getInstanceName(), PDO::PARAM_STR);
      $query->bindValue(':global', $instance->getTimelineChoice(), PDO::PARAM_BOOL);
      $query->execute();

    } catch (Exception $e) {
      echo 'Bad Request';
      die;
    }

    return (int) $this->lastInsert();
  }

  public function lastInsert()
  {
    $query = $this->db->query('SELECT id FROM instances WHERE id = (SELECT MAX(id) FROM instances)');
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return (int) $result['id'];
  }

  public function getInstanceById(int $id)
  {
    $query = $this->query('SELECT * FROM instances WHERE id =' . $id);
    return $query->fetch(PDO::FETCH_ASSOC);
  }
}
