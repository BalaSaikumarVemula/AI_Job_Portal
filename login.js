document.getElementById("loginForm").addEventListener("submit", function(e){
    e.preventDefault();

    const formData = new FormData(this);
    formData.append('login', '1');

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
