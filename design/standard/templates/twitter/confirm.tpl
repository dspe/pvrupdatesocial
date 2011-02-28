{if is_set( $haveToken )}
	{def $token 	= ezini( 'TwitterToken', 'Token', 'twittertoken.ini' )
		 $secret 	= ezini( 'TwitterToken', 'Secret', 'twittertoken.ini' ) 
		 $info = twitterInfo( $token, $secret )
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
	
	{undef $token $secret $info}
{if}