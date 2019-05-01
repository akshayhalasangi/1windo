   /* $('#user-form').on('submit',function(){
        var fields;
        fields = $("form").serialize();       
        var formData = new FormData(form);
        var password = $('#Val_Password').val();          
        var email = $('#Val_Email').val();
        var name = $('#Val_FullName').val();
        var userId = 7;

        $.ajax({ 
            type: "POST",
            url: admin_url + 'Users/User',         
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            success: function(response){ 
            $('#cover_image-error').hide();
            $('#profile_image-error').hide(); 
              var obj = $.parseJSON(response);
              firebase.auth().createUserWithEmailAndPassword(email, password).then(function(user) {
            
                user.updateProfile({
                    displayName: name

                }).then(function() {
                    alert(1);
                     writeUserData(userId, name, email);
                }, function(error) {
                    alert(error);
                });        
            }, function(error) {
                // Handle Errors here.
                var errorCode = error.code;            
                var errorMessage = error.message;
                // [START_EXCLUDE]
                if (errorCode == 'auth/weak-password') {
                    alert('The password is too weak.');
                } else {
                    console.error(error);
                }
                // [END_EXCLUDE]
            });
            }
        });
            
 });            

function writeUserData(userId, name, email) {    
    firebase.database().ref('users/' + userId).set({
    username: name,
    email: email,

    });
}*/