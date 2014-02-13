<?php
/**
 * Theme Model
 */
class Theme extends ActiveRecord
{
	/**
	 * @return object
	 */
	public static function model($class=__CLASS__)
	{
		return parent::model($class);
	}
	
	/**
	 * @return string Table name
	 */
	public function tableName()
	{
		return 'theme';
	}
	
	/**
	 * Attribute values
	 *
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'name' => at('Theme Name'),
			'dirname' => at('Theme Directory Name'),
			'is_active' => at('Theme Active'),
			'author' => at('Theme Author Name'),
			'author_site' => at('Theme Author Site'),
		);
	}
	
	/**
	 * @return array - list of theme names indexed by theme dirname
	 */
	public function getThemesByDirname() {
		$rows = Theme::model()->isActive()->findAll();
		$list = array('' => '-- Default --');
		foreach($rows as $row) {
			$list[$row->dirname] = $row->name;
		}
		return $list;
	}
	
	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'created_at',
				'updateAttribute' => null,
				'setUpdateOnCreate' => false,
			),
		);
	}
	
	/**
	 * Before save operations
	 */
	public function beforeSave() {
		// Check to make sure the theme dir is writeable
		$path = Yii::getPathOfAlias('application.www.themes');
		if(!is_writeable($path)) {
			$this->addError('dirname', at('Sorry, We could not create the theme directory as the theme path "{d}" is not writeable. Please set the appropriate permissions.', array('{d}' => $path)));
			return;
		}
		
		return parent::beforeSave();
	}
	
	public function afterSave() {
		if($this->isNewRecord) {
			$this->createTheme('site');
		}
		return parent::afterSave();
	}
	
	/** 
	 * Check dirname
	 */
	public function checkDirname() {
		// Clean dirname
		$this->dirname = preg_replace('/[^0-9a-zA-Z\_]/', '', strtolower($this->dirname));
		
		// See if we have that dirname in use already
		if($this->isNewRecord) {
			$exists = Theme::model()->exists('dirname=LOWER(:dirname)', array(':dirname' => strtolower($this->dirname)));
		} else {
			$exists = Theme::model()->exists('dirname=LOWER(:dirname) AND id!=:id', array(':id' => $this->id, ':dirname' => strtolower($this->dirname)));
		}
		
		// If we have it already then we will show an error
		if($exists) {
			$this->addError('dirname', at('Sorry, That directory name is already in use.'));
		}	
	}
	
	/**
	 * This method iterates over the source view directory and returns a number
	 */
	public function getTotalSourceFiles($type='site') {
		$cache = Yii::app()->cache->get('site_source_files_' . $type);
		if($cache !== false) {
			return $cache;
		}
		
		$count = count(Theme::model()->getSourceFiles());
		Yii::app()->cache->get('site_source_files_' . $type, $count);
		return $count;
	}
	
	/**
	 * This method iterates over the source view directory and returns a list of files
	 * that we will need to copy when new theme is created
	 */
	public function getSourceFiles($type='site') {
		$files = glob_recursive( Theme::model()->getSourceViewDir($type) . '/*.php' );
		return $files;
	}
	
	/**
	 * Get resources files
	 */
	public function getResourcesFiles($type='site') {
		$files = array_merge(glob_recursive( Theme::model()->getSourceWwwDir($type) . '/*.css' ), glob_recursive( Theme::model()->getSourceWwwDir($type) . '/*.js' ));
		return $files;
	}
	
	/**
	 * Return the files we need to insert
	 */
	public function getInsertFiles($type='site') {
		// Load the source files that we will need
		$files = $this->getSourceFiles($type);
		
		// Loop through the files and remove the full path leave just the /views/....
		$inserts = array();
		foreach($files as $key => $file) {
			$source = $file;
			$files[$key] = $file = str_replace($this->getSourceModuleDir($type), '', $file);
			$inserts[] = array(
				'file_name' => end(explode('/', $file)),
				'file_location' => str_replace('/views', '/views/' . $type, str_replace($type . '/', '', $file)),
				'file_directory' => 'views/' . trim(str_replace(array('/views', end(explode('/', $file))), '', $file), '/'),
				'file_ext' => end(explode('.', $file)),
				'content' => file_get_contents($source),
			);
		}
		
		return $inserts;
	}
	
	/**
	 * This method grabs all theme files from db and writes them to the php files
	 */
	public function syncTheme($type='site') {
		// First load all source files and load missing ones into the db
		$files = $this->getInsertFiles($type='site');
		
		foreach($files as $insert) {
			// Check if the file location exists
			$exists = ThemeFile::model()->exists('theme_id=:id AND file_location=:location', array(':id' => $this->id, ':location' => $insert['file_location']));
			if(!$exists) {
				// Add to the db
				$themeFile = new ThemeFile;
				$themeFile->theme_id = $this->id;
				$themeFile->file_name = $insert['file_name'];
				$themeFile->file_location = $insert['file_location'];
				$themeFile->file_directory = $insert['file_directory'];
				$themeFile->file_ext = $insert['file_ext'];
				$themeFile->content = $insert['content'];
				$themeFile->save(false);
			}
		}
		
		$path = Yii::getPathOfAlias('application.www.themes');
		
		if(!is_dir($path) || !is_writeable($path)) {
			throw new CHttpException(at('Sorry, The path "{d}" does not exists or is not writeable.', array('{d}' => $path)));
		}
		
		$themeDir = $path . '/' . $this->dirname;
		
		if(!is_dir($themeDir) || !is_writeable($themeDir)) {
			throw new CHttpException(at('Sorry, The path "{d}" does not exists or is not writeable.', array('{d}' => $themeDir)));
		}
		
		$synced = 0;
		foreach($this->files as $file) {
			// Write to the file
			$location = $themeDir . $file->file_location;
			$directory = $themeDir . (str_replace(end(explode('/', $file->file_location)), '', $file->file_location));
			// make sure directory exists
			if(!is_dir($directory)) {
				mkdir($directory, 0777, true);
			}
			file_put_contents($location, $file->content);
			$synced++;
		}
		
		// Copy www folder
		$this->copyResourcesFolder($type, false);
		
		return $synced;
	}
	
	/**
	 * Sync all themes
	 *
	 */
	public function syncAllThemes() {
		$rows = Theme::model()->findAll();
		foreach($rows as $row) {
			$row->syncTheme();
		}
		
		return true;
	}
	
	/**
	 * Create new theme
	 */
	public function createTheme($type='site') {
		$inserts = $this->getInsertFiles($type);
		
		// Create the theme directory
		$this->createThemeDir($type);
		
		// Add the inserts to the theme file table
		foreach($inserts as $insert) {
			$themeFile = new ThemeFile;
			$themeFile->theme_id = $this->id;
			$themeFile->file_name = $insert['file_name'];
			$themeFile->file_location = $insert['file_location'];
			$themeFile->file_directory = $insert['file_directory'];
			$themeFile->file_ext = $insert['file_ext'];
			$themeFile->content = $insert['content'];
			$themeFile->save(false);
		}
		
		// Sync theme files
		$this->syncAllThemes();
		
		// Copy www folder
		$this->copyResourcesFolder($type);
		
		return true;
	}
	
	public function copyResourcesFolder($type='site', $overWrite=true) {
		// Get theme path
		$path = Yii::getPathOfAlias('themes.' . $type) . '/www';
		$dest = Yii::getPathOfAlias('themes.' . $this->dirname . '/www');
		
		$files = $this->getResourcesFiles($type);
		
		foreach($files as $key => $file) {
			$source = $file;
			$file = str_replace($this->getSourceWwwDir($type), '', $file);
			$inserts[] = array(
				'file_name' => end(explode('/', $file)),
				'file_location' => '/www' . $file,
				'file_directory' => 'www/' . trim(str_replace(array('/www', end(explode('/', $file))), '', $file), '/'),
				'file_ext' => end(explode('.', $file)),
				'content' => file_get_contents($source),
			);
		}
		
		foreach($inserts as $insert) {
			// Check if the file location exists
			$exists = ThemeFile::model()->exists('theme_id=:id AND file_location=:location', array(':id' => $this->id, ':location' => $insert['file_location']));
			if(!$exists) {
				// Add to the db
				$themeFile = new ThemeFile;
				$themeFile->theme_id = $this->id;
				$themeFile->file_name = $insert['file_name'];
				$themeFile->file_location = $insert['file_location'];
				$themeFile->file_directory = $insert['file_directory'];
				$themeFile->file_ext = $insert['file_ext'];
				$themeFile->content = $insert['content'];
				$themeFile->save(false);
			}
		}
		
		if($path) {
			// We copy themes/$type/www to the new theme location
			return copyr($path, $dest, $overWrite);
		}
		
		return false;
	}
	
	/**
	 * Create the actual theme directory
	 */
	public function createThemeDir($type='site') {
		// Get theme path
		$path = Yii::getPathOfAlias('application.www.themes');
		$themeDir = $path . '/' . $this->dirname;
		
		// Create theme dir
		if(is_dir($themeDir)) {
			// If it exists then just make sure the www and views is under that dir
			//throw new CHttpException(at('Sorry, Could not create the theme directory "{d}" as it already exists.', array('{d}' => $this->dirname)));
			// Set permissions
			if(!is_writeable($themeDir)) {
				throw new CHttpException(at('Sorry, Could not create the theme sub folders as the theme directory is not writeable. Please set the correct permissions.'));
			}
			
			if(!is_dir($themeDir . '/views')) {
				mkdir($themeDir . '/views', 0777, true);
			}
			if(!is_dir($themeDir . '/www')) {
				mkdir($themeDir . '/www', 0777, true);
			}
			chmod($themeDir, 0777);
			chmod($themeDir . '/views', 0777);
			chmod($themeDir . '/www', 0777);
		} else {
			// Make sure we can write to the themes dir
			if(!is_writeable($path)) {
				throw new CHttpException(at('Sorry, Could not create the theme directory "{d}" as the theme path is not writeable, Please set the appropriate permissions.', array('{d}' => $this->dirname)));
			}
			
			// Create dir
			mkdir($themeDir, 0777, true);
			mkdir($themeDir . '/views', 0777, true);
			mkdir($themeDir . '/www', 0777, true);
		}
		
		return true;
	}
	
	/**
	 * Return source view directory path
	 */
	public function getSourceViewDir($type='site') {
		return Yii::getPathOfAlias($type.'.views');
	}
	
	/**
	 * Return source www directory path
	 */
	public function getSourceWwwDir($type='site') {
		return Yii::getPathOfAlias('themes.' . $type. '.www');
	}
	
	/**
	 * Return source view directory path
	 */
	public function getSourceModuleDir($type='site') {
		return Yii::getPathOfAlias('application.modules');
	}
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('name, dirname', 'required' ),
			array('name, dirname', 'length', 'min' => 3, 'max' => 55 ),
			array('is_active', 'numerical'),
			array('dirname', 'match', 'pattern' => '/[0-9a-zA-Z\_]/'),
			array('dirname', 'unique'),
			array('dirname', 'checkDirname'),
			array('author, author_site', 'length', 'max' => 100 ),
			array('name, dirname, is_active, author, author_site', 'safe', 'on' => 'search'),
		);
	}
	
	public function scopes() {
		return array(
			'byName' => array(
				'order' => 'name ASC'
			),
			'isActive' => array(
				'condition' => 'is_active=:active',
				'params' => array(':active' => 1),
			),
		);
	}
	
	public function relations()
	{
		return array(
			'author' => array(self::BELONGS_TO, 'User', 'author_id'),
			'files' => array(self::HAS_MANY, 'ThemeFile', 'theme_id'),
			'filesCount' => array(self::STAT, 'ThemeFile', 'theme_id'),
		);
	}
	
	/**
	 * Before delete event
	 */
	public function beforeDelete() {
		// Delete all theme files first
		ThemeFile::model()->deleteAll('theme_id=:id', array(':id' => $this->id));
		
		// Delete theme folder with all files
		$path = Yii::getPathOfAlias('application.www.themes');
		$themeDir = $path . '/' . $this->dirname;
		if(is_dir($themeDir)) {
			recursiveDirRemove($themeDir);
		}
		
		return parent::beforeDelete();
	}
	
	/**
	 * Build the theme files tree 
	 * to use under the CTreeView
	 */
	public function getThemeFilesTree() {
		// Select the distinct file_directories
		$files = Yii::app()->db->createCommand()->select('id, file_location, file_directory, file_name')->from('theme_file')->where('theme_id=:id', array(':id' => $this->id))->queryAll();
		$paths = array();
		
		foreach ($files as $file) {
		    $paths[$file['file_location']] = $file['id'];
		}
		
		// Convert paths into an array
		$paths = $this->explodeTree($paths, '/');
		
		// Loop through the paths and create the tree elements
		$treeData = $this->buildTree($paths);
		
		return $treeData;
	}
	
	/**
	 * Recursive tree builder
	 *
	 */
	function buildTree(array $arr) {
		$tree = array();
	    foreach($arr as $title => $elems) {
			if(is_array($elems)) {
				$tree[] = array('text' => '<span class="folder">'.$title.'</span>', 'children' => $this->buildTree($elems));
			} else {
				$tree[] = array('text' => '<span class="file edit-theme-file" id="theme_file_'.$elems.'">'.$title.'</span>');
			}
		}

	    return $tree;
	}

	/**
	 * Explode any single-dimensional array into a full blown tree structure,
	 * based on the delimiters found in it's keys.
	 *
	 * @author  Kevin van Zonneveld <kevin@vanzonneveld.net>
	 * @author  Lachlan Donald
	 * @author  Takkie
	 * @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
	 * @version   SVN: Release: $Id: explodeTree.inc.php 89 2008-09-05 20:52:48Z kevin $
	 * @link    http://kevin.vanzonneveld.net/
	 *
	 * @param array   $array
	 * @param string  $delimiter
	 * @param boolean $baseval
	 *
	 * @return array
	 */
	public function explodeTree($array, $delimiter = '_', $baseval = false) {
	  if(!is_array($array)) return false;
	  $splitRE   = '/' . preg_quote($delimiter, '/') . '/';
	  $returnArr = array();
	  foreach ($array as $key => $val) {
	    // Get parent parts and the current leaf
	    $parts  = preg_split($splitRE, $key, -1, PREG_SPLIT_NO_EMPTY);
	    $leafPart = array_pop($parts);

	    // Build parent structure
	    // Might be slow for really deep and large structures
	    $parentArr = &$returnArr;
	    foreach ($parts as $part) {
	      if (!isset($parentArr[$part])) {
	        $parentArr[$part] = array();
	      } elseif (!is_array($parentArr[$part])) {
	        if ($baseval) {
	          $parentArr[$part] = array('__base_val' => $parentArr[$part]);
	        } else {
	          $parentArr[$part] = array();
	        }
	      }
	      $parentArr = &$parentArr[$part];
	    }

	    // Add the final part to the structure
	    if (empty($parentArr[$leafPart])) {
	      $parentArr[$leafPart] = $val;
	    } elseif ($baseval && is_array($parentArr[$leafPart])) {
	      $parentArr[$leafPart]['__base_val'] = $val;
	    }
	  }
	  return $returnArr;
	}
	
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;
		
		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('dirname',$this->dirname,true);
		$criteria->compare('author',$this->author,true);
		$criteria->compare('author_site',$this->author_site,true);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 100),
		));
	}
}