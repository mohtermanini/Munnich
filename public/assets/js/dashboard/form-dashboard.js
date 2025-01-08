import { clearErrors } from "../utils/forms.js";
import { updateDuration } from "./ui-dashboard.js";

export function ressetEndTripForm() {
  clearErrors({ formId: "endTripForm" });
  document.getElementById("endTripForm").reset();
  const now = new Date();

  const today = now.toISOString().split("T")[0];
  document.getElementById("endDate").value = today;

  const currentTime = now.toTimeString().slice(0, 5);
  document.getElementById("endTime").value = currentTime;

  updateDuration();
}
