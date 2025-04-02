document.getElementById("uploadForm").addEventListener("submit", function(event) {
    event.preventDefault();

    let placeName = document.getElementById("placeName").value.trim();
    let locationDesc = document.getElementById("locationDesc").value.trim();
    let historyDesc = document.getElementById("historyDesc").value.trim();
    let architectureDesc = document.getElementById("architectureDesc").value.trim();
    let cultureDesc = document.getElementById("cultureDesc").value.trim();
    let funFacts = document.getElementById("funFacts").value.trim();
    let location = document.getElementById("location").value.trim();
    let imageFile = document.getElementById("imageUpload").files[0];
    let modelFile = document.getElementById("modelUpload").files[0];

    if (!placeName || !locationDesc || !historyDesc || !architectureDesc || !cultureDesc || !funFacts || !location || !imageFile || !modelFile) {
        alert("Please fill out all fields and upload the necessary files.");
        return;
    }

    let imagePath = `../places/${imageFile.name}`;
    let modelPath = `../places/${modelFile.name}`;
    let funFactsArray = funFacts.split(',').map(fact => `<li>✅ ${fact.trim()}</li>`).join('');

    let newPageContent = `
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>${placeName} - X-Plorer</title>
        <link rel="stylesheet" href="../places/style.css">
    </head>
    <body>
    
        <nav class="navbar">
            <div class="logo">X-Plorer</div>
            <ul class="nav-links">
                <li><a href="../index.html">Home</a></li>
                <li><a href="../pages/visit.html" class="active">Visit</a></li>
            </ul>
        </nav>
    
        <main>
            <section class="hero">
                <img src="${imagePath}" alt="${placeName}">
                <h1>${placeName}</h1>
            </section>

            <section id="explore" class="section">
                <h2>3D Explore</h2>
                <model-viewer src="${modelPath}" auto-rotate camera-controls shadow-intensity="1" disable-zoom disable-pan disable-tap></model-viewer>
                <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/4.0.0/model-viewer.min.js"></script>
            </section>

            <section id="review" class="section">
                <div class="description-container">
                    <h2>Description and Review</h2>
                    
                    <p><strong>📍 Location:</strong></p>
                    <p>${locationDesc}</p>

                    <p><strong>🏛️ Historical Significance:</strong></p>
                    <p>${historyDesc}</p>

                    <p><strong>🏗️ Architectural Marvel:</strong></p>
                    <ul>${architectureDesc.split('.').map(sentence => `<li>${sentence.trim()}.</li>`).join('')}</ul>

                    <p><strong>🌍 Cultural & Touristic Importance:</strong></p>
                    <ul>${cultureDesc.split('.').map(sentence => `<li>${sentence.trim()}.</li>`).join('')}</ul>

                    <p><strong>⭐ Fun Facts:</strong></p>
                    <ul>${funFactsArray}</ul>
                </div>
            </section>

            <section id="location" class="section">
                <h2>Location</h2>
                <div style="display: flex; justify-content: center; align-items: center; width: 100%; padding: 40px 0;">
                    <div id="embed-map-canvas" style="width: 900px; height: 400px; max-width: 90%; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(255, 255, 255, 0.2);">
                        <iframe 
                            style="height: 100%; width: 100%; border: 0; border-radius: 10px;" 
                            frameborder="0" 
                            allowfullscreen 
                            src="${location}">
                        </iframe>
                    </div>
                </div>
            </section>
        </main>

        <footer>
            <p>&copy; 2025 X-Plorer | Explore the world like never before</p>
        </footer>
    
    </body>
    </html>
    `;

    let newPagePath = `../places/${placeName.toLowerCase().replace(/\s+/g, '-')}.html`;

    localStorage.setItem(newPagePath, newPageContent);

    document.getElementById("successMessage").classList.remove("hidden");
    document.getElementById("newPageLink").href = newPagePath;

    // Path for new HTML file and image
let pageFileName = placeName.toLowerCase().replace(/\s+/g, '-') + ".html";
let imageFileName = imageFile.name;

// Add to visit/index.html DOM via localStorage (just simulating client-side; server-side required in real scenario)
let cardHTML = `
<a href="places/${pageFileName}" class="place-card">
    <img src="places/assets/${imageFileName}" alt="${placeName}">
    <div class="place-info">${placeName}</div>
</a>
`;

let visitIndexKey = "visitIndexCards";
let currentCards = localStorage.getItem(visitIndexKey) || "";
localStorage.setItem(visitIndexKey, currentCards + cardHTML);

});
