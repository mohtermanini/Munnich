export function truncateText(text, charCount) {
  if (!text || typeof text !== "string") {
    return ""; // Return an empty string or a fallback value
  }

  if (text.length <= charCount) {
    return text; // Return the full text if it's within the limit
  }

  const truncatedText = text.slice(0, charCount) + "...";

  // Return a single span with toggle functionality
  return `
    <span class="truncated-text" data-full-text="${text}" data-truncated-text="${truncatedText}">
      ${truncatedText}
      <button class="btn btn-link text-muted p-0 toggle-btn" 
        style="text-decoration: none; font-size: inherit; margin-left: 5px; vertical-align: baseline;">
        More
      </button>
    </span>
  `;
}
