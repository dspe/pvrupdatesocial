{ezscript_require( array( 'ezjsc::jquery' ) )}

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
		{*
			-> Si le fichier .ini n existe pas 
				alors
					on affiche le bouton ...
					on lance la popin ...
					si ok on ferme popin puis affiche donné compte
				sinon
					on charge les infos du compte 
		*}
		
			{if ezini_exists( 'twittertoken.ini', 'settings/override' )}
				{def $token 	= ezini( 'TwitterToken', 'Token', 'twittertoken.ini' )
					 $secret 	= ezini( 'TwitterToken', 'Secret', 'twittertoken.ini' ) 
				}
				{def $info = twitterInfo( $token, $secret )}
				{"Correctly login on account"|i18n('extension/twitter')} : <strong>{$info.screen_name}</strong>
				
				{undef $token $secret $info}
			{else}
				<a href={"twitter/redirect"|ezurl()}><img src={"./images/lighter.png"|ezdesign()} alt="Sign in with Twitter"/></a>
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