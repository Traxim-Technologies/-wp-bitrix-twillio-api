<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://traximtech.com
 * @since      1.0.0
 *
 * @package    Twillio_Mobile_Verification
 * @subpackage Twillio_Mobile_Verification/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Twillio_Mobile_Verification
 * @subpackage Twillio_Mobile_Verification/public
 * @author     Traxim Technologies <info@traximtech.com>
 */

session_start();
require_once( plugin_dir_path( __FILE__ ) .'/../vendor/Twilio/autoload.php');
require_once( plugin_dir_path( __FILE__ ) .'/../vendor/Bitrix/Restbit.php');
use Twilio\Rest\Client;

class Twillio_Mobile_Verification_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Twillio_Mobile_Verification_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Twillio_Mobile_Verification_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/twillio-mobile-verification-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Twillio_Mobile_Verification_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Twillio_Mobile_Verification_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/twillio-mobile-verification-public.js', array( 'jquery' ), $this->version, false );

	}

	function twillio_verification_send(){
		if( !isset($_POST['twillio_verification_send']) ){ 
			return ; 
		}
		//gets our api details from the database.
		$api_details = get_option('twillio-mobile-verification'); #twillio-mobile-verification is what we use to identify our option, it can be anything

		if(is_array($api_details) AND count($api_details) != 0) {
			if(isset($api_details['api_sid']) && isset($api_details['api_auth_token']) && isset($api_details['api_verification_sender'])){
				$rand = rand(100000,999999);
				if(isset($_POST['number'])){
					$_SESSION["CODE"] = $rand;
					$_SESSION["NUMBER"]  = $_POST['number'];
					try{
						$twilio = new Client($api_details['api_sid'], $api_details['api_auth_token']);
						$message = $twilio->messages->create($_POST['number'], ["body" => "Your verification code is : $rand", "from" => $api_details['api_verification_sender']]);
						if(isset($message->sid)){
							echo 'true';
						}else{
							echo 'false';
						}
					}catch (Exception $e) {
						echo $e->getMessage();
					}
				}else{
					echo 'receiver_number_not_given';
				}
				exit;
			}
			echo "missing_auth";
			exit;
		}
		exit;
	}
	
	function twillio_verification_check(){
		if( !isset($_POST['twillio_verification_check']) ){ 
			return ; 
		}
		if(isset($_SESSION["NUMBER"]) && isset($_SESSION["CODE"])){
			if(isset($_POST['number']) && isset($_POST['code'])){
				if($_SESSION["NUMBER"] == $_POST['number'] && $_SESSION["CODE"] == $_POST['code']){
					unset($_SESSION["NUMBER"]);
					unset($_SESSION["CODE"]);
					echo "true";
				}else{
					echo "false";
				}
			}
		}
		exit;
	}

	function bitrix_send_deal_data(){
		if( !isset($_POST['bitrix_send_deal_data']) ){ 
			return ; 
		}
		$UF = [
			// CUSTOM FIELDS MAPPER
			// [TODO]: MAKE THESE FIELDS DYNAMIC HANDLED FROM PLUGIN ADMIN...
			"what-are-you-looking-to-do"  =>  'UF_CRM_XXXXXXXXXX',
			"what-kind-of-debts-do-you-have" =>  'UF_CRM_XXXXXXXXXX', 
			"are-you-behind-on-your-payments" => 'UF_CRM_XXXXXXXXXX"',
			"what-made-you-fall-behind" => 'UF_CRM_XXXXXXXXXX',
			"what-is-your-monthly-income" => 'UF_CRM_XXXXXXXXXX',
			"what-is-the-total-amount-of-your-unsecured-debt" => 'UF_CRM_XXXXXXXXXX',
			"what-city-are-you-in" => 'UF_CRM_XXXXXXXXXX'
		];
		$fields = [];
		$fields["STATUS_ID"] = "NEW";
		$fields["OPENED"] = "Y";
		$fields["ASSIGNED_BY_ID"] = 1;
		if(isset($_POST['FIRSTNAME']) && isset($_POST['LASTNAME']) && isset($_POST['EMAIL']) && isset($_POST['PHONE'])){
			$get_result=$result = Restbit::call(
				'crm.contact.add',
				[
					"fields" => [
						"NAME" => $_POST['FIRSTNAME'],
						"LAST_NAME" => $_POST['LASTNAME'],
						"OPENED" => "Y",
						"ASSIGNED_BY_ID" => 1,
						"TYPE_ID" => "CLIENT",
						"PHONE" => [ [ "VALUE" => $_POST['PHONE'], "VALUE_TYPE" => "WORK" ] ],
						"EMAIL" => [ [ "VALUE" => $_POST['EMAIL'], "VALUE_TYPE" => "WORK" ] ],
					]
				]
			);
			if(isset($get_result['result'])){
				foreach($_POST as $key=>$value){
					if($key !=  "bitrix_send_deal_data" && $key != "FIRSTNAME" && $key != "LASTNAME"){
						if($key != "PHONE" && $key != "EMAIL"){
							if(isset($UF[$key])){
								$fields[$UF[$key]] =  $value;
							}else{
								$fields[$key] = $value;
							}
						}
					}
				}
				$fields["CATEGORY_ID"] = "1";
				$fields["CONTACT_ID"] = $get_result['result'];
				$fields["TITLE"] = $_POST['FIRSTNAME'] . " " . $_POST['LASTNAME'];
				$fields["SOURCE_DESCRIPTION"] = "WEBSITE SUBMITTED";
				$get_result=$result = Restbit::call(
						'crm.deal.add',
						[
							"fields" => $fields
						]
					);
				echo json_encode($get_result);
				exit;
			}else{
				echo 'false';
				exit;
			}
		}
		exit;
	}
}
