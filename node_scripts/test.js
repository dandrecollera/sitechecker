const fs = require("fs");
const path = require("path");

// Specify the directory where the file will be created
const directory = "/var/www/sitechecker/storage/app/public/screenshots";

// Specify the filename and content
const filename = "test.txt";
const content = "This is a test file created by a Node.js script.\n";

// Construct the full path to the file
const filepath = path.join(directory, filename);

// Write content to the file
fs.writeFile(filepath, content, (err) => {
    if (err) {
        console.error("Error:", err);
    } else {
        console.log(
            `File "${filename}" created successfully at "${directory}".`
        );
    }
});
