// 1. Add this at the very top
const crypto = require('crypto');
if (!global.crypto) {
    global.crypto = crypto;
}

// 2. Then your existing requires
const express = require('express');
const mongoose = require('mongoose');
const bodyParser = require('body-parser');

const app = express();
app.use(bodyParser.json());


// Task 1: Configure MongoDB Connection
mongoose.connect('mongodb://localhost:27017/StudentDB')
    .then(() => console.log("Connected to StudentDB..."))
    .catch(err => console.error("Could not connect:", err));

// Task 2: Create Student Schema (Table fields)
const studentSchema = new mongoose.Schema({
    name: String,
    email: String,
    course: String
});

const Student = mongoose.model('Student', studentSchema);

// task 3 : Insert New Records (POST)
app.post('/register', async (req, res) => {
    try {
        const student = new Student({
            name: req.body.name,
            email: req.body.email,
            course: req.body.course
        });
        const result = await student.save();
        res.status(201).send(result);
    } catch (error) {
        res.status(400).send(error.message);
    }
});

//Task 4 & 5: Retrieve and Display Records (GET)
app.get('/students', async (req, res) => {
    try {
        const students = await Student.find();
        
        // Start building the HTML string
        let html = `
            <html>
            <head>
                <title>Student List</title>
                <style>
                    table { width: 80%; border-collapse: collapse; margin: 25px 0; font-family: sans-serif; }
                    th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
                    th { background-color: #4CAF50; color: white; }
                    tr:nth-child(even) { background-color: #f2f2f2; }
                </style>
            </head>
            <body>
                <h2>Registered Students</h2>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Course</th>
                    </tr>`;

        // Add a row for each student
        students.forEach(student => {
            html += `
                <tr>
                    <td>${student.name}</td>
                    <td>${student.email}</td>
                    <td>${student.course}</td>
                </tr>`;
        });

        html += `</table></body></html>`;
        
        res.send(html); // Send the HTML string to the browser
    } catch (error) {
        res.status(500).send("Error retrieving students");
    }
});

// Start the server
const port = 3000;
app.listen(port, () => console.log(`Listening on port ${port}...`));


// Run the application:
// node server.js