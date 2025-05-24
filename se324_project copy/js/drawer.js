 // Sidebar toggle functionality
 const sidebar = document.getElementById('sidebar');
 const sidebarToggle = document.getElementById('sidebarToggle');
 const toggleIcon = document.getElementById('toggleIcon');
 const mainContent = document.querySelector('.main-content');

 sidebarToggle.addEventListener('click', () => {
   if (sidebar.classList.contains('drawer-rail')) {
     sidebar.classList.remove('drawer-rail');
     sidebar.classList.add('drawer-full');
     toggleIcon.classList.remove('fa-chevron-right');
     toggleIcon.classList.add('fa-chevron-left');
     mainContent.style.marginLeft = '256px';
   } else {
     sidebar.classList.remove('drawer-full');
     sidebar.classList.add('drawer-rail');
     toggleIcon.classList.remove('fa-chevron-left');
     toggleIcon.classList.add('fa-chevron-right');
     mainContent.style.marginLeft = '80px';
   }
 });

 // Initialize current date for the dashboard
 const dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };
 const currentDate = new Date().toLocaleDateString(undefined, dateOptions);
