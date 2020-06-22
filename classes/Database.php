<?php

class Database
{
  private $db;

  public function __construct()
  {

    try {
      $this->db = new PDO('sqlite:./db/sntl.db');
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

    return (int) $this->db->lastInsertId();
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
