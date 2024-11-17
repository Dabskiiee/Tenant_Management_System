function showSection(sectionId) {
    // Hide all sections
    document.querySelectorAll('.wrapper').forEach(function(section) {
      section.classList.remove('active');
    });
    // Show the clicked section
    document.getElementById(sectionId).classList.add('active');
  }