<?php
/**
 * Help Topic model
 */
class HelpTopic extends ActiveRecord
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
		return 'help_topic';
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
			'name' => at('Name'),
			'question' => at('Question'),
			'answer' => at('Answer'),
			'alias' => at('Alias'),
			'tags' => at('Tags'),
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
		
		return parent::beforeSave();
	}
	
	public function beforeValidate() {
		// Alias
		if($this->alias) {
			$this->alias = makeAlias($this->alias);
		} else {
			$this->alias = makeAlias($this->question);
		}
		
		if( $this->isNewRecord ) {
			// Check if we already have an alias with those parameters
			if( HelpTopic::model()->exists('alias=:alias', array(':alias' => $this->alias ) ) ) {
				$this->addError('alias', at('There is already a help topic with that alias.'));
			}
		} else {
			// Check if we already have an alias with those parameters
			if( HelpTopic::model()->exists('alias=:alias AND id!=:id', array( ':id' => $this->id, ':alias' => $this->alias) ) ) {
				$this->addError('alias', at('There is already a help topic with that alias.'));
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
			array('name, question, answer', 'required' ),
			array('name, question', 'length', 'min' => 3, 'max' => 55 ),
			array('status, tags, alias, sort_ord', 'safe' ),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('question',$this->question,true);
		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('answer',$this->answer,true);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 50),
		));
	}
}