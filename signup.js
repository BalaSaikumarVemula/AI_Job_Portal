document.getElementById("signupForm").addEventListener("submit", function(e){
    e.preventDefault();

    const formData = new FormData(this);
    formData.append('signup', '1'); // Ensure PHP detects signup

    fetch("user.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        data = data.trim();
        if(data === "success"){
            window.location.href = "dashboard.php";
        } else {
            alert(data);
        }
    })
    .catch(error => console.error("Error:", error));
});
