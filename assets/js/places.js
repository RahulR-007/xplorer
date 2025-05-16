// /dashboard/xplorer/assets/js/places.js

// Example part info mapping (you can expand this as per your model's mesh names)
const PART_INFO = {
    "Door": "This is the main entrance door of the monument.",
    "Dome": "The central dome is an architectural marvel.",
    "Pillar": "Each pillar is intricately carved.",
    "Window": "Windows provide natural lighting inside."
    // Add more mesh/part names as needed
  };
  
  document.addEventListener("DOMContentLoaded", function () {
    const modelViewer = document.getElementById('main-model');
    const tooltip = document.getElementById('model-tooltip');
  
    if (modelViewer && tooltip) {
      // Click event for picking parts
      modelViewer.addEventListener('click', async (event) => {
        // Get click coordinates relative to model-viewer
        const rect = modelViewer.getBoundingClientRect();
        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;
  
        // Use positionAndNormalFromPoint to get mesh info
        const hit = await modelViewer.positionAndNormalFromPoint(x, y);
        if (hit && hit.meshName) {
          // Show tooltip with info about the mesh/part
          const info = PART_INFO[hit.meshName] || `You clicked on: ${hit.meshName}`;
          tooltip.textContent = info;
          tooltip.style.left = (x + 20) + 'px';
          tooltip.style.top = (y - 20) + 'px';
          tooltip.style.display = 'block';
          tooltip.style.opacity = 1;
  
          // Hide tooltip after 3 seconds
          setTimeout(() => {
            tooltip.style.opacity = 0;
            setTimeout(() => { tooltip.style.display = 'none'; }, 300);
          }, 3000);
        } else {
          tooltip.style.display = 'none';
        }
      });
  
      // Optional: Hide tooltip on model-viewer mouseout
      modelViewer.addEventListener('mouseout', () => {
        tooltip.style.display = 'none';
      });
    }
  });
  