<?php
/**
 * Cities Controller
 */
class CityController extends AdminController {	
	/**
	 * init
	 */
	public function init() {
		parent::init();
		
		// Check Access
		checkAccessThrowException('op_uscities_view');
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('US Cities Manager'));
		$this->title[] = at('US Cities Manager');
	}
	/**
	 * Index action
	 */
    public function actionIndex() {
		$city = new USCity('search');
		$city->unsetAttributes();
        if(isset($_GET['USCity'])) {
			$city->attributes=$_GET['USCity'];
		}
        $this->render('index', array( 'model' => $city ) );
    }

	/**
	 * Get list of country names
	 *
	 */
	public function actionGetCityCountyNames($term) {
		$res = array();

		if ($term) {
            $command =Yii::app()->db->createCommand("SELECT DISTINCT city_county FROM us_city WHERE city_county LIKE :name ORDER BY city_county ASC LIMIT 20");
	        $command->bindValue(":name", '%'.$term.'%', PDO::PARAM_STR);
	        $res =$command->queryColumn();
        }

		echoJson($res);
	}
	
	/**
	 * Get list of city names
	 *
	 */
	public function actionGetCityNames($term) {
		$res = array();

		if ($term) {
            $command =Yii::app()->db->createCommand("SELECT DISTINCT city_name FROM us_city WHERE city_name LIKE :name ORDER BY city_name ASC LIMIT 20");
	        $command->bindValue(":name", '%'.$term.'%', PDO::PARAM_STR);
	        $res =$command->queryColumn();
        }

		echoJson($res);
	}
	
	/**
	 * Get city info by zip
	 *
	 */
	public function actionGetCityInfoByZip($zipCode) {
		$city = USCity::model()->find('city_zip=:zipcode', array(':zipcode' => $zipCode));
		if(!$city) {
			echoJson(array('error' => at('Sorry, That zip code does not exists.')));
		}
		
		// Get state id
		$state = USState::model()->find('short=LOWER(:short)', array(':short' => strtolower($city->city_state)));
		
		// Get us country
		$country = Country::model()->find('short=LOWER(:short)', array(':short' => 'us'));
		
		$info = array(
			'city_name' => $city->city_name,
			'city_zip' => $city->city_zip,
			'city_state' => $state ? $state->id : 0,
			'country' => $country ? $country->id : 0,
		);
		$text = at("Zip Code Exists.\nCity Name: {cityname}, State: {state}", array('{cityname}' => $city->city_name, '{state}' => $city->city_state));
		echoJson(array('info' => $info, 'text' => $text));
	}

	/**
	 * Add a new city
	 */
	public function actionCreate()
	{		
		// Check Access
		checkAccessThrowException('op_uscities_addpages');
		
		$model = new USCity;
		
		if( isset( $_POST['USCity'] ) ) {
			$model->attributes = $_POST['USCity'];
			if( $model->save() ) {
				fok(at('City Created.'));
				alog(at("Created City Record '{name}'.", array('{name}' => $model->city_name)));
				$this->redirect(array('city/index'));
			}
		}
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Creating New City'));
		$this->title[] = at('Creating New City Record');
		
		// Display form
		$this->render('form', array( 'model' => $model ));
	}
	
	/**
	 * Edit city action
	 */
	public function actionUpdate()
	{	
		// Check Access
		checkAccessThrowException('op_uscities_editpages');
		
		if( isset($_GET['id']) && ( $model = USCity::model()->findByPk($_GET['id']) ) ) {		
			if( isset( $_POST['USCity'] ) ) {
				$model->attributes = $_POST['USCity'];
				if( $model->save() ) {
					fok(at('City Updated.'));
					alog(at("Updated City '{name}'.", array('{name}' => $model->city_name)));
					$this->redirect(array('city/index'));
				}
			}
		
			// Add Breadcrumb
			$this->addBreadCrumb(at('Updating City Record'));
			$this->title[] = at('Updating City Record');
		
			// Display form
			$this->render('form', array( 'model' => $model ));
		}
		else
		{
			ferror(at('Could not find that ID.'));
			$this->redirect(array('city/index'));
		}
	}
	
	/**
	 * view city action
	 */
	public function actionView()
	{
		// Check Access
		checkAccessThrowException('op_uscities_viewpages');
		
		if( isset($_GET['id']) && ( $model = USCity::model()->findByPk($_GET['id']) ) ) {	
			alog(at("Viewed City Record '{name}'.", array('{name}' => $model->city_name)));
			
			// Add Breadcrumb
			$this->addBreadCrumb(at('Viewing City'));
			$this->title[] = at('Viewing City Record "{name}"', array('{name}' => $model->city_name));

			// Display form
			$this->render('view', array( 'model' => $model ));
		} else {
			ferror(at('Could not find that ID.'));
			$this->redirect(array('city/index'));
		}
	}
	
	/**
	 * Delete city action
	 */
	public function actionDelete()
	{
		// Check Access
		checkAccessThrowException('op_uscities_deletepages');
		
		if( isset($_GET['id']) && ( $model = USCity::model()->findByPk($_GET['id']) ) ) {	
			alog(at("Deleted City Record '{name}'.", array('{name}' => $model->city_name)));
					
			$model->delete();
			
			fok(at('City Record Deleted.'));
			$this->redirect(array('city/index'));
		} else {
			$this->redirect(array('city/index'));
		}
	}
}