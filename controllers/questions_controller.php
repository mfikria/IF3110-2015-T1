<?php
	class QuestionsController {
		public function index() {
			$questions = Question::all();
			require_once('views/question/index.php');
		}
		
		public function show() {
			include '/models/answer.php';
			
			$qid = $_GET['qid'];
			
			if(!isset($qid)){
				return call('pages', 'error');	
			}
			
			$question = Question::get($qid);
			$answers = Answer::all($qid);
			require_once('views/questions/show.php');
		}
		
		public function insert() {
			
			if(isset($_POST['authorname']) && isset($_POST['authoremail']) && $_POST['topic'] && $_POST['content']){
				$authorname = $_POST['authorname'];
				$authoremail = $_POST['authoremail'];
				$topic = $_POST['topic'];
				$content = $_POST['content'];	 
				$datetime = date("Y-m-d H:i:s");
				
				$question = new Question('', $authorname, $authoremail, $topic, $content, $datetime, '', '');
				$question->post();
			}
			
			require_once('views/questions/form.php');
		}
		
		public function vote() {
			
			$vote = intval($_GET['vote']);
			$qid = intval($_GET['qid']);
			echo Question::vote($vote, $qid);
			
		}
		
		public function edit() {
			if(isset($_GET['qid'])){
				$qid = $_GET['qid'];
				$question = Question::get($qid);
				require_once('views/questions/formedit.php');
			}
		}
		
		public function update() {
			if(isset($_POST['authorname']) && isset($_POST['authoremail']) && $_POST['topic'] && $_POST['content'] && $_GET['qid']){
				$authorname = $_POST['authorname'];
				$authoremail = $_POST['authoremail'];
				$topic = $_POST['topic'];
				$content = $_POST['content'];
				$datetime = date("Y-m-d H:i:s");
				$qid = $_GET['qid'];
				
				$question = new Question($qid, $authorname, $authoremail, $topic, $content, $datetime, '', '');
				$question->update();
				
				$url= 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' ."{$_SERVER['HTTP_HOST']}".'/if3110-2015-t1/';
				
				header('Location: '.$url);
				die();
			}		
		}
		
		public function delete() {
			if(isset($_GET['qid'])){
				$qid = $_GET['qid'];
				Question::delete($qid);
				
				$url= 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' ."{$_SERVER['HTTP_HOST']}".'/if3110-2015-t1/';
				
				header('Location: '.$url);
				die();
			}
		}
		
		public function search(){
			$keyword = $_POST['keyword'];
			$questions = Question::all();
			foreach ($questions as $key => $question) {
				if ((stripos($question->topic, $keyword) === false) && (stripos($question->content, $keyword) === false)) {
					unset($questions[$key]);
				}
			}
			
			require_once('/views/pages/home.php');
		}
	}


	
	if(isset($_GET['action'])){
		if($_GET['action'] == 'vote'){
				require_once('../connection.php');
				require_once('../models/question.php');
				QuestionsController::vote();
		}
	}

	if(isset($_GET['query']) && isset($_GET['field'])){
		require_once('../connection.php');
		require_once('../models/question.php');
		$value = $_GET['query'];
		$formfield = $_GET['field'];
		
		if (($formfield == "authorname") || ($formfield == "topic") || ($formfield == "content")) {
			if (strlen($value) <= 0) {
				echo "Tidak boleh kosong!";
			} else {
				echo "OK";
			}
		}
		
		if ($formfield == "authoremail") {
			if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $value)) {
				echo "Invalid email";
			} else {
				echo "Valid";
			}
		}	
	}
?>