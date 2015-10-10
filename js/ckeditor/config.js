/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	
	// %REMOVE_START%
	// The configuration options below are needed when running CKEditor from source files.
	config.plugins = 'dialogui,dialog,a11yhelp,basicstyles,blockquote,clipboard,panel,floatpanel,menu,contextmenu,resize,button,toolbar,elementspath,enterkey,entities,popup,filebrowser,floatingspace,listblock,richcombo,format,horizontalrule,htmlwriter,wysiwygarea,image,indent,indentlist,fakeobjects,link,list,magicline,maximize,pastetext,pastefromword,removeformat,showborders,sourcearea,specialchar,menubutton,scayt,stylescombo,tab,table,tabletools,undo,wsc,youtube,imageuploader';
	config.skin = 'moono';
	// %REMOVE_END%

	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

		config.toolbarGroups = [
			{name: 'clipboard', groups: ['clipboard', 'undo']},
			{name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing']},
			{name: 'links', groups: ['links']},
			{name: 'insert', groups: ['insert']},
			{name: 'forms', groups: ['forms']},
			{name: 'tools', groups: ['tools']},
			{name: 'document', groups: ['mode', 'document', 'doctools']},
			{name: 'others', groups: ['others']},
			'/',
			{name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
			{name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']},
			{name: 'styles', groups: ['styles']},
			{name: 'colors', groups: ['colors']},
			{name: 'about', groups: ['about']}
		];

		config.removeButtons = 'Underline,Cut,Copy,Paste,PasteText,PasteFromWord,SpecialChar,HorizontalRule,About';

};
