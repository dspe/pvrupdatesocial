<div class="context-block">
<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
		<h1 class="context-title">{'Connect Twitter account:'|i18n( 'extension/twitter' )}</h1>
		<div class="header-subline"></div>
	</div></div></div></div></div></div>

	<div class="box-ml"><div class="box-mr"><div class="box-content">

		<div class="context-toolbar">
			<div class="block"></div>
		</div>

		<div class="content-navigation-childlist">
            {def $twitter_object = social_connect('twitter' ) }
            {if is_null( $twitter_object )}
                <div class="twitter_infos">
				    <a href="#" onclick="window.open('{"layout/set/popin/twitter/redirect"|ezurl(no,full)}', 'Twitter', 'height=400, width=800, top=' + (screen.height-400)/2 + ', left=' + (screen.width-800)/2 + ', toolbar=no, menubar=no, location=no, resizable=no, scrollbars=no, status=no'); return false;">
				    <img src={"./images/lighter.png"|ezdesign()} alt="Sign in with Twitter"/></a>
			    </div>
            {else}
				{def $info = twitterInfo( $twitter_object.token, $twitter_object.secret )}

				{if eq( $info['error'], '' )}
					{"Correctly login on account"|i18n('extension/twitter')} : <strong>{$info.screen_name}</strong>
				{elseif ne( $info['error'], '' )}
					<strong>{"Warning"|i18n('extension/twitter')}:</strong> {$info['error']}
				{/if}
									
				{undef $twitter_object $info}
            {/if}
		</div>

		<div class="context-toolbar">
			<div class="block"></div>
		</div>

	</div></div></div>

	<div class="controlbar">
		<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
			<div class="block"></div>
		</div></div></div></div></div></div>
	</div>

</div>