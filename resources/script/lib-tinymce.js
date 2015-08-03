function Load_Wysiwyg_Editor(id,toolbar,plugins,options) {
	toolbar = toolbar || tinymceOptions.toolbar;
	plugins = plugins || tinymceOptions.plugins;
	options = options || {
		language: tinymceOptions.language,
		theme: tinymceOptions.theme,
		toolbar: toolbar,
		plugins: plugins,
		menubar: tinymceOptions.menubar,
		image_advtab: tinymceOptions.image_advtab,

		subfolder: "",
		external_filemanager_path:base_uri+'contribute/filemanager/filemanager/',
		external_plugins: {
			"filemanager": base_uri+"contribute/filemanager/filemanager/plugin.min.js"
		},

		jquery_script_url: base_uri+'contribute/tinymce/js/tinymce/jquery.tinymce.min.js',
		script_url: base_uri+'contribute/tinymce/js/tinymce/tinymce.min.js',
		content_css: base_uri+'resources/css/lib-tinymce.css',
		setup: function (editor) {
			editor.on('change', function () {
				tinymce.triggerSave();
			});
		}
	};

	$.getScript(options.jquery_script_url,function(){
		$('textarea#'+id).tinymce(options);
    });
}
