// /dashboard/xplorer/assets/js/places.js

const PART_INFO = {
    "Door": "This is the main entrance door of the monument.",
    "Dome": "The central dome is an architectural marvel.",
    "Pillar": "Each pillar is intricately carved.",
    "Window": "Windows provide natural lighting inside."
  };
  
  document.addEventListener("DOMContentLoaded", function () {
    const modelViewer = document.getElementById('main-model');
    const tooltip = document.getElementById('model-tooltip');
  
    if (modelViewer && tooltip) {
      modelViewer.addEventListener('click', async (event) => {
        const rect = modelViewer.getBoundingClientRect();
        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;
  
        const hit = await modelViewer.positionAndNormalFromPoint(x, y);
        if (hit && hit.meshName) {
          const info = PART_INFO[hit.meshName] || `You clicked on: ${hit.meshName}`;
          tooltip.textContent = info;
          tooltip.style.left = (x + 20) + 'px';
          tooltip.style.top = (y - 20) + 'px';
          tooltip.style.display = 'block';
          tooltip.style.opacity = 1;
  
          setTimeout(() => {
            tooltip.style.opacity = 0;
            setTimeout(() => { tooltip.style.display = 'none'; }, 300);
          }, 3000);
        } else {
          tooltip.style.display = 'none';
        }
      });
  
      modelViewer.addEventListener('mouseout', () => {
        tooltip.style.display = 'none';
      });
    }
  });
  