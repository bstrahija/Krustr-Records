/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	config.toolbar         = 'Krustr';
	config.resize_maxWidth = "100%";
	config.skin            = 'chris';
	//config.extraPlugins    = 'mediaembed';
	config.forcePasteAsPlainText = true;
	config.pasteFromWordRemoveStyles = true;

	config.toolbar_Krustr  =
	[
		['Preview','-','Bold','Italic','-','JustifyLeft','JustifyCenter','JustifyRight','-','NumberedList','BulletedList','-'],
		['Link','Unlink','-','MediaEmbed','Table','Smiley','RemoveFormat','-'],
		['PasteText','PasteFromWord','CleanUp','-','Source']
		
		/*['NewPage','Preview'],['Bold','Italic','Strike'],
		['JustifyLeft','JustifyCenter','JustifyRight','-','NumberedList','BulletedList','Blockquote'],
		['Link','Unlink'],['Image','Flash','Table','Smiley','RemoveFormat'],
		'/',
		['Format','-','Cut','Copy','Paste','PasteText','PasteFromWord','CleanUp'],['Maximize'],['Styles','ShowBlocks','Source']
		
        /*['NewPage','Preview'],
        ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Scayt'],
        ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
        ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
        '/',
        ['Styles','Format'],
        ['Bold','Italic','Strike'],
        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
        ['Link','Unlink','Anchor'],
        ['Maximize','-','About']*/
    ];
};
