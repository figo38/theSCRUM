var UserMngt = Class.create({
	// Enable inline editing of each release
	enableInteraction: function(userlogin) {
		new PBInPlaceEditor('user-login-' + releaseId, {});
		new PBDeleteObject('user-delete-' + userlogin, "Delete the user", 'userrow-' + userlogin);
	},
	
	// Initialize the "Add Release" button
	initAddUserButton: function() {
		new PBAddButton('Add a new user', 'user', function(id){
			var user = new UserMngt();
			user.enableInteraction(id);
		});
	}	
});