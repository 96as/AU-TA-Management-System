document.addEventListener('DOMContentLoaded', function() {
    const drawer = document.getElementById('drawer');
    const drawerToggle = document.getElementById('drawerToggle');
    const mainContent = document.getElementById('mainContent');

    // Toggle drawer width
    drawerToggle.addEventListener('click', function() {
      if (drawer.classList.contains('drawer-rail')) {
        drawer.classList.remove('drawer-rail');
        drawer.classList.add('drawer-full');
        mainContent.style.marginLeft = '256px';
        drawerToggle.innerHTML = '<i class="fas fa-chevron-left"></i>';
      } else {
        drawer.classList.remove('drawer-full');
        drawer.classList.add('drawer-rail');
        mainContent.style.marginLeft = '80px';
        drawerToggle.innerHTML = '<i class="fas fa-chevron-right"></i>';
      }
    });

    // Set initial main content margin
    if (drawer.classList.contains('drawer-rail')) {
      mainContent.style.marginLeft = '80px';
    } else {
      mainContent.style.marginLeft = '256px';
    }

    // Add event listeners to "View Details" buttons
    const viewDetailsButtons = document.querySelectorAll('.btn-details');
    viewDetailsButtons.forEach(button => {
      button.addEventListener('click', function() {
        // Get the course name from the parent card
        const card = this.closest('.card');
        const courseName = card.querySelector('.text-lg').textContent;
        const courseCode = card.querySelector('.text-sub').textContent.split('·')[0].trim();

        // In a real application, you'd redirect to a details page with the course ID
        alert(`Viewing details for ${courseName} (${courseCode})`);
        // window.location.href = `course-details.html?code=${courseCode}`;
      });
    });
  });