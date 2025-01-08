import { ressetEndTripForm } from "./form-dashboard.js";
import { saveEndTrip } from "./update-dashboard.js";
import { DashboardModule } from "./init-dashboard.js";
import { updateDuration } from "./ui-dashboard.js";

export const initDashboardEvents = () => {
  document.getElementById("ongoingTripsTableBody").addEventListener("click", (e) => {
    if (e.target && e.target.classList.contains("end-trip-btn")) {
      const tripId = e.target.dataset.tripId;
      DashboardModule.setSelectedOnGoingTripId(tripId);
      const endTripModal = new bootstrap.Modal(document.getElementById("endTripModal"));
      endTripModal.show();
    }
  });

  document.body.addEventListener("click", (event) => {
    if (event.target.classList.contains("saveEndTrip")) {
      saveEndTrip();
    }
  });

  document.getElementById("endTripModal").addEventListener("show.bs.modal", function () {
    ressetEndTripForm();
  });

  document.getElementById("endTripModal").addEventListener("hide.bs.modal", function () {
    DashboardModule.setSelectedOnGoingTripId(null);
  });

  document.getElementById("endDate").addEventListener("change", () => {
    updateDuration();
  });
  document.getElementById("endTime").addEventListener("input", () => {
    updateDuration();
  });
};
