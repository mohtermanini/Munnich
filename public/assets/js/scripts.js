document.addEventListener("click", (event) => {
    if (event.target.classList.contains("toggle-btn")) {
      event.preventDefault();
  
      const span = event.target.parentElement; // Get the parent span
      const isTruncated = span.textContent.includes("More");
  
      if (isTruncated) {
        // Show full text
        span.firstChild.textContent = span.getAttribute("data-full-text");
        event.target.textContent = " Less";
      } else {
        // Show truncated text
        span.firstChild.textContent = span.getAttribute("data-truncated-text");
        event.target.textContent = " More";
      }
    }
  });
  