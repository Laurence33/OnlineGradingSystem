# Senior High School - Online Grading System

> This application is **_not ready for production_**, it has various **_limitations_** and will **_not_** be able to
> handle **_huge amount of data_**. This is purely for my personal development and practice,
> the featured school does not own the app, it's just because I went to the stated school during my Senior High School.

## Sample User Interfaces

> Check them on the **/screenshots/** directory

## Technology Used

- ✅ **Xampp** - (Apache server and MySQL server)
- ✅ **PHP** - backend script
- ✅ **MariaDB** - database
- ✅ **Bootstrap** - front-end
- ✅ **JQuery** - client-side functionalities

## Terms

> **Credential** - _Username_ and _Password_ used to access the portal.
> **Class** - A group where a student belong to (mostly called **section**).
> **Subject/Course** - A series of lessons or lectures on a particular subject.
> **Subject Loading** - A subject/course assigned to a class
> **Subject Advising** - Subject loading assigned to a professor _Ex.: Professor X is teaching Web Development on Class BSCS3A_

## Features

### Admin

- Add/Edit Students
- Create/Reset Student Credential
- Add/Edit Professors
- Create/Reset Professor Credential
- Add/Edit Class
- Add/Edit Subjects
- Add/Edit Subject Loading
- Add/Edit Subject Advising

### Professor

- Add Component Tasks (Written Work, Performance Task, Quarterly Assessment).
- Declare/Edit result of a student on a task (grades/remarks will be auto computed).
- Post individual student grades using a button for each student on the table.
- Post updated grades on changes (changes to grades will not be posted unless the button is clicked)

### Student

- View posted final grades/ remarks on every subject/course they are enrolled in.

> **_All sides will have to login to use features_**

## Limitations

- 🛑 The app is designed for a **single school year only**
- 🛑 The app **cannot handle huge data**
- 🛑 The tables are **not paginated**
- 🛑 The app does **not use table joins on the SQL Queries**, but the Database Design is **Normalized**

## License

MIT
Feel free to use it on your learning! 🎉
