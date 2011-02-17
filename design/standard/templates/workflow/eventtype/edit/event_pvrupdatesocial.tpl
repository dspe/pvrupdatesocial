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
			{if ezini( 'TwitterToken', 'Token', 'twittertoken.ini' )}
				{def $token 	= ezini( 'TwitterToken', 'Token', 'twittertoken.ini' )
					 $secret 	= ezini( 'TwitterToken', 'Secret', 'twittertoken.ini' ) 
				}
				{def $info = twitterInfo( $token, $secret )}
				{"Correctly login on account"|i18n('extension/twitter')} : <strong>{$info.screen_name}</strong>
				
				{undef $token $secret $info}
			{else}
				<a href="./redirect.php"><img src={"./images/lighter.png"|ezdesign()} alt="Sign in with Twitter"/></a>
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