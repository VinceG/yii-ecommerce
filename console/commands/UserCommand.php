<?php
/**
 * UserCommand
 */
class UserCommand extends CConsoleCommand
{
	    public function getHelp()
		{
			return <<<EOD
	USAGE
   		- yiic user create [options]
			-- Create Options:
				1. --name=some_name
				2. --email=some_email@address.com
				3. --role=admin (Or any other role)
				4. --password=somepassword
			-- If the above settings are not set or one of them will be missing the following settings will default:
				1. --name=admin
				2. --email=admin@admin.com
				3. --role=admin
				4. --password=1q2w3e
	DESCRIPTION
   		- Create a user with a certain permission
EOD;
		}
		
		/**
		 * Command index action
		 */
		public function actionIndex() {
			die("\n\n--------------------------------------------------------\nPlease use --help to understand how to use this command.\n--------------------------------------------------------\n\n");
		}
		
		/**
		 * Create new user
		 */
		public function actionCreate($name='admin', $email='admin@admin.com', $role='admin', $password='1q2w3e') {
			// Make sure we have all the required values
			$required = array('name', 'email', 'role', 'password');
			foreach($required as $req) {
				if(!$$req) {
					echoCli(sprintf('Please specify a value for the "%s" property or don\'t specify it at all.', $req));
					return;
				}
			}
			
			// Check if the user exists by email and name
			$userExists = Yii::app()->db->createCommand(array(
			    'select' => array('id'),
			    'from' => 'user',
			    'where' => 'name=:name OR email=:email',
			    'params' => array(':name'=>$name, ':email' => $email),
			))->queryRow();
			
			// If exists error
			if($userExists) {
				echoCli(sprintf("Sorry, That user with the email address or name already exists."));
				return;
			}
			
			// Create the user
			Yii::app()->db->createCommand()->insert('user', array('created_at' => time(), 'name' => $name, 'email' => $email, 'role' => $role, 'password_hash' => User::hashPassword($password)));
			$lastID = Yii::app()->db->getLastInsertID();
			
			// Assign the role to the user
			if( !Yii::app()->authManager->isAssigned( $role, $lastID ) ) {
				$authItem = Yii::app()->authManager->getAuthItem( $role );
				Yii::app()->authManager->assign( $role, $lastID, $authItem->bizrule, $authItem->data );
				Yii::app()->authManager->assign( 'op_acp_access', $lastID, $authItem->bizrule, $authItem->data );
			}
			
			// Done
			echoCli('User Created!');
			
		}
}