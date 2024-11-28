
function showSection(sectionId) {
    // Hide all sections
    document.querySelectorAll('.wrapper').forEach(function(section) {
      section.classList.remove('active');
    });
    // Show the clicked section
    document.getElementById(sectionId).classList.add('active');
  }

const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});

loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});

