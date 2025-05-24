// Function to toggle drawer
function toggleDrawer() {
    const drawer = document.getElementById('sidebar');
    const content = document.getElementById('main-content');
  
    if (drawer.classList.contains('drawer-rail')) {
      drawer.classList.remove('drawer-rail');
      drawer.classList.add('drawer-full');
      content.style.marginLeft = '256px';
    } else {
      drawer.classList.remove('drawer-full');
      drawer.classList.add('drawer-rail');
      content.style.marginLeft = '80px';
    }
  }
  
  // Attach event listener to sidebar toggle button
  document.addEventListener('DOMContentLoaded', function() {
    const drawer = document.getElementById('sidebar');
    drawer.classList.add('drawer-rail');
    document.getElementById('main-content').style.marginLeft = '80px';
  
    // Add event listener for sidebar toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
      sidebarToggle.addEventListener('click', toggleDrawer);
    }
  });
  
  // Store current TA context
  let currentTA = null;
  
  // Helper: Capitalize
  function capitalize(str) {
    return str.replace(/\w\S*/g, text => text.charAt(0).toUpperCase() + text.substr(1).toLowerCase());
  }
  
  // Open modal with TA data
  function openTaskModal(ta, courseCode) {
    currentTA = { ...ta, courseCode };
    const modal = document.getElementById('task-modal');
    const body = document.getElementById('modal-body');
    document.getElementById('modal-title').innerText = `Distribute Tasks for ${ta.ta_name} (${courseCode})`;
  
    // Create form HTML
    body.innerHTML = `
      <div class="task-field">
        <label>Proctoring Hours:</label>
        <input type="number" id="proctor-hours" min="0" value="${ta.proctor_hours || 0}">
      </div>
      <div class="task-field">
        <label>Correcting Hours:</label>
        <input type="number" id="correcting-hours" min="0" value="${ta.correcting_hours || 0}">
      </div>
      <div class="task-field">
        <label>Lab Hours:</label>
        <input type="number" id="lab-hours" min="0" value="${ta.lab_hours || 0}">
      </div>
      <div class="hours-summary">
        <span>Total Hours:</span>
        <span id="total-hours">0</span>
      </div>
    `;
  
    // Add input event listeners to update total
    ['proctor-hours', 'correcting-hours', 'lab-hours'].forEach(id => {
      document.getElementById(id).addEventListener('input', updateTotalHours);
    });
  
    // Show modal
    modal.style.display = 'flex';
    updateTotalHours(); // Initial total
  }
  
  // Update total hours display
  function updateTotalHours() {
    const proctor = parseInt(document.getElementById('proctor-hours').value) || 0;
    const correcting = parseInt(document.getElementById('correcting-hours').value) || 0;
    const lab = parseInt(document.getElementById('lab-hours').value) || 0;
    const total = proctor + correcting + lab;
    const totalElement = document.getElementById('total-hours');
    
    // Update text to show "total/total_assigned"
    totalElement.textContent = `${total}/${currentTA.total_assigned_hours}`;
    
    // Add color coding
    if (total > currentTA.total_assigned_hours) {
      totalElement.style.color = '#ff5252'; // Red if exceeding
    } else if (total === currentTA.total_assigned_hours) {
      totalElement.style.color = '#4caf50'; // Green if exact
    } else {
      totalElement.style.color = ''; // Default color if under
    }
  }
  
  // Close modal
  function closeTaskModal() {
    const modal = document.getElementById('task-modal');
    modal.style.display = 'none';
    currentTA = null;
  }
  
  // Save task distribution
  function saveTaskDistribution() {
    if (!currentTA) {
      alert('No TA selected');
      return;
    }
  
    const proctorHours = parseInt(document.getElementById('proctor-hours').value) || 0;
    const correctingHours = parseInt(document.getElementById('correcting-hours').value) || 0;
    const labHours = parseInt(document.getElementById('lab-hours').value) || 0;
  
    // Validate total hours
    const totalHours = proctorHours + correctingHours + labHours;
    if (totalHours <= 0) {
      alert('Total hours must be greater than 0');
      return;
    }
  
    // Validate against original total_assigned_hours
    if (totalHours > currentTA.total_assigned_hours) {
      alert(`Total hours (${totalHours}) cannot exceed the assigned hours (${currentTA.total_assigned_hours})`);
      return;
    }
  
    // Prepare form data
    const formData = new FormData();
    formData.append('course_code', currentTA.courseCode);
    formData.append('ta_name', currentTA.ta_name);
    formData.append('proctor_hours', proctorHours);
    formData.append('correcting_hours', correctingHours);
    formData.append('lab_hours', labHours);
    formData.append('total_assigned_hours', currentTA.total_assigned_hours); // Add original total
  
    // Send request
    fetch('../../php/extra_phps/update_ta_tasks.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Update UI
        const taItem = document.querySelector(`[data-ta-name="${currentTA.ta_name}"][data-course-code="${currentTA.courseCode}"]`).closest('.ta-item');
        taItem.querySelector('.ta-tasks-summary').innerHTML = `
          Labs: ${labHours}h | Proctoring: ${proctorHours}h | Correcting: ${correctingHours}h
        `;
        taItem.querySelector('.ta-hours').textContent = `${currentTA.total_assigned_hours} hrs/week`;
        
        // Close modal
        closeTaskModal();
        
        // Show success message
        alert('Tasks updated successfully');
      } else {
        alert('Error: ' + (data.error || 'Failed to update tasks'));
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Network error occurred. Please try again.');
    });
  }
  
  // Attach event listeners when DOM is loaded
  document.addEventListener('DOMContentLoaded', function() {
    // Attach click handlers to all distribute task buttons
    const buttons = document.querySelectorAll('.distribute-tasks-btn');
    console.log('Attaching listeners to', buttons.length, 'Distribute Tasks buttons');
    
    buttons.forEach(btn => {
      btn.addEventListener('click', function() {
        const taName = this.dataset.taName;
        const courseCode = this.dataset.courseCode;
        console.log('Distribute Tasks clicked:', { taName, courseCode });
        
        // Find TA data from the global taCourseData
        let taObj = null;
        for (const course of taCourseData) {
          if (course.course_code === courseCode) {
            taObj = course.tas.find(ta => ta.ta_name === taName);
            if (taObj) break;
          }
        }
        
        if (taObj) {
          openTaskModal(taObj, courseCode);
        } else {
          alert('Error: Could not find TA data');
        }
      });
    });
  });