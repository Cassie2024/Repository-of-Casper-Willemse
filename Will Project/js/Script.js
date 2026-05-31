const images = [
  "images/properties/Mars_habitat.jpg",
  "images/properties/Mars_habitat_2.jpeg",
  "images/properties/venus_floating_habitat.jpg",
  "images/properties/Mars_Greenhouse.jpg",
];

const offerings = [
  "This Mars habitat is home to the most sophisticated habitats on any planetary surface.",
  "This Mars Habitat is one of the most beautiful habitats located deep in one of the many Martian canyons. It is shielded from the harmful solar rays and makes for spectacular scenery.",
  "This Earth home has a very robust and elegant design, combining comfort with nature.",
  "This habitat is a one-of-a-kind floating habitat. It uses the water vapor trapped in the Venusian clouds as a water source and has Earth-like pressure, with similar conditions to Earth despite the hellish Venusian surface."
];

let about_currentIndex = 0;

function changeImage() {
  const imgElement = document.getElementById("image-slider");
  const textElement = document.getElementById("offerings_text");
  about_currentIndex = (about_currentIndex + 1) % images.length; // Cycle through images
  imgElement.src = images[about_currentIndex];
  textElement.textContent = offerings[about_currentIndex];
}

// Call the function immediately to display the first image and text
changeImage();

// Automatically change images every 3 seconds (3000 ms)
setInterval(changeImage, 3000);

const region_imgs = [
  "images/planets/Arcadia.png",
  "images/planets/Earth.png",
  "images/planets/Venus.png",
  "images/planets/Mars.png"
];

const region_name =[
  "Arcadia",
  "Earth",
  "Venus",
  "Mars"
]

const region_description = [
  "Arcadia is state of a state of the art home for all the best and brightest of the solar system, with its wondorous view of space to its awe inspiring bliss of comfort. You are gauranteed a bright and innovative future. We have a list of appartments waiting for you, check it out now!",
  "This is Earth and was called home for bilions of years. Earth offers a wide range of unique experiences and attractions but most importantly it combines technological advancedment with nature in the most beautifull and breathtaking way, we have allot of bargains waiting for you from the most advanced smart home to the luxurious log cabins.",
  "Venus offers striking scenery and a breathtaking view of the stars, while the venusian surface is unlivable scientists have found that high above the clouds presures are similiar to earth with a breathable atmosphere and ububdance of water vapour clouds. We have managed to buy out many of the apartments inside the floating city to distribute to you the buyer.",
  "Mars is the first human colony outside of earth. Spearheaded by Elon Musk, it has surpassed expectations and allow us to distribute habitats and homes to you. we own many of the habitats in each colony, we have purchased the rights of habitation so you can easily get a habitat that doesnt dent your wallet excesively."
];

let region_currentIndex = 0;

function change_region_Image() {
  const regionimgElement = document.getElementById("hovered_region");
  const regiontextElement = document.getElementById("hovered_text");
  const regionheader = document.getElementById("region_header");

  regionimgElement.style.width="130%";
  regionimgElement.style.marginLeft="-15%";
  regionimgElement.style.marginTop="1%";
  

  region_currentIndex = (region_currentIndex + 1) % images.length;

  regionheader.textContent = region_name[region_currentIndex];
  regionimgElement.src = region_imgs[region_currentIndex];
  regiontextElement.textContent = region_description[region_currentIndex];

  if(region_name[region_currentIndex] ==="Arcadia"){
    const element = document.getElementById("Arcadia");
    element.style.marginTop="-40px";
    element.style.transition="margin-top 1s ease"
    document.getElementById("Mars").style.marginTop="0px";
    document.getElementById("Earth").style.marginTop="-30px";
    document.getElementById("Venus").style.marginTop="-50px";
  }else if(region_name[region_currentIndex] ==="Earth"){
    const element = document.getElementById("Earth");
    element.style.marginTop="-80px";
    element.style.transition="margin-top 1s ease"
    document.getElementById("Mars").style.marginTop="0px";
    document.getElementById("Arcadia").style.marginTop="0px";
    document.getElementById("Venus").style.marginTop="-50px";
  }else if(region_name[region_currentIndex] ==="Venus"){
    const element = document.getElementById("Venus");
    element.style.marginTop="-100px";
    element.style.transition="margin-top 1s ease"
    document.getElementById("Earth").style.marginTop="-30px";
    document.getElementById("Arcadia").style.marginTop="0px";
    document.getElementById("Mars").style.marginTop="0px";
  }else if(region_name[region_currentIndex] ==="Mars"){
    const element = document.getElementById("Mars");
    element.style.marginTop="-40px";
    element.style.transition="margin-top 1s ease"
    document.getElementById("Earth").style.marginTop="-30px";
    document.getElementById("Arcadia").style.marginTop="0px";
    document.getElementById("Venus").style.marginTop="-50px";
  }
}
change_region_Image();
// Automatically change images every 3 seconds (3000 ms)
setInterval(change_region_Image, 10000);

document.addEventListener('DOMContentLoaded', () => {
  // Fetch the JSON data from the file
  fetch('../Json/Agents.json')
      .then(response => {
          if (!response.ok) {
              throw new Error('Network response was not ok ' + response.statusText);
          }
          return response.json();
      })
      .then(agents => {
          // Loop through each agent and create elements to display
          const img1 = document.getElementById('Agent1');
          const img2 = document.getElementById('Agent2');
          const img3 = document.getElementById('Agent3');

          const lbl1 = document.getElementById('Agent1name');
          const lbl2 = document.getElementById('Agent2name');
          const lbl3 = document.getElementById('Agent3name');

          // Update the image sources and labels with agent data
          if (agents[0]) {
              img1.src = agents[0].img;
              lbl1.textContent = agents[0].agent_name;
          }
          if (agents[1]) {
              img2.src = agents[1].img;
              lbl2.textContent = agents[1].agent_name;
          }
          if (agents[2]) {
              img3.src = agents[2].img;
              lbl3.textContent = agents[2].agent_name;
          }
      })
      .catch(error => {
          console.error('There was a problem with the fetch operation:', error);
      });
});

let property_currentIndex = 0;

function changepropertyImage() {
  const imgElement = document.getElementById("image-slider");
  const textElement = document.getElementById("offerings_text");
  about_currentIndex = (about_currentIndex + 1) % images.length; // Cycle through images
  imgElement.src = images[about_currentIndex];
  textElement.textContent = offerings[about_currentIndex];
}

const ftimages = [
  "images/properties/Earth_8.jpg",
  "images/properties/Earth_9.jpg",
  "images/properties/Earth_4.jpg",
  "images/properties/Venus_39.jpg",
];

const ftofferings = [
  "This Mars habitat is home to the most sophisticated habitats on any planetary surface.",
  "This Mars Habitat is one of the most beautiful habitats located deep in one of the many Martian canyons. It is shielded from the harmful solar rays and makes for spectacular scenery.",
  "This Earth home has a very robust and elegant design, combining comfort with nature.",
  "This habitat is a one-of-a-kind floating habitat. It uses the water vapor trapped in the Venusian clouds as a water source and has Earth-like pressure, with similar conditions to Earth despite the hellish Venusian surface."
];

const ftnames =[
"Beautiful Villa",
"Modern House",
"Futuristic Smart Home",
"Winter Estates"

]
const ftprices =[
"R 2421000",
"R 10000000",
"R 3252000",
"R 1245641"
]
const ftplanets =[
 ["images/planets/Earth.png","Africa","South Africa"],
["images/planets/Venus.png","Volcunia","Region 12"],
["images/planets/Mars.png","Olympus mons","Central"],
["images/planets/Earth.png","Usa","Kansas"] 
]

let ftproperties = 0;

function changeptyImage() {
  const ptyimg = document.getElementById("property_img");
  const ptydesc = document.getElementById("property_description");
  const ptyname = document.getElementById("property_name");
  const price = document.getElementById("property_price");
  const ptyregionimg = document.getElementById("region_img");
  const ptylocation = document.getElementById("property_location");
  
  // Cycle through images
  ftproperties = (ftproperties + 1) % ftimages.length;
  
  ptyimg.src = ftimages[ftproperties];
  ptydesc.textContent = ftofferings[ftproperties];
  ptyname.textContent = ftnames[ftproperties];
  ptylocation.textContent = "Continent: " + ftplanets[ftproperties][1] + "  State: " + ftplanets[ftproperties][2];
  price.textContent = ftprices[ftproperties];
  ptyregionimg.src = ftplanets[ftproperties][0];
}

// Call the function immediately to display the first image and text
changeptyImage();

// Automatically change images every 3 seconds (3000 ms)
setInterval(changeptyImage, 3000);