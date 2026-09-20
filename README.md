# Teaching Assistants Management System

A web-based system for managing teaching assistants (TAs), courses, assignments, working hours, and task distributions at Alfaisal University.

> **Course:** SE 324 – Web Application Development  
> **Project:** Teaching Assistants Management System

## Overview

The Teaching Assistants Management System digitizes the process of managing TAs, which was previously handled primarily on paper. The system provides role-based dashboards for managers, instructors, and teaching assistants, helping users manage courses, assignments, working hours, and reports in one place.

## Key Features

### Managers

- Select and activate courses from the preloaded course catalog.
- Enter and update course details.
- Add and remove teaching assistants.
- Assign TAs to active courses.
- Set total working hours for TAs.
- Remove TAs from courses or remove courses from the active course list.
- View system-wide reports covering TA assignments and hour distributions.

### Instructors

- View assigned courses and their teaching assistants.
- View total TA hours assigned to each course.
- Distribute TA hours across tasks or course needs.
- Track total, distributed, and remaining hours.
- View TA summary reports.

### Teaching Assistants

- View all assigned courses.
- View total working hours allocated per course.
- View hours distributed across tasks or weeks.
- Access information in a view-only dashboard without modifying course or hour data.

## Technology Stack

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** Relational database managed through the project schema
- **Development practices:** Git and collaborative version control

## System Roles and Access

| Role | Main Responsibilities |
| --- | --- |
| Manager | Manage courses, TAs, assignments, working hours, and system-wide reports |
| Instructor | Manage TA hours for assigned courses and review related reports |
| TA | View assigned courses, working hours, and task distributions |

## Database Structure

The system database includes the following core tables:

- **Courses** – Preloaded list of SE courses.
- **Active Courses** – Courses selected and configured by managers.
- **Managers** – Accounts with system-wide management privileges.
- **Instructors** – Preloaded faculty records.
- **TAs** – Teaching assistants added and managed by managers.
- **TA Course** – Relationship table connecting teaching assistants with assigned courses.

## Course Categorization

The system uses course codes to categorize courses by academic year. For example:

- `1**` – Year 1 course
- `2**` – Year 2 course
- `3**` – Year 3 course
- `4**` – Year 4 course

## Getting Started

### Prerequisites

To run the project locally, install or configure:

- A web server capable of running PHP, such as Apache or XAMPP.
- PHP and a supported relational database system.
- A web browser.

### Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/96as/SE324-project.git
   cd SE324-project
   ```

2. Configure the database using the schema and database files included in the project.
3. Update the PHP database connection settings to match your local environment.
4. Place the project in your web server's document root.
5. Start the web server and database service.
6. Open the project through your local server in a browser.

> Database configuration details may vary depending on the local development environment. Review the PHP connection files before running the application.

## User Guide

### Manager Workflow

1. Log in through the shared login screen.
2. Open **Course Management** and select a course from the preloaded catalog.
3. Enter the required course details.
4. Open **TA Management** to add or remove TAs.
5. Assign TAs to active courses and configure their total working hours.
6. Use the reports section to review assignments and hour distributions.

### Instructor Workflow

1. Log in to access a dashboard customized to the instructor's courses.
2. Select a course under **Manage TA Hours**.
3. Review the TAs assigned to the course.
4. Distribute working hours according to course requirements.
5. Review real-time total and remaining hour information.

### TA Workflow

1. Log in to access a personalized, view-only dashboard.
2. Review all assigned courses.
3. Check total hours allocated for each course.
4. Review how hours are distributed across tasks or weeks.

## Project Team

| Member | Role | Main Contributions |
| --- | --- | --- |
| Abdul Rahman Salameh (230326) | Project Manager | Coordinated meetings, timelines, task assignments, code reviews, and frontend/backend development |
| Tariq Ahmad Dabbagh (230279) | Documentation & System Design | Designed dashboard layouts, user journeys, use cases, and project documentation |
| Belal Othman (230031) | HTML/CSS | Assisted with login, registration, user profile, HTML, and CSS implementation |
| Abdulrahman Mahmalji (230474) | Database Administrator | Designed the database schema, ER diagram, and database integration |
| Mohammad Deri (230151) | HTML/CSS/JavaScript | Implemented major interface designs and JavaScript functionality |
| Abdulaziz AlTamimi (230714) | PHP/JavaScript | Connected frontend features to backend functionality |
| Basil Muhammad Ali (230653) | PHP/JavaScript | Connected frontend features to backend functionality |
| Abdullah Damati | HTML/CSS | Focused on interface design and visual details |

## Development Responsibilities

- **Project management:** Abdul Rahman Salameh
- **HTML/CSS:** Mohammad Deri, Abdullah Damati, Belal Othman
- **Database administration:** Abdulrahman Mahmalji
- **PHP/JavaScript:** Abdulaziz AlTamimi, Basil Muhammad Ali
- **Documentation and system design:** Tariq Ahmad Dabbagh

## Project Phases

1. **Requirements and Design** – Defined system functionality, user journeys, use cases, and interface structure.
2. **Database Schema** – Designed the database and relationships between courses, users, TAs, and assignments.
3. **UI Development** – Built role-specific dashboards and panels using HTML and CSS.
4. **Backend Integration** – Added functionality using PHP and JavaScript.
5. **Testing and Deployment** – Tested the system and prepared the final project deployment.

## Known Limitations

- The system does not currently include email or in-app notifications.
- The database contains a limited dataset, including only SE courses and a subset of university instructors.
- Default passwords are based on the first part of a user's email address followed by `1234`, which is not suitable for production use.
- Password strength rules and secure password-reset functionality are not currently implemented.
- The user details page was started but removed from the deployed version because its PHP integration was incomplete.
- Manager registration exists primarily to satisfy the project requirements and demonstrate the registration workflow.
- Instructor reports currently use the same general report layout as manager reports instead of being limited to the instructor's own courses and TAs.

## Future Improvements

- Add email and in-app notifications for assignments, course changes, and hour distributions.
- Integrate with university databases to provide complete course, instructor, and student information.
- Replace the current login process with a secure university authentication solution, such as Microsoft authentication or Moodle-compatible authentication.
- Complete and restore the user details page.
- Restrict instructor reports to the courses and TAs assigned to the logged-in instructor.
- Add stronger password policies, secure password hashing, password reset, and account recovery features.
- Expand automated testing and improve deployment documentation.

## Lessons Learned

- Clearly defined roles reduced task overlap and improved accountability.
- Git and consistent branching practices supported collaborative development.
- Testing individual features before merging helped reduce integration problems.
- Consistent naming conventions improved code readability and maintainability.
- Early database and user-flow planning made frontend and backend integration easier.

## Academic Context

This project was developed as part of **SE 324 – Web Application Development**. It demonstrates requirements analysis, interface design, relational database modeling, PHP backend development, JavaScript integration, role-based functionality, testing, and deployment planning.
