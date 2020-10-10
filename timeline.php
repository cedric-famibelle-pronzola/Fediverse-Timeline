<head>
    <meta charset="UTF-8">
    <base target="_blank">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favico.png" type="image/png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.6.3/dist/css/foundation.min.css" integrity="sha256-ogmFxjqiTMnZhxCqVmcqTvjfe1Y/ec4WaRj/aQPvn+I=" crossorigin="anonymous">    <link rel="stylesheet" href="./scripts/css/style.css">
    <link rel="stylesheet" href="./scripts/css/style.css">
    <title>Fediverse Social Network Timeline</title>
</head>

<?php

  include('./connect.php');
  $request = json_decode(file_get_contents('php://input'));

  if(is_object($request) && property_exists($request, 'default')) {
    $instanceDb = $request->id ? $db->getInstanceById($request->id) : $db->getInstanceById($db->lastInsert());
    $instanceName = $instanceDb['name'];
  } else if (is_object($request) && property_exists($request, 'id')) {
    $instanceDb = $db->getInstanceById($request->id);
    $instanceName = $instanceDb['name'];
  }
  if (!is_object($request)) {
    $lastInsert = $db->lastInsert();
    $instanceDb = $db->getInstanceById($lastInsert);
    $instanceName = $instanceDb['name'];
  }
  if (!property_exists($request, 'default') && !property_exists($request, 'id')) {
    $instance = new Instance($request);
    $instanceName = $instance->getInstanceName();
    $instanceId = $db->setInstance($instance);
    $instanceDb = $db->getInstanceById($instanceId);
  }

  $instanceDbObject = new stdClass;
  $instanceDbObject->instanceName = $instanceDb['name'];
  $instanceDbObject->width = 400;
  $instanceDbObject->height = 800;
  $instanceDbObject->timelineChoice = $instanceDb['global'];
  $createdInstance = new Instance($instanceDbObject);

  $url = "https://{$createdInstance->getInstanceName()}/api/v1/timelines/public";
  $contents = new GetContents($url, $createdInstance->getTimelineChoice());
  $noImg = 'https://place-hold.it/90x101?text=Pas d\'image';

foreach($contents->getApiContents() as $content):
  $postDate = new PostDate($content->created_at);
  $postSince = $postDate->getPostSince();
  $getPostDate = $postDate->getPostDate();
?>
  <div id="integrate" class="status card text-white bg-dark">
    <div class="author card-header">
        <a class="avatar" href="<?= $content->account->url ?>">
            <img class="avatar" src="<?= $content->account->avatar ?>">
        </a>
        <div class="author-info">
            <a class="author-displayname text-success" href="<?= $content->account->url ?>"><?= $content->account->username ?></a>
        </div>
    </div>
    <div class="content">
        <p> <span class="text-danger"><?= $content->spoiler_text ? $content->spoiler_text . '<br />' : null ?></span> <?= $content->content ?></p>
    </div>
    <div class="enclosures">
      <?php
        if(!empty($content->media_attachments)):
          for($i = 0; $i < count($content->media_attachments); $i++):
            if($content->media_attachments[$i]->type === 'image'):
      ?>
              <a class="enclosure" href="<?= $content->media_attachments[$i]->url ?>">
                  <img src="<?= $content->media_attachments[$i]->preview_url ?>" alt="">
              </a>
      <?php
            endif;
          endfor;
        endif;
      ?>
    </div>
    <?php
      if(!empty($content->card)):
    ?>
        <div class="open-graph">
          <a class="d-flex" href="<?= $content->card->url ?>">
            <div class="card-image">
              <img src="<?= $content->card->image ? $content->card->image : $noImg ?>" alt="">
            </div>
            <div class="card-content">
              <span class="card-host"><?= $content->card->provider_name ?></span>
              <div class="card-title">
                <h5><?= $content->card->title ?></h5>
              </div>
              <p class="card-description"><?= $content->card->description ?></p>
            </div>
          </a>
        </div>
    <?php
      endif;
    ?>
      <a title="<?= $getPostDate ?>" class="card-footer text-success text-center" href="<?= $content->url ?>" class="date"><?= $postSince ?></a>
  </div>
<?php
endforeach;
?>
<script src="./scripts/js/main.js"></script>