// Function to toggle drawer
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
        section.classList.remove('expanded');
        chevron.classList.remove('rotate');
    } else {
        section.style.maxHeight = section.scrollHeight + "px";
        section.classList.add('expanded');
        chevron.classList.add('rotate');
        
        // Add a small delay to ensure content is fully expanded
        setTimeout(() => {
            if (section.classList.contains('expanded')) {
                section.style.maxHeight = 'none';
            }
        }, 300);
    }
}

// Function to open the add course modal
function openAddCourseModal() {
    const modal = document.getElementById('add-course-modal');
    modal.classList.add('active');
    
    // Reset the form
    document.getElementById('addCourseForm').reset();
}

// Function to close the add course modal
function closeAddCourseModal() {
    const modal = document.getElementById('add-course-modal');
    modal.classList.remove('active');
}

// Function to populate select elements with data from the database
function populateSelect(selectId, type) {
    fetch(`../../php/fetch_select_data.php?type=${type}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(result => {
            if (result.success) {
                const select = document.getElementById(selectId);
                select.innerHTML = '<option value="">Select an option</option>';
                result.data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.value;
                    option.textContent = item.text;
                    select.appendChild(option);
                });
            } else {
                console.error('Error fetching data:', result.error);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Function to get course year section based on course code
function getCourseYearSection(courseYear) {
    switch(courseYear) {
        case '2001':
            return 'first-year';
        case '2002':
            return 'second-year';
        case '2003':
            return 'third-year';
        case '2004':
            return 'fourth-year';
        default:
            return null;
    }
}

// Function to submit the add course form
async function submitAddCourseForm() {
    // Get all form values
    const courseCode = document.getElementById('courseCode').value;
    const numStudents = document.getElementById('numStudents').value;
    const instructorId = document.getElementById('selectInstructor').value;
    const taSelect = document.getElementById('selectTA');
    const taName = taSelect.value ? taSelect.options[taSelect.selectedIndex].text : null;
    const numSections = document.getElementById('numSections').value;
    const taHours = document.getElementById('TaHours').value;

    // Basic validation for required fields
    if (!courseCode || !instructorId || !numSections) {
        alert('Please fill in all required fields (Course Code, Instructor, and Number of Sections).');
        return;
    }

    // Only validate TA hours if a TA is selected
    if (taSelect.value) {
        if (!taHours || parseInt(taHours) === 0) {
            alert('Please enter valid TA hours (greater than 0) if you select a TA.');
            return;
        }

        // Check TA hours with backend before proceeding
        try {
            const checkRes = await fetch('../../php/check_ta_hours.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ ta_name: taName, requested_hours: taHours })
            });
            const checkData = await checkRes.json();
            if (!checkData.success) {
                alert(checkData.message);
                return;
            }
        } catch (err) {
            alert('Error checking TA hours. Please try again.');
            return;
        }
    }

    console.log('Submitting form with data:', {
        courseCode,
        instructorId,
        taName,
        numSections,
        numStudents
    });

    // First, add to activecourses table
    const activeCourseData = {
        course_code: courseCode,
        instructor: instructorId,
        num_students: numStudents || null,
        num_sections: numSections,
        semester: 'Spring 2025',
        is_active: 1
    };

    // Send request to add active course
    fetch('../../php/add_active_course.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(activeCourseData)
    })
    .then(response => {
        console.log('Active course response:', response);
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Failed to add active course');
            });
        }
        return response.json();
    })
    .then(result => {
        console.log('Active course result:', result);
        if (result.success) {
            // Only add TA assignment if a TA was selected
            if (taSelect.value) {
                const taCourseData = {
                    ta_name: taName,
                    course_code: courseCode,
                    total_assigned_hours: taHours
                };
                console.log('Sending TA assignment:', taCourseData);

                // Send request to add TA assignment
                return fetch('../../php/add_ta_course.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(taCourseData)
                }).then(response => response.json());
            } else {
                // No TA selected, just return success
                return { success: true };
            }
        } else {
            throw new Error(result.message || 'Failed to add active course');
        }
    })
    .then(result => {
        console.log('Final result:', result);
        if (result.success) {
            alert(taSelect.value ? 'Course and TA assignment added successfully' : 'Course added successfully (No TA assigned)');
            closeAddCourseModal();
            fetchActiveCourses(); // Refresh the course list
            window.location.reload(); // Refresh the page
        } else {
            throw new Error(result.message || 'Failed to add TA assignment');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'An error occurred while adding the course');
    });
}

// Function to fetch active courses from the database
async function fetchActiveCourses() {
    try {
        console.log('Fetching active courses...');
        const response = await fetch('../../php/get_active_courses.php');
        if (!response.ok) {
            throw new Error('Failed to fetch courses');
        }
        const result = await response.json();
        console.log('Fetched courses:', result);
        
        if (result.success) {
            // Clear existing content
            ['first-year', 'second-year', 'third-year', 'fourth-year'].forEach(yearId => {
                const yearContent = document.getElementById(yearId);
                if (yearContent) {
                    yearContent.innerHTML = '';
                }
            });

            // Group courses by year
            const coursesByYear = {
                'first-year': [],
                'second-year': [],
                'third-year': [],
                'fourth-year': []
            };

            // Sort courses into their respective year sections
            result.data.forEach(course => {
                console.log('Processing course:', course);
                let yearSection;
                
                // Get year section based on course_year
                switch(course.course_year) {
                    case '2001':
                        yearSection = 'first-year';
                        break;
                    case '2002':
                        yearSection = 'second-year';
                        break;
                    case '2003':
                        yearSection = 'third-year';
                        break;
                    case '2004':
                        yearSection = 'fourth-year';
                        break;
                }

                if (yearSection) {
                    coursesByYear[yearSection].push(course);
                }
            });

            console.log('Grouped courses:', coursesByYear);

            // Render courses in their respective sections
            const renderedCourses = {};
            for (const [yearId, courses] of Object.entries(coursesByYear)) {
                const yearContent = document.getElementById(yearId);
                if (yearContent) {
                    if (courses.length === 0) {
                        yearContent.innerHTML = `
                            <div class="course-item">
                                <p class="text-sub">No Courses Added Yet</p>
                            </div>
                        `;
                    } else {
                        courses.forEach(course => {
                            if (!renderedCourses[course.course_code]) {
                                renderCourse(course, yearId);
                                renderedCourses[course.course_code] = true;
                            }
                        });
                    }
                }
            }
        } else {
            console.error('Error fetching courses:', result.message);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// Function to render a course in the UI
function renderCourse(course, yearSectionId) {
    console.log('Rendering course in section:', yearSectionId, course);
    const yearSection = document.getElementById(yearSectionId);
    const courseElement = document.createElement('div');
    courseElement.className = 'course-item';
    courseElement.id = `course-${course.course_code}`;

    // Log the course data to debug
    console.log('Course data:', {
        name: course.course_name,
        code: course.course_code,
        type: course.course_type,
        terms: course.terms_offered
    });

    // Get all TAs for this course
    fetch(`../../php/get_course_tas.php?course_code=${course.course_code}`)
        .then(response => response.json())
        .then(result => {
            let tasHtml = '';
            if (result.success && result.data.length > 0) {
                tasHtml = result.data.map(ta => 
                    `<p>TA: ${ta.ta_name} (${ta.total_assigned_hours} hours)</p>`
                ).join('');
            } else {
                tasHtml = '<p>TA: Not Assigned</p>';
            }

            courseElement.innerHTML = `
                <div class="course-header">
                    <div class="course-title-group">
                        <h3>${course.course_code} - ${course.course_name}</h3>
                    </div>
                    <div class="status-badge">${course.is_active ? 'Active' : 'Inactive'}</div>
                </div>
        
                <div class="course-subheader">
                    <p>${course.num_sections || 0} Section(s) </p>
                    <p>Type: ${course.course_type || 'N/A'} | Terms: ${course.terms_offered || 'N/A'}</p>
                    <p>Instructor: ${course.instructor_name || 'Not Assigned'}</p>
                    ${tasHtml}
                </div>
        
                <div class="course-actions">
                    <button class="edit-course-btn" onclick="openAddTaModal('${course.course_code}')">
                        <i class="mdi mdi-account-plus"></i> Add TA
                    </button>
                    
                    <button class="delete-course-btn" onclick="openRemoveTaModal('${course.course_code}')">
                        <i class="mdi mdi-account-remove"></i> Remove TA
                    </button>
                    
                    <button class="delete-course-btn" onclick="deleteCourse('${course.course_code}')">
                        <i class="mdi mdi-delete"></i> Delete Course
                    </button>
                </div>
            `;
        
            yearSection.appendChild(courseElement);
        })
        .catch(error => {
            console.error('Error fetching TAs:', error);
            // Fallback display if TA fetch fails
            courseElement.innerHTML = `
                <div class="course-header">
                    <div class="course-title-group">
                        <h3>${course.course_code} - ${course.course_name}</h3>
                    </div>
                    <div class="status-badge">${course.is_active ? 'Active' : 'Inactive'}</div>
                </div>
        
                <div class="course-subheader">
                    <p>${course.num_sections || 0} Section(s) </p>
                    <p>Type: ${course.course_type || 'N/A'} | Terms: ${course.terms_offered || 'N/A'}</p>
                    <p>Instructor: ${course.instructor_name || 'Not Assigned'}</p>
                    <p>TA: Not Assigned</p>
                </div>
        
                <div class="course-actions">
                    <button class="edit-course-btn" onclick="openAddTaModal('${course.course_code}')">
                        <i class="mdi mdi-account-plus"></i> Add TA
                    </button>
                    <button class="delete-course-btn" onclick="deleteCourse('${course.course_code}')">
                        <i class="mdi mdi-delete"></i> Delete
                    </button>
                </div>
            `;
            yearSection.appendChild(courseElement);
        });
}

// Function to open the add TA modal
function openAddTaModal(courseCode) {
    const modal = document.getElementById('add-ta-modal');
    modal.classList.add('active');
    
    // Store the course code for later use
    modal.dataset.courseCode = courseCode;
    
    // Reset the form
    document.getElementById('addTaForm').reset();
    
    // Populate TA select
    populateSelect('selectNewTA', 'tas');
}

// Function to close the add TA modal
function closeAddTaModal() {
    const modal = document.getElementById('add-ta-modal');
    modal.classList.remove('active');
}

// Function to submit the add TA form
async function submitAddTaForm() {
    const modal = document.getElementById('add-ta-modal');
    const courseCode = modal.dataset.courseCode;
    const taSelect = document.getElementById('selectNewTA');
    const taName = taSelect.options[taSelect.selectedIndex].text;
    const taHours = document.getElementById('newTaHours').value;

    // Validation
    if (!taSelect.value || !taName || !taHours || parseInt(taHours) === 0) {
        alert('Please fill in all required fields and ensure TA Hours is greater than 0.');
        return;
    }

    // Check TA hours with backend before proceeding
    try {
        const checkRes = await fetch('../../php/check_ta_hours.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ta_name: taName, requested_hours: taHours })
        });
        const checkData = await checkRes.json();
        if (!checkData.success) {
            alert(checkData.message);
            return;
        }
    } catch (err) {
        alert('Error checking TA hours. Please try again.');
        return;
    }

    // Prepare TA assignment data
    const taCourseData = {
        ta_name: taName,
        course_code: courseCode,
        total_assigned_hours: taHours
    };

    // Send request to add TA assignment
    fetch('../../php/add_ta_course.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(taCourseData)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Failed to add TA assignment');
            });
        }
        return response.json();
    })
    .then(result => {
        if (result.success) {
            alert('TA assigned successfully');
            closeAddTaModal();
            fetchActiveCourses(); // Refresh the course list
        } else {
            throw new Error(result.message || 'Failed to add TA assignment');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'An error occurred while adding the TA');
    });
}

// Function to edit a course
function editCourse(courseCode) {
    fetch(`../php/get_course.php?code=${courseCode}`)
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                const course = result.data;
                const modal = document.getElementById('add-course-modal');
                const programYearSpan = document.getElementById('program-year');
                
                programYearSpan.textContent = 'Edit Course';
                
                document.getElementById('courseCode').value = course.course_code;
                document.getElementById('numStudents').value = course.num_students || '';
                document.getElementById('numSections').value = course.num_sections || 1;
                
                if (course.instructor_id) {
                    document.getElementById('selectInstructor').value = course.instructor_id;
                }
                if (course.ta_id) {
                    document.getElementById('selectTA').value = course.ta_id;
                }
                
                modal.classList.add('active');
            } else {
                alert(result.message || 'Error fetching course details');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching course details');
        });
}

//Tamimi modified
// Function to delete a course
function deleteCourse(courseCode) {
    if (!confirm('Are you sure you want to delete this course?')) {
        return;
    }

    fetch('../../php/delete_course.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ courseCode: courseCode })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Course deleted successfully');
            const courseElement = document.getElementById(`course-${courseCode}`);
            if (courseElement) {
                courseElement.remove();
            }
            fetchActiveCourses();
        } else {
            alert(result.message || 'Error deleting course');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the course');
    });
}

// Initialize when the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize drawer
    const drawer = document.getElementById('sidebar'); // Changed from 'drawer' to 'sidebar'
    if (drawer) {
        drawer.classList.add('drawer-rail');
        const mainContent = document.getElementById('main-content');
        if (mainContent) {
            mainContent.style.marginLeft = '80px';
        }
    }

    // Populate select elements
    populateSelect('courseCode', 'courses');
    populateSelect('selectInstructor', 'instructors');
    populateSelect('selectTA', 'tas');

    // Fetch initial course data
    fetchActiveCourses();
    fetchSummaryStats();
});

// Function to populate select elements with data from the database
function populateSelect(selectId, type) {
    fetch(`../../php/fetch_select_data.php?type=${type}`)
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                const select = document.getElementById(selectId);
                // Clear existing options
                select.innerHTML = '<option value="">Select an option</option>';
                // Add new options
                result.data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.value;
                    option.textContent = item.text;
                    select.appendChild(option);
                });
            } else {
                console.error('Error fetching data:', result.error);
            }
        })
        .catch(error => console.error('Error:', error));
}

// Populate select elements when the page loads
document.addEventListener('DOMContentLoaded', function() {
    populateSelect('courseCode', 'courses');
    populateSelect('selectInstructor', 'instructors');
    populateSelect('selectTA', 'tas');
});

// Function to open the remove TA modal
function openRemoveTaModal(courseCode) {
    const modal = document.getElementById('remove-ta-modal');
    modal.classList.add('active');
    modal.dataset.courseCode = courseCode;
    document.getElementById('removeTaForm').reset();

    // Fetch TAs for this course and populate the select
    fetch(`../../php/get_course_tas.php?course_code=${courseCode}`)
        .then(response => response.json())
        .then(result => {
            const select = document.getElementById('selectRemoveTA');
            select.innerHTML = '<option value="">Select a TA</option>';
            if (result.success && result.data.length > 0) {
                result.data.forEach(ta => {
                    const option = document.createElement('option');
                    option.value = ta.ta_name;
                    option.textContent = `${ta.ta_name} (${ta.total_assigned_hours} hours)`;
                    select.appendChild(option);
                });
            }
        });
}

function closeRemoveTaModal() {
    const modal = document.getElementById('remove-ta-modal');
    modal.classList.remove('active');
}

function submitRemoveTaForm() {
    const modal = document.getElementById('remove-ta-modal');
    const courseCode = modal.dataset.courseCode;
    const taSelect = document.getElementById('selectRemoveTA');
    const taName = taSelect.value;

    if (!taName) {
        alert('Please select a TA to remove.');
        return;
    }

    if (!confirm(`Are you sure you want to remove TA "${taName}" from this course?`)) {
        return;
    }

    fetch('../../php/remove_ta_from_course.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ta_name: taName, course_code: courseCode })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('TA removed successfully');
            closeRemoveTaModal();
            fetchActiveCourses();
        } else {
            alert(result.message || 'Failed to remove TA');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while removing the TA');
    });
}

function fetchSummaryStats() {
    fetch('../../php/get_summary_stats.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('courses-count').textContent = data.courses_count;
                document.getElementById('instructors-count').textContent = data.instructors_count;
                document.getElementById('tas-count').textContent = data.tas_count;
                document.getElementById('total-ta-hours').textContent = data.total_ta_hours;
            }
        })
        .catch(err => console.error('Error fetching summary stats:', err));
}

// Call this after DOMContentLoaded and after any action that changes the stats
document.addEventListener('DOMContentLoaded', function() {
    fetchSummaryStats();
    // ...existing code...
});

// After any action that changes the stats, also call fetchSummaryStats()
// For example, after fetchActiveCourses, add:
// fetchSummaryStats();
// And after adding/removing a TA or course, call fetchSummaryStats();