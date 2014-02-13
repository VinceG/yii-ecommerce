 //<![CDATA[

jQuery(document).ready(function(){
	
	var methods = {	        
		alertlike : function(){
			jQuery.fallr('show', {
				content : '<p>Howdy.</p>'
			});
		},
		confirmlike : function(){
			var clicked = function(){
				alert('congrats, you\'ve deleted internet');
				jQuery.fallr('hide');
			};
			jQuery.fallr('show', {
				buttons : {
					button1 : {text: 'Yes', danger:true, onclick: clicked},
					button2 : {text: 'Cancel'}
				},
				content : '<p>Are you sure you want to delete internet?</p>',
				icon    : 'error'
			});
		},
		promptlike : function(){
			var clicked = function(){
				var yourname = jQuery(this).children('form').children('#yourname').val();
				alert('Hello, ' + yourname);
				jQuery.fallr('hide');
			};
			jQuery.fallr('show', {
				buttons : {
					button1 : {text: 'Submit', onclick: clicked},
					button2 : {text: 'Cancel'}
				},
				content : '<p>Give me your name</p><form><input type="text" id="yourname" /'+'></form>',
				icon    : 'form'
			});
		},
		multiplechoices : function(){
			var clicked = function(n){
				alert(n);
			};
			jQuery.fallr('show', {
				buttons : {
					button1 : {text: 'Yes', onclick: function(){clicked(1)}},
					button3 : {text: 'No', onclick: function(){clicked(2)}},
					button4 : {text: 'Whatever', danger: true}
				},
				content : '<p>Pick one</p>',
				icon    : 'help'
			});
		},
		below : function(){
			jQuery.fallr('show', {
				content : '<p>You\'ve got a message</p>',
				position: 'bottom'
			});
		},
		middle : function(){
			jQuery.fallr('show', {
				content : '<p>Hello there</p>',
				position: 'center'
			});
		},
		close : function(){
			jQuery.fallr('show', {
				closeKey : true,
				closeOverlay : true,
				content : '<p>Click on overlay or press ESC to close this message</p>',
				icon: 'info'
			});
		},
		effect : function(){
			jQuery.fallr('show', {
				easingDuration    : 1000,
				easingIn    : 'easeOutBounce',
				easingOut   : 'easeInElastic',
				icon        : 'card',
				position    : 'center',
				content     : '<h4>Animation please</h4><p>Everyone wants animation.</p>'
			});
		},
		forms : function(){
			var login = function(){
				var user = jQuery(this).children('form').children('input[type="text"]').val();
				var pass = jQuery(this).children('form').children('input[type="password"]').val();
				if(user.length < 1 || pass.length < 1){
					alert('Invalid!\nPlease fill all required forms');
				} else {
					alert('username: '+user+'\npassword: '+pass);
					jQuery.fallr('hide');
				}
			}
			
			jQuery.fallr('show', {
				icon        : 'secure',
				width       : '320px',
				content     : '<h4>Sign in</h4>'
							+ '<form>'
							+     '<input placeholder="username" type="text"/'+'>'
							+     '<input placeholder="password" type="password"/'+'>'
							+ '</form>',
				buttons : {
					button1 : {text: 'Submit', onclick: login},
					button4 : {text: 'Cancel'}
				}
			});
		},
		callback : function(){
			var hide2 = function(){
				jQuery.fallr('hide', function(){
					alert('callback after 2nd hide');
				});
			};
			
			var hide1 = function(n){
				jQuery.fallr('hide', function(){
					alert('Hi, this is a callback after hide');
					jQuery.fallr('show', {
						content     : '<p>You choose ' + n + '</p>',
						position    : 'bottom',
						buttons     : {
										button1 : {text: 'OK', onclick: hide2}
						}              
					}, function(){
						alert('callback after 2nd show');
					});
				});
			};
								
			jQuery.fallr('show', {
				icon        : 'warning',
				content     : '<p>Yes or No?</p>',
				buttons     : {
								button1 : {text: 'Yes', onclick: function(){hide1('Yes');}},
								button2 : {text: 'No', onclick: function(){hide1('No');}}
				},
			}, function(){
				alert('Hi, this is a callback after show');
			});
		},
		size : function(){
			jQuery.fallr('show', {
				maxWidth: '800px',
				height  : '500px',
				width   : '600px',
				content : '<h4>Lorem Ipsum</h4><p>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur."</p>',
				icon    : 'config'
			});
		},
		autoresize : function(){
			var smaller = function(){
				jQuery.fallr('resize', {width: '420px', height: '350px'});
			};
			var bigger = function(){
				jQuery.fallr('resize', {width: '600px', height: '500px'});
			};
			jQuery.fallr('show', {
				content : '<h4>Click a button to resize</h4><p>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>',
				buttons : {
					button1 : {text: 'Smaller', onclick: smaller},
					button2 : {text: 'Bigger', onclick: bigger},
					button3 : {text: 'Cancel'}
				},
				width: '300px',
				height: '300px',
				position: 'center'
			}, function(){
				// on show callback
				jQuery.fallr('resize', {width: '400px', height: '400px'});
			});
		},
		fullsize : function(){
			var gap     = 20;
			var boxH    = jQuery(window).height() - gap;     // bottom gap
			var boxW    = jQuery(window).width() - gap * 2;  // left + right gap
			jQuery.fallr('show', {
				content : '<p>Fullscreen</p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
				width   : boxW,
				height  : boxH
			});
		},
		autoclose : function(){
			jQuery.fallr('show', {
				content     : '<p>This will be self closing</p>',
				autoclose   : 2000,
				icon        : 'warning'
			});
		},
		nooverlay : function(){
			jQuery.fallr('show', {
				content     : '<p>Yay, no overlay!</p>',
				icon        : 'smile',
				useOverlay  : false
			});
		},
		effect : function(){
			var blinkIt = function(){
				jQuery.fallr('blink');
			};
			var shakeIt = function(){
				jQuery.fallr('shake');
			};
			jQuery.fallr('show', {
				content     : '<h4>Special Effects</h4>',
				position    : 'center',
				icon        : 'wizard',
				buttons     : {
					button1 : {text: 'Blink', onclick: blinkIt},
					button2 : {text: 'Shake', onclick: shakeIt},
					button3 : {text: 'Cancel'}    
				}
			});
		},
		redirect : function(){
			var redirect = function(){
				window.location.href = "http://www.google.com";
			};

			jQuery.fallr({
				buttons : {
					button1 : { text: 'confirm', onclick : redirect },
					button2 : { text: 'cancel' }
				},
				content: '<p>please confirm to get redirected</p>'
			});
		}
	};
	jQuery('a[href^="#fallr-"]').click(function(){
		var id = jQuery(this).attr('href').substring(7);
		methods[id].apply(this,[this]);
		return false;
	});
});

//]]>