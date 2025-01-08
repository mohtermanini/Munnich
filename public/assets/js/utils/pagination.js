export function generatePagination(containerId, totalPages, currentPage, onPageChange) {
  const paginationContainer = document.getElementById(containerId);

  paginationContainer.innerHTML = "";

  if (!paginationContainer || totalPages <= 1) return; // Exit if there's only one page
  const prevButton = document.createElement("button");
  prevButton.textContent = "Previous";
  prevButton.className = "btn btn-secondary btn-sm";
  prevButton.disabled = currentPage === 1;
  prevButton.addEventListener("click", () => {
    if (currentPage > 1) {
      onPageChange(currentPage - 1);
    }
  });
  paginationContainer.appendChild(prevButton);

  for (let i = 1; i <= totalPages; i++) {
    const link = document.createElement("button");
    link.textContent = i;
    link.className = `btn btn-sm mx-1 btn-secondary ${i === currentPage ? "active" : ""}`;
    link.addEventListener("click", () => onPageChange(i));
    paginationContainer.appendChild(link);
  }

  const nextButton = document.createElement("button");
  nextButton.textContent = "Next";
  nextButton.className = "btn btn-secondary btn-sm";
  nextButton.disabled = currentPage === totalPages;
  nextButton.addEventListener("click", () => {
    if (currentPage < totalPages) {
      onPageChange(currentPage + 1);
    }
  });
  paginationContainer.appendChild(nextButton);
}
