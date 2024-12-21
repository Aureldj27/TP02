


document.getElementById('formulaire').addEventListener('submit', function(event) {
    event.preventDefault(); 
    var name = document.getElementById('name').value;
   
    localStorage.setItem('name', name);
    window.location.href = 'quiz.php'; 
});