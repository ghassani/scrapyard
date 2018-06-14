$(function() {
	$('.delete_file_confirmation').click(function(e){
		e.preventDefault();
		var url = $(this).attr('target-path');
		var isDir = $(this).attr('is-dir');
		var typeText = isDir ? "directory and all its contents" : "file";
		$("<p>Are you sure you wish to delete this " + typeText + "?</p>").dialog({
			title: 'Confirmation',
			closeOnEscapeType: false,
			modal : true,
			buttons : {
				Yes: function() {
					$( this ).dialog( "close" );
					document.location.href = url;
				},
				No: function() {
					$( this ).dialog( "close" );
				}
			}
		});
	});
});
