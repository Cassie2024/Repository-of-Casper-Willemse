function createdesktop() {
    const body = document.getElementById('monitor-body');
    body.innerHTML = ''; // Clear monitor body

    const grid = document.createElement("div");
    grid.className = 'grid';

    const Items = 29;
    for (let i = 1; i < Items; i++) {
        const gridItem = document.createElement('div');
        gridItem.className = 'grid-Item'; // Match with the CSS class

        switch (i) {
            case 1:
                createButton(gridItem, '../Assets/Images/mail.png', 'Mail', 'Mail');
                break;
            case 8:
                createButton(gridItem, '../Assets/Images/map.png', 'Location', 'Location');
                break;
            case 15:
                createButton(gridItem, '../Assets/Images/contacts.png', 'Contacts', 'Contacts');
                break;
            case 28:
                createButton(gridItem, '../Assets/Images/recycling.png', 'Recycle bin');
                break;
        }

        grid.appendChild(gridItem);
    }

    body.appendChild(grid); // Append the grid to the monitor body
}

function createButton(gridItem, imageSrc, labelText, url = null) {
    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'button-container';
    buttonContainer.style.display = 'flex';
    buttonContainer.style.flexDirection = 'column';
    buttonContainer.style.alignItems = 'center';
    buttonContainer.style.padding = '-20px'; // Adjust padding as needed
    buttonContainer.style.color = "white";

    const button = document.createElement('button');
    button.className = 'image-button';
    button.style.backgroundColor = 'black';
    button.style.border = 'none';
    button.style.padding = '0'; // Adjust padding inside the button if needed

    if (url === 'Mail') {
        button.onclick = () => {
            displayForm();
        };
    } else if (url === 'Location') {
        button.onclick = () => {
            displayLocation(); // Redirect to the specified URL
        };
    } else if (url === 'Contacts') {
        button.onclick = () => {
            displaycontacts(); // Redirect to the specified URL
        };
    }

    const image = document.createElement('img');
    image.src = imageSrc;
    image.alt = labelText;
    image.style.width = '10px'; // Adjust width as needed
    image.style.height = '10px'; // Adjust height as needed

    button.appendChild(image);

    const label = document.createElement('div');
    label.className = 'label';
    label.textContent = labelText;
    label.style.fontSize = '4px'; // Adjust font size as needed
    label.style.marginTop = '-3px'; // Adjust margin above the label

    buttonContainer.appendChild(button);
    buttonContainer.appendChild(label);

    gridItem.appendChild(buttonContainer);
}

function displayForm() {
    const monitorBody = document.getElementById('monitor-body');
    monitorBody.innerHTML = ''; // Clear monitor body

    monitorBody.innerHTML = `
      <form class="form-container" id="form-monitor">
        <div class="form-group">
            <label for="email">Company Email</label>
            <input type="email" id="email" name="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$" required>
        </div>
        <div class="form-group">
            <label for="textarea">How Can We Help You?</label>
            <textarea required cols="50" rows="10" id="textarea" name="textarea"></textarea>
        </div>
        <div class="form-buttons">
            <button type="button" id="back" class="form-btn">Back</button>
            <button type="submit" id="submit" class="form-btn">Submit</button>
        </div>
      </form>`;

    document.getElementById("back").addEventListener("click", () => {
        createdesktop(); // Recreate the desktop view
    });

    document.getElementById("form-monitor").addEventListener("submit", (e) => {
        e.preventDefault();
        // Handle form submission here
        alert("Form submitted successfully!");
        createdesktop(); // Return to desktop view after submission
    });
}

function displaycontacts() {
    const monitorBody = document.getElementById('monitor-body');
    monitorBody.innerHTML = ''; // Clear monitor body

    monitorBody.innerHTML = `
    <div class="contacts-container">
      <div class="contacts-div">
        <p>Contacts</p>
        <button class="btn-contactlink">Janco Van Heerden- 20240185</button>
        <button class="btn-contactlink">Casper Hendriks - 20241167</button>
        <button class="btn-contactlink">Casper Andries Willemse - 20241237</button>
        <button class="btn-contactlink">Amy Baker - 20240116</button>
      </div>
      <button id="back" class="btn-back">Back</button>
    </div>`;

    document.getElementById("back").addEventListener("click", () => {
        createdesktop(); // Recreate the desktop view
    });
}

function displayLocation() {
    const monitorBody = document.getElementById('monitor-body');
    monitorBody.innerHTML = ''; // Clear monitor body

    monitorBody.innerHTML = `
    <form class="Location-container">
      <div class="address-div">
        <p>South Africa, Freestate</p>
        <p>Bloemfontein, Langenhoven Park, 9301</p>
        <p>1st Floor, Pretty Suites Office Block</p>
        <button id="back" class="form-submit-btn">Back</button>
      </div>
      <div class="address-div">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3486.761293538482!2d26.154309312000038!3d-29.083197987269457!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1e8fdb6a5867b92b%3A0x17e2c5b46d445057!2sPretty%20Suites!5e0!3m2!1sen!2sza!4v1665680929341!5m2!1sen!2sza" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
      </div>
    </form>`;

    document.getElementById("back").addEventListener("click", () => {
        createdesktop(); // Recreate the desktop view
    });
}

createdesktop();