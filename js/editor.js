

function checar(){
 alert("carol");
}

function initHtmlEditor(altura) {
	tinyMCE.init({ 
    language: "pt_br", 
	 // ask : "true",
    mode : "textareas",
    theme : "advanced",
	//debug : true,
	focus_alert : true,
	height : altura,
    plugins: "paste, contextmenu, autosave,ibrowser",
    // Theme specific setting CSS classes
    theme_advanced_styles : "Header 1=header1;Header 2=header2;Header 3=header3;Table Row=tableRow1", 
    theme_advanced_buttons1: "bold,italic,underline, strikethrough, separator, justifyleft, justifycenter, justifyright, justifyfull, separator, styleselect, formatselect, fontselect,fontsizeselect, forecolor, backcolor", 
    theme_advanced_buttons2: "cut, copy, paste, pastetext, pasteword, selectall, undo, redo, separator, bullist, numlist, hr, code, separator, ibrowser,link, unlink",
    theme_advanced_buttons3: "removeformat,separator,sub,sup,separator,tablecontrols,charmap,cleanup,help"
//link, unlink, 
 });  
}
