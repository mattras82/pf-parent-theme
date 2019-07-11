<?php if(($social = pf_get_social()) && count($social) > 0):?>
    <ul class="social-links menu simple">
        <?php foreach($social as $network): ?>
          <li>
            <a href="<?php echo $network->url ?>" target="_blank" class="social-link social-link-<?php echo $network->type ?>" title="<?php echo $network->name ?>" rel="noopener">
              <?php $network->icon() ?>
              <span class="show-for-sr"><?php echo $network->name ?></span>
            </a>
          </li>
        <?php endforeach ?>
    </ul>
<?php endif; ?>
