/*
 * BBCode Plugin v1.0 for CKEditor - http://www.site-top.com/
 * Copyright (C) 2010 PitBult
 *
 * == BEGIN LICENSE ==
 *
 * Licensed under the terms of any of the following licenses at your
 * choice:
 *
 *  - GNU General Public License Version 2 or later (the "GPL")
 *    http://www.gnu.org/licenses/gpl.html
 *
 *  - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 *    http://www.gnu.org/licenses/lgpl.html
 *
 *  - Mozilla Public License Version 1.1 or later (the "MPL")
 *    http://www.mozilla.org/MPL/MPL-1.1.html
 *
 * == END LICENSE ==
 *
 * Sample custom configuration settings used by the BBCode plugin. It simply
 * loads the plugin. All the rest is done by the plugin itself.
 */

 // Toolbar config
CKEDITOR.config.toolbar_Full = [
	['Source'],
	['Undo','Redo'],
	['Bold','Italic','Underline','-','Link', 'Unlink'], 
	['Blockquote', 'TextColor', 'Image'],
	['SelectAll', 'RemoveFormat']
] ;

// Add the BBCode plugin.
CKEDITOR.config.extraPlugins = 'bbcode';