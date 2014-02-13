<?php
/**
 * Login controller
 */
class LoginController extends AdminController {
	public $layout = 'login';
	/**
	 * init
	 */
	public function init() {
		parent::init();
		
		$this->title[] = at('Login');
	}
	
	/**
	 * List of available actions
	 */
	public function actions()
	{
	   return array(
	      'captcha' => array(
	         'class' => 'CCaptchaAction',
	         'backColor' => 0xFFFFFF,
		     'minLength' => 5,
		     'maxLength' => 8,
			 'testLimit' => 3,
			 'padding' => array_rand( range( 2, 10 ) ),
	      ),
	   );
	}
	
	/**
	 * Index action - login
	 */
    public function actionIndex() {	
    	$form = new AdminLogin;
    	if(isset($_POST['AdminLogin'])) {
    		$form->setAttributes($_POST['AdminLogin']);
    		if($form->validate()) {
    			// Login
    			Yii::app()->user->login($form->identity);
    			
    			AdminUser::model()->deleteAll('userid=:id', array(':id' => Yii::app()->user->id));
    			
    			// Update admin login table
    			$admin = new AdminUser;
    			$admin->save();
    			
    			// Add to session the last time we clicked
    			Yii::app()->session['admin_clicked'] = time();
    			
    			fok(at('Thank You! You are now logged in.'));

				// Add to login history
				AdminLoginHistory::model()->addLog($_POST['AdminLogin']['email'], $_POST['AdminLogin']['password'], 1);
				
				// Log Message
				alog(at("User logged in."));
				
				// Update last visited
				User::model()->updateByPk(Yii::app()->user->id, array('last_visited' => time()));
				
				// Make sure return url is within the admin
				$returnUrl = Yii::app()->request->getUrl();
				if(strpos($returnUrl, '/admin') === false) {
					$returnUrl = array('/admin');
				} elseif(strpos($returnUrl, '/admin/login') !== false) {
					$returnUrl = array('/admin');
				}

    			$this->redirect($returnUrl);
    		} else {
    			ferror(at('Sorry, There were errors with the information provided.'));

				// Add to login history
				AdminLoginHistory::model()->addLog($_POST['AdminLogin']['email'], $_POST['AdminLogin']['password'], 0);
    		}

    	}  		
        $this->render('login', array('form' => $form));
    }

	/**
	 * Logout action
	 */
	public function actionLogout() {
		// Log Message
		alog(at("User logged out."));
		
		// Delete records for this users from admin logged in
    	AdminUser::model()->deleteAll('userid=:id', array(':id' => Yii::app()->user->id));
    	Yii::app()->user->logout();
    	fok(at('Thank You! You are now logged out.'));
    	$this->redirect(array('/admin/login'));
	}
}