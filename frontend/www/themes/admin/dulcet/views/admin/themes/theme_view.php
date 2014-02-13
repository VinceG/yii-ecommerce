<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('Viewing Theme {t}', array('{t}' => $model->name)); ?></div>
		
		<div class="inside">
			<div class="in">
				<div class="grid_12">
					<h3><?php echo at("Root Folder: {r}", array('{r}' => 'themes/' . $model->dirname)); ?></h3>
					<?php
					$this->widget('system.web.widgets.CTreeView', array(
							'collapsed' => true,
							'persist' => 'location',
							'htmlOptions' => array('class' => 'filetree'),
					        'data' => $model->getThemeFilesTree()
					));
					?>
				</div>
				<div class="clear"></div>
			</div>
		</div>

	</div>
</section>
<div class="clear"></div>

<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('Theme Editor Usage'); ?></div>

		<div class="inside">
			<div class="in">
				<div class="grid_12">
					<table width="100%">
						<tr>
							<th>Key</th>
							<th>Result</th>
						</tr>
						<tr>
							<td>Ctrl-F / Cmd-F</td>
							<td>Start searching</td>
						</tr>
						<tr>
							<td>Ctrl-G / Cmd-G</td>
							<td>Find next</td>
						</tr>
						<tr>
							<td>Shift-Ctrl-G / Shift-Cmd-G</td>
							<td>Find previous</td>
						</tr>
						<tr>
							<td>Shift-Ctrl-F / Cmd-Option-F</td>
							<td>Replace</td>
						</tr>
						<tr>
							<td>Shift-Ctrl-R / Shift-Cmd-Option-F</td>
							<td>Replace all</td>
						</tr>
					</table>
				</div>
				<div class="clear"></div>
			</div>
		</div>

	</div>
</section>
<div class="clear"></div>

<section class="grid_12">
	<div class="box">
		<div class="title"><?php echo at('Theme File Editor'); ?></div>

		<div class="inside">
			<div class="in">
				<div class="grid_12">
					<div id='theme_editor_div'>
						<?php echo at('Select a file to see the editor.') ?>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>

	</div>
</section>
<div class="clear"></div>

<input type='hidden' name='current_loaded_theme_file' id='current_loaded_theme_file' value='' />

<?php
$codeMirror = Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.vendors.codemirror'));

// Base
cs()->registerScriptFile($codeMirror . '/lib/codemirror.js', CClientScript::POS_END);
cs()->registerScriptFile($codeMirror . '/mode/xml/xml.js', CClientScript::POS_END);
cs()->registerScriptFile($codeMirror . '/mode/javascript/javascript.js', CClientScript::POS_END);
cs()->registerScriptFile($codeMirror . '/mode/clike/clike.js', CClientScript::POS_END);
cs()->registerScriptFile($codeMirror . '/mode/php/php.js', CClientScript::POS_END);
cs()->registerScriptFile($codeMirror . '/mode/css/css.js', CClientScript::POS_END);
cs()->registerScriptFile($codeMirror . '/mode/htmlmixed/htmlmixed.js', CClientScript::POS_END);

cs()->registerCSSFile($codeMirror . '/lib/codemirror.css');

// Search
cs()->registerScriptFile($codeMirror . '/lib/util/search.js', CClientScript::POS_END);
cs()->registerScriptFile($codeMirror . '/lib/util/searchcursor.js', CClientScript::POS_END);
cs()->registerScriptFile($codeMirror . '/lib/util/dialog.js', CClientScript::POS_END);
cs()->registerCSSFile($codeMirror . '/lib/util/dialog.css');

// formatting
cs()->registerScriptFile($codeMirror . '/lib/util/formatting.js', CClientScript::POS_END);

// overlay
cs()->registerScriptFile($codeMirror . '/lib/util/overlay.js', CClientScript::POS_END);

// match highlight
cs()->registerScriptFile($codeMirror . '/lib/util/match-highlighter.js', CClientScript::POS_END);

cs()->registerScriptFile(themeUrl('js/modules/theme.js'), CClientScript::POS_END);
?>

<style type="text/css">
	.CodeMirror {background-color: #fff;}
  .CodeMirror-scroll {
        height: auto;
        overflow-y: hidden;
        overflow-x: auto;
      }
	.activeline {background: #e8f2ff !important;}
	span.CodeMirror-matchhighlight { background: #e9e9e9 }
	.CodeMirror-focused span.CodeMirror-matchhighlight { background: #e7e4ff; !important }
</style>
