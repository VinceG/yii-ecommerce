<?php
/**
 * custom pages model
 */
class CustomPage extends ActiveRecord
{		
	/**
	 * @return object
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string Table name
	 */
	public function tableName()
	{
		return 'custom_page';
	}
	
	/**
	 * Relations
	 */
	public function relations()
	{
		return array(
			'author' => array(self::BELONGS_TO, 'User', 'author_id'),
			'last_author' => array(self::BELONGS_TO, 'User', 'updated_author_id'),
		);
	}
	
	/**
	 * Attribute values
	 *
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'title' => at('Title'),
			'alias' => at('Alias'),
			'content' => at('Content'),
			'tags' => at('Tags'),
			'metadesc' => at('Meta Description'),
			'metakeys' => at('Meta Keywords'),
			'visible' => at('Visibility'),
			'status' => at('Status'),
		);
	}
	
	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'created_at',
				'updateAttribute' => 'updated_at',
				'setUpdateOnCreate' => false,
			),
		);
	}
	
	/**
	 * Before save operations
	 */
	public function beforeSave()
	{
		if( $this->isNewRecord ) {
			$this->author_id = Yii::app()->user->id;
		} else {
			$this->updated_author_id = Yii::app()->user->id;
		}
		
		// Fix the language, tags and visibility
		$this->visible = ( is_array( $this->visible ) && count( $this->visible ) ) ? implode(',', $this->visible) : $this->visible;
		
		return parent::beforeSave();
	}
	
	public function beforeValidate() {
		// Alias
		if($this->alias) {
			$this->alias = makeAlias($this->alias);
		} else {
			$this->alias = makeAlias($this->title);
		}
		
		if( $this->isNewRecord ) {
			// Check if we already have an alias with those parameters
			if( CustomPage::model()->exists('alias=:alias', array(':alias' => $this->alias ) ) ) {
				$this->addError('alias', at('There is already a page with that alias.'));
			}
		} else {
			// Check if we already have an alias with those parameters
			if( CustomPage::model()->exists('alias=:alias AND id!=:id', array( ':id' => $this->id, ':alias' => $this->alias) ) ) {
				$this->addError('alias', at('There is already a page with that alias.'));
			}
		}
		
		return parent::beforeValidate();
	}
	
	/**
	 * after save method
	 */
	public function afterSave()
	{
		Yii::app()->urlManager->clearCache();
		
		return parent::afterSave();
	}
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('title, content', 'required' ),
			array('title', 'length', 'min' => 3, 'max' => 55 ),
			array('metadesc, metakeys, visible, status, tags, alias', 'safe' ),
		);
	}
	
	/**
	 * Return user link for the author and last updated author
	 *
	 */
	public function getAuthorLink($relation) {
		return $this->$relation ? ($this->$relation->id . " - " . CHtml::link($this->$relation->name, array('user/view', 'id' => $this->$relation->id))) : "--";
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->with = array('author', 'last_author');

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('metadesc',$this->metadesc,true);
		$criteria->compare('metakeys',$this->metakeys,true);
		$criteria->compare('visible',$this->visible,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 50),
		));
	}
}