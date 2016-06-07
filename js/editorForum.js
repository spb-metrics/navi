

function initHtmlEditor(altura) {
	tinyMCE.init({ 
    language: "pt_br", 
	 // ask : "true",
    mode : "textareas",
    theme : "advanced",
	//debug : true,
	focus_alert : true,
	height : altura,
   // plugins: "paste, contextmenu, autosave,ibrowser",
    // Theme specific setting CSS classes
    //theme_advanced_styles : "Header 1=header1;Header 2=header2;Header 3=header3;Table Row=tableRow1", 
    theme_advanced_buttons1: "bold,italic,underline, strikethrough", 
    theme_advanced_buttons2: " sub,sup,separator,tablecontrols,charmap,code,separator,ibrowser,link, unlink",
    theme_advanced_buttons3: ""

 });  
}
