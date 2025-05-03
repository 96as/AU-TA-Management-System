
//Logout alert to be added here


function toggleDrawer() {
  const drawer = document.getElementById('drawer');
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

// Function to toggle year sections
function toggleYearSection(sectionId) {
  const section = document.getElementById(sectionId);
  const header = event.currentTarget;
  const chevron = header.querySelector('.chevron-icon');

  if (section.style.maxHeight) {
    section.style.maxHeight = null;
    chevron.classList.remove('rotate');
  } else {
    section.style.maxHeight = section.scrollHeight + "px";
    chevron.classList.add('rotate');
  }
}

// Function to open the TA modal
function openTAModal(courseName) {
  const modal = document.getElementById('ta-modal');
  if (modal) {
    modal.classList.add('active');
    const courseNameElement = document.getElementById('course-name');
    if (courseNameElement) {
      courseNameElement.textContent = courseName || 'Course';
    }
  }
}

// Function to close the TA modal
function closeTAModal() {
  const modal = document.getElementById('ta-modal');
  if (modal) {
    modal.classList.remove('active');
  }
}

// Function to open the Course modal
function openCourseModal(yearSection) {
  const modal = document.getElementById('course-modal');
  if (modal) {
    modal.classList.add('active');
    const programNameElement = document.getElementById('program-name');
    if (programNameElement) {
      programNameElement.textContent = yearSection || '';
    }
  }
}

// Function to close the Course modal
function closeCourseModal() {
  const modal = document.getElementById('course-modal');
  if (modal) {
    modal.classList.remove('active');
  }
}

//  when the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
  const drawer = document.getElementById('drawer');
  drawer.classList.add('drawer-rail');
  document.getElementById('main-content').style.marginLeft = '80px';

  const addCourseBtns = document.querySelectorAll('.add-section-btn');
  addCourseBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      // Find the closest year container to determine which year we're adding to
      const yearContainer = this.closest('.year-container');
      const yearHeader = yearContainer.querySelector('.year-header h3').textContent;
      openCourseModal(yearHeader);
    });
  });
});

function openAddCourseModal(year) {
  const modal = document.getElementById('add-course-modal');
  const programYearSpan = document.getElementById('program-year');
  programYearSpan.textContent = year;
  modal.classList.add('active');
  renderSections(1);

  // Reset the form
  document.getElementById('addCourseForm').reset();
}

function closeAddCourseModal() {
  const modal = document.getElementById('add-course-modal');
  modal.classList.remove('active');
}

function submitAddCourseForm() {
  // Get form values
  const courseTitle = document.getElementById('courseTitle').value;
  const courseCode = document.getElementById('courseCode').value;

  if (!courseTitle || !courseCode) {
    alert('Please fill out all required fields');
    return;
  }

  alert('Course added successfully!');
  closeAddCourseModal();

}

function renderSections(number) {
  const container = document.getElementById('sectionsContainer');
  container.innerHTML = '';

  for (let i = 1; i <= number; i++) {
    const sectionDiv = document.createElement('div');
    sectionDiv.className = 'section-item';

    sectionDiv.innerHTML = `
      <span class="section-number">Section ${i}</span>
      <div class="section-field">
        <input type="text" class="section-input" placeholder="Section details">
      </div>
      <div class="section-times">
        <input type="text" class="times-input" placeholder="Times">
      </div>
    `;

    container.appendChild(sectionDiv);
  }
}

document.addEventListener('DOMContentLoaded', function() {
  const numSectionsInput = document.getElementById('numSections');
  if (numSectionsInput) {
    numSectionsInput.addEventListener('change', function() {
      const numSections = parseInt(this.value);
      renderSections(numSections);
    });
  }

  // REMEMBER WE NEED TO update the "Add Courses" buttons to open the modal with the correct year
  const addCourseBtns = document.querySelectorAll('.add-courses-btn');
  addCourseBtns.forEach(btn => {
    // Get the year from the closest year container if possible
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      let yearText = "1st Year";

      // Try to find the closest year container to determine which year to add the course to
      const yearContainer = this.closest('.year-container');
      if (yearContainer) {
        const yearHeader = yearContainer.querySelector('.year-header h3');
        if (yearHeader) {
          yearText = yearHeader.textContent;
        }
      }

      openAddCourseModal(yearText);
    });
  });
});






// Storage for course data
let coursesData = {
  'first-year': [],
  'second-year': [],
  'third-year': [],
  'fourth-year': []
};

// Track summary statistics
let statistics = {
  totalTAs: 0,
  totalCourses: 0,
  totalHours: 0
};

// Function to update summary statistics
function updateStatistics() {
  document.querySelector('.summary-item:nth-child(2) .summary-value').textContent = statistics.totalCourses;
  document.querySelector('.summary-item:nth-child(1) .summary-value').textContent = statistics.totalTAs;
  document.querySelector('.summary-item:nth-child(3) .summary-value').textContent = statistics.totalHours;

  const avgHours = statistics.totalCourses > 0 ?
    Math.round((statistics.totalHours / statistics.totalCourses) * 10) / 10 : 0;
  document.querySelector('.summary-item:nth-child(4) .summary-value').textContent = avgHours;
}

function openAddCourseModal(year) {
  const modal = document.getElementById('add-course-modal');
  const programYearSpan = document.getElementById('program-year');
  programYearSpan.textContent = year;

  // Store the year section ID
  modal.dataset.yearSection = getYearSectionId(year);

  modal.classList.add('active');
  renderSections(1);

  // Reset the form
  document.getElementById('addCourseForm').reset();
}

function getYearSectionId(yearText) {
  if (yearText.includes('1st')) return 'first-year';
  if (yearText.includes('2nd')) return 'second-year';
  if (yearText.includes('3rd')) return 'third-year';
  if (yearText.includes('4th')) return 'fourth-year';
  return 'first-year'; // Default
}

function closeAddCourseModal() {
  const modal = document.getElementById('add-course-modal');
  modal.classList.remove('active');
}

function submitAddCourseForm() {
  // Get form values
  const courseTitle = document.getElementById('courseTitle').value;
  const courseCode = document.getElementById('courseCode').value;
  const numStudents = document.getElementById('numStudents').value;
  const selectedTA = document.getElementById('selectTA');
  const taName = selectedTA.options[selectedTA.selectedIndex].text;
  const taValue = selectedTA.value;
  const additionalInfo = document.getElementById('additionalInfo').value;

  // Get sections data
  const numSections = parseInt(document.getElementById('numSections').value);
  const sections = [];

  const sectionElements = document.querySelectorAll('.section-item');
  sectionElements.forEach((element, index) => {
    const sectionDetails = element.querySelector('.section-field input').value;
    const sectionTimes = element.querySelector('.section-times input').value;
    sections.push({
      number: index + 1,
      details: sectionDetails,
      times: sectionTimes
    });
  });

  if (!courseTitle || !courseCode) {
    alert('Please fill out all required fields');
    return;
  }

  // Get target year section
  const modal = document.getElementById('add-course-modal');
  const yearSectionId = modal.dataset.yearSection;

  // Create course object
  const course = {
    id: Date.now(),
    title: courseTitle,
    code: courseCode,
    numStudents: numStudents || '0',
    ta: taValue ? { name: taName, value: taValue } : null,
    sections: sections,
    additionalInfo: additionalInfo,
    createdAt: new Date()
  };

  // Add to storage
  coursesData[yearSectionId].push(course);

  // Update statistics
  statistics.totalCourses++;
  if (taValue) statistics.totalTAs++;
  updateStatistics();

  // Render the course in UI
  renderCourse(course, yearSectionId);

  // Close modal
  closeAddCourseModal();
}

function renderCourse(course, yearSectionId) {
  const yearSection = document.getElementById(yearSectionId);

  // Check if this is the first course
  if (yearSection.querySelector('.text-sub')) {
    // Remove the "No Courses Added Yet" message
    yearSection.innerHTML = '';
  }

  // Create course element
  const courseElement = document.createElement('div');
  courseElement.className = 'course-item';
  courseElement.id = `course-${course.id}`;

  // Parse section schedule text
  let scheduleText = '';
  if (course.sections && course.sections.length > 0 && course.sections[0].times) {
    scheduleText = course.sections[0].times;
  }

  // Abbreviated department for avatar
  const deptMatch = course.code.match(/^([A-Za-z]+)/);
  const deptCode = deptMatch ? deptMatch[0] : 'SE';

  // Create HTML for course
  courseElement.innerHTML = `
    <div class="course-header">
      <h3>${course.title}</h3>
      <div class="status-badge">Course Active</div>
    </div>

    <div class="course-subheader">
      <p><strong>${course.code}</strong> | ${course.sections.length} Section(s) | Spring 2025</p>
      <p>Schedule: ${scheduleText || 'Not specified'}</p>
    </div>

    <div class="section-title">
      <span><i class="mdi mdi-account-group"></i> Assigned Teaching Assistants</span>
      <button class="add-ta-btn" onclick="openTAModal('${course.title}')">
        <i class="fas fa-plus"></i>
        Add TA
      </button>
    </div>

    <div class="assignment-content">
      <div class="ta-list">
        ${course.ta ? `
        <div class="ta-item">
          <div class="ta-info">
            <div class="ta-avatar">${course.ta.name.substring(0, 2).toUpperCase()}</div>
            <div class="ta-details">
              <div class="ta-name">${course.ta.name.split(' - ')[0]}</div>
              <div class="ta-type">${course.ta.name.includes('Graduate') ? 'Graduate Teaching Assistant' : 'Undergraduate Teaching Assistant'}</div>
            </div>
          </div>
          <div class="hours-input">
            <input type="number" value="10" min="1" max="20"> hrs/week
          </div>
        </div>
        ` : `
        <p class="no-tas-message">No TAs assigned yet</p>
        `}
      </div>
    </div>

    <div class="course-actions">
      <button class="edit-course-btn" onclick="editCourse(${course.id})">
        <i class="mdi mdi-pencil"></i> Edit
      </button>
      <button class="delete-course-btn" onclick="deleteCourse(${course.id}, '${yearSectionId}')">
        <i class="mdi mdi-delete"></i> Delete
      </button>
    </div>
  `;

  yearSection.appendChild(courseElement);

  // Make sure section is expanded
  const yearContainer = yearSection.closest('.year-container');
  const chevron = yearContainer.querySelector('.chevron-icon');
  yearSection.style.maxHeight = yearSection.scrollHeight + "px";
  chevron.classList.add('rotate');
}
// Enhance the editCourse function to actually edit courses
function editCourse(courseId) {
  // Find the course in our data
  let foundCourse = null;
  let yearSectionId = null;

  // Search through all year sections to find the course
  for (const yearId in coursesData) {
    const courseIndex = coursesData[yearId].findIndex(c => c.id === courseId);
    if (courseIndex !== -1) {
      foundCourse = coursesData[yearId][courseIndex];
      yearSectionId = yearId;
      break;
    }
  }

  if (!foundCourse) {
    alert('Course not found');
    return;
  }

  // Open the edit modal (we'll reuse the add course modal)
  const modal = document.getElementById('add-course-modal');
  const programYearSpan = document.getElementById('program-year');

  // Convert yearSectionId to display text
  let yearText = '';
  if (yearSectionId === 'first-year') yearText = '1st Year';
  else if (yearSectionId === 'second-year') yearText = '2nd Year';
  else if (yearSectionId === 'third-year') yearText = '3rd Year';
  else if (yearSectionId === 'fourth-year') yearText = '4th Year';

  programYearSpan.textContent = yearText;
  modal.dataset.yearSection = yearSectionId;
  modal.dataset.editCourseId = courseId; // Flag that we're editing an existing course

  // Fill the form with existing course data
  document.getElementById('courseTitle').value = foundCourse.title;
  document.getElementById('courseCode').value = foundCourse.code;
  document.getElementById('numStudents').value = foundCourse.numStudents || '';

  // Set TA if one exists
  const selectTA = document.getElementById('selectTA');
  if (foundCourse.ta) {
    // Try to find the matching option
    for (let i = 0; i < selectTA.options.length; i++) {
      if (selectTA.options[i].value === foundCourse.ta.value) {
        selectTA.selectedIndex = i;
        break;
      }
    }
  } else {
    selectTA.selectedIndex = 0; // Select the default "-- Select TA --" option
  }

  // Set additional info
  document.getElementById('additionalInfo').value = foundCourse.additionalInfo || '';

  // Set up sections
  const numSections = foundCourse.sections ? foundCourse.sections.length : 1;
  document.getElementById('numSections').value = numSections;
  renderSections(numSections);

  // Fill in section data
  if (foundCourse.sections && foundCourse.sections.length > 0) {
    const sectionElements = document.querySelectorAll('.section-item');
    foundCourse.sections.forEach((section, index) => {
      if (index < sectionElements.length) {
        sectionElements[index].querySelector('.section-field input').value = section.details || '';
        sectionElements[index].querySelector('.section-times input').value = section.times || '';
      }
    });
  }

  // Change the submit button text to "Update Course"
  const submitBtn = modal.querySelector('.add-course-btn');
  submitBtn.textContent = 'Update Course';
  submitBtn.onclick = function() {
    updateCourse(courseId, yearSectionId);
  };

  // Show the modal
  modal.classList.add('active');
}

// Function to update an existing course
function updateCourse(courseId, yearSectionId) {
  // Get form values
  const courseTitle = document.getElementById('courseTitle').value;
  const courseCode = document.getElementById('courseCode').value;
  const numStudents = document.getElementById('numStudents').value;
  const selectedTA = document.getElementById('selectTA');
  const taName = selectedTA.options[selectedTA.selectedIndex].text;
  const taValue = selectedTA.value;
  const additionalInfo = document.getElementById('additionalInfo').value;

  // Get sections data
  const numSections = parseInt(document.getElementById('numSections').value);
  const sections = [];

  const sectionElements = document.querySelectorAll('.section-item');
  sectionElements.forEach((element, index) => {
    const sectionDetails = element.querySelector('.section-field input').value;
    const sectionTimes = element.querySelector('.section-times input').value;
    sections.push({
      number: index + 1,
      details: sectionDetails,
      times: sectionTimes
    });
  });

  if (!courseTitle || !courseCode) {
    alert('Please fill out all required fields');
    return;
  }

  // Find the course in our data to update it
  const courseIndex = coursesData[yearSectionId].findIndex(c => c.id === courseId);
  if (courseIndex === -1) {
    alert('Course not found');
    return;
  }

  // Get the old course data for statistics update
  const oldCourse = coursesData[yearSectionId][courseIndex];

  // Create updated course object
  const updatedCourse = {
    id: courseId,
    title: courseTitle,
    code: courseCode,
    numStudents: numStudents || '0',
    ta: taValue ? { name: taName, value: taValue } : null,
    sections: sections,
    additionalInfo: additionalInfo,
    createdAt: oldCourse.createdAt,
    updatedAt: new Date(),
    taAssignments: oldCourse.taAssignments || [] // Preserve existing TA assignments
  };

  // Update in storage
  coursesData[yearSectionId][courseIndex] = updatedCourse;

  // Update UI
  const courseElement = document.getElementById(`course-${courseId}`);
  if (courseElement) {
    courseElement.remove();
  }

  // Render the updated course
  renderCourse(updatedCourse, yearSectionId);

  // Close modal
  closeAddCourseModal();

  // Reset the submit button text and function
  const modal = document.getElementById('add-course-modal');
  const submitBtn = modal.querySelector('.add-course-btn');
  submitBtn.textContent = 'Add Course';
  submitBtn.onclick = submitAddCourseForm;
}

// Reset the form and button when closing the modal
function closeAddCourseModal() {
  const modal = document.getElementById('add-course-modal');
  modal.classList.remove('active');

  // Reset the submit button
  const submitBtn = modal.querySelector('.add-course-btn');
  submitBtn.textContent = 'Add Course';
  submitBtn.onclick = submitAddCourseForm;

  // Clear the editCourseId flag
  delete modal.dataset.editCourseId;
}

// Update the submitAddCourseForm function to handle TA assignments
function submitAddCourseForm() {
  // Get form values
  const courseTitle = document.getElementById('courseTitle').value;
  const courseCode = document.getElementById('courseCode').value;
  const numStudents = document.getElementById('numStudents').value;
  const selectedTA = document.getElementById('selectTA');
  const taName = selectedTA.options[selectedTA.selectedIndex].text;
  const taValue = selectedTA.value;
  const additionalInfo = document.getElementById('additionalInfo').value;

  // Get sections data
  const numSections = parseInt(document.getElementById('numSections').value);
  const sections = [];

  const sectionElements = document.querySelectorAll('.section-item');
  sectionElements.forEach((element, index) => {
    const sectionDetails = element.querySelector('.section-field input').value;
    const sectionTimes = element.querySelector('.section-times input').value;
    sections.push({
      number: index + 1,
      details: sectionDetails,
      times: sectionTimes
    });
  });

  if (!courseTitle || !courseCode) {
    alert('Please fill out all required fields');
    return;
  }

  // Get target year section
  const modal = document.getElementById('add-course-modal');
  const yearSectionId = modal.dataset.yearSection;

  // Check if we're editing an existing course
  const editCourseId = modal.dataset.editCourseId;
  if (editCourseId) {
    updateCourse(parseInt(editCourseId), yearSectionId);
    return;
  }

  // Create course object
  const course = {
    id: Date.now(),
    title: courseTitle,
    code: courseCode,
    numStudents: numStudents || '0',
    ta: taValue ? { name: taName, value: taValue } : null,
    sections: sections,
    additionalInfo: additionalInfo,
    createdAt: new Date(),
    taAssignments: [] // New property for multiple TA assignments
  };

  // Add to storage
  coursesData[yearSectionId].push(course);

  // Update statistics
  statistics.totalCourses++;
  if (taValue) statistics.totalTAs++;
  updateStatistics();

  // Render the course in UI
  renderCourse(course, yearSectionId);

  // Close modal
  closeAddCourseModal();
}

// Function to open modal for assigning multiple TAs to a course
function openTAModal(courseTitle, courseId, yearSectionId) {
  // Create modal if it doesn't exist
  if (!document.getElementById('ta-assignment-modal')) {
    createTAAssignmentModal();
  }

  const modal = document.getElementById('ta-assignment-modal');

  // Find the course
  let course = null;
  if (!yearSectionId) {
    // Search through all year sections to find the course by title
    for (const yearId in coursesData) {
      course = coursesData[yearId].find(c => c.id === courseId || c.title === courseTitle);
      if (course) {
        yearSectionId = yearId;
        courseId = course.id;
        break;
      }
    }
  } else {
    course = coursesData[yearSectionId].find(c => c.id === courseId);
  }

  if (!course) {
    alert('Course not found');
    return;
  }

  // Set modal data
  modal.dataset.courseId = course.id;
  modal.dataset.yearSectionId = yearSectionId;

  // Set course title in modal
  document.getElementById('ta-modal-course-title').textContent = course.title;

  // Load existing TA assignments
  loadTAAssignments(course);

  // Show the modal
  modal.classList.add('active');
}

// Create the TA assignment modal
function createTAAssignmentModal() {
  const modalHtml = `
    <div id="ta-assignment-modal" class="course-modal">
      <div class="modal-content">
        <div class="modal-header">
          <div class="modal-title">Assign TAs to <span id="ta-modal-course-title"></span></div>
          <button class="close-modal" onclick="closeTAModal()">×</button>
        </div>
        <div class="modal-body">
          <div class="current-assignments-container">
            <h4>Current TA Assignments</h4>
            <div id="current-ta-assignments" class="ta-list">
              <!-- Current assignments will be loaded here -->
              <p class="no-tas-message">No TAs assigned yet</p>
            </div>
          </div>

          <div class="new-assignment-form">
            <h4>Add New TA Assignment</h4>
            <div class="form-row">
              <div class="form-group">
                <label for="newTASelect">Teaching Assistant</label>
                <select id="newTASelect" class="form-control">
                  <option value="" selected>-- Select TA --</option>
                  <option value="rashid">Rashid - Graduate Teaching Assistant</option>
                  <option value="aziz">Aziz - Graduate Teaching Assistant</option>
                  <option value="john">John - Undergraduate Teaching Assistant</option>
                  <option value="safia">Safia - Undergraduate Teaching Assistant</option>
                  <option value="yara">Yara - Graduate Teaching Assistant</option>
                </select>
              </div>

              <div class="form-group">
                <label for="taHoursPerWeek">Hours/Week</label>
                <input type="number" id="taHoursPerWeek" class="form-control" value="10" min="1" max="20">
              </div>
            </div>

            <div class="form-group">
              <label>Assign to Sections</label>
              <div id="section-checkboxes" class="section-checkbox-list">
                <!-- Section checkboxes will be added here -->
              </div>
            </div>

            <button class="add-ta-btn" style="margin-top: 15px;" onclick="addTAAssignment()">
              <i class="fas fa-plus"></i>
              Add TA Assignment
            </button>
          </div>
        </div>
        <div class="modal-footer">
          <button class="cancel-btn" onclick="closeTAModal()">Close</button>
          <button class="save-btn" onclick="saveTAAssignments()">Save Assignments</button>
        </div>
      </div>
    </div>
  `;

  // Append the modal to the body
  const modalDiv = document.createElement('div');
  modalDiv.innerHTML = modalHtml;
  document.body.appendChild(modalDiv.firstElementChild);

  // Add styles for the new modal
  const style = document.createElement('style');
  style.textContent = `
    .current-assignments-container {
      margin-bottom: 20px;
    }

    .current-assignments-container h4,
    .new-assignment-form h4 {
      margin-top: 0;
      margin-bottom: 10px;
      font-size: 1rem;
      color: #333;
    }

    .ta-assignment-item {
      background-color: #f8f9fa;
      border: 1px solid #e9ecef;
      border-radius: 5px;
      padding: 10px 15px;
      margin-bottom: 10px;
    }

    .ta-assignment-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 5px;
    }

    .ta-name-hours {
      font-weight: 500;
    }

    .remove-ta-btn {
      background-color: #e74c3c;
      color: white;
      border: none;
      border-radius: 4px;
      padding: 4px 8px;
      font-size: 0.8rem;
      cursor: pointer;
    }

    .section-assignments {
      font-size: 0.9rem;
      color: #666;
    }

    .section-checkbox-list {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 5px;
    }

    .section-checkbox {
      display: flex;
      align-items: center;
    }

    .section-checkbox input {
      margin-right: 5px;
    }

    .save-btn {
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      padding: 8px 16px;
      cursor: pointer;
    }
  `;
  document.head.appendChild(style);
}

// Load TA assignments into the modal
function loadTAAssignments(course) {
  const assignmentsContainer = document.getElementById('current-ta-assignments');
  const sectionCheckboxes = document.getElementById('section-checkboxes');

  // Clear existing content
  assignmentsContainer.innerHTML = '';
  sectionCheckboxes.innerHTML = '';

  // Add section checkboxes
  if (course.sections && course.sections.length > 0) {
    course.sections.forEach((section, index) => {
      const sectionLabel = section.details || `Section ${section.number}`;
      const sectionTime = section.times || 'No time specified';

      const checkboxDiv = document.createElement('div');
      checkboxDiv.className = 'section-checkbox';
      checkboxDiv.innerHTML = `
        <input type="checkbox" id="section-${section.number}" value="${section.number}">
        <label for="section-${section.number}">${sectionLabel} (${sectionTime})</label>
      `;
      sectionCheckboxes.appendChild(checkboxDiv);
    });
  } else {
    sectionCheckboxes.innerHTML = '<p>No sections defined for this course</p>';
  }

  // Display existing TA assignments
  if (course.taAssignments && course.taAssignments.length > 0) {
    course.taAssignments.forEach((assignment, index) => {
      const assignmentDiv = document.createElement('div');
      assignmentDiv.className = 'ta-assignment-item';
      assignmentDiv.dataset.index = index;

      // Format section assignments text
      let sectionsText = 'No specific sections assigned';
      if (assignment.sections && assignment.sections.length > 0) {
        const sectionNumbers = assignment.sections.map(s => `Section ${s}`);
        sectionsText = `Assigned to: ${sectionNumbers.join(', ')}`;
      }

      assignmentDiv.innerHTML = `
        <div class="ta-assignment-header">
          <div class="ta-name-hours">${assignment.taName} (${assignment.hoursPerWeek} hrs/week)</div>
          <button class="remove-ta-btn" onclick="removeTAAssignment(${index})">
            <i class="mdi mdi-delete"></i> Remove
          </button>
        </div>
        <div class="section-assignments">${sectionsText}</div>
      `;

      assignmentsContainer.appendChild(assignmentDiv);
    });
  } else {
    assignmentsContainer.innerHTML = '<p class="no-tas-message">No TAs assigned yet</p>';
  }
}

// Add a new TA assignment
function addTAAssignment() {
  const taSelect = document.getElementById('newTASelect');
  const taValue = taSelect.value;
  const taName = taSelect.options[taSelect.selectedIndex].text;
  const hoursPerWeek = parseInt(document.getElementById('taHoursPerWeek').value);

  if (!taValue) {
    alert('Please select a TA');
    return;
  }

  // Get selected sections
  const selectedSections = [];
  const sectionCheckboxes = document.querySelectorAll('#section-checkboxes input[type="checkbox"]:checked');
  sectionCheckboxes.forEach(checkbox => {
    selectedSections.push(parseInt(checkbox.value));
  });

  // Get course and modal data
  const modal = document.getElementById('ta-assignment-modal');
  const courseId = parseInt(modal.dataset.courseId);
  const yearSectionId = modal.dataset.yearSectionId;

  // Find the course
  const courseIndex = coursesData[yearSectionId].findIndex(c => c.id === courseId);
  if (courseIndex === -1) {
    alert('Course not found');
    return;
  }

  const course = coursesData[yearSectionId][courseIndex];

  // Initialize taAssignments array if it doesn't exist
  if (!course.taAssignments) {
    course.taAssignments = [];
  }

  // Add new assignment
  course.taAssignments.push({
    taValue: taValue,
    taName: taName,
    hoursPerWeek: hoursPerWeek,
    sections: selectedSections,
    assignedAt: new Date()
  });

  // Update statistics
  statistics.totalTAs++;
  statistics.totalHours += hoursPerWeek;
  updateStatistics();

  // Reload assignments in the modal
  loadTAAssignments(course);

  // Reset form
  taSelect.selectedIndex = 0;
  document.getElementById('taHoursPerWeek').value = 10;
  sectionCheckboxes.forEach(checkbox => {
    checkbox.checked = false;
  });
}

// Remove a TA assignment
function removeTAAssignment(index) {
  const modal = document.getElementById('ta-assignment-modal');
  const courseId = parseInt(modal.dataset.courseId);
  const yearSectionId = modal.dataset.yearSectionId;

  // Find the course
  const courseIndex = coursesData[yearSectionId].findIndex(c => c.id === courseId);
  if (courseIndex === -1) {
    alert('Course not found');
    return;
  }

  const course = coursesData[yearSectionId][courseIndex];

  if (course.taAssignments && index < course.taAssignments.length) {
    // Update statistics
    statistics.totalTAs--;
    statistics.totalHours -= course.taAssignments[index].hoursPerWeek;
    updateStatistics();

    // Remove the assignment
    course.taAssignments.splice(index, 1);

    // Reload assignments in the modal
    loadTAAssignments(course);
  }
}

// Save all TA assignments
function saveTAAssignments() {
  const modal = document.getElementById('ta-assignment-modal');
  const courseId = parseInt(modal.dataset.courseId);
  const yearSectionId = modal.dataset.yearSectionId;

  // Find the course
  const courseIndex = coursesData[yearSectionId].findIndex(c => c.id === courseId);
  if (courseIndex === -1) {
    alert('Course not found');
    return;
  }

  const course = coursesData[yearSectionId][courseIndex];

  // Re-render the course to update the UI
  renderCourse(course, yearSectionId);

  // Close the modal
  closeTAModal();

  alert('TA assignments saved successfully!');
}

// Close the TA assignment modal
function closeTAModal() {
  const modal = document.getElementById('ta-assignment-modal');
  modal.classList.remove('active');
}

// Update renderCourse function to display multiple TAs
function renderCourse(course, yearSectionId) {
  const yearSection = document.getElementById(yearSectionId);

  // Check if this is the first course
  if (yearSection.querySelector('.text-sub')) {
    // Remove the "No Courses Added Yet" message
    yearSection.innerHTML = '';
  }

  // Create course element
  const courseElement = document.createElement('div');
  courseElement.className = 'assignment-container';
  courseElement.id = `course-${course.id}`;

  // Parse section schedule text
  let scheduleText = '';
  let locationText = '';
  if (course.sections && course.sections.length > 0) {
    if (course.sections[0].times) scheduleText = course.sections[0].times;
    if (course.sections[0].details) locationText = course.sections[0].details;
  }

  // Calculate total hours assigned
  let totalHoursAssigned = 0;
  if (course.taAssignments && course.taAssignments.length > 0) {
    totalHoursAssigned = course.taAssignments.reduce((sum, assignment) => sum + assignment.hoursPerWeek, 0);
  }
  // Default max hours (could be calculated based on student count in the future)
  const maxHours = 40;

  // Generate TA HTML
  let taListHTML = '';
  if (course.taAssignments && course.taAssignments.length > 0) {
    course.taAssignments.forEach(assignment => {
      // Format section assignments text
      let sectionsText = 'All sections';
      if (assignment.sections && assignment.sections.length > 0) {
        const sectionNumbers = assignment.sections.map(s => `Section ${s}`);
        sectionsText = sectionNumbers.join(', ');
      }

      // Get first two letters of TA name for avatar
      const avatarText = assignment.taName.split(' - ')[0].substring(0, 2).toUpperCase();

      taListHTML += `
        <div class="ta-item">
          <div class="ta-info">
            <div class="ta-avatar">${avatarText}</div>
            <div class="ta-details">
              <div class="ta-name">${assignment.taName.split(' - ')[0]}</div>
              <div class="ta-type">${assignment.taName.includes('Graduate') ? 'Graduate Teaching Assistant' : 'Undergraduate Teaching Assistant'}</div>
              <div class="ta-sections">Assigned to: ${sectionsText}</div>
            </div>
          </div>
          <div class="hours-input">
            <span>${assignment.hoursPerWeek}</span> hrs/week
          </div>
        </div>
      `;
    });
  } else {
    taListHTML = `
      <div class="ta-item">
        <p style="text-align: center; width: 100%; color: #666;">No TAs assigned yet</p>
      </div>
    `;
  }

  // Create HTML for course
  courseElement.innerHTML = `
    <div class="course-header">
      <h3>${course.title}</h3>
      <div class="status-badge">Assignments In Progress</div>
    </div>

    <div class="course-subheader">
      <p><strong>${course.code}</strong> | ${course.sections.length} Section(s) | Spring 2025</p>
      <p>Schedule: ${scheduleText || 'Not specified'} ${locationText ? '| ' + locationText : ''}</p>
    </div>

    <div class="section-title">
      <span><i class="mdi mdi-account-group"></i> Assigned Teaching Assistants</span>
      <div class="hours-badge">${totalHoursAssigned} / ${maxHours} Hours Used</div>
    </div>

    <div class="assignment-content">
      <div class="ta-list">
        ${taListHTML}
      </div>
      <button class="add-ta-btn" style="margin-top: 10px;" onclick="openTAModal('${course.title}', ${course.id}, '${yearSectionId}')">
        <i class="fas fa-plus"></i>
        ${course.taAssignments && course.taAssignments.length > 0 ? 'Add Another TA' : 'Add TA'}
      </button>
    </div>

    <div class="course-actions">
      <button class="edit-course-btn" onclick="editCourse(${course.id})">
        <i class="mdi mdi-pencil"></i> Edit
      </button>
      <button class="delete-course-btn" onclick="deleteCourse(${course.id}, '${yearSectionId}')">
        <i class="mdi mdi-delete"></i> Delete
      </button>
    </div>
  `;

  yearSection.appendChild(courseElement);

  // Make sure section is expanded
  const yearContainer = yearSection.closest('.year-container');
  const chevron = yearContainer.querySelector('.chevron-icon');
  yearSection.style.maxHeight = yearSection.scrollHeight + "px";
  chevron.classList.add('rotate');
}



const taData = [
  {
    id: 1,
    name: 'Lara',
    avatar: 'LA',
    type: 'Graduate Teaching Assistant',
    course: 'Software Engineering Principles',
    totalHours: 15,
    hours: 15,
    tasks: {
      labs: 6,
      proctoring: 5,
      correcting: 4,
      officeHours: 0,
      prepTime: 0
    }
  },
  {
    id: 2,
    name: 'Sara',
    avatar: 'SA',
    type: 'Undergraduate Teaching Assistant',
    course: 'Software Engineering Principles',
    totalHours: 12,
    hours: 12,
    tasks: {
      labs: 4,
      proctoring: 3,
      correcting: 3,
      officeHours: 2,
      prepTime: 0
    }
  },
  {
    id: 3,
    name: 'Mohamad',
    avatar: 'MAD',
    type: 'Graduate Teaching Assistant',
    course: 'Software Engineering Principles',
    totalHours: 9,
    hours: 9,
    tasks: {
      labs: 3,
      proctoring: 2,
      correcting: 2,
      officeHours: 1,
      prepTime: 1
    }
  }
];

function renderTAs() {
  const container = document.querySelector('.ta-list');
  container.innerHTML = '';

  taData.forEach(ta => {
    const taItem = document.createElement('div');
    taItem.className = 'ta-item';
    taItem.innerHTML = `
      <div class="ta-info">
        <div class="ta-avatar">${ta.avatar}</div>
        <div class="ta-details">
          <div class="ta-name">${ta.name}</div>
          <div class="ta-type">${ta.type}</div>
        </div>
      </div>
      <div class="hours-input">
        <input type="number" value="${ta.hours}" min="1" max="20" data-ta-id="${ta.id}"  readonly class="read-only"> hrs/week  .
        <button class="distribute-tasks-btn" data-ta-id="${ta.id}">Distribute Tasks</button>
      </div>
    `;
    container.appendChild(taItem);
  });

  document.querySelectorAll('input[type="number"]').forEach(input => {
    input.addEventListener('input', e => {
      const id = parseInt(e.target.dataset.taId);
      const ta = taData.find(t => t.id === id);
      if (ta) {
        ta.hours = parseInt(e.target.value) || 0;
      }
    });
  });

  document.querySelectorAll('.distribute-tasks-btn').forEach(button => {
    button.addEventListener('click', e => {
      const id = parseInt(e.target.dataset.taId);
      const ta = taData.find(t => t.id === id);
      if (ta) openTaskModal(ta);
    });
  });
}

let currentTA = null;

function openTaskModal(ta) {
  currentTA = ta;
  const modal = document.getElementById('task-modal');
  const body = document.getElementById('modal-body');
  document.getElementById('modal-title').innerText = `Distribute Tasks for ${ta.name}`;

  body.innerHTML = '';
  for (let key in ta.tasks) {
    body.innerHTML += `
      <div class="task-field">
        <label>${capitalize(key)}</label>
        <input type="number" value="${ta.tasks[key]}" min="0" data-task="${key}" />
      </div>
    `;
  }

  modal.style.display = 'flex';
}

function closeTaskModal() {
  document.getElementById('task-modal').style.display = 'none';
  currentTA = null;
}

function saveTaskDistribution() {
  if (!currentTA) return;

  const inputs = document.querySelectorAll('#modal-body input');
  let total = 0;
  inputs.forEach(input => {
    const key = input.dataset.task;
    const val = parseInt(input.value) || 0;
    currentTA.tasks[key] = val;
    total += val;
  });

  if (total > currentTA.totalHours) {
    alert(`Total task hours (${total}) exceed assigned hours (${currentTA.totalHours})!`);
    return;
  }

  closeTaskModal();
}

function capitalize(str) {
  return str.charAt(0).toUpperCase() + str.slice(1);
}

document.addEventListener('DOMContentLoaded', renderTAs);






// (THISS IS THE MAIMN SUDEBAR USED REST ARE FOR DIFFERENT TRIES PAGES)
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
