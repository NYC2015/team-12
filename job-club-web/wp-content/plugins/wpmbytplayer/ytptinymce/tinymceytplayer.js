(function() {

    window.tinyPopUp = {};

      tinymce.create('tinymce.plugins.YTPlayer', {

        init : function(ed, url) {
        	var popUpURL = url + '/ytplayertinymce.php';

			ed.addCommand('YTPlayerpopup', function() {
				ed.windowManager.open({
					url : popUpURL,
					width : 680,
					height :  jQuery(window).height(),
					inline : 1
				});
			});

			ed.addButton('YTPlayerbutton', {
				title : 'Add a YTPlayer',
				image : url + '/ytplayerbutton.svg',
				cmd : 'YTPlayerpopup'
			});
		},

		createControl : function() {
            return null;
        },

		getInfo : function() {
            return {};
        }
    });
    tinymce.PluginManager.add('YTPlayer', tinymce.plugins.YTPlayer);
}());
