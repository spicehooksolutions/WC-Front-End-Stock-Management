function wfesm_change_state(pid) {

    var data = {
        'action': 'wfesm_st_update',
        'wfesm_pid': pid
    };

    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(ajax_object.ajax_url, data, function(response) {
        jQuery('#td-' + pid).html(response);
    });
}