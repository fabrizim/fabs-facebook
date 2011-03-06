<?php

function fabs_facebook_shortcode_feed($attrs)
{
    $uid = isset($attrs['uid']) ? $attrs['uid'] : null;
    fabs_facebook_feed($uid, $attrs);
}
add_shortcode('fabs_facebook_feed', 'fabs_facebook_shortcode_feed');

function fabs_facebook_shortcode_events($attrs)
{
    $uid = isset($attrs['uid']) ? $attrs['uid'] : null;
    fabs_facebook_events($uid, $attrs);
}
add_shortcode('fabs_facebook_events', 'fabs_facebook_shortcode_events');

function fabs_facebook_shortcode_albums($attrs)
{
    $uid = isset($attrs['uid']) ? $attrs['uid'] : null;
    fabs_facebook_albums($uid, $attrs);
}
add_shortcode('fabs_facebook_albums', 'fabs_facebook_shortcode_albums');