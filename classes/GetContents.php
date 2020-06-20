<?php

class GetContents
{
  private $url;
  private $global;

  public function __construct(string $url, bool $global = false)
  {
    $this->url = $url;
    $this->global = $global;
  }

  public function getApiContents()
  {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
      ]);
    $this->global ? curl_setopt($curl, CURLOPT_URL, $this->url) : curl_setopt($curl, CURLOPT_URL, "{$this->url}?local=true");

    $response = json_decode(curl_exec($curl));
    curl_close($curl);
    return $response;
  }

  public function getUrl(): string
  {
    return $this->url;
  }

  public function setUrl(string $url)
  {
    $this->url = $url;
  }

  public function getGlobal(): bool
  {
    return $this->global;
  }

  public function setGlobal(bool $global)
  {
    $this->global = $global;
  }
}
