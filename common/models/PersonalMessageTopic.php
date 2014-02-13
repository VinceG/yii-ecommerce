<?php
/**
 * Personal Message Topic model
 */
class PersonalMessageTopic extends ActiveRecord
{		
	/** 
	 * Message types
	 */
	public $messageTypes = array(
				0 => 'Normal',
				1 => 'Urgent',
			);
			
	/**
	 * To element
	 */		
	public $to;
	/**
	 * Message
	 */
	public $message;
	
	public static $_topicNotifications = array();		
	
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
		return 'personal_message_topic';
	}
	
	/**
	 * Relations
	 */
	public function relations()
	{
		return array(
			'author' => array(self::BELONGS_TO, 'User', 'author_id'),
			'lastReplyAuthor' => array(self::BELONGS_TO, 'User', 'author_id'),
			'replies' => array(self::HAS_MANY, 'PersonalMessageReply', 'topic_id'),
			'participants' => array(self::HAS_MANY, 'PersonalMessageParticipant', 'topic_id'),
			'notifications' => array(self::HAS_MANY, 'PersonalMessageNotification', 'topic_id'),
			'userNotifications' => array(self::HAS_MANY, 'PersonalMessageNotification', 'topic_id', 'condition' => 'userNotifications.user_id=:user', 'params' => array(':user' => Yii::app()->user->id)),
			'lastReply' => array(self::HAS_ONE, 'PersonalMessageReply', 'topic_id'),
			'repliesCount' => array(self::STAT, 'PersonalMessageReply', 'topic_id'),
			'participantsCount' => array(self::STAT, 'PersonalMessageParticipant', 'topic_id'),
		);
	}
	
	/**
	 * Return number of new PM messages/replies
	 *
	 */
	public function getUserNotificationCount($userId) {
		$criteria = new CDbCriteria;
		$criteria->select = 'COUNT(DISTINCT topic_id)';
		$criteria->addCondition('user_id=:user');
		$criteria->params = array(':user' => $userId);
		return PersonalMessageNotification::model()->count($criteria);
	}
	
	/**
	 * Get user notifications
	 */
	public function getTopicNotifications($userId=null) {
		$userId = $userId !== null ? $userId : Yii::app()->user->id;
		
		if(isset(self::$_topicNotifications[$userId])) {
			return self::$_topicNotifications[$userId];
		}
		
		$criteria = new CDbCriteria;
		$criteria->addCondition('user_id=:user');
		$criteria->params = array(':user' => $userId);
		$notifications = array();
		$rows = PersonalMessageNotification::model()->findAll($criteria);
		foreach($rows as $row) {
			$notifications[$row->topic_id] = $row->topic_id;
		}
		// Save
		self::$_topicNotifications[$userId] = $notifications;
		// Return
		return self::$_topicNotifications[$userId];
	}
	
	/**
	 * Get topic title based on notification settings
	 */
	public function getTopicTitle($showBoldTitle=true) {
		$prefix = '';
		$suffix = '';
		if($showBoldTitle && in_array($this->id, $this->getTopicNotifications())) {
			$prefix = '<strong>';
			$suffix = '</strong>';
		}
		
		$title = $prefix.CHtml::encode($this->title).$suffix;
		return CHtml::link($title, array("personalmessages/view", "id" => $this->id));
	}
	
	public function getAuthorLink($relation) {
		return $this->author ? CHtml::link($this->author->name, array('user/view', 'id' => $this->author->id)) : "N/A";
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
			'type' => at('Type'),
			'to' => at('Recipients'),
			'message' => at('Message'),
			'last_reply_created_at' => at('Last Reply Date'),
			'last_reply_author_id' => at('Last Reply Author'),
		);
	}
	
	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'created_at',
				'updateAttribute' => false,
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
		}
		
		return parent::beforeSave();
	}
	
	/**
	 * After save operations
	 */
	public function afterSave()
	{
		// Add message to the reply table
		$reply = new PersonalMessageReply;
		$reply->topic_id = $this->id;
		$reply->message = $this->message;
		$reply->save();
		
		// Update Topic
		PersonalMessageTopic::model()->updateByPk($this->id, array('first_post' => $reply->id, 'last_reply_created_at' => $reply->created_at, 'last_reply_author_id' => $reply->user_id));
		
		// Add current user
		$participant = new PersonalMessageParticipant;
		$participant->topic_id = $this->id;
		$participant->user_id = Yii::app()->user->id;
		$participant->save();
		
		// Add recipients to the particiapnts table
		foreach($this->to as $userid) {
			$participant = new PersonalMessageParticipant;
			$participant->topic_id = $this->id;
			$participant->user_id = $userid;
			$participant->save();
		}
		
		// Add the notifications for all the participants
		$this->sendNotifications(null, $reply->id);
		
		return parent::afterSave();
	}
	
	/**
	 * Send notifications to the topic participants
	 */
	public function sendNotifications($id=null, $replyId=null) {
		$topicId = $id!==null ? $id : $this->id;
		$participants = PersonalMessageParticipant::model()->findAll('topic_id=:topic', array(':topic' => $topicId));
		
		// Get current notifications for that topic
		$topicNotifications = PersonalMessageNotification::model()->findAll('topic_id=:topic AND was_shown=:shown', array(':shown' => 0, ':topic' => $topicId));
		$notificationExists = array();
		foreach($topicNotifications as $topicNotification) {
			$notificationExists[] = $topicNotification->user_id;
		}
		
		foreach($participants as $participant) {
			// Skip ourself
			if($participant->user_id == Yii::app()->user->id) {
				continue;
			}
			
			// If we have one then dont add it again
			if(in_array($participant->user_id, $notificationExists)) {
				continue;
			}
			
			// Create the record
			$notification = new PersonalMessageNotification;
			$notification->user_id = $participant->user_id;
			$notification->topic_id = $topicId;
			if($replyId) {
				$notification->reply_id = $replyId;
			}
			$notification->save();
		}
	}
	
	/**
	 * Get total personal messages
	 *
	 */
	public function getTotalMessages() {
		// Count the messages in the participants table
		return PersonalMessageParticipant::model()->count('user_id=:id', array(':id' => Yii::app()->user->id));
	}
	
	/**
	 * Return an array of userid=>name to show in the drop down list
	 * By default we will show only the ones with the Admin role or Admin assigned to them 
	 * If the setting for showing more roles was selected then we will add those as well
	 * everything will be searched lower case
	 */
	public function getRecipientsList() {
		$roles = array('admin' => 'admin');
		$users = array();
		
		// Did we select more roles/tasks/operations in the select box?
		if(getParam('personal_messages_additional_roles')) {
			// Explode it and add to the array
			$explodeRoles = explode(',', getParam('personal_messages_additional_roles'));
			if(count($explodeRoles)) {
				foreach($explodeRoles as $role) {
					$role = strtolower($role);
					$roles[$role] = $role;
				}
			}
		}
		
		// First select all the auth_assignments
		$authAssignedItems = Yii::app()->db->createCommand()
		    ->select('a.userid, u.name, u.first_name, u.last_name')
		    ->from('auth_assignment a')
			->join('user u', 'u.id=a.userid')
		    ->where(array('in', 'itemname', $roles))
		    ->queryAll();
		
		foreach($authAssignedItems as $authAssignedItem) {
			$users[ $authAssignedItem['userid'] ] = sprintf("%s%s", $authAssignedItem['name'], ($authAssignedItem['first_name'] && $authAssignedItem['last_name'] ? (' ('.$authAssignedItem['first_name'] . ' ' . $authAssignedItem['last_name'].')') : ''));
		}
		
		// Now select all the users that have that role assigned to them
		$userRows = Yii::app()->db->createCommand()
		    ->select('id, name, first_name, last_name')
		    ->from('user')
		    ->where(array('in', 'role', $roles))
		    ->queryAll();
		
		foreach($userRows as $userRow) {
			$users[ $userRow['id'] ] = sprintf("%s%s", $userRow['name'], ($userRow['first_name'] && $userRow['last_name'] ? (' ('.$userRow['first_name'] . ' ' . $userRow['last_name'].')') : ''));
		}
		
		// Remove the current user from the list
		if(isset($users[Yii::app()->user->id])) {
			unset($users[Yii::app()->user->id]);
		}
		
		return $users;
	}
	
	/**
	 * Return dropdown list for adding participants
	 */
	public function getRecipientsListDropDown() {
		// Default list
		$users = $this->getRecipientsList();
	
		// Remove author
		if(isset($users[$this->author_id])) {
			unset($users[$this->author_id]);
		}
		
		// Remove participants
		foreach($this->participants as $participant) {
			if(isset($users[$participant->user_id])) {
				unset($users[$participant->user_id]);
			}
		}
		
		return $users;
	}
	
	/**
	 * Before Delete event
	 *
	 */
	public function beforeDelete() {
		foreach($this->replies as $reply) {
			$reply->delete();
		}
		
		foreach($this->participants as $participant) {
			$participant->delete();
		}
		
		foreach($this->notifications as $notification) {
			$notification->delete();
		}
		
		return parent::beforeDelete();
	}
	
	/**
	 * table data rules
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('title, type, message, to', 'required' ),
			array('type', 'in', 'range' => array_keys($this->messageTypes)),
			array('to', 'checkPMToCount'),
			array('title', 'length', 'min' => 3, 'max' => 55 ),
			array('message', 'length', 'min' => 3),
			array('title, type, author_id', 'safe', 'on' => 'search' ),
		);
	}
	
	/**
	 * Get the message type
	 */
	public function getType() {
		return $this->messageTypes[$this->type];
	}
	
	/**
	 * Check the amount of participants selected
	 *
	 */
	public function checkPMToCount() {
		if(getParam('personal_message_max_participants')) {
			// How many did we choose
			if(count($this->to) > getParam('personal_message_max_participants')) {
				$this->addError('to', at("Sorry, You can only add up to {n} participants at a time.", array('{n}' => getParam('personal_message_max_participants'))));
			}
		}
	}
	
	/**
	 * Get all topics a user is participating in
	 */
	public function getUserTopicIds() {
		// Get all user particiapted topics
		$rows = Yii::app()->db->createCommand()
		    ->selectDistinct('topic_id')
		    ->from('personal_message_participant')
		    ->where('user_id=:id', array(':id'=>Yii::app()->user->id))
		    ->queryAll();
		$topics = array();
		foreach($rows as $topic) {
			$topics[] = $topic['topic_id'];
		}
		return $topics;
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($term=null)
	{
		$criteria=new CDbCriteria;
		$criteria->with = array(
							'replies', 
							'participants',
							'author',
							'lastReplyAuthor',
							'lastReply',
							'repliesCount',
							'participantsCount',
							);
		
		$criteria->together = true;
		$criteria->group = 't.id';
		
		$criteria->addCondition('author_id=:authorid');
		$criteria->addCondition('participants.user_id=:userid', 'OR');
		$criteria->params = array(':authorid' => Yii::app()->user->id, ':userid' => Yii::app()->user->id);
		
		// Did we search
		if($term) {
			$criteria->addSearchCondition('title', $term);
			$criteria->addSearchCondition('replies.message', $term, true, 'OR');
		}
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 50),
			'sort'=>array(
			    'defaultOrder'=> 'last_reply_created_at DESC',
	        ),
		));
	}
}