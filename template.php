<?php

function fabs_facebook_feed($uid=null, $params=array())
{
    static $_id=0;
    $id = 'fabs_facebook_feed_'.($_id++);
    $uid = $uid ? $uid : fabs_facebook_default_account();
    /*
    $path = '/'.$uid.'/feed';
    $cacheKey = fabs_facebook_cache_key($path, $params);
    $cache = fabs_facebook_cache($path, $params);
    */
    fabs_facebook_jquery_tmpl(array(
        'message-status',
        'message-default',
        'message-link',
        'message-video',
        'message',
        'feed'
    ));
    ?>
        <div id="<?=$id?>"><div class="fb-loading"></div></div>
        <?=fabs_facebook_js('feed');?>
        <script type="text/javascript">
        // Fabs_Facebook.cache( '<?= $cacheKey ?>', <?= json_encode($cache) ?> );
        Fabs_Facebook.feed( '<?= $uid ?>', '<?= $id ?>', <?= json_encode($params) ?> );
        </script>
    <?php
}

function fabs_facebook_events($uid=null, $params=array())
{
    static $_id=0;
    $id = 'fabs_facebook_events_'.($_id++);
    $uid = $uid ? $uid : fabs_facebook_default_account();
    fabs_facebook_jquery_tmpl(array('events','event'));
    /*
    $path = '/'.$uid.'/events';
    $cacheKey = fabs_facebook_cache_key($path, $params);
    $cache = fabs_facebook_cache($path, $params);
    */
    ?>
        <div id="<?=$id?>"><div class="fb-loading"></div></div>
        <?=fabs_facebook_js('events');?>
        <script type="text/javascript">
        //Fabs_Facebook.cache( '<?= $cacheKey ?>', <?= json_encode($cache) ?>, '<?= $cacheKey ?>' );
        Fabs_Facebook.events( '<?= $uid ?>', '<?= $id ?>', <?= json_encode($params) ?> );
        </script>
    <?php
}

function fabs_facebook_latestevent($uid=null, $params=array())
{
    static $_id=0;
    $id = 'fabs_facebook_latest_'.($_id++);
    $uid = $uid ? $uid : fabs_facebook_default_account();
    fabs_facebook_jquery_tmpl(array('latestevent'));
    $path = '/'.$uid.'/events';
    $cacheKey = fabs_facebook_cache_key($path, $params);
    $cache = fabs_facebook_cache($path, $params);
    ?>
        <span id="<?=$id?>"></span>
        <?=fabs_facebook_js('latestevent');?>
        <script type="text/javascript">
        Fabs_Facebook.cache( '<?= $cacheKey ?>', <?= json_encode($cache) ?>);
        Fabs_Facebook.latestevent( '<?= $uid ?>', '<?= $id ?>', <?= json_encode($params) ?> );
        </script>
    <?php
}


function fabs_facebook_albums($uid=null, $params=array())
{
    static $_id=0;
    $id = 'fabs_facebook_albums_'.($_id++);
    $uid = $uid ? $uid : fabs_facebook_default_account();
    fabs_facebook_jquery_tmpl(array('albums','albums-item','album','album-item','photo'));
    /*
    $path = '/'.$uid.'/albums';
    $cacheKey = fabs_facebook_cache_key($path, $params);
    $cache = fabs_facebook_cache($path, $params);
    */
    ?>
        <div id="<?=$id?>"><div class="fb-loading"></div></div>
        <?=fabs_facebook_js('albums');?>
        <script type="text/javascript">
        // Fabs_Facebook.cache( '<?= $cacheKey ?>', <?= json_encode($cache) ?> );
        Fabs_Facebook.albums( '<?= $uid ?>', '<?= $id ?>', <?= json_encode($params) ?>);
        </script>
    <?php
}
