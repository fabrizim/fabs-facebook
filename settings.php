<div class="wrap">
    <h2>Fab's Facebook Settings</h2>
    
    <form method="post" action="options.php">
        <?php settings_fields( 'fabs-facebook' ); ?>
        <?php if( !get_option('fabs_facebook_application_id') ){ ?>
        <p><a href="http://developers.facebook.com/setup/">Setup your Facebook App</a></p>
        <?php } ?>
        <table class="form-table">
            
            <tr valign="top">
            <th scope="row">Facebook Application ID</th>
            <td><input class="regular-text" type="text" name="fabs_facebook_application_id" value="<?php echo get_option('fabs_facebook_application_id'); ?>" /></td>
            </tr>
            
            <tr valign="top">
            <th scope="row">Facebook Application Secret</th>
            <td><input class="regular-text" type="text" name="fabs_facebook_application_secret" value="<?php echo get_option('fabs_facebook_application_secret'); ?>" /></td>
            </tr>
            
        </table>
        <?php if( get_option('fabs_facebook_application_id') && get_option('fabs_facebook_application_secret') ){ ?>
        <p>
            <a href="<?php echo fabs_facebook_login_url() ?>">Grant access to your Facebook Account</a>
        </p>
        <?php } ?>
        <?php if( ($accounts =& fabs_facebook_accounts() ) ){ ?>
        <h3>Accounts</h3>
        <table class="form-table">
            <tr valign="top">
                <th style="width: 60px">Default</th><th>Name</th><th>ID</th><th>Delete</th>
            </tr>
            <?php foreach( $accounts as $id => $account ){ ?>
            <?php if( !$account['name'] ){ continue; } ?>
            <?php $default = $id == get_option('fabs_facebook_default_account'); ?>
            <tr valign="top">
                <td><input <?= $default ? 'checked ':''?>type="radio" name="fabs_facebook_default_account" value="<?= $id ?>" /></td>
                <th scope="row"><?= $account['name'] ?> [<?= $account['type'] ?>]</th>
                <td><?= $account['id'] ?></td>
                <td><input type="checkbox" name="_fabs_facebook_delete_accounts[]" value="<?= $account['id'] ?>" /></td>
            </tr>
            <?php } ?>
        </table>
        <?php } ?>
        <p class="submit">
        <input type="submit" value="Save Settings" class="button-primary" />
        </p>
    </form>
</div>
<h3>Sample Feed</h3>
<? fabs_facebook_feed(null, array('limit'=>5)) ?>
<h3>Sample Events</h3>
<? fabs_facebook_events(null, array('limit'=>1)) ?>