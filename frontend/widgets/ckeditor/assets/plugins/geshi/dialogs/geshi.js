/*
A Geshi plugin for CKEditor (3.x)

Created by PkLab.net starting from ckeditor-syntaxhighlight 
(http://www.ramble.in/ckeditor/syntaxhighlight/)
 
 For more information about installation and how to manage the code see
 http://www.pklab.net/index.php?id=350
 */
CKEDITOR.dialog.add('geshi', function(editor)
{    
    var parseHtml = function(htmlString) {
        htmlString = htmlString.replace(/<br>/g, '\n');
        htmlString = htmlString.replace(/&amp;/g, '&');
        htmlString = htmlString.replace(/&lt;/g, '<');
        htmlString = htmlString.replace(/&gt;/g, '>');
        htmlString = htmlString.replace(/&quot;/g, '"');
        return htmlString;
    }
    
    var getDefaultOptions = function(options) {
        var options = new Object();
        options.hideLineNum = false;
        options.firstLineChecked = false;
        options.firstLine = 0;
        options.highlightChecked = false;
        options.highlight = null;
        options.lang = null;
        options.code = '';
        return options;
    }
    
    var getOptionsForString = function(optionsString) {
        var options = getDefaultOptions();
        if (optionsString) {
            if (optionsString.indexOf("geshi") > -1) {
                var match = /geshi:[ ]{0,1}(\w*)/.exec(optionsString);
                if (match != null && match.length > 0) {
                    options.lang = match[1].replace(/^\s+|\s+$/g, "");
                }
            }
            
            if (optionsString.indexOf("line_num") > -1)
                options.hideLineNum = true;

            if (optionsString.indexOf("first-line") > -1) {
                var match = /first-line:[ ]{0,1}([0-9]{1,4})/.exec(optionsString);
                if (match != null && match.length > 0 && match[1] > 1) {
                    options.firstLineChecked = true;
                    options.firstLine = match[1];
                }
            }
            
            if (optionsString.indexOf("highlight") > -1) {
                // make sure we have a comma-seperated list
                if (optionsString.match(/highlight:[ ]{0,1}\[[0-9]+(,[0-9]+)*\]/)) {
                    // now grab the list
                    var match_hl = /highlight:[ ]{0,1}\[(.*)\]/.exec(optionsString);
                    if (match_hl != null && match_hl.length > 0) {
                        options.highlightChecked = true;
                        options.highlight = match_hl[1];
                    }
                }
            }

        }
        return options;
    }
    
    var getStringForOptions = function(optionsObject) {
        var result = 'geshi:' + optionsObject.lang + ';';
        if (optionsObject.hideLineNum)
            result += 'line_num:false;';
        if (optionsObject.firstLineChecked && optionsObject.firstLine > 1)
            result += 'first-line:' + optionsObject.firstLine + ';';
        if (optionsObject.highlightChecked && optionsObject.highlight != '')
            result += 'highlight: [' + optionsObject.highlight.replace(/\s/gi, '') + '];';
        return result;
    }
    
    return {
        title: editor.lang.geshi.title,
        minWidth: 500,
        minHeight: 400,
        onShow: function() {
            // Try to grab the selected pre tag if any
            var editor = this.getParentEditor();
            var selection = editor.getSelection();
            var element = selection.getStartElement();
            var preElement = element && element.getAscendant('pre', true);
            
            // Set the content for the textarea
            var text = '';
            var optionsObj = null;
            if (preElement) {
                code = parseHtml(preElement.getHtml());
                optionsObj = getOptionsForString(preElement.getAttribute('class'));
                optionsObj.code = code;
            } else {
                optionsObj = getDefaultOptions();
            }
            this.setupContent(optionsObj);
        },
        onOk: function() {
            var editor = this.getParentEditor();
            var selection = editor.getSelection();
            var element = selection.getStartElement();
            var preElement = element && element.getAscendant('pre', true);
            var data = getDefaultOptions();
            this.commitContent(data);
            var optionsString = getStringForOptions(data);
            
            if (preElement) {
                preElement.setAttribute('class', optionsString);
                preElement.setText(data.code);
            } else {
                var newElement = new CKEDITOR.dom.element('pre');
                newElement.setAttribute('class', optionsString);
                newElement.setText(data.code);
                editor.insertElement(newElement);
            }
        },
        contents : [
            {
                id : 'source',
                label : editor.lang.geshi.sourceTab,
                accessKey : 'S',
                elements :
                [
                    {
                        type : 'vbox',
                        children: [
                          {
                              id: 'cmbLang',
                              type: 'select',
                              labelLayout: 'horizontal',
                              label: editor.lang.geshi.langLbl,
                              'default': 'java',
                              widths : [ '25%','75%' ],
                              items: [
                                       [ 'abap' , 'abap' ],
                                       [ 'actionscript' , 'actionscript' ],
                                       [ 'ada' , 'ada' ],
                                       [ 'apache' , 'apache' ],
                                       [ 'asm' , 'asm' ],
                                       [ 'asp' , 'asp' ],
                                       [ 'bash' , 'bash' ],
                                       [ 'bf' , 'bf' ],
                                       [ 'c' , 'c' ],
                                       [ 'c_mac' , 'c_mac' ],
                                       [ 'caddcl' , 'caddcl' ],
                                       [ 'cadlisp' , 'cadlisp' ],
                                       [ 'cdfg' , 'cdfg' ],
                                       [ 'cobol' , 'cobol' ],
                                       [ 'cpp' , 'cpp' ],
                                       [ 'csharp' , 'csharp' ],
                                       [ 'css' , 'css' ],
                                       [ 'd' , 'd' ],
                                       [ 'delphi' , 'delphi' ],
                                       [ 'diff' , 'diff' ],
                                       [ 'dos' , 'dos' ],
                                       [ 'gdb' , 'gdb' ],
                                       [ 'gettext' , 'gettext' ],
                                       [ 'gml' , 'gml' ],
                                       [ 'gnuplot' , 'gnuplot' ],
                                       [ 'groovy' , 'groovy' ],
                                       [ 'haskell' , 'haskell' ],
                                       [ 'html4strict' , 'html4strict' ],
                                       [ 'ini' , 'ini' ],
                                       [ 'java' , 'java' ],
                                       [ 'javascript' , 'javascript' ],
                                       [ 'klonec' , 'klonec' ],
                                       [ 'klonecpp' , 'klonecpp' ],
                                       [ 'latex' , 'latex' ],
                                       [ 'lisp' , 'lisp' ],
                                       [ 'lua' , 'lua' ],
                                       [ 'matlab' , 'matlab' ],
                                       [ 'mpasm' , 'mpasm' ],
                                       [ 'mysql' , 'mysql' ],
                                       [ 'nsis' , 'nsis' ],
                                       [ 'objc' , 'objc' ],
                                       [ 'oobas' , 'oobas' ],
                                       [ 'oracle8' , 'oracle8' ],
                                       [ 'oracle10' , 'oracle10' ],
                                       [ 'pascal' , 'pascal' ],
                                       [ 'perl' , 'perl' ],
                                       [ 'php' , 'php' ],
                                       [ 'povray' , 'povray' ],
                                       [ 'providex' , 'providex' ],
                                       [ 'prolog' , 'prolog' ],
                                       [ 'python' , 'python' ],
                                       [ 'qbasic' , 'qbasic' ],
                                       [ 'reg' , 'reg' ],
                                       [ 'ruby' , 'ruby' ],
                                       [ 'sas' , 'sas' ],
                                       [ 'scala' , 'scala' ],
                                       [ 'scheme' , 'scheme' ],
                                       [ 'scilab' , 'scilab' ],
                                       [ 'smalltalk' , 'smalltalk' ],
                                       [ 'smarty' , 'smarty' ],
                                       [ 'tcl' , 'tcl' ],
                                       [ 'vb' , 'vb' ],
                                       [ 'vbnet' , 'vbnet' ],
                                       [ 'visualfoxpro' , 'visualfoxpro' ],
                                       [ 'whitespace' , 'whitespace' ],
                                       [ 'xml' , 'xml' ],
                                       [ 'z80' , 'z80' ]                              
                                     ],
                              setup: function(data) {
                                  if (data.lang)
                                      this.setValue(data.lang);
                              },
                              commit: function(data) {
                                  data.lang = this.getValue();
                              }
                          }
                        ]
                    },
                    {
                        type: 'textarea',
                        id: 'hl_code',
                        rows: 22,
                        style: "width: 100%",
                        setup: function(data) {
                            if (data.code)
                                this.setValue(data.code);
                        },
                        commit: function(data) {
                            data.code = this.getValue();
                        }
                    }
                ]
            },
            {
                id : 'advanced',
                label : editor.lang.geshi.advancedTab,
                accessKey : 'A',
                elements :
                [
                    {
                        type : 'vbox',
                        children: [
                          {
                              type: 'html',
                              html: '<strong>' + editor.lang.geshi.hideLineNum + '</strong>'
                          },
                          {
                              type: 'checkbox',
                              id: 'hide_line_num',
                              label: editor.lang.geshi.hideLineNumLbl,
                              setup: function(data) {
                                  this.setValue(data.hideLineNum)
                              },
                              commit: function(data) {
                                  data.hideLineNum = this.getValue();
                              }
                          },
                          {
                              type: 'html',
                              html: '<strong>' + editor.lang.geshi.highlight + '</strong>'
                          },
                          {
                              type: 'hbox',
                              widths: [ '5%', '95%' ],
                              children: [
                                 {
                                     type: 'checkbox',
                                     id: 'hl_toggle',
                                     label: '',
                                     setup: function(data) {
                                         this.setValue(data.highlightChecked)
                                     },
                                     commit: function(data) {
                                         data.highlightChecked = this.getValue();
                                     }
                                 },
                                 {
                                     type: 'text',
                                     id: 'default_hl',
                                     style: 'width: 40%;',
                                     label: '',
                                     setup: function(data) {
                                         if (data.highlight != null)
                                             this.setValue(data.highlight);
                                     },
                                     commit: function(data) {
                                         if (this.getValue() && this.getValue() != '')
                                             data.highlight = this.getValue();
                                     }
                                 }
                              ]
                          },
                          {
                              type: 'hbox',
                              widths: [ '5%', '95%' ],
                              children: [
                                  {type: 'html', html: ''},
                                  {
                                      type: 'html',
                                      html: '<i>' + editor.lang.geshi.highlightLbl + '</i>'
                                  }
                              ]
                          }
                        ]
                    }
                ]
            }
        ]
    };
});
