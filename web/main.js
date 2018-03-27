
var startBtn = document.getElementById('startBtn');
var nextQuestionBtn = document.getElementById('nextQuestionBtn');
var questionNumber = document.getElementById('questionNumber');
var checkAnswerBtn = document.getElementById('checkAnswerBtn');

/*if(startBtn){startBtn.addEventListener('click', changeQuestionNumber(1), false);}*/
if(nextQuestionBtn){nextQuestionBtn.addEventListener('click', changeQuestionNumber(1), false);}
if(checkAnswerBtn){checkAnswerBtn.addEventListener('click', testFunction, false);}


function changeQuestionNumber(val){
	return function(){
	//alert(val);
	if(document.querySelectorAll('input[type="radio"][name="question"]:checked').length > 0 ){
		questionNumber.value = questionNumber.value * 1 + val;
	}
	//document.cookie = "result=0";
	//alert(questionNumber);
	}
}


		
function testFunction(){
	if(document.querySelectorAll('input[type="radio"][name="question"]:checked').length > 0 ){
		var xhttp = new XMLHttpRequest();
		//console.log(document.querySelector('input[name="question"]:checked').value);

		//should check for NULL value in case that no radio button is selected
		var answerIndex = document.querySelector('input[name="question"]:checked').value;

		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				console.log(JSON.parse(this.responseText));
				var result = JSON.parse(this.responseText);
				if(result.answerCorrectness){
					document.getElementById('correctAnswer').style.display = 'block';
				} else {
					document.getElementById('incorrectAnswer').style.display = 'block';
				}


				var answerText = document.getElementById('answerText');
				answerText.innerHTML = result['descriptionOfCorrectAnswer'];
				var radioBtnArr = document.querySelectorAll('input[name="question"]');
				console.log(radioBtnArr);

				document.getElementById('checkAnswerBtn').style.display = 'none';
				document.getElementById('nextQuestionBtn').style.display = 'block';
				for(var i = 0; i < radioBtnArr.length; i++){
					if(i == result['indexOfCorrectAnswer']){radioBtnArr[i].parentElement.lastElementChild.style.color = "yellowgreen";}
					//radioBtnArr[i].parentElement.lastElementChild.disabled = true;
					//radioBtnArr[i].disabled = true;
					}
			}
		};
		xhttp.open("POST", "https://mighty-escarpment-32450.herokuapp.com/testPage.php", true);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.send("currentQuestion=" + questionNumber.value + "&answerIndex=" + answerIndex);
	} else {
		console.log('change this');
	}
}
