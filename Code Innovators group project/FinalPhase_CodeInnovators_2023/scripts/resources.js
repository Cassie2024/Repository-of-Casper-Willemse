    document.addEventListener("DOMContentLoaded", function () {
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileFormInput');
        const submitForm = document.getElementById('submitForm');
        const uploadButton = document.getElementById('uploadButton');
        const uploadResourceLinks = document.getElementById('upload_resource_links');

        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);    
            document.body.addEventListener(eventName, preventDefaults, false);  
        });

        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        // Handle dropped files
        dropZone.addEventListener('drop', handleDrop, false);

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight() {
            dropZone.classList.add('highlight');
        }

        function unhighlight() {
            dropZone.classList.remove('highlight');
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            handleFiles(files);
        }

        function handleFiles(files) {
            const dataTransfer = new DataTransfer();
            
            for (let i = 0; i < files.length; i++) {
                dataTransfer.items.add(files[i]);
                
                // Create a new div for the file name
                const fileNameDiv = document.createElement('div');
                fileNameDiv.textContent = files[i].name; // Set the text to the file name
                uploadResourceLinks.appendChild(fileNameDiv); // Append the file name to the links container
            }
            
            fileInput.files = dataTransfer.files;

            // Automatically submit the form to upload files
            submitForm.click(); // Submit the form
        }

        // Manual Upload Functionality
        uploadButton.addEventListener('click', function () {
            fileInput.click(); // Trigger hidden input click
        });

        fileInput.addEventListener('change', function () {
            handleFiles(fileInput.files); // Handle files from the input
        });
    });