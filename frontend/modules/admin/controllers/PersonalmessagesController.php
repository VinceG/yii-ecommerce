<?php
/**
 * personal messages controller Home page
 */
class PersonalmessagesController extends AdminController {	
	/**
	 * init
	 */
	public function init() {
		parent::init();
		
		// Check Access
		checkAccessThrowException('op_personalmessages_view');
		// Add Breadcrumb
		$this->addBreadCrumb(at('Personal Messages Manager'));
		$this->title[] = at('Personal Messages Manager');
	}
	/**
	 * Index action
	 */
    public function actionIndex() {
		$model = new PersonalMessageTopic('search');
		$model->unsetAttributes();
        if(isset($_GET['PersonalMessageTopic'])) {
			$model->attributes=$_GET['PersonalMessageTopic'];
		}
        $this->render('index', array( 'model' => $model ) );
    }

	/**
	 * Get user new messages
	 */
	public function actionGetLastNewMessage() {
		// Check if we have any messages to show order by urgency level
		$message = PersonalMessageNotification::model()->getLastMessage();
		if(!$message) {
			echoJson(array());
		}
		
		// Build the text message
		$type = PersonalMessageTopic::model()->messageTypes[$message->notificationTopic->type];
		$text = at("{author} Sent a new reply in PM topic '{title}' on {date}", array('{author}' => $message->notificationAuthor->getUserLink(), '{title}' => $message->notificationTopic->getTopicTitle(false), '{date}' => timeSince($message->created_at)));
		$title = at("You've received a new message");
	
		// Based on type
		if($message->notificationTopic->type > 0 ) {
			$html = $this->renderPartial('_pm_notification', array('message' => $message, 'text' => $text), true);
		} else {
			$html = $this->renderPartial('_pm_normal_notification', array('message' => $message, 'text' => $text), true);
		}
		
		$footer = $this->renderPartial('_pm_notification_footer', array('message' => $message, 'text' => $text), true);
		
		// Update the record to show that we showed that notification
		PersonalMessageNotification::model()->updateByPk($message->id, array('was_shown' => 1));
		
		// Output
		echoJson(array('html' => $html, 'footer' => $footer, 'text' => $text, 'title' => $title, 'type' => $message->notificationTopic->type));
	}
	
	/**
	 * View PM via ajax request
	 * 
	 */
	public function actionGetAjaxMessageView($topicId) {
		// Make sure topic id exists
		if(!$topicId) {
			echoJson(array('error' => at('Sorry, We could not find that topic.')));
		}
		
		// load topic
		$topic = PersonalMessageTopic::model()->with(array('author', 'participants', 'replies'))->findByPk($topicId);
		if(!$topic) {
			echoJson(array('error' => at('Sorry, We could not find that topic.')));
		}
		
		// Make sure we are a participant
		if($topic->author_id != Yii::app()->user->id) {
			$participantExists = PersonalMessageParticipant::model()->exists('topic_id=:topic AND user_id=:user', array(':user' => Yii::app()->user->id, ':topic' => $topic->id));
			if(!$participantExists) {
				alog(at("Tried Accessing a Personal Message '{name}' When he is not a participant.", array('{name}' => $topic->title)));
				echoJson(array('error' => at('Sorry, You are not allowed to view this personal message as you are not a participant.')));
				
			}
		}
		
		alog(at("Viewed Personal Message '{name}'.", array('{name}' => $topic->title)));
		
		// Delete all the notifications we have for this topic
		PersonalMessageNotification::model()->deleteAll('user_id=:user AND topic_id=:topic', array(':user' => Yii::app()->user->id, ':topic' => $topic->id));
		
		$reply = new PersonalMessageReply;
				
		// Display form
		$this->layout = false;
		$html = $this->renderPartial('_pm_ajax_view', array( 'model' => $topic, 'reply' => $reply ), true);
		$footer = $this->renderPartial('_pm_ajax_view_footer', array( 'model' => $topic ), true);
		$title = at('Viewing Personal Message "{title}"', array('{title}' => $topic->title));
		echoJson(array('html' => $html, 'footer' => $footer, 'title' => $title));
	}
	
	/**
	 * Ajax send reply!
	 *
	 */
	public function actionPostAjaxPMMessage() {
		$topicId = getPostParam('topicId', 0);
		$message = trim(str_replace(array('<p></p>', '<p><br /></p>'), '', getPostParam('message', '')));

		// Make sure topic id exists
		if(!$topicId) {
			echoJson(array('error' => at('Sorry, We could not find that topic.')));
		}
		
		// Make sure We have a message
		if(!$message || empty($message)) {
			echoJson(array('error' => at('Sorry, You must submit a message.')));
		}
		
		// load topic
		$topic = PersonalMessageTopic::model()->with(array('author', 'participants', 'replies'))->findByPk($topicId);
		if(!$topic) {
			echoJson(array('error' => at('Sorry, We could not find that topic.')));
		}
		
		// Make sure we can post message
		if(!checkAccess('op_personalmessages_reply')) {
			echoJson(array('error' => at('Sorry, You are not allowed to reply to messages.')));
		}
		
		// Make sure we are a participant
		if($topic->author_id != Yii::app()->user->id) {
			$participantExists = PersonalMessageParticipant::model()->exists('topic_id=:topic AND user_id=:user', array(':user' => Yii::app()->user->id, ':topic' => $topic->id));
			if(!$participantExists) {
				alog(at("Tried Accessing a Personal Message '{name}' When he is not a participant.", array('{name}' => $topic->title)));
				echoJson(array('error' => at('Sorry, You are not allowed to view this personal message as you are not a participant.')));
				
			}
		}
		
		alog(at("Repllied To Personal Message '{name}'.", array('{name}' => $topic->title)));
		
		// Delete all the notifications we have for this topic
		PersonalMessageNotification::model()->deleteAll('user_id=:user AND topic_id=:topic', array(':user' => Yii::app()->user->id, ':topic' => $topic->id));
		
		$reply = new PersonalMessageReply;
		$reply->topic_id = $topic->id;
		$reply->message = $message;
		if(!$reply->save()) {
			echoJson(array('error' => at('Sorry, There was a problem saving your message.')));
		}		
				
		// Display Success
		$html = at('Thank You! Your message was sent!');
		$footer = $this->renderPartial('_pm_ajax_sent_footer', array( 'model' => $topic ), true);
		$title = at('Viewing Personal Message "{title}"', array('{title}' => $topic->title));
		echoJson(array('html' => $html, 'footer' => $footer, 'title' => $title));
	}
	
	/**
	 * Create PM Via Ajax
	 * 
	 */
	public function actionGetAjaxTopicForm() {
		// Check Access
		if(!checkAccess('op_personalmessages_add')) {
			echoJson(array('error' => at('Sorry, You are not allowed to create messages.')));
		}
		
		$model = new PersonalMessageTopic;
				
		// Display form
		$this->layout = false;
		$html = $this->renderPartial('_pm_ajax_create', array( 'model' => $model, 'viaAjax' => true ), true);
		$footer = $this->renderPartial('_pm_ajax_create_footer', array( 'model' => $model ), true);
		$title = at('Creating Personal Message');
		echoJson(array('html' => $html, 'footer' => $footer, 'title' => $title));
	}
	
	/**
	 * Create new topic
	 *
	 */
	public function actionPostAjaxCreateMessage() {
		$title = trim(getPostParam('title'));
		$type = getPostParam('type');
		$to = getPostParam('to');
		$message = trim(str_replace(array('<p></p>', '<p><br /></p>'), '', getPostParam('message', '')));
		
		// Make sure we have a title
		if(!$title || empty($title)) {
			echoJson(array('error' => at('Sorry, You must submit a title.')));
		}
		
		// Make sure We have a message
		if(!$message || empty($message)) {
			echoJson(array('error' => at('Sorry, You must submit a message.')));
		}
		
		// Check to make sure we've selected at least one participant
		if(!$to || !count($to)) {
			echoJson(array('error' => at('Sorry, You must select at least one participant.')));
		}
		
		// Make sure we didn't select more partiticpants then we need to
		if(getParam('personal_message_max_participants') && count($to) > getParam('personal_message_max_participants')) {
			echoJson(array('error' => at('Sorry, You have selected too many participants.')));
		}
		
		// Add the new topic
		$model = new PersonalMessageTopic;
		$model->title = $title;
		$model->type = $type;
		$model->to = $to;
		$model->message = $message;
		if(!$model->save()) {
			echoJson(array('error' => at('Sorry, We could not save the personal message.')));
		}
		
		// Display Success
		$html = at('Thank You! Your message was sent!');
		$footer = $this->renderPartial('_pm_ajax_sent_footer', array(), true);
		$title = at('Personal Message Sent!');
		echoJson(array('html' => $html, 'footer' => $footer, 'title' => $title));
	}

	/**
	 * Remove a participant from a topic
	 *
	 */
	public function actionRemoveParticipant($userId, $topicId) {
		if(!checkAccess('op_personalmessages_remove_participants')) {
			echoJson(array('error' => at('Sorry, You are not allowed to perform this action.')));
		}
		
		// Load the topic id
		$topic = PersonalMessageTopic::model()->findByPk($topicId);
		if(!$topic) {
			echoJson(array('error' => at('Sorry, We could not find that topic.')));
		}
		
		// Load the user id
		$user = User::model()->findByPk($userId);
		if(!$user) {
			echoJson(array('error' => at('Sorry, We could not find that user.')));
		}
		
		// Make sure the user is not the author
		if($topic->author_id == $userId) {
			echoJson(array('error' => at('Sorry, That user is the topic author. He can not be removed from the participants list.')));
		}
		
		// Make sure we are not removing others if we are not the author
		if($topic->author_id != Yii::app()->user->id) {
			if($userId != Yii::app()->user->id) {
				echoJson(array('error' => at('Sorry, You are not allowed to remove other participants from this topic as you are not the topic author.')));
			}
		}
		
		// Make sure the user is part of the topic already
		$participantExists = PersonalMessageParticipant::model()->exists('topic_id=:topic AND user_id=:user', array(':user' => $userId, ':topic' => $topic->id));
		if(!$participantExists) {
			echoJson(array('error' => at('Sorry, That user is not a participant.')));
		}
		
		// Remove
		PersonalMessageParticipant::model()->deleteAll('topic_id=:topic AND user_id=:user', array(':user' => $userId, ':topic' => $topic->id));
		
		// Load the new participants list
		$participantsList = $this->renderPartial('_participants', array('model' => $topic), true);
		
		// Load the new add participant form
		$addParticipantsForm = $this->renderPartial('_add_participant', array('model' => $topic), true);
		
		// Success message
		$html = at("{name} Was removed!", array('{name}' => $user->name));
		
		echoJson(array('html' => $html, 'participantsList' => $participantsList, 'addParticipantsForm' => $addParticipantsForm));
	}

	/**
	 * Add participant to a topic
	 *
	 */
	public function actionAddParticiapnt($userId, $topicId) {
		if(!checkAccess('op_personalmessages_add_participants')) {
			echoJson(array('error' => at('Sorry, You are not allowed to perform this action.')));
		}
		
		// Load the topic id
		$topic = PersonalMessageTopic::model()->findByPk($topicId);
		if(!$topic) {
			echoJson(array('error' => at('Sorry, We could not find that topic.')));
		}
		
		// Load the user id
		$user = User::model()->findByPk($userId);
		if(!$user) {
			echoJson(array('error' => at('Sorry, We could not find that user.')));
		}
		
		// Make sure the user is not the author
		if($topic->author_id == $userId) {
			echoJson(array('error' => at('Sorry, That user is the topic author. He can not be added as a participant.')));
		}
		
		// Make sure the user is not part of the topic already
		$participantExists = PersonalMessageParticipant::model()->exists('topic_id=:topic AND user_id=:user', array(':user' => $userId, ':topic' => $topic->id));
		if($participantExists) {
			echoJson(array('error' => at('Sorry, That user is already a participant.')));
		}
		
		// Add him
		$newParticipant = new PersonalMessageParticipant;
		$newParticipant->topic_id = $topic->id;
		$newParticipant->user_id = $userId;
		$newParticipant->save();
		
		// Load the new participants list
		$participantsList = $this->renderPartial('_participants', array('model' => $topic), true);
		
		// Load the new add participant form
		$addParticipantsForm = $this->renderPartial('_add_participant', array('model' => $topic), true);
		
		// Success message
		$html = at("{name} added to the topic.", array('{name}' => $user->name));
		
		echoJson(array('html' => $html, 'participantsList' => $participantsList, 'addParticipantsForm' => $addParticipantsForm));
	}

	/**
	 * Add a new help topic action
	 */
	public function actionCreate()
	{		
		// Check Access
		checkAccessThrowException('op_personalmessages_add');
		
		$model = new PersonalMessageTopic;
		
		if( isset( $_POST['PersonalMessageTopic'] ) ) {
			$model->attributes = $_POST['PersonalMessageTopic'];
			if( $model->save() ) {
				fok(at('Personal Message Created.'));
				alog(at("Created Personal Message '{name}'.", array('{name}' => $model->title)));
				$this->redirect(array('personalmessages/index'));
			}
		}
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Creating New Message'));
		$this->title[] = at('Creating New Message');
		
		// Display form
		$this->render('form', array( 'model' => $model ));
	}
	
	/**
	 * view help topic action
	 */
	public function actionView()
	{
		// Check Access
		checkAccessThrowException('op_personalmessages_view');
		
		if( isset($_GET['id']) && ( $model = PersonalMessageTopic::model()->with(array('author', 'participants', 'replies'))->findByPk($_GET['id']) ) ) {	
			// Make sure we are a participant
			if($model->author_id != Yii::app()->user->id) {
				$participantExists = PersonalMessageParticipant::model()->exists('topic_id=:topic AND user_id=:user', array(':user' => Yii::app()->user->id, ':topic' => $model->id));
				if(!$participantExists) {
					ferror(at('Sorry, You are not allowed to view this personal message as you are not a participant.'));
					alog(at("Tried Accessing a Personal Message '{name}' When he is not a participant.", array('{name}' => $model->title)));
					$this->redirect(getReferrer('personalmessages/index'));
				}
			}
			
			alog(at("Viewed Personal Message '{name}'.", array('{name}' => $model->title)));
			
			// Add Breadcrumb
			$this->addBreadCrumb(at('Viewing Personal Message'));
			$this->title[] = at('Viewing Personal Message "{name}"', array('{name}' => $model->title));
			
			$reply = new PersonalMessageReply;

			if( isset( $_POST['PersonalMessageReply'] ) ) {
				checkAccessThrowException('op_personalmessages_reply');
				
				$reply->attributes = $_POST['PersonalMessageReply'];
				$reply->topic_id = $model->id;
				if( $reply->save() ) {
					fok(at('Reply Sent.'));
					alog(at("Replied To Personal Message '{name}'.", array('{name}' => $model->title)));
					$this->redirect(getReferrer('personalmessages/index'));
				}
			}
			
			// Delete all the notifications we have for this topic
			PersonalMessageNotification::model()->deleteAll('user_id=:user AND topic_id=:topic', array(':user' => Yii::app()->user->id, ':topic' => $model->id));

			// Display form
			$this->render('view', array( 'model' => $model, 'reply' => $reply ));
		} else {
			ferror(at('Could not find that ID.'));
			$this->redirect(array('personalmessages/index'));
		}
	}
	
	/**
	 * Delete help topic action
	 */
	public function actionDelete()
	{
		// Check Access
		checkAccessThrowException('op_personalmessages_delete');
		
		if( isset($_GET['id']) && ( $model = PersonalMessageTopic::model()->findByPk($_GET['id']) ) ) {	
			alog(at("Deleted Personal Message '{name}'.", array('{name}' => $model->title)));
					
			// Make sure we are allowed to delete this
			if($model->author_id != Yii::app()->user->id) {
				ferror(at('Sorry, You are not the author of this personal message so you can not delete it.'));
				alog(at("Tried Deleting a Personal Message '{name}' When he is not the author.", array('{name}' => $model->title)));
				$this->redirect(getReferrer('personalmessages/index'));
			}		
					
			$model->delete();
			
			fok(at('Personal Message Deleted.'));
			$this->redirect(array('personalmessages/index'));
		} else {
			$this->redirect(array('personalmessages/index'));
		}
	}
}