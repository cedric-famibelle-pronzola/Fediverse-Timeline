<?php

class PostDate
{
  private $createdPostDate;
  private $now;
  private $post;
  private $diff;

  public function __construct(string $createdPostDate)
  {
    $this->createdPostDate = $createdPostDate;
    $this->now = new DateTime();
    $this->post = new DateTime($this->createdPostDate);
    $this->diff = $this->now->diff($this->post);
  }

  public function getPostSince()
  {
    if ($this->diff->days && $this->diff->days >= 7) {
      $week = $this->diff->days >= 14 ? ' semaines' : ' semaine';
      $nbWeek = '';
      if ($this->diff->days >= 7 && $this->diff->days < 14) {
        $nbWeek = 1;
      }
      else if ($this->diff->days >= 14 && $this->diff->days < 21) {
        $nbWeek = 2;
      }
      else if ($this->diff->days >= 21) {
        $nbWeek = 3;
      }
      return "Il y a {$nbWeek} {$week}";
    }

    if (!$this->diff->days && !$this->diff->i) {
      $sec = $this->diff->s > 1 ? ' secondes' : ' seconde'; 
      return "Il y a {$this->diff->s} {$sec}";
    }

    if (!$this->diff->days && !$this->diff->h) {
      $min = $this->diff->i > 1 ? ' minutes' : ' minute';
      return "Il y a {$this->diff->i} {$min}";
    }

    if (!$this->diff->days) {
      $hours = $this->diff->h > 1 ? ' heures' : ' heure';
      return "Il y a {$this->diff->h} {$hours}";
    }

    $day = $this->diff->days > 1 ? ' jours' : ' jour';
    return "Il y a  {$this->diff->format('%a')} {$day}";
  }

  public function getPostDate()
  {
    $UTC = new DateTimeZone('UTC');
    $timeZone = new DateTimeZone('Europe/Paris');
    $post = new DateTime($this->createdPostDate, $UTC);
    $post->setTimezone($timeZone);
    return $post->format('d/m/Y à H:i:s');
  }
}