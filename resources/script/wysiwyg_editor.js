function Load_Wysiwyg_Editor(id,toolbar,plugins,options) {
	/*
	toolbar = toolbar || [
		"undo redo",
		"bold italic underline",
		"styleselect forecolor backcolor",
		"alignleft aligncenter alignright alignjustify",
		"bullist numlist outdent indent",
		"link unlink",
		"image media filemanager",
		"preview code fullscreen"
	].join(' | ');
	*/
	toolbar = toolbar || [
		"bold italic underline",
		"forecolor backcolor",
		"alignleft aligncenter alignright",
		"bullist numlist outdent indent",
		"link unlink image media filemanager"
	].join(' | ');
	/*
	plugins = plugins || [ 
		"advlist autolink autoresize link image lists charmap print preview hr anchor pagebreak spellchecker",
		"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
		"save table contextmenu directionality emoticons template paste textcolor filemanager"
	];
	*/
	plugins = plugins || [ 
		"autoresize link image lists charmap hr pagebreak",
		"searchreplace insertdatetime media",
		"table contextmenu paste textcolor filemanager"
	];
	options = options || {
		script_url : base_uri+'contribute/tinymce/js/tinymce/tinymce.min.js',
		content_css : base_uri+'resources/css/js-tinymce.css',
		language : 'ko_KR',
		theme : "modern",
		subfolder: "",
		plugins: plugins,
		external_filemanager_path:base_uri+'contribute/filemanager/filemanager/',
		external_plugins: {"filemanager":base_uri+"contribute/filemanager/filemanager/plugin.min.js"},
		image_advtab: true,
		menubar: false,
		setup: function (editor) {
			editor.on('change', function () {
				tinymce.triggerSave();
			});
		},
		toolbar: toolbar
	};

	$.getScript(base_uri+'contribute/tinymce/js/tinymce/jquery.tinymce.min.js',function(){
		$('textarea#'+id).tinymce(options);
    });
}
