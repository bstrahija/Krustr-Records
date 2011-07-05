( function(){
	
	var kr_media = function(editor){
		return {
			buttons:[{
			    type:'button',
			    id:'someButtonID', /* note: this is not the CSS ID attribute! */
			    label: 'Button',
			    onClick: function(){
			       //action on clicking the button
			    }
			},CKEDITOR.dialog.okButton, CKEDITOR.dialog.cancelButton],
		}
	}
	
	CKEDITOR.dialog.add('insertHTML', function(editor) {
		return exampleDialog(editor);
	});
		
})();