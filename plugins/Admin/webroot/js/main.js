var checkingSessionObj = null;
var countDownLogoutObj = null;
var timeToCountDown = 60;
$(document).ready(function(){	
	 $(document).on('click','#logoutCountDown_Yes',function(){
		//alert('YES');
		extendSession();
		return false;
	});		
	$(document).on('click','#logoutCountDown_No',function(){
		//alert('NO');
		sessionLogout();
		return false;
	});
	$(document).on('click','#logoutCountDown_Close',function(){
		$("#popup_logoutCountDown").modal('hide');
		extendSession();
	});	
	startCountDown(SessionExprieIn);
});
function startCountDown(SessionExprieTime)
{	
	var timeToShow = SessionExprieTime - timeToCountDown;
	if(SessionExprieTime != '' && SessionExprieTime>0)
	{
		if(checkingSessionObj  && checkingSessionObj != null)
		{
			checkingSessionObj.stop();			
		}
		if(countDownLogoutObj  && countDownLogoutObj != null)
		{
			countDownLogoutObj.stop();			
		}
		
		checkingSessionObj = Tock({
									countdown: true,
									interval: 500,
									callback: function () {
										var checkinRemainTime = checkingSessionObj.lap() / 1000;
										//var minutes = Math.ceil (checkinRemainTime/(60));
										//var seconds = Math.ceil (checkinRemainTime);
										//console.log('checking',seconds);										
									},
									complete: function () {
										validateSessionExprie(function(response){
											if(response.SessionExprieIn <= timeToCountDown)
											{
												if($('#popup_logoutCountDown').css('display') == 'none')
												{
													$("#popup_logoutCountDown").modal('show');
												}
												countDownLogoutObj.start(response.SessionExprieIn*1000);
											}
											else
											{
												startCountDown(response.SessionExprieIn);
											}
										});										
									}
								});	
			
		countDownLogoutObj = Tock({
									countdown: true,
									interval: 500,
									callback: function () {
										var checkinRemainTime = countDownLogoutObj.lap() / 1000;
										//var minutes = Math.ceil (checkinRemainTime/(60));
										var seconds = Math.ceil (checkinRemainTime);
										//console.log(countDownLogoutObj.lap() / 1000,seconds);										
										//console.log('countDown',seconds);	
										var label = seconds > 1 ? 'seconds':'second';
										label = seconds+' '+label;
										$('#logoutCountDownLabel').html(label);
									},
									complete: function () {
										validateSessionExprie(function(response){
											if(response.SessionExprieIn <= 0)
											{
												sessionLogout();
											}
											else
											{
												startCountDown(response.SessionExprieIn);
											}
										});	
										
									}
								});	
		checkingSessionObj.start(timeToShow*1000);			
	}
}
function extendSession(){
	var params = '';
	$.ajax({
			url:  window_app.webroot+ 'Users/extendSession.json',
			type: 'GET',
			data: params
	})
	.done(function(response) {
		SessionExprieIn = response.SessionExprieIn;
		startCountDown(SessionExprieIn);
		$("#popup_logoutCountDown").modal('hide');
	})
	.fail(function() {
	});	
}	
function sessionLogout(){
	var params = '';
	$.ajax({
			url:  window_app.webroot+ 'Users/sessionLogout.json',
			type: 'GET',
			data: params
	})
	.done(function(response) {
		if(response.status == 'ok')
		{
			$("#popup_logoutCountDown").modal('hide');		
			window.location.reload();
		}
	})
	.fail(function() {
	});
	
}
function validateSessionExprie(callBackAction){
	var params = '';
	$.ajax({
			url:  window_app.webroot+ 'users/validateSessionExprie.json',
			type: 'GET',
			data: params
	})
	.done(function(response) {
		if(typeof(callBackAction) == 'function')
		{
			callBackAction(response);
		}
	})
	.fail(function() {
	});
	
}	
