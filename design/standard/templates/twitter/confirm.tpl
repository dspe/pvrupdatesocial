{if is_set( $haveToken )}
    {def $twitter_object = social_connect('twitter')
         $info = twitterInfo( $twitter_object.token, $twitter_object.secret )
    }
    <div class="message-feedback">
        You currently logged as: <strong>{$info.screen_name}</strong>
    </div>
    <script type="text/javascript">
    {literal}
    $(document).ready( function() {
        var openerDiv = window.opener.jQuery(".twitter_infos");
        if (openerDiv != null) {
            openerDiv.html( $(".message-feedback").text() );
        }
        window.close();
    });
    {/literal}
    </script>
    {undef $twitter_object $info}
{/if}