<?php
/**
 * Country Controller
 */
class CountryController extends AdminController {	
	/**
	 * init
	 */
	public function init() {
		parent::init();
		
		// Check Access
		checkAccessThrowException('op_country_view');
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Countries Manager'));
		$this->title[] = at('Countries Manager');
	}
	/**
	 * Index action
	 */
    public function actionIndex() {
		$country = new Country('search');
        $this->render('index', array( 'model' => $country ) );
    }

	/**
	 * Add a new country
	 */
	public function actionCreate()
	{		
		// Check Access
		checkAccessThrowException('op_country_addpages');
		
		$model = new Country;
		
		if( isset( $_POST['Country'] ) ) {
			$model->attributes = $_POST['Country'];
			if( $model->save() ) {
				fok(at('Country Created.'));
				alog(at("Created Country Record '{name}'.", array('{name}' => $model->name)));
				$this->redirect(array('country/index'));
			}
		}
		
		// Add Breadcrumb
		$this->addBreadCrumb(at('Creating New Country'));
		$this->title[] = at('Creating New Country Record');
		
		// Display form
		$this->render('form', array( 'model' => $model ));
	}
	
	/**
	 * Edit country action
	 */
	public function actionUpdate()
	{	
		// Check Access
		checkAccessThrowException('op_country_editpages');
		
		if( isset($_GET['id']) && ( $model = Country::model()->findByPk($_GET['id']) ) ) {		
			if( isset( $_POST['Country'] ) ) {
				$model->attributes = $_POST['Country'];
				if( $model->save() ) {
					fok(at('Country Updated.'));
					alog(at("Updated Country '{name}'.", array('{name}' => $model->name)));
					$this->redirect(array('country/index'));
				}
			}
			
			// Add Breadcrumb
			$this->addBreadCrumb(at('Updating Country'));
			$this->title[] = at('Updating Country Record');
		
			// Display form
			$this->render('form', array( 'model' => $model ));
		}
		else
		{
			ferror(at('Could not find that ID.'));
			$this->redirect(array('country/index'));
		}
	}
	
	/**
	 * view country action
	 */
	public function actionView()
	{
		// Check Access
		checkAccessThrowException('op_country_viewpages');
		
		if( isset($_GET['id']) && ( $model = Country::model()->findByPk($_GET['id']) ) ) {	
			alog(at("Viewed Country Record '{name}'.", array('{name}' => $model->name)));
			
			// Add Breadcrumb
			$this->addBreadCrumb(at('Viewing Country'));
			$this->title[] = at('Viewing Country Record "{name}"', array('{name}' => $model->name));

			// Display form
			$this->render('view', array( 'model' => $model ));
		} else {
			ferror(at('Could not find that ID.'));
			$this->redirect(array('country/index'));
		}
	}
	
	/**
	 * Delete country action
	 */
	public function actionDelete()
	{
		// Check Access
		checkAccessThrowException('op_country_deletepages');
		
		if( isset($_GET['id']) && ( $model = Country::model()->findByPk($_GET['id']) ) ) {	
			alog(at("Deleted Country Record '{name}'.", array('{name}' => $model->name)));
					
			$model->delete();
			
			fok(at('Country Record Deleted.'));
			$this->redirect(array('country/index'));
		} else {
			$this->redirect(array('country/index'));
		}
	}
}