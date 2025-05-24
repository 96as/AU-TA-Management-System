// Initialize charts
document.addEventListener('DOMContentLoaded', async function() {
    // Task Distribution Chart
    const taskCtx = document.getElementById('taskDistributionChart').getContext('2d');
    const taskChart = new Chart(taskCtx, {
      type: 'bar',
      data: {
        labels: [], 
        datasets: [
          {
            label: 'Marking',
            backgroundColor: '#093254',
            data: []
          },
          {
            label: 'Proctoring',
            backgroundColor: '#5783db',
            data: []
          },
          {
            label: 'Lab Supervision',
            backgroundColor: '#92c5de',
            data: []
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
          },
          title: {
            display: true,
            text: 'Task Hours Distribution by Course'
          }
        },
        scales: {
          x: {
            stacked: true,
          },
          y: {
            stacked: true,
            title: {
              display: true,
              text: 'Hours'
            }
          }
        }
      }
    });
  
    // Initialize workload chart with dynamic data
    const workloadChart = await initializeWorkloadChart();
    const taskDistributionChart = await updateTaskDistributionChart();
  
    // Filter change event handlers
    document.getElementById('semesterFilter').addEventListener('change', updateTaskDistributionChart);
    document.getElementById('departmentFilter').addEventListener('change', updateTaskDistributionChart);
  
    async function updateTaskDistributionChart() {
      const semester = document.getElementById('semesterFilter').value;
      const department = document.getElementById('departmentFilter').value;
  
      try {
        const courseHourSplit = await getCourseHourSplit();
        
        // Extract data for the chart
        const courseCodes = courseHourSplit.map(course => course.course_code);
        const markingHours = courseHourSplit.map(course => parseInt(course.marking_hours));
        const proctoringHours = courseHourSplit.map(course => parseInt(course.proctoring_hours));
        const labHours = courseHourSplit.map(course => parseInt(course.lab_hours));
  
        // Update the chart data
        taskChart.data.labels = courseCodes;
        taskChart.data.datasets[0].data = markingHours;
        taskChart.data.datasets[1].data = proctoringHours;
        taskChart.data.datasets[2].data = labHours;
  
        taskChart.update();
      } catch (error) {
        console.error('Error updating task distribution chart:', error);
      }
    }
  
    // Export button functionality
    document.querySelector('.export-btn').addEventListener('click', function() {
      alert('Exporting data to CSV... (This is a demo functionality)');
      // In a real application, this would trigger a CSV or PDF export
    });
  
    // Search functionality
    const searchInput = document.querySelector('.search-bar input');
    searchInput.addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      const tables = document.querySelectorAll('table');
  
      tables.forEach(table => {
        const rows = table.querySelectorAll('tbody tr');
  
        rows.forEach(row => {
          const text = row.textContent.toLowerCase();
          if(text.includes(searchTerm)) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      });
    });
  
    async function getTAHours() {
      try {
        const response = await fetch('../php/get_TAhours.php');
        const data = await response.json();
        if (data.success && data.data) {
          return data.data;
        } else {
          console.error('No TA hours found in response:', data);
          return [];
        }
      } catch (error) {
        console.error('Error fetching TA hours:', error);
        return [];
      }
    }
  
    async function getTAs() {
      try {
        const response = await fetch('../php/get_TAs_reports.php');
        const data = await response.json();
        if (data.ta_names) {
          return data.ta_names;
        } else {
          console.error('No TA names found in response:', data);
          return [];
        }
      } catch (error) {
        console.error('Error fetching TAs:', error);
        return [];
      }
    }
  
    async function getTotalCourses() {
      try {
        const response = await fetch('../php/get_total_courses.php');
        const data = await response.json();
        return data.total_courses;
      } catch (error) {
        console.error('Error fetching total courses:', error);
      }
    }
  
    async function getTotalTAs() {
      try {
        const response = await fetch('../php/get_total_TAs.php');
        const data = await response.json();
        return data.total_TAs;
      } catch (error) {
        console.error('Error fetching total TAs:', error);
      }
    }
  
    async function getTotalHours() {
      try {
        const response = await fetch('../php/get_total_hours.php');
        const data = await response.json();
        return data.total_hours;
      } catch (error) {
        console.error('Error fetching total hours:', error);
      }
    }
  
    async function getTotalInstructors() {
      try {
        const response = await fetch('../php/get_total_instructors.php');
        const data = await response.json();
        if (data.success) {
          return data.total_instructors || 0;
        } else {
          console.error('Error fetching total instructors:', data.message);
          return 0;
        }
      } catch (error) {
        console.error('Error fetching total instructors:', error);
        return 0;
      }
    }
  
    async function getTotalProctoringHours() {
      try {
        const response = await fetch('../php/get_proctoring_hours.php');
        const data = await response.json();
        return data.total_proctoring_hours;
      } catch (error) {
        console.error('Error fetching total proctoring hours:', error);
      }
    }
  
    async function getTotalCorrectingHours() {
      try {
        const response = await fetch('../php/get_correcting_hours.php');
        const data = await response.json();
        return data.total_correcting_hours;
      } catch (error) {
        console.error('Error fetching total correcting hours:', error);
      }
    }
  
    async function getTotalLabHours() {
      try {
        const response = await fetch('../php/get_lab_hours.php');
        const data = await response.json();
        return data.total_lab_hours;
      } catch (error) {
        console.error('Error fetching total lab hours:', error);
      }
    }
  
    async function getCourseHourSplit() {
      try {
        const response = await fetch('../php/get_course_hour_split.php');
        const data = await response.json();
        return data.data;
      } catch (error) {
        console.error('Error fetching course hour split:', error);
      }
    }
  
    async function getTAHourSplit() {
      try {
        const response = await fetch('../php/get_TA_hour_split.php');
        const data = await response.json();
        return data.data;
      } catch (error) {
        console.error('Error fetching TA hour split:', error);
      }
    }
    
  
     async function updateTotalCourses() {
      const totalCourses = await getTotalCourses();
      document.getElementById('totalCourses').innerHTML = totalCourses;
    }
  
    updateTotalCourses();
    
    async function updateCourseHourSplit() {
      try {
        const courseHourSplit = await getCourseHourSplit();
        const tableBody = document.getElementById('courseHourSplitTable');
        
        // Clear existing table rows
        tableBody.innerHTML = '';
        
        // Add new rows with the data
        courseHourSplit.forEach(course => {
          const row = document.createElement('tr');
  
          const totalHours = parseInt(course.marking_hours) + parseInt(course.proctoring_hours) + parseInt(course.lab_hours);
          
          const percentage = (totalHours / 120) * 100;
  
          row.innerHTML = `
            <td>${course.course_code}</td>
            <td>${course.course_name}</td>
            <td>${course.marking_hours}</td>
            <td>${course.proctoring_hours}</td>
            <td>${course.lab_hours}</td>
            <td>${totalHours}</td>
            <td>
              <div class="progress-bar-container">
                <div class="progress-bar" style="width: ${percentage}%"></div>
              </div>
            </td>
          `;
          
          tableBody.appendChild(row);
        });
      } catch (error) {
        console.error('Error updating course hour split table:', error);
      }
    }
  
    updateCourseHourSplit();
  
    async function updateTAHourSplit() {
      try {
        const TAHourSplit = await getTAHourSplit();
        const tableBody = document.getElementById('TAHourSplitTable');
        
        // Clear existing table rows
        tableBody.innerHTML = '';
        
        // Add new rows with the data
        TAHourSplit.forEach(TA => {
          const row = document.createElement('tr');
  
          const totalHours = parseInt(TA.marking_hours) + parseInt(TA.proctoring_hours) + parseInt(TA.lab_hours);
          
          const percentage = (totalHours / 15) * 100;
  
          row.innerHTML = `
            <td>${TA.ta_name}</td>
            <td>${TA.total_courses}</td>
            <td>${TA.marking_hours}</td>
            <td>${TA.proctoring_hours}</td>
            <td>${TA.lab_hours}</td>
            <td>${totalHours}</td>
            <td>
              <div class="progress-bar-container">
                <div class="progress-bar" style="width: ${percentage}%"></div>
              </div>
            </td>
          `;
          
          tableBody.appendChild(row);
        });
      } catch (error) {
        console.error('Error updating course hour split table:', error);
      }
    }
  
    updateTAHourSplit();
    
    async function updateTotalTAs() {
      const totalTAs = await getTotalTAs();
      document.getElementById('totalTAs').innerHTML = totalTAs;
    }
  
    updateTotalTAs();
  
    async function updateTotalHours() {
      const totalHours = await getTotalHours();
      document.getElementById('totalHours').innerHTML = totalHours;
    }
  
    updateTotalHours();
  
    async function updateTotalInstructors() {
      const totalInstructors = await getTotalInstructors();
      document.getElementById('totalInstructors').innerHTML = totalInstructors;
    }
  
    updateTotalInstructors();
  
    async function updateTotalProctoringHours() {
      const totalProctoringHours = await getTotalProctoringHours();
      document.getElementById('proctoringHours').innerHTML = totalProctoringHours;
    }
  
    updateTotalProctoringHours();
  
    async function updateTotalCorrectingHours() {
      const totalCorrectingHours = await getTotalCorrectingHours();
      document.getElementById('markingHours').innerHTML = totalCorrectingHours;
    }
  
    updateTotalCorrectingHours();
  
    async function updateTotalLabHours() {
      const totalLabHours = await getTotalLabHours();
      document.getElementById('labHours').innerHTML = totalLabHours;
    }
  
    updateTotalLabHours();
    
    async function initializeWorkloadChart() {
      try {
        const [taNames, taHours] = await Promise.all([getTAs(), getTAHours()]);
        
        if (!taNames.length || !taHours.length) {
          console.warn('No data available for workload chart');
          return null;
        }

        const workloadCtx = document.getElementById('workloadChart').getContext('2d');
        const workloadChart = new Chart(workloadCtx, {
          type: 'bar',
          data: {
            labels: taNames,
            datasets: [
              {
                label: 'Total Hours',
                backgroundColor: '#093254',
                data: taHours,
                borderWidth: 1
              },
              {
                label: 'Maximum Capacity',
                backgroundColor: 'rgba(220, 53, 69, 0.2)',
                borderColor: '#dc3545',
                borderWidth: 1,
                data: taNames.map(() => 15),
                type: 'line',
                fill: false,
                borderDash: [5, 5]
              }
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                position: 'top',
              },
              title: {
                display: true,
                text: 'TA Workload Analysis'
              },
              tooltip: {
                callbacks: {
                  label: function(context) {
                    return `${context.dataset.label}: ${context.raw} hours`;
                  }
                }
              }
            },
            scales: {
              y: {
                title: {
                  display: true,
                  text: 'Hours'
                },
                suggestedMin: 0,
                suggestedMax: 20
              },
              x: {
                grid: {
                  display: false
                }
              }
            }
          }
        });
  
        return workloadChart;
      } catch (error) {
        console.error('Error initializing workload chart:', error);
        return null;
      }
    }
  });