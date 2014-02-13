<?php
/**
 * auth item model
 */
class AuthItem extends ActiveRecord
{
	/**
	 * array of auth item types
	 */
	public $types = array( 
			CAuthItem::TYPE_OPERATION => 'Operation', 
			CAuthItem::TYPE_TASK => 'Task', 
			CAuthItem::TYPE_ROLE => 'Role' 
	);
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'auth_item';
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
			'description' => at('Description'),
			'type' => at('Type'),
			'bizrule' => at('bizRule'),
			'data' => at('Data'),
		);
	}
	
	public function relations() {
		return array(
		);
	}
	
	public function getChilds() {
		return AuthItemChild::model()->findAll('parent=:name', array(':name' => $this->name));
	}
	
	public function getChildsCount() {
		return AuthItemChild::model()->count('parent=:name', array(':name' => $this->name));
	}
	
	public function getAuthName($id) {
		$match = AuthItem::model()->findByPk($id);
		if($match) {
			return $match->name;
		}
		return 'N/A';
	}
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('name, type, description', 'required' ),
			array('name', 'match', 'allowEmpty'=>false, 'pattern'=>'/^[A-Za-z0-9_]+$/'),
			array('name', 'checkName'),
			array('name', 'length', 'min' => 3, 'max' => 55 ),
			array('description', 'length', 'min' => 1, 'max' => 125 ),
			array('bizrule', 'safe'),
			array('data', 'safe'),
		);
	}
	
	public function checkName() {
		if($this->isNewRecord) {
			if(AuthItem::model()->exists('name=LOWER(:name)', array(':name' => strtolower($this->name)))) {
				$this->addError('name', at('Sorry, That name is already in use.'));
			}
		} else {
			if(AuthItem::model()->exists('name=LOWER(:name) AND id!=:id', array(':id' => $this->id, ':name' => strtolower($this->name)))) {
				$this->addError('name', at('Sorry, That name is already in use.'));
			}
		}
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($type=null)
	{
		$criteria=new CDbCriteria;
		
		if($type!==null) {
			$criteria->compare('type', $type);
		}

		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 200),
		));
	}
}