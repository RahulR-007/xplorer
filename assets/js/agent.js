document.getElementById("uploadForm").addEventListener("submit", async function (event) {
    event.preventDefault();
  
    const placeName = document.getElementById("placeName").value.trim();
    const locationDesc = document.getElementById("locationDesc").value.trim();
    const historyDesc = document.getElementById("historyDesc").value.trim();
    const architectureDesc = document.getElementById("architectureDesc").value.trim();
    const cultureDesc = document.getElementById("cultureDesc").value.trim();
    const funFacts = document.getElementById("funFacts").value.trim();
    const location = document.getElementById("location").value.trim();
    const imageFile = document.getElementById("imageUpload").files[0];
    const modelFile = document.getElementById("modelUpload").files[0];
  
    if (!placeName || !locationDesc || !historyDesc || !architectureDesc || !cultureDesc || !funFacts || !location || !imageFile || !modelFile) {
      alert("Please fill out all fields and upload the necessary files.");
      return;
    }
  
    // ✅ Upload function to Vercel Blob
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
  
    // ⬆️ Upload image and 3D model
    const imageURL = await uploadToBlob(imageFile);
    const modelURL = await uploadToBlob(modelFile);
  
    // ✅ Store or use the URLs
    console.log("Image URL:", imageURL);
    console.log("Model URL:", modelURL);
  
    // 👉 Here we just show success and prepare data.
    document.getElementById("successMessage").classList.remove("hidden");
  
    // 🚧 Coming next:
    // - Store metadata in KV
    // - Auto-render in visit.html and single dynamic page
  });
  