import { ToastUtils } from "./toast.js";

/**
 * Clears all existing errors for a form.
 * @param {string} formId - The ID of the form.
 */
export function clearErrors({ formId }) {
  const form = document.getElementById(formId);

  if (!form) {
    console.error(`Form with ID ${formId} not found.`);
    return;
  }

  const errorElements = form.querySelectorAll(".input-error");
  errorElements.forEach((errorElement) => {
    errorElement.textContent = ""; // Clear error text
  });
}
export function displayErrors({ formId, validationErrors = {}, error }) {
  clearErrors({ formId });

  const form = document.getElementById(formId);

  if (!form) {
    console.error(`Form with ID "${formId}" not found.`);
    return;
  }

  // Display a toast message if there's a general error
  if (error) {
    ToastUtils.showError(error); // Use showError from toast.js
  }

  // Safely iterate over validationErrors
  if (validationErrors && typeof validationErrors === "object") {
    for (const [field, errorMessage] of Object.entries(validationErrors)) {
      // Find the input field within the specified form by its name attribute
      const inputField = form.querySelector(`[name="${field}"]`);

      if (inputField) {
        // Directly find the sibling element with the class '.input-error'
        const errorElement = inputField.nextElementSibling;

        // Check if the sibling element is the error display ('.input-error')
        if (errorElement && errorElement.classList.contains("input-error")) {
          // Display the error message inside the '.input-error' element
          errorElement.textContent = errorMessage; // Show the error message
          errorElement.style.display = "block"; // Ensure it's visible
        }
      }
    }
  } else {
    console.warn("No validation errors provided or validationErrors is not an object.");
  }
}

