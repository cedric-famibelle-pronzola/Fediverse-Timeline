<?php
  include './classes/GetContents.php';
  include './classes/PostDate.php';

  $url = 'https://mamot.fr/api/v1/timelines/public';
  $contents = new GetContents($url);
  $noImg = 'https://place-hold.it/90x101?text=Pas d\'image';

foreach($contents->getApiContents() as $content):
  $postDate = new PostDate($content->created_at);
  $postSince = $postDate->getPostSince();
  $getPostDate = $postDate->getPostDate();
?>
  <div class="status card text-white bg-dark">
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
