<?php
/*
*	Uncolored by Michael Kaufmann
*	Copyright 2012 - 2013 (c) - C3ltaX Media
*	@Developed for Wikibyte Cloud Network
*	@Version 5.1.23
*/
if( !defined( 'MEDIAWIKI' ) ) {
	die( -1 );
}

class SkinUncolored extends SkinTemplate {

	var $skinname = 'uncolored', $stylename = 'uncolored',
		$template = 'UncoloredTemplate', $useHeadElement = true;

	public function initPage( OutputPage $out ) {
		global $wgLocalStylePath;

		parent::initPage( $out );
			
		$out->addHeadItem('personal.css','<style type="text/css">.pill{border-radius:200em;}.pop{box-shadow:0px 1px 5px rgba(0,0,0,0.2);}.inset{box-shadow:inset 0px 1px 3px rgba(0,0,0,0.3);}.square{-moz-border-radius:0;-webkit-border-radius:0;-o-border-radius:0;border-radius:0;}</style>' );
		$out->addHeadItem('buttons.css', '<link rel="stylesheet" type="text/css" href="' .$wgLocalStylePath. '/uncolored/buttons.css">');
	    $out->addHeadItem('screen.css', '<link rel="stylesheet" type="text/css" href="' .$wgLocalStylePath. '/uncolored/screen.css">');
		
		#(fixed-#293450)
		$out->addHeadItem("uncolored.js",'<script type="text/javascript" src="' .$wgLocalStylePath. '/uncolored/uncolored.js"></script>');
		
		#new 3.2.21
		//$out->addHeadItem("tiptip.js",'<script type="text/javascript" src="' .$wgLocalStylePath. '/uncolored/js/tiptip.js"></script>');
	    //$out->addHeadItem('tiptip.css', '<link rel="stylesheet" type="text/css" href="' .$wgLocalStylePath. '/uncolored/css/tiptip.css">');
       
	}
}

class UncoloredTemplate extends BaseTemplate {

	var $skin;

	public function execute() {
		global $wgLang, $wguncoloredUseIconWatch;

		$this->skin = $this->data['skin'];
		$nav = $this->data['content_navigation'];

		if ( $wguncoloredUseIconWatch ) {
			$mode = $this->skin->getTitle()->userIsWatching() ? 'unwatch' : 'watch';
			if ( isset( $nav['actions'][$mode] ) ) {
				$nav['views'][$mode] = $nav['actions'][$mode];
				$nav['views'][$mode]['class'] = rtrim( 'icon ' . $nav['views'][$mode]['class'], ' ' );
				$nav['views'][$mode]['primary'] = true;
				unset( $nav['actions'][$mode] );
			}
		}

		$xmlID = '';
		foreach ( $nav as $section => $links ) {
			foreach ( $links as $key => $link ) {
				if ( $section == 'views' && !( isset( $link['primary'] ) && $link['primary'] ) ) {
					$link['class'] = rtrim( 'collapsible ' . $link['class'], ' ' );
				}

				$xmlID = isset( $link['id'] ) ? $link['id'] : 'ca-' . $xmlID;
				$nav[$section][$key]['attributes'] =
					' id="' . Sanitizer::escapeId( $xmlID ) . '"';
				if ( $link['class'] ) {
					$nav[$section][$key]['attributes'] .=
						' class="' . htmlspecialchars( $link['class'] ) . '"';
					unset( $nav[$section][$key]['class'] );
				}
				if ( isset( $link['tooltiponly'] ) && $link['tooltiponly'] ) {
					$nav[$section][$key]['key'] =
						Linker::tooltip( $xmlID );
				} else {
					$nav[$section][$key]['key'] =
						Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( $xmlID ) );
				}
			}
		}
		$this->data['namespace_urls'] = $nav['namespaces'];
		$this->data['view_urls'] = $nav['views'];
		$this->data['action_urls'] = $nav['actions'];
		$this->data['variant_urls'] = $nav['variants'];

		if ( $wgLang->isRTL() ) {
			$this->data['view_urls'] =
				array_reverse( $this->data['view_urls'] );
			$this->data['namespace_urls'] =
				array_reverse( $this->data['namespace_urls'] );
			$this->data['personal_urls'] =
				array_reverse( $this->data['personal_urls'] );
		}
		$this->html( 'headelement' );
?>
<div id="mw-head-base" class="noprint"></div>
<div id="right-navigation"></div>
		<div id="content">
			<a id="top"></a>
			<div id="mw-js-message" style="display:none;"<?php $this->html( 'userlangattributes' ) ?>></div>
			<?php if ( $this->data['sitenotice'] ): ?>
			<!-- sitenotice -->
			<div id="siteNotice"><?php $this->html( 'sitenotice' ) ?></div>
			<!-- /sitenotice -->
			<?php endif; ?>
			<!-- firstHeading -->
			<h1 id="firstHeading" class="firstHeading"><?php $this->html( 'title' ) ?></h1>
			<!-- /firstHeading -->
			<!-- bodyContent -->
			<div id="bodyContent">
				<?php if ( $this->data['isarticle'] ): ?>
				<!-- tagline -->
				<div id="siteSub"><?php $this->msg( 'tagline' ) ?></div>
				<!-- /tagline -->
				<?php endif; ?>
				<!-- subtitle -->
				<div id="contentSub"<?php $this->html( 'userlangattributes' ) ?>><?php $this->html( 'subtitle' ) ?></div>
				<!-- /subtitle -->
				<?php if ( $this->data['undelete'] ): ?>
				<!-- undelete -->
				<div id="contentSub2"><?php $this->html( 'undelete' ) ?></div>
				<!-- /undelete -->
				<?php endif; ?>
				<?php if( $this->data['newtalk'] ): ?>
				<!-- newtalk -->
				<div class="usermessage"><?php $this->html( 'newtalk' )  ?></div>
				<!-- /newtalk -->
				<?php endif; ?>
				<?php if ( $this->data['showjumplinks'] ): ?>
				<!-- jumpto -->
				<div id="jump-to-nav">
					<?php $this->msg( 'jumpto' ) ?> <a href="#mw-head"><?php $this->msg( 'jumptonavigation' ) ?></a>,
					<a href="#p-search"><?php $this->msg( 'jumptosearch' ) ?></a>
				</div>
				<!-- /jumpto -->
				<?php endif; ?>
				<!-- bodycontent -->
				<?php $this->html( 'bodycontent' ) ?>
				<!-- /bodycontent -->
				<?php if ( $this->data['printfooter'] ): ?>
				<!-- printfooter -->
				<div class="printfooter">
				<?php $this->html( 'printfooter' ); ?>
				</div>
				<!-- /printfooter -->
				<?php endif; ?>
				<?php if ( $this->data['catlinks'] ): ?>
				
				<?php endif; ?>
				<?php if ( $this->data['dataAfterContent'] ): ?>
				<!-- dataAfterContent -->
				<?php $this->html( 'dataAfterContent' ); ?>
				<!-- /dataAfterContent -->
				<?php endif; ?>
				<div class="visualClear"></div>
				<!-- debughtml -->
				<?php $this->html( 'debughtml' ); ?>
				<!-- /debughtml -->
			</div>
			<!-- /bodyContent -->
		</div>
		<!-- /content -->
<!-- panel -->
<div id="mw-panel" class="noprint">
				<!-- logo -->
					<div id="p-logo"><a style="background-image: url(<?php /*$this->text( 'logopath' ) ?>);" href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ) ?>" <?php echo Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( 'p-logo' ) ) */?>></a></div>
				<!-- /logo -->				
<!-- NAVIGATION -->				
<?php $this->renderPortals( $this->data['sidebar'] ); ?>
<?php if($this->data['loggedin']) { ?>
<div id="p-navigation" class="portal" >
<h5 id="Sitenavi">Seiteninformationen</h5>
<div id="Sitenavi" class="body"><ul>
<?php foreach( $this->getFooterLinks() as $category => $links ): ?>
<?php foreach( $links as $link ): ?>
<li style="width:136px; color:#999;" id="siteinfos"><?php $this->html( $link ) ?></li>
<?php endforeach; ?>
<?php endforeach; ?>
</ul>
</div>
<?php } ?>
<?php $this->html( 'catlinks' ); ?>
</div>	
<div id="userline">
<div id="logosmall"><img src="" alt=""></div>
<?php 
	$this->renderNavigation( 'PERSONAL' ); 
	$this->renderNavigation( 'SEARCH' ); 
?>
<div style="float:left; padding-top:2px; width: 511px;">
<?php 
if($this->data['loggedin']) { 
	$this->renderNavigation( array( 'NAMESPACES' ) ); 
	$this->renderNavigation( array( 'VIEWS' ) ); 
	$this->renderNavigation( array( 'VARIANTS', 'ACTIONS' ) ); 
}
?>
</div>
	<div class="clear"></div>
</div>
		
<!-- footer -->
<div id="footer"<?php $this->html( 'userlangattributes' ) ?>></div>
		<!-- /footer -->
		<!-- fixalpha -->
		<script type="<?php $this->text( 'jsmimetype' ) ?>"> if ( window.isMSIE55 ) fixalpha(); </script>
		<!-- /fixalpha -->
		<?php $this->printTrail(); ?>
	</body>
</html>
<?php
	}
	private function renderPortals( $portals ) {

		if ( !isset( $portals['SEARCH'] ) ) {
			$portals['SEARCH'] = true;
		}
		if ( !isset( $portals['TOOLBOX'] ) ) {
			$portals['TOOLBOX'] = true;
		}
		if ( !isset( $portals['LANGUAGES'] ) ) {
			$portals['LANGUAGES'] = true;
		}

		foreach ( $portals as $name => $content ) {
			if ( $content === false )
				continue;

			echo "\n<!-- {$name} -->\n";
			switch( $name ) {
				case 'SEARCH':
					break;
				case 'TOOLBOX':
				
		
    		if($this->data['loggedin']) { 
				$this->renderPortal( 'tb', $this->getToolbox(), 'toolbox', 'SkinTemplateToolboxEnd' ); 	
			}
					break;				
				case 'LANGUAGES':
					if ( $this->data['language_urls'] ) {
						$this->renderPortal( 'lang', $this->data['language_urls'], 'otherlanguages' );
					}
					break;
				default:
					$this->renderPortal( $name, $content );
				break;
			}
			echo "\n<!-- /{$name} -->\n";
		}
	}

	private function renderPortal( $name, $content, $msg = null, $hook = null ) {
		if ( !isset( $msg ) ) {
			$msg = $name;
		}
		?>
<div class="portal" id='<?php echo Sanitizer::escapeId( "p-$name" ) ?>'<?php echo Linker::tooltip( 'p-' . $name ) ?>>
	<h5<?php $this->html( 'userlangattributes' ) ?>><?php $msgObj = wfMessage( $msg ); echo htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $msg ); ?></h5>
	<div class="body">
<?php
		if ( is_array( $content ) ): ?>
		<ul>
<?php
			foreach( $content as $key => $val ): ?>
			<?php echo $this->makeListItem( $key, $val ); ?>

<?php
			endforeach;
			if ( isset( $hook ) ) {
				wfRunHooks( $hook, array( &$this, true ) );
			}
			?>
		</ul>
<?php
		else: ?>
		<?php echo $content; ?>
<?php
		endif; ?>
	</div>
</div>
<?php
	}

	/**
	 * Render one or more navigations elements by name, automatically reveresed
	 * when UI is in RTL mode
	 */
	private function renderNavigation( $elements ) {
		global $wguncoloredUseSimpleSearch, $wguncoloredShowVariantName, $wgUser, $wgLang;

		// If only one element was given, wrap it in an array, allowing more
		// flexible arguments
		if ( !is_array( $elements ) ) {
			$elements = array( $elements );
		// If there's a series of elements, reverse them when in RTL mode
		} elseif ( $wgLang->isRTL() ) {
			$elements = array_reverse( $elements );
		}
		// Render elements
		foreach ( $elements as $name => $element ) {
			echo "\n<!-- {$name} -->\n";
			switch ( $element ) {
				case 'NAMESPACES':
?>
<?php foreach ( $this->data['namespace_urls'] as $link ): ?>
<a class="button small inset" href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>>
<span <?php echo $link['attributes'] ?>><?php echo htmlspecialchars( $link['text'] ) ?></span>
</a>
<?php endforeach; ?>
<?php
				break;
				case 'VARIANTS':
?>
<?php if ( $wguncoloredShowVariantName ): ?></li>
	<?php endif; ?>
	<?php foreach ( $this->data['variant_urls'] as $link ): ?>
	<a class="button pill" href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><?php echo htmlspecialchars( $link['text'] ) ?></a>
<?php endforeach; ?>

<?php
				break;
				case 'VIEWS':
?>
<?php foreach ( $this->data['view_urls'] as $link ): ?>

<a <?php echo $link['attributes'] ?> href="<?php echo htmlspecialchars( $link['href'] ) ?>" >
<span <?php echo $link['attributes'] ?>>
	<?php
				// $link['text'] can be undefined - bug 27764
				if ( array_key_exists( 'text', $link ) ) {
					echo array_key_exists( 'img', $link ) ?  '<img src="' . $link['img'] . '" alt="' . $link['text'] . '" />' : htmlspecialchars( $link['text'] );
				}
				?>		</span>	
			
</a>
<?php endforeach; ?>

<?php
				break;
				case 'ACTIONS':
?>

<?php foreach ( $this->data['action_urls'] as $link ): ?>
	<a <?php echo $link['attributes'] ?> href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>>
		<span <?php echo $link['attributes'] ?>>
		<?php echo htmlspecialchars( $link['text'] ) ?></span>
	</a>
<?php endforeach; ?>


<?php
				break;
				case 'PERSONAL':
?>
<div id="p-personal" class="uncolored<?php if ( count( $this->data['personal_urls'] ) == 0 ) echo ' emptyPortlet'; ?>">
<span<?php #User einlogen schaltet Bild um auf blau
	if($this->data['loggedin']) { ?>
	id="onloged"<?php } ?>></span>
	<h5>
		<span>
			<?php $this->msg( 'personaltools' ) ?>
		</span>
		<a href="#"></a>
	</h5>
  <div class="menu">
	<ul<?php $this->html( 'userlangattributes' ) ?>>
		<?php foreach( $this->getPersonalTools() as $key => $item ) { ?>
		<?php echo $this->makeListItem( $key, $item ); ?>
		<?php } ?>
	</ul>
  </div>
</div>
<?php
				break;
				case 'SEARCH':
?>
<div id="p-search">
	<form method="get" id="searchform" action="<?php $this->text('searchaction') ?>">
	<span style="padding-top: 8px;;" class="info blue" data-icon="s"></span>
		<input name="search" type="text"  placeholder="Suche..." onfocus="if (this.value == '') {this.value = ''}" name="s" id="s" /> 
			<input name="go" type="hidden" tabindex="5" value="Go" />
	</form>
</div>
<?php

				break;
			}
			echo "\n<!-- /{$name} -->\n";
		}
	}
}