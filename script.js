function verif(){
    let username = document.getElementById("username").value;
    let mail = document.getElementById("email").value;
    let phone = document.getElementById("phone").value;
    
    console.log(typeof username);

    var mrigl = true;

    for(i=0;i<username.length;i++){
        if(username.charAt(i).toUpperCase()< 'A' || username.charAt(i).toUpperCase()> 'Z'){
            alert("username invalid");
            mrigl = false;
            break;
        }
    }

   
    if(isNaN(phone)||phone.length!=8){
        alert("phone invalid");
        mrigl = false;

    }
   

    if(mrigl==false){
        return false;
    }
    
}

document.getElementById('form').addEventListener('submit', function(event) {
    if (!verif()) {
      // If verification fails, prevent form submission (redirect)
      event.preventDefault();
    }
  });