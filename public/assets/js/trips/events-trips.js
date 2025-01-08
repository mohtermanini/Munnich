import { deleteTrip } from "./delete-trips.js";
import { ressetEndTripForm, ressetStartTripForm } from "./forms-trips.js";
import { fetchTrips } from "./get-trips.js";
import { TripModule } from "./init-trips.js";
import { updateDuration } from "./ui-trips.js";
import { saveEndTrip, saveStartTrip } from "./update-trips.js";

export const initTripsEvents = () => {
  document.getElementById("applyFilters").addEventListener("click", () => {
    TripModule.setCurrentPage(1);
    fetchTrips(TripModule.getCurrentPage(), TripModule.getCurrentLimit());
  });

  document.getElementById("tripsTableBody").addEventListener("click", (e) => {
    if (e.target && e.target.classList.contains("end-trip-btn")) {
      const tripId = e.target.dataset.tripId;
      TripModule.setSelectedTripId(tripId);
      const endTripModal = new bootstrap.Modal(document.getElementById("endTripModal"));
      endTripModal.show();
    }
  });

  document.body.addEventListener("click", (event) => {
    if (event.target.classList.contains("saveStartTrip")) {
      saveStartTrip();
    }
    if (event.target.classList.contains("saveEndTrip")) {
      saveEndTrip();
    }
    if (event.target.classList.contains("delete-trip-btn")) {
      const tripId = event.target.dataset.tripId;
      TripModule.setSelectedTripId(tripId);
    }
    if (event.target.classList.contains("confirm-delete-trip-btn")) {
      deleteTrip();
    }
  });

  document.getElementById("deleteTripModal").addEventListener("hide.bs.modal", function () {
    TripModule.setSelectedTripId(null);
  });

  document.getElementById("startTripModal").addEventListener("show.bs.modal", function () {
    ressetStartTripForm();
  });

  document.getElementById("endTripModal").addEventListener("show.bs.modal", function () {
    ressetEndTripForm();
  });

  document.getElementById("endTripModal").addEventListener("hide.bs.modal", function () {
    TripModule.setSelectedTripId(null);
  });

  document.getElementById("endDate").addEventListener("change", () => {
    updateDuration();
  });

  document.getElementById("endTime").addEventListener("input", () => {
    updateDuration();
  });
};
