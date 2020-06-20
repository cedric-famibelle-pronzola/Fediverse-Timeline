<?php
  include 'contents.php';
  include './functions/PostDate.php';

  $noImg = 'https://place-hold.it/90x101?text=Pas d\'image';

foreach($pages as $page):
  $postDate = new PostDate($page->created_at);
  $postSince = $postDate->getPostSince();
  $getPostDate = $postDate->getPostDate();
?>
  <div class="status card text-white bg-dark">
    <div class="author card-header">
        <a class="avatar" href="<?= $page->account->url ?>">
            <img class="avatar" src="<?= $page->account->avatar ?>">
        </a>
        <div class="author-info">
            <a class="author-displayname text-success" href="<?= $page->account->url ?>"><?= $page->account->username ?></a>
        </div>
    </div>
    <div class="content">
        <p> <span class="text-danger"><?= $page->spoiler_text ? $page->spoiler_text . '<br />' : null ?></span> <?= $page->content ?></p>
    </div>
    <div class="enclosures">
      <?php
        if(!empty($page->media_attachments)):
          for($i = 0; $i < count($page->media_attachments); $i++):
            if($page->media_attachments[$i]->type === 'image'):
      ?>
              <a class="enclosure" href="<?= $page->media_attachments[$i]->url ?>">
                  <img src="<?= $page->media_attachments[$i]->preview_url ?>" alt="">
              </a>
      <?php
            endif;
          endfor;
        endif;
      ?>
    </div>
    <?php
      if(!empty($page->card)):
    ?>
        <div class="open-graph">
          <a class="d-flex" href="<?= $page->card->url ?>">
            <div class="card-image">
              <img src="<?= $page->card->image ? $page->card->image : $noImg ?>" alt="">
            </div>
            <div class="card-content">
              <span class="card-host"><?= $page->card->provider_name ?></span>
              <div class="card-title">
                <h5><?= $page->card->title ?></h5>
              </div>
              <p class="card-description"><?= $page->card->description ?></p>
            </div>
          </a>
        </div>
    <?php
      endif;
    ?>
      <a title="<?= $getPostDate ?>" class="card-footer text-success text-center" href="<?= $page->url ?>" class="date"><?= $postSince ?></a>
  </div>
<?php
endforeach;
