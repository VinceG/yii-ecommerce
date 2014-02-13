<?php
/**
 * Asset manager
 *
 * Automatically publishes JS / CSS and adds a link to HTML
 *
 * @author Alex
 */
class Asset {
    /**
	 * The script is rendered in the head section right before the title element.
	 */
	const POS_HEAD=0;
	/**
	 * The script is rendered at the beginning of the body section.
	 */
	const POS_BEGIN=1;
	/**
	 * The script is rendered at the end of the body section.
	 */
	const POS_END=2;
	/**
	 * The script is rendered inside window onload function.
	 */
	const POS_LOAD=3;
	/**
	 * The body script is rendered inside a jQuery ready function.
	 */
	const POS_READY=4;

	/**
	 * @static
	 * @param string $path Path to CSS file to publish
	 * @param string $media media type, all by default
	 * @param bool $hashByName whether the published directory should be named as the hashed basename (publishing directories isn't recommended)
	 * @param int $level level of recursive copying when the asset is a directory (publishing directories isn't recommended)
	 * @param bool $forceCopy true to force copying even if asset is already published before
	 * @return void
	 */
    static function registerCssFile($path, $media = '', $hashByName=false,$level=-1,$forceCopy=false){
        Yii::app()->clientScript->registerCssFile(
			self::publish($path, $hashByName, $level, $forceCopy),
            $media
		);
    }

	/**
	 * @static
	 * @param string $path Path to JS file to publish
	 * @param int $position position to render link see Asset::POS_*
	 * @param bool $hashByName
	 * @param int $level level of recursive copying when the asset is a directory (publishing directories isn't recommended)
	 * @param bool $forceCopy true to force copying even if asset is already published before
	 * @return void
	 */
    static function registerScriptFile($path, $position=self::POS_HEAD, $hashByName=false,$level=-1,$forceCopy=false){
        Yii::app()->clientScript->registerScriptFile(
            self::publish($path, $hashByName, $level, $forceCopy),
            $position
        );
    }

	/**
	 * Registers a script package that is listed in packages config.
	 * This method is the same as CClientScript::registerCoreScript or CClientScript::registerPackage.
	 * @param string $name the name of the script package.
	 * @static
	 */
    static function registerPackage($name){
        Yii::app()->clientScript->registerPackage($name);
    }

	/**
	 * Publishes a file or a directory.
	 * This method will copy the specified asset to a web accessible directory
	 * and return the URL for accessing the published asset.
	 * <ul>
	 * <li>If the asset is a file, its file modification time will be checked
	 * to avoid unnecessary file copying;</li>
	 * <li>If the asset is a directory, all files and subdirectories under it will
	 * be published recursively. Note, in this case the method only checks the
	 * existence of the target directory to avoid repetitive copying.</li>
	 * </ul>
	 * @param string $path the asset (file or directory) to be published
	 * @param boolean $hashByName whether the published directory should be named as the hashed basename.
	 * If false, the name will be the hashed dirname of the path being published.
	 * Defaults to false. Set true if the path being published is shared among
	 * different extensions.
	 * @param integer $level level of recursive copying when the asset is a directory.
	 * Level -1 means publishing all subdirectories and files;
	 * Level 0 means publishing only the files DIRECTLY under the directory;
	 * level N means copying those directories that are within N levels.
	 * @param boolean $forceCopy whether we should copy the asset file or directory even if it is already published before.
	 * This parameter is set true mainly during development stage when the original
	 * assets are being constantly changed. The consequence is that the performance
	 * is degraded, which is not a concern during development, however.
	 * This parameter has been available since version 1.1.2.
	 * @return string an absolute URL to the published asset
	 * @throws CException if the asset to be published does not exist.
	 * @static
	 */
    static function publish($path,$hashByName=false,$level=-1,$forceCopy=false){
        return Yii::app()->getAssetManager()->publish($path, $hashByName, $level, $forceCopy);
    }
}