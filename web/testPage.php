<?php
require('../vendor/autoload.php');

session_start();

if($_POST){
	//echo $_POST['myArray'];
	$currentQuestion = $_POST['currentQuestion'];
	$indexOfAnswer = $_POST['answerIndex'];
	$questionNumber = $_POST['questionNumber'];
	$_SESSION['userAnswers'][] = array('currentQuestion' => $currentQuestion, 'indexOfAnswer' => $indexOfAnswer);
	//echo $currentQuestion;
	
	require('db.php');

	$ajaxResult = $testArr[$currentQuestion];
	$ajaxResult['answerCorrectness'] = $testArr[$currentQuestion]['indexOfCorrectAnswer'] == $indexOfAnswer;

	echo json_encode($ajaxResult);
	
	//$tempQuestionArr[$currentQuestion]['answerCorrectness'] = $tempQuestionArr[$currentQuestion]['indexOfCorrectAnswer'] == $answerIndex;
	//echo json_encode($tempQuestionArr[$currentQuestion]);
}






