document.getElementById("uploadForm").addEventListener("submit", async function (event) {
    event.preventDefault();
  
    const getVal = id => document.getElementById(id).value.trim();
    const getFile = id => document.getElementById(id).files[0];
  
    const placeName = getVal("placeName");
    const locationDesc = getVal("locationDesc");
    const historyDesc = getVal("historyDesc");
    const architectureDesc = getVal("architectureDesc");
    const cultureDesc = getVal("cultureDesc");
    const funFacts = getVal("funFacts");
    const mapEmbed = getVal("mapEmbed");
  
    const coverImage = getFile("coverImage");
    const cardImage = getFile("cardImage");
    const ratingImage = getFile("ratingImage");
    const modelFile = getFile("modelUpload");
  
    if (!placeName || !coverImage || !cardImage || !ratingImage || !modelFile) {
      alert("Please fill all required fields and upload all files.");
      return;
    }
  
    async function uploadToBlob(file) {
      const formData = new FormData();
      formData.append("file", file);
      const response = await fetch("/api/blob-upload", {
        method: "POST",
        body: formData,
      });
      const result = await response.json();
      return result.url;
    }
  
    // Upload all assets
    const [coverImageURL, cardImageURL, ratingImageURL, modelURL] = await Promise.all([
      uploadToBlob(coverImage),
      uploadToBlob(cardImage),
      uploadToBlob(ratingImage),
      uploadToBlob(modelFile)
    ]);
  
    // Prepare final data object
    const placeData = {
      name: placeName,
      desc: `${placeName} is one of the world’s most iconic landmarks.`,
      locationDesc,
      historyDesc,
      architectureDesc,
      cultureDesc,
      funFacts: funFacts.split(",").map(f => f.trim()),
      mapEmbed,
      coverImageURL,
      cardImageURL,
      ratingImageURL,
      modelURL,
      createdAt: new Date().toISOString()
    };
  
    // Send to /api/save-place
    const save = await fetch("/api/save-place", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ id: placeName.toLowerCase().replace(/\s+/g, "-"), data: placeData })
    });
  
    if (save.ok) {
      document.getElementById("successMessage").classList.remove("hidden");
      alert("✅ Place saved to database!");
    } else {
      alert("❌ Failed to save place metadata.");
    }
  });
  