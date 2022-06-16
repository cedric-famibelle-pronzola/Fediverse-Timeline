<!DOCTYPE html>
<?php
  include('./connect.php')
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="favico.png" type="image/png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.6.3/dist/css/foundation.min.css" integrity="sha256-ogmFxjqiTMnZhxCqVmcqTvjfe1Y/ec4WaRj/aQPvn+I=" crossorigin="anonymous">    <link rel="stylesheet" href="./scripts/css/style.css">
  <link rel="stylesheet" href="./scripts/css/style.css">
  <title>Fediverse Social Network Timeline</title>
</head>
<body>
  <h2>Choose an instance</h2>
  <div class="switch large global">
    <small class="validation" id="errors"></small>
    <div>Do you want to display the global timeline ?</div>
    <div>
      <small><i>Yes = Global timeline</i></small> |
      <small><i>No = Local timeline</i></small>
    </div>
    <input class="switch-input" id="yes-no" type="checkbox" name="global-local">
    <label class="switch-paddle" for="yes-no">
      <span class="show-for-sr">Do you want to display global timeline ?</span>
      <span class="switch-active" aria-hidden="true">Yes</span>
      <span class="switch-inactive" aria-hidden="true">No</span>
    </label>
  </div>
  <div class="grid-container">
    <form id="instance-text" data-abide novalidate>
      <div>
        <label>Instance name
          <small class="validation" id="validation-instance-name"></small>
          <input id="instance-name" type="text" placeholder="mamot.fr" value="mamot.fr">
          </label>
      </div>
      <div>
        <label>Width (px)
          <small class="validation" id="validation-width"></small>
          <input id="width" type="number" placeholder="400">
        </label>
      </div>
      <div>
        <label>Height (px)
          <small class="validation" id="validation-height"></small>
          <input id="height" type="number" placeholder="800">
        </label>
      </div>
      <button type="button" class="success button expanded" id="form-text" name="form-text">Create integration</button>
    </form>
    <h2>Or select one</h2>
    <form id="instance-select">
      <label>Instance name
        <select id="selected-instance">
          <?php
            if(!empty($db->getInstanceList())):
              foreach ($db->getInstanceList() as $instance):
          ?>
                <option value="<?= $instance['id']?>"><?= $instance['name'] ?><?= $instance['global'] === '1' ? " (global)" : " (local)" ?> -> (ID : <?= $instance['id'] ?>)</option>
          <?php
              endforeach;
            else:
          ?>
                <option value="0">No instance</option>
          <?php
            endif;
          ?>
        </select>
      </label>
      <button type="button" class="success button expanded" id="form-select" name="form-select">Generate</button>
    </form>
  </div>
    <div id="instance-id">Last generated instance ID : <span id="last-id"><?= $db->lastInsert() ?></span></div>
    <div id="iframe-link"></div>
    <div id="iframe-container">
      <iframe id="iframe" allowfullscreen referrerpolicy="no-referrer" frameborder="0" src="./timeline.php"></iframe>
    </div>
<script src="./scripts/js/main.js"></script>
</body>
</html>