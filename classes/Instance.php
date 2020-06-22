<?php

class Instance
{
  private $instanceName;
  private $width;
  private $height;
  private $timelineChoice;

  public function __construct(object $instance)
  {
    $this->instanceName = $instance->instanceName;
    $this->width = $instance->width;
    $this->height = $instance->height;
    $this->timelineChoice = $instance->timelineChoice;
  }

  public function getInstanceName(): string
  {
    return $this->instanceName;
  }

  public function setInstanceName(string $instanceName)
  {
    $this->instanceName = $instanceName;
  }

  public function getWidth(): int
  {
    return $this->width;
  }

  public function setWidth(int $width)
  {
    $this->width = $width;
  }

  public function getHeight(): int
  {
    return $this->height;
  }

  public function setHeight(int $height)
  {
    $this->height = $height;
  }

  public function getTimelineChoice(): bool
  {
    return $this->timelineChoice;
  }

  public function setTimelineChoice(bool $timelineChoice)
  {
    $this->timelineChoice = $timelineChoice;
  }
}