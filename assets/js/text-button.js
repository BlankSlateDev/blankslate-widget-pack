(function() {
	//Takes pathname of url, returns key
	var parseIt = function( path ){
		var re = /\/page\/(\w*)\//;

		var match = re.exec( path );
 
		return match[1];
	};

	tinymce.PluginManager.add('blankslate_pages_shortcode_button', function( editor, url ) {
		editor.addButton( 'blankslate_pages_shortcode_button', {
			title: 'BlankSlate Pages',
			icon: 'icon blankslate-icon',
			onclick: function() {
				editor.windowManager.open( {
					title: 'Configure Page Display',
					body: [{
						type: 'textbox',
						name: 'id',
						label: 'Key'
					},
					{
						type: 'listbox', 
						name: 'type', 
						label: 'Type', 
						'values': [
							{text: 'Card', value: 'card'},
							{text: 'Link', value: 'link'}
						]
					},
					{
						type: 'listbox',
						name: 'show_photo',
						label: 'Display Photo (for card display)',
						'values': [
							{text: 'Yes', value: true},
							{text: 'No', value: false}
						]
					},
					{
						type: 'textbox',
						name: 'utm_content',
						label: 'UTM Content'
					}],
					onsubmit: function( e ) {
						var id = e.data.id;

						var keyArray = id.split(',');
						var keyString = '';

						_.each( keyArray, function( element ){
							var key = element;

							var pageUrl = document.createElement('a');
							pageUrl.href = key;

							//If pages url, parse the url for the key, otherwise assume they input a key
							if ( pageUrl.hostname === 'pages.blankslate.com' ){
								key = parseIt( pageUrl.pathname );
							}

							keyString += key + ',';
						});

						//slice off last ,
						keyString = keyString.slice( 0,-1 );

						editor.insertContent( 
							'[blankslate_pages id="' + keyString + '" type="' + e.data.type + '" show_photo="' + e.data.show_photo + '" utm_content="' + e.data.utm_content + '"][/blankslate_pages]');
					}
				});
			}
		});
	});
})();