jQuery(function() {
	/**
	 * Add participant
	 */
	$('#add-participant-button').live('click', function() {
		var $userId = $('#add_participant_field').val();
		var $topicId = $('#topic-id').val();
		
		if(!$userId) {
			showErrorFallr('Please select a user to add.');
			return;
		}
		
		if(!$topicId) {
			showErrorFallr('Sorry, Topic was not found.');
			return;
		}
		
		$.ajax({
		  url: "/admin/personalmessages/AddParticiapnt",
		  data: {"userId" : $userId, 'topicId': $topicId},
	      dataType: "json",
		  success: function(data){
			if(data.error && data.error != '') {
				showErrorFallr(data.error);
				return;
			}
			
			// Update participants list
			$('#participants-list').html(data.participantsList);
			
			// Update add participants form
			$('#add-participant-form').html(data.addParticipantsForm);
			
			// Update chosen
			UpdateChosen();
			
			// Confirmation message
			ajaxOK(data.html);
		  }
		});
	});
	
	/**
	 * Remove participant
	 */
	$('.remove-participant-button').live('click', function() {
		var $userId = $(this).attr('id').replace('participant-', '');
		var $topicId = $('#topic-id').val();
		
		if(!$userId) {
			showErrorFallr('Please select a user to remove.');
			return;
		}
		
		if(!$topicId) {
			showErrorFallr('Sorry, Topic was not found.');
			return;
		}
		
		$.ajax({
		  url: "/admin/personalmessages/RemoveParticipant",
		  data: {"userId" : $userId, 'topicId': $topicId},
	      dataType: "json",
		  success: function(data){
			if(data.error && data.error != '') {
				showErrorFallr(data.error);
				return;
			}
			
			// Update participants list
			$('#participants-list').html(data.participantsList);
			
			// Update add participants form
			$('#add-participant-form').html(data.addParticipantsForm);
			
			// Update chosen
			UpdateChosen();
			
			// Confirmation message
			ajaxOK(data.html);
		  }
		});
	});
});