<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://localhostp/outreach/admin
	 *	- or -
	 * 		http://localhostp/outreach/admin/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 */
	function __construct() {
		error_reporting(0);
		parent::__construct();
		$this -> output -> set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
		$this -> output -> set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this -> output -> set_header('Cache-Control: post-check=0, pre-check=0', false);
		$this -> output -> set_header('Pragma: no-cache');
		$this -> load -> model('homeSiteModel');
		$this -> load -> library(array('form_validation', 'session'));
		$this -> load -> helper(array('url', 'html', 'form'));
	}

	/**
	 * Index (outreach home page)
	 * @param string $data
	 * @return object if success  redirect to  Homepage
	 */
	public function index($data = "") {
		if ($this -> session -> flashdata('msg')) {
			$data['msg'] = $this -> session -> flashdata('msg');
		}
		$data['nodalcenters'] = $this -> homeSiteModel -> nodalcenterscount();
		$data['workshoprun'] = $this -> homeSiteModel -> workshopruncount();
		$data['getworkshopupcoming'] = $this -> homeSiteModel -> getWorkShopupcoming();
		$data['map'] = $this -> homeSiteModel -> displayMap();
		$this -> load -> view('site/header', $data);
		$this -> load -> view('site/home/home', $data);
		$this -> load -> view('site/footer', $data);

	}

	/**
	 * login method:  Authenticating coordinator
	 * Submits an HTTP POST method to server
	 * @param   $postdata $data Values
	 * @return object  if success coordinator Dashboard else Login View
	 */
	public function login($data = "", $postdata = "") {
		$this -> load -> view('site/header', $data);
		$this -> form_validation -> set_rules('email', 'E-Mail', 'required|xss_clean');
		$this -> form_validation -> set_rules('password', 'Password', 'required|xss_clean');
		if ($this -> form_validation -> run() == FALSE) {
			$this -> session -> set_flashdata('msg', validation_errors());
			redirect('', 'refresh');
		} elseif ($this -> input -> post()) {
			$postdata = $this -> input -> post();
			$result = $this -> homeSiteModel -> login($postdata);

			if ($result == 0) {
				$Nodalresult = $this -> homeSiteModel -> NodalLogin($postdata);
				if ($Nodalresult) {
					redirect('NodalDashboard', 'refresh');
				} else {
					$this -> session -> set_flashdata('msg', 'Invalid User Name or password');
					redirect('', 'refresh');
				}
			} else {
				redirect('dashboard', 'refresh');

			}
		}
		$this -> load -> view('site/footer');
	}

	/**
	 * dashboard method:   outreach coordinator dashboard
	 * @param   $value $data Values
	 * @return object  if success coordinator Dashboard else Login View
	 */
	public function dashboard($value = '') {
		$this -> load -> view('site/header', $data);
		$data[''] = $this -> homeSiteModel -> getActiveWorkshopOutreach();
		$data['getActiveWorkshop'] = $this -> homeSiteModel -> getActiveWorkshopOutreach();
		$data['getPendingWorkshopOutreach'] = $this -> homeSiteModel -> getPendingWorkshopOutreach();
		$data['getWorkshopHistory'] = $this -> homeSiteModel -> getWorkshopHistory();
		$data['getNodalCoordinator'] = $this -> homeSiteModel -> fatchNodalCoordinator();
		$data['getTraininging'] = $this -> homeSiteModel -> fatchTrainingingCoordinator();
		$this -> load -> view('site/outreachcoordinator/manageWorkshop', $data);
		$this -> load -> view('site/footer');
	}

	/**
	 * viewReport    If user session exist redirecting to detail view page else Login Page
	 * @param string $inputdata
	 * @param string $data
	 * @return object detail view  Listing   else Login View
	 */
	public function viewReport($inputdata = "", $data = "") {
		$ses_data = $this -> session -> userdata('user_details');
		if (empty($ses_data)) {
			redirect('Login');
		}
		$inputdata = $this -> uri -> segment(2);
		$data['viewReports'] = $this -> homeSiteModel -> getViewReport($inputdata);
		$this -> load -> view('site/header', $data);
		$this -> load -> view('site/outreachcoordinator/viewReport', $data);
		$this -> load -> view('site/footer');
	}

	/**
	 * approverepost   (changing workshop status)
	 * @param string $inputdata
	 * @return object  if success redirect to workshop page
	 */

	public function approverepost($inputdata = "") {
		$ses_data = $this -> session -> userdata('user_details');
		if (empty($ses_data)) {
			redirect('Login');
		}
		$inputdata = $this -> input -> post();
		$res = $this -> homeSiteModel -> approverepost($inputdata);
		if ($res) {
			$this -> session -> set_flashdata('msg', 'approve workshop Successfully ');
			redirect('dashboard', 'refresh');
		}
	}

	/**
	 * Nodal coordinator Dashboard
	 *  @param string $data
	 * @return object  if success redirect to Nodal coordinator Dashboard page
	 */
	public function NodalDashboard($data = '') {
		$ses_data = $this -> session -> userdata('user_details');
		$this -> load -> view('site/header', $data);
		$data['getActiveWorkshop'] = $this -> homeSiteModel -> getActiveWorkshop();
		$data['nodalcoordinatorworkshopcount'] = $this -> homeSiteModel -> nodalcoordinatorworkshopcount();
		//$data['participatecount'] = $this -> homeSiteModel -> participatecount();
		$data['getGuidesMaterial'] = $this -> homeSiteModel -> getGuidesMaterial();
		$data['getWorkshopMetirial'] = $this -> homeSiteModel -> getWorkshopMetirial();
		$data['getPresentationReporting'] = $this -> homeSiteModel -> getPresentationReporting();
		$data['getWorkshopHistoryNodal'] = $this -> homeSiteModel -> getWorkshopHistoryNodal();
		$this -> load -> view('site/nodal-coordinator/nodal_workshop.php', $data);
		$this -> load -> view('site/footer');
	}

	/**
	 * addWorkshop
	 * @param string $inputdata
	 * @return object  if success redirect to addWorkshop Listing View with Success Message else Create addWorkshop View
	 */
	public function addWorkshop($inputdata = "") {
		$ses_data = $this -> session -> userdata('user_details');
		if (empty($ses_data)) {
			redirect('Login');
		}
		$inputdata = $this -> input -> post();
		$res = $this -> homeSiteModel -> addWorkshop($inputdata);
		if ($res) {
			$this -> session -> set_flashdata('msg', 'Workshop add successfully.');
			redirect('NodalDashboard', "refresh");
		}
	}

	/** editWorkshop   Updating edit Workshop Page
	 * @param string $inputdata
	 * @param string $data
	 * @return object  if success redirect to editWorkshop View
	 */
	public function editWorkshop($inputdata, $data) {
		$ses_data = $this -> session -> userdata('user_details');
		if (empty($ses_data)) {
			redirect('Login');
		}
		$inputdata = $this -> uri -> segment(2);
		$data['Workshopedit'] = $this -> homeSiteModel -> editWorkshop($inputdata);
		if ($data) {
			$this -> load -> view('site/header', $data);
			$this -> load -> view('site/nodal-coordinator/editWorkshop', $data);
			$this -> load -> view('site/footer');
		} else {
			redirect('NodalDashboard', "refresh");
		}

	}

	/**
	 * updateWorkshop
	 * @param string $inputdata
	 * @return object  if success redirect to Workshop  Listing View with Success Message else editWorkshop View
	 */

	public function updateWorkshop($inputdata = '') {
		$ses_data = $this -> session -> userdata('user_details');
		if (empty($ses_data)) {
			redirect('Login');
		}
		$inputdata = $this -> input -> post();
		$res = $this -> homeSiteModel -> updateWorkshop($inputdata);
		if ($res) {
			$this -> session -> set_flashdata('msg', 'Update Successfully ');
			redirect('NodalDashboard', "refresh");
		}
	}

	/**
	 * cancelworkshop   (changing workshop status)
	 * @param string $postval
	 * @return object  if success redirect to workshop page
	 */

	public function cancelWorkshop($postval = "") {
		$ses_data = $this -> session -> userdata('user_details');
		if (empty($ses_data)) {
			redirect('Login');
		}
		$inputdata = $this -> input -> post();
		$res = $this -> homeSiteModel -> cancelWorkshop($inputdata);
		if ($res) {
			$this -> session -> set_flashdata('msg', 'cancel workshopp Successfully ');
			redirect('NodalDashboard', "refresh");
		}
	}

	/**
	 * submitReport   (changing workshop status)
	 * @param string $filea
	 * @return object  if success redirect to workshop page else submit Reports  page
	 */
	public function submitReport($filea = "") {
		$ses_data = $this -> session -> userdata('user_details');
		if (empty($ses_data)) {
			redirect('Login');
		}

		$inputdata = $this -> input -> post();
		$report_data = $this -> session -> userdata('report_data');
		$target_dir = 'assests/uploads/attend_sheet/';
		$target_dir1 = 'assests/uploads/college_report/';
		$target_file = $target_dir . basename($_FILES["upload_attend_sheet"]["name"]);
		$target_file1 = $target_dir1 . basename($_FILES["college_report"]["name"]);
		$workshop_photos[] = ($_FILES['workshop_photos']['name']);
		$workshop_photos = json_encode($workshop_photos);
		if (move_uploaded_file($_FILES["upload_attend_sheet"]["tmp_name"], $target_file)) {
			$upload_attend_sheet = $_FILES["upload_attend_sheet"]["name"];
		} else {
			$upload_attend_sheet = $report_data[0]['upload_attend_sheet'];
		}
		if (move_uploaded_file($_FILES["college_report"]["tmp_name"], $target_file1)) {
			$college_report = $_FILES["college_report"]["name"];
		} else {
			$college_report = $report_data[0]['college_report'];
		}
		$uploads_dir = 'assests/uploads/workshop_photos/';
		foreach ($_FILES["file_name"]["error"] as $key => $error) {
			if ($error == UPLOAD_ERR_OK) {
				$tmp_name = $_FILES["workshop_photos"]["tmp_name"][$key];
				$name = $_FILES["workshop_photos"]["name"][$key];
				move_uploaded_file($tmp_name, "$uploads_dir/$name");
			}
		}
		$filea = array('upload_attend_sheet' => $upload_attend_sheet, 'college_report' => $college_report, 'workshop_photos' => $workshop_photos, 'participate_attend' => $inputdata['participate_attend'], 'participate_experiment' => $inputdata['participate_experiment'], 'comments_positive' => $inputdata['comments_positive'], 'comments_negative' => $inputdata['comments_negative'], 'workshop_id' => $inputdata['workshop_id']);
		if ($inputdata['submit'] == "save") {
			$res = $this -> homeSiteModel -> editReport($filea);
			if ($res > 0) {
				$this -> session -> set_flashdata('msg', 'Saved Reports Successfully');
				redirect('NodalDashboard', "refresh");
				$this -> session -> unset_userdata('report_data');
			}
		} else {
			$res = $this -> homeSiteModel -> submitReport($filea);
			if ($res > 0) {
				$this -> session -> set_flashdata('msg', 'Submit Reports Successfully');
				redirect('NodalDashboard', "refresh");
				$this -> session -> unset_userdata('report_data');
			}
		}

	}

	/************nodal***************/
	/**
	 * forgot password  sending mail to register user
	 * @param string $data
	 * @param string $email
	 * @param string $pwd
	 * @param string $message
	 * @return object if success redirect to the view  with status
	 */
	public function forgotPassword($data = "", $email = "", $message = "") {

		$this -> load -> view('site/header', $data);
		$this -> form_validation -> set_rules('email', 'User Name', 'required|xss_clean');
		if ($this -> form_validation -> run() == FALSE) {
			$this -> load -> view('site/home/forgot_password', $data);
		} elseif ($this -> input -> post()) {
			$email = $this -> input -> post('email');
			$emailResult = $this -> homeSiteModel -> checkEmail($email);
			if ($emailResult == 0) {
				$this -> session -> set_flashdata('msg', 'Invalid User Name');
				$this -> load -> view('site/home/forgot_password', $data);
			} else {
				$pwd = rand(000000, 999999);
				$postvalues = array('email' => $email, 'password' => md5($pwd));
				$result = $this -> homeSiteModel -> forgotPassword($postvalues);
				if ($result > 0) {
					$message = "<html><head><META http-equiv='Content-Type' content='text/html; charset=utf-8'>
									   </head><body>
										  <div style='margin:0;padding:0'>
										<table border='0' cellspacing='0' cellpadding='0'>
									   <tbody>
									   <tr>
											<td valign='top'><p>" . $res['name'] . " please click bellow link to activate your outreach Account.</p></td>
									   </tr>
									  <tr>
										   <td valign='top'><p><strong>User Name :</strong> " . $email . "</p></td>
									  </tr>
									  <tr>
										   <td valign='top'><p><strong>Password :</strong> " . $pwd . "</p></td>
									  </tr>
								</tbody>
							</table>  
						 </div>
						</body></html>";
					$to = $email;
					$subject = "Your Nodal  account Password";
					$headers = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					if (mail($to, $subject, $message, $headers)) {
						$this -> session -> set_flashdata('msg', 'Please check Your Email to receive Password');
						redirect('', 'refresh');
					} else {
						echo "iam here forgot password page";
						die();
						$this -> session -> set_flashdata('msg', 'Your Request Failed.Please Re-Try.');
						$this -> load -> view('site/home/forgot_password', $data);
					}
				} else {
					$this -> session -> set_flashdata('msg', 'Your Request Failed.Please Re-Try.');
					$this -> load -> view('site/home/forgot_password', $data);
				}
			}
		}
		$this -> load -> view('site/footer');
	}

	/*
	 *@method traininging
	 *@param  Post Values
	 *@return object  if success redirect to nodal-coordinator page
	 */
	public function traininging($traininginputs = "") {
		$ses_data = $this -> session -> userdata('user_details');
		if (empty($ses_data)) {
			redirect('login');
		}
		$this -> form_validation -> set_rules('name', 'Name ', 'required|alpha');
		$this -> form_validation -> set_rules('date', 'date', 'required');
		$this -> form_validation -> set_rules('location', 'location', 'required|xss_clean|alpha');
		$this -> form_validation -> set_rules('duration', 'duration', 'required|is_natural');
		$this -> form_validation -> set_rules('description', 'description', 'required|alpha');
		$this -> form_validation -> set_rules('invitees', 'invitees', 'required');
		if ($this -> form_validation -> run() == FALSE) {
			echo validation_errors();
		} elseif ($this -> input -> post()) {
			$postdata = $this -> input -> post();
			$postdata['outreach_id'] = $ses_data['outreach_id'];
			$res = $this -> homeSiteModel -> traininging($postdata);
			if ($res > 0) {
				$this -> session -> set_flashdata('msg', 'Submit Reports Successfully');
				redirect('dashboard', 'refresh');
			}
			redirect('dashboard', 'refresh');
		}
	}

	/**
	 *  addNodal
	 * @param string $postdata
	 * @param string $to
	 * @param string $subject
	 * @param string $message,$headers
	 * @return object  if success redirect to addNodal Listing View with Success Message else Create addNodal View
	 */
	public function addNodalcenter($postdata = "", $to, $subject, $message, $headers) {
		$ses_data = $this -> session -> userdata('user_details');
		if (empty($ses_data)) {
			redirect('Login');
		}
		$this -> form_validation -> set_rules('location', 'Name of the Center', 'required|alpha');
		$this -> form_validation -> set_rules('name', 'Name of Coordinator', 'required|alpha');
		$this -> form_validation -> set_rules('email', 'email', 'required|xss_clean|valid_email');
		$this -> form_validation -> set_rules('target_workshops', 'No of Workshops', 'required|is_natural');
		$this -> form_validation -> set_rules('target_participants', 'No of Participants', 'required|is_natural');
		$this -> form_validation -> set_rules('target_expriments', 'No of experiments', 'required|is_natural');
		if ($this -> form_validation -> run() == FALSE) {
			echo validation_errors();
		} elseif ($this -> input -> post()) {
			$postdata = $this -> input -> post();
			$target_dir = 'assests/uploads/mou/';
			$target_file = $target_dir . basename($_FILES["mou"]["name"]);
			if (move_uploaded_file($_FILES["mou"]["tmp_name"], $target_file)) {
				$upload_attend_sheet = $_FILES["mou"]["name"];
			}
			$postdata['mou'] = $_FILES["mou"]["name"];
			$this -> load -> helper('string');
			$postdata['password'] = random_string('alnum', 6);
			$result = $this -> homeSiteModel -> addNodalcenter($postdata);
			if ($result > 0) {
				$message = "<html><head><META http-equiv='Content-Type' content='text/html; charset=utf-8'>
                                   </head><body>
                                      <div style='margin:0;padding:0'>
 	                                <table border='0' cellspacing='0' cellpadding='0'>
    	                           <tbody>
								   <tr>
				                        <td valign='top'><p> Hi Nodal Coordinator,</p></td>
		                           </tr>
		                           <tr>
				                        <td valign='top'><p> Your   follow the below details to login here " . base_url() . "</p></td>
		                           </tr>
		                          <tr>
				                       <td valign='top'><p><strong>User Name :</strong> " . $postdata['email'] . "</p></td>
		                          </tr>
								  <tr>
				                       <td valign='top'><p><strong>Password :</strong> " . $postdata['password'] . "</p></td>
		                          </tr>
		                    </tbody>
	                    </table>  
                     </div>
                    </body></html>";
				$to = $postdata['email'];
				$subject = "Your Nodal  account Password";
				$headers = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				mail($to, $subject, $message, $headers);
				$this -> session -> set_flashdata('msg', 'Nodalcenter Added Successfully');
				echo "success";
				exit ;
			} else {
				//$this->session->set_flashdata('msg', 'Nodalcenter already Exists');
				echo "Nodalcenter already Exists";
				exit ;
			}
		}
	}

	/**
	 * logout   killing admin session data
	 * @param null
	 * @return object  redirect to index method if session killing
	 */
	public function logout() {
		$this -> session -> unset_userdata('user_details');
		$this -> session -> sess_destroy();
		redirect(base_url(), 'refresh');
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
