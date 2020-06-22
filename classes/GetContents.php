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
    try {
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_HTTPHEADER, [
          'Content-Type: application/json'
        ]);
      $this->global ? curl_setopt($curl, CURLOPT_URL, $this->url) : curl_setopt($curl, CURLOPT_URL, "{$this->url}?local=true");
      curl_setopt($curl, CURLOPT_FAILONERROR, true);
  
      $response = json_decode(curl_exec($curl));
      if (curl_errno($curl)) {
        $error_msg = curl_error($curl);
      }
      curl_close($curl);
      if (isset($error_msg)) {
        throw new Exception;
      }
    } catch (Exception $e) {
      echo '<span style="color: red; font-weight: bold;">Instance name error</span>';
      die;
    }

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
