<script>
    jQuery.ajaxSetup({
        beforeSend: function(jqXHR, settings) {
            if (settings.data && typeof settings.data === 'string' && settings.data.includes("oxygen_edit_post_lock_transient")) {
                console.log("Edit locking is disabled");
                jqXHR.abort();
            }
        }
    });
</script>