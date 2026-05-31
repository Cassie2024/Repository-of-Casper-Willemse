
document.addEventListener('DOMContentLoaded', () => {
        // Fetch the JSON data from the file
    fetch('../Json/Properties.json')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(propertyData => {
        // Get the container where property cards will be added
        const propertyCardsContainer = document.getElementById('propertyCardsContainer');

        // Loop through each property and create a card
        propertyData.forEach(property => {
            const card = document.createElement('div');
            card.className = 'card';
            // Create the inner HTML for each card
            card.innerHTML = `
                <div>
                    <h3 style="font-size:18px;">${property.Name}</h3>
                    <p style="font-size:18px; width:150px;">${property.description}</p>
                </div>
                <div style="">
                    <h3 style="font-size:18px;">${property.price}</h3>
                    <div>
                        <img style=" width:150px; height:150px; border-radius:50%;" src="${property.img}" alt="${property.Name}">
                    </div>
                  <p style="font-size:18px;">${property.planet}</p>
                  <div class="info_box">
                        <p style="font-size:18px;">${property.kitchens} Kitchens</p>
                        <p style="font-size:18px;">${property.bedrooms} Bedrooms</p>
                        <p style="font-size:18px;">${property.bathrooms} Bathrooms</p>
                        <p style="font-size:18px;">${property.livingrooms} Living Rooms</p>
                        <p style="font-size:18px;">${property.garages} Garages</p> 
                    </div>
                </div>
            `;

            // Append each card to the container
            propertyCardsContainer.appendChild(card);
        });
});
});
